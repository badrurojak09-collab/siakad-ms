# SIAKAD Laravel 13 + Filament 5

Proyek ini adalah skeleton database dan resource Filament untuk 16 modul pada dokumen arsitektur SIAKAD. Implementasi menggunakan pendekatan **modular monolith** agar seluruh domain dapat dikembangkan dalam satu deployment terlebih dahulu, sementara event, queue, dan service adapter dapat dipisahkan ketika skala meningkat.

## Pemetaan modul

| No. | Modul | Tabel skeleton | Resource Filament |
|---:|---|---|---|
| 1 | Multi-Tenancy | `tenants` | `TenantResource` |
| 2 | Identity & Authentication | `user_profiles` | `UserProfileResource` |
| 3 | Organization Structure | `faculties`, `departments`, `study_programs` | resource masing-masing |
| 4 | Academic Master Data | `lecturers`, `students`, `courses`, `course_prerequisites` | resource masing-masing |
| 5 | Academic Period | `academic_years`, `semesters` | resource masing-masing |
| 6 | Curriculum | `curriculums`, `curriculum_courses`, `curriculum_templates` | resource masing-masing |
| 7 | Course & Class | `course_classes` | `CourseClassResource` |
| 8 | Student & Lecturer | `academic_advisors`, `course_equivalencies` | resource masing-masing |
| 9 | KRS | `krs_headers`, `krs_details`, `krs_logs` | resource masing-masing |
| 10 | Scheduling | `rooms`, `schedules` | resource masing-masing |
| 11 | Attendance | `attendance_sessions`, `attendance_records`, `attendance_corrections` | resource masing-masing |
| 12 | Grading | `assessments`, `student_grades`, `grade_submissions` | resource masing-masing |
| 13 | Administration | `leave_requests`, `transfers`, `mbkm_activities` | resource masing-masing |
| 14 | Thesis | `theses`, `supervisions`, `thesis_grades` | resource masing-masing |
| 15 | Graduation | `graduations`, `graduation_ceremonies`, `ceremony_registrations` | resource masing-masing |
| 16 | Reporting & PDDikti | `pddikti_sync_logs`, `report_definitions`, `generated_reports` | resource masing-masing |

Skeleton ini menghasilkan **40 tabel/model domain** dan **40 resource Filament** sebagai titik awal CRUD. Beberapa relasi dan business rule lintas modul sengaja dibuat longgar agar migrasi awal mudah diterapkan; relasi foreign key, policy berbasis tenant, validasi workflow, dan service layer perlu diperketat pada fase implementasi berikutnya.

## Instalasi

```bash
cp .env.example .env
php artisan key:generate
composer install
```

Isi koneksi MySQL:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siakad
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan:

```bash
php artisan migrate
php artisan db:seed
php artisan make:filament-user
npm install
npm run build
php artisan serve
```

Panel tersedia di `/admin`.

## Package pendukung

Filament 5 digunakan untuk panel admin, Spatie Laravel Permission untuk RBAC, Spatie Activitylog untuk audit trail, Laravel Sanctum untuk token API, dan Redis direkomendasikan untuk cache serta queue. MySQL dipakai sebagai database utama. Elasticsearch dan RabbitMQ/Kafka dapat ditambahkan pada tahap scale-up.

## Integrasi PDDikti

Tabel `pddikti_sync_logs` disediakan untuk idempotency, response code, payload, status, retry count, dan waktu sinkronisasi. Implementasi produksi sebaiknya menambahkan `Job` Laravel dengan `backoff()`, scheduler periodik, adapter Neo Feeder terpisah, serta pembatasan data sensitif pada log.

## Validasi yang telah dilakukan

Migrasi penuh dan seeding berhasil dijalankan pada SQLite sebagai smoke test, route discovery Filament menghasilkan 122 route admin, dan 218 berkas PHP lulus pemeriksaan sintaks. Uji MySQL aktual perlu dilakukan setelah instance MySQL dan kredensial target tersedia.

## Referensi arsitektur

Baseline desain berasal dari `AristekturSIAKAD.docx.pdf` yang disediakan pengguna. Dokumen tersebut merekomendasikan 16 layer/domain, event-driven integration untuk PDDikti, serta prioritas pengembangan bertahap dari multi-tenancy/auth sampai reporting/PDDikti.
