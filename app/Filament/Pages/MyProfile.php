<?php

namespace App\Filament\Pages;

use App\Models\UserProfile;
use App\Services\TenantContext;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;
use BackedEnum;
use UnitEnum;

class MyProfile extends Page
{
    protected string $view = 'filament.pages.my-profile';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    protected static string|UnitEnum|null $navigationGroup = 'Administrasi Sistem';

    protected static ?string $navigationLabel = 'Profil Saya';

    protected static ?string $modelLabel = 'Profil Saya';

    protected static ?string $title = 'Profil Saya';

    protected static ?string $slug = 'my-profile';

    protected static ?int $navigationSort = 99;

    /**
     * Data akun (tabel users) + profil (tabel user_profiles)
     * disatukan pada state `data` milik interaksinya.
     */
    public ?array $data = [];

    public function mount(): void
    {
        $user = auth()->user();
        $profile = $this->resolveProfile();

        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'username' => $profile?->username,
            'full_name' => $profile?->full_name,
            'phone' => $profile?->phone,
            'is_active' => $profile?->is_active ?? true,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Akun')
                    ->description('Data akun untuk masuk ke sistem.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Pengguna')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Surel')
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ]),
                Section::make('Profil')
                    ->description('Data profil Anda pada institusi ini.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('username')
                            ->label('Nama Panggilan')
                            ->maxLength(100),
                        TextInput::make('full_name')
                            ->label('Nama Lengkap')
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Telepon')
                            ->tel()
                            ->maxLength(30),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Status profil ditetapkan oleh institusi.')
                            ->disabled()
                            ->dehydrated(false),
                    ]),
                Section::make('Ubah Kata Sandi')
                    ->description('Biarkan kosong jika tidak ingin mengubah kata sandi.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Kata Sandi Saat Ini')
                            ->password()
                            ->revealable()
                            ->dehydrated(false),
                        TextInput::make('new_password')
                            ->label('Kata Sandi Baru')
                            ->password()
                            ->revealable()
                            ->dehydrated(false),
                        TextInput::make('new_password_confirmation')
                            ->label('Konfirmasi Kata Sandi Baru')
                            ->password()
                            ->revealable()
                            ->dehydrated(false),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $user = auth()->user();
        $data = $this->form->getState();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->getKey()],
        ];

        $passwordFilled = filled($data['new_password'] ?? null);
        if ($passwordFilled) {
            $rules['current_password'] = ['required', 'current_password:web'];
            $rules['new_password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        $this->validate($rules);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if ($passwordFilled) {
            $user->update(['password' => Hash::make($data['new_password'])]);
        }

        $payload = [
            'username' => $data['username'] ?? null,
            'full_name' => filled($data['full_name'] ?? null) ? $data['full_name'] : $data['name'],
            'phone' => $data['phone'] ?? null,
        ];

        $profile = $this->resolveProfile();

        if ($profile instanceof UserProfile) {
            $profile->update($payload);
        } else {
            $user->userProfiles()->create($payload + [
                'user_id' => $user->getKey(),
                'tenant_id' => $this->resolveTenantId(),
            ]);
        }

        Notification::make()
            ->title('Profil berhasil diperbarui')
            ->success()
            ->send();
    }

    protected function resolveProfile(): ?UserProfile
    {
        $user = auth()->user();
        $tenantId = $this->resolveTenantId();

        return $user->userProfileFor($tenantId)
            ?? $user->userProfileFor(null)
            ?? $user->userProfiles()->first();
    }

    protected function resolveTenantId(): ?int
    {
        try {
            if (app(TenantContext::class)->check()) {
                return app(TenantContext::class)->id();
            }
        } catch (\Throwable) {
            // Tenant context belum tersedia pada request ini.
        }

        return null;
    }

    protected function getFormStatePath(): ?string
    {
        return 'data';
    }
}
