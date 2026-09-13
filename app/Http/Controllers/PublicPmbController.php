<?php

namespace App\Http\Controllers;

use App\Actions\Admissions\RegisterPublicApplicantAction;
use App\Http\Requests\PublicPmbRegistrationRequest;
use App\Models\AdmissionPeriod;
use App\Models\Applicant;
use App\Models\StudyProgram;
use App\Models\Tenant;
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicPmbController extends Controller
{
    public function __construct(private TenantContext $context) {}

    public function index()
    {
        $tenant = $this->ensureTenantContext();
        $periods = $this->openPeriods($tenant);

        return view('public.pmb.index', [
            'periods' => $periods,
            'defaultPeriod' => $periods->firstWhere('status', 'open') ?? $periods->first(),
            'programs' => $this->activePrograms($tenant),
        ]);
    }

    public function store(PublicPmbRegistrationRequest $request, RegisterPublicApplicantAction $action)
    {
        // Lookup TANPA global scope tenant (context memang belum ada di route publik;
        // tenant justru diturunkan dari hasil lookup ini).
        $period = AdmissionPeriod::query()
            ->withoutGlobalScope('tenant')
            ->find($request->integer('admission_period_id'));
        abort_if(! $period || blank($period->tenant_id), 422, 'Gelombang pendaftaran tidak valid.');

        $tenant = Tenant::query()->find($period->tenant_id);
        abort_if(! $tenant || ! $tenant->isOperational(), 423, 'Kampus penyelenggara PMB sedang tidak operasional.');

        // Context dipinjam sementara (run) agar aman walau request datang
        // dari user yang sedang login pada tenant lain.
        $result = $this->context->run($tenant, fn() => $action->execute($request->validated()));

        return redirect()
            ->route('pmb.success', $result['registration_number'])
            ->with('registered', true);
    }

    public function success(string $registrationNumber)
    {
        $applicant = $this->resolveApplicant($registrationNumber);
        abort_if(! $applicant, 404, 'Pendaftaran tidak ditemukan.');

        return view('public.pmb.success', [
            'applicant' => $applicant,
            'selections' => $applicant->selections()->with('studyProgram')->orderBy('choice_order')->get(),
        ]);
    }

    public function status()
    {
        return view('public.pmb.status');
    }

    public function check(Request $request)
    {
        $validated = $request->validate([
            'registration_number' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email'],
        ], [
            'registration_number.required' => 'Nomor pendaftaran wajib diisi.',
            'email.required' => 'Surel wajib diisi.',
            'email.email' => 'Format surel tidak valid.',
        ]);

        $applicant = $this->resolveApplicant($validated['registration_number']);

        if (! $applicant || strcasecmp($applicant->email, $validated['email']) !== 0) {
            return back()
                ->withInput()
                ->withErrors(['registration_number' => 'Data pendaftaran tidak ditemukan. Periksa kembali nomor dan surel Anda.']);
        }

        return view('public.pmb.status', [
            'applicant' => $applicant,
            'selections' => $applicant->selections()->with('studyProgram')->orderBy('choice_order')->get(),
        ]);
    }

    public static function statusLabels(): array
    {
        return [
            'draft' => 'Draf',
            'submitted' => 'Diajukan',
            'under_review' => 'Ditinjau',
            'selection_passed' => 'Lulus Seleksi',
            'selection_failed' => 'Tidak Lulus',
            'rejected' => 'Tidak Lulus',
            'converted' => 'Dikonversi',
            'enrolled' => 'Telah Terdaftar',
        ];
    }

    public static function statusBadgeClasses(): array
    {
        return [
            'draft' => 'bg-slate-100 text-slate-700 border-slate-200',
            'submitted' => 'bg-blue-50 text-blue-700 border-blue-200',
            'under_review' => 'bg-amber-50 text-amber-700 border-amber-200',
            'selection_passed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'selection_failed' => 'bg-rose-50 text-rose-700 border-rose-200',
            'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
            'converted' => 'bg-violet-50 text-violet-700 border-violet-200',
            'enrolled' => 'bg-violet-50 text-violet-700 border-violet-200',
        ];
    }

    public static function selectionStatusLabels(): array
    {
        return [
            'pending' => 'Menunggu',
            'passed' => 'Lulus',
            'failed' => 'Tidak Lulus',
        ];
    }

    public static function documentStatusLabels(): array
    {
        return [
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
        ];
    }

    public static function documentStatusClasses(): array
    {
        return [
            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
            'verified' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
        ];
    }

    /**
     * Mencari pendaftar berdasarkan nomor registrasi dari halaman publik.
     * Global scope BelongsToTenant membutuhkan TenantContext, sedangkan pengunjung
     * publik tidak melewati ResolveTenant — maka tenant di-resolve dulu via query builder.
     */
    private function resolveApplicant(string $registrationNumber): ?Applicant
    {
        $tenantId = DB::table('applicants')
            ->where('registration_number', trim($registrationNumber))
            ->whereNull('deleted_at')
            ->value('tenant_id');

        if (! $tenantId) {
            return null;
        }

        $this->ensureTenantContext((int) $tenantId);

        return Applicant::query()
            ->where('registration_number', trim($registrationNumber))
            ->first();
    }

    /**
     * Konteks tenant untuk alur publik: diturunkan dari periode yang dipilih,
     * atau fallback ke tenant operasional pertama (instalasi per-kampus).
     */
    private function ensureTenantContext(?int $tenantId = null): Tenant
    {
        if ($this->context->check()) {
            return $this->context->require();
        }

        $tenant = $tenantId ? Tenant::query()->find($tenantId) : null;
        $tenant ??= Tenant::query()->whereIn('status', ['active', 'trial'])->orderBy('id')->first();
        abort_if(! $tenant, 503, 'Belum ada kampus (tenant) yang aktif menerima pendaftaran.');

        $this->context->set($tenant);

        return $tenant;
    }

    private function openPeriods(Tenant $tenant)
    {
        $today = now()->toDateString();

        return AdmissionPeriod::query()
            ->where(fn($q) => $q->where('tenant_id', $tenant->getKey())->orWhereNull('tenant_id'))
            ->whereIn('status', ['open', 'closed'])
            ->whereDate('registration_start', '<=', $today)
            ->whereDate('registration_end', '>=', $today)
            ->orderBy('registration_start')
            ->get();
    }

    private function activePrograms(Tenant $tenant)
    {
        return StudyProgram::query()
            ->where(fn($q) => $q->where('tenant_id', $tenant->getKey())->orWhereNull('tenant_id'))
            ->orderBy('name')
            ->get();
    }
}
