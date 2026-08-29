# SIAKAD Laravel 13 + Filament 5 — Skeleton 16 Modul

Skeleton ini memetakan 16 domain pada dokumen arsitektur ke modular monolith Laravel. Setiap domain memiliki tabel utama dan satu resource Filament utama sebagai titik awal pengembangan. Relasi detail dan business rule dapat diperketat pada iterasi berikutnya.

## Modul

1. Multi-Tenancy
2. Identity & Authentication
3. Organization Structure
4. Academic Master Data
5. Academic Period
6. Curriculum
7. Course & Class
8. Student & Lecturer
9. KRS
10. Scheduling
11. Attendance
12. Grading
13. Administration
14. Thesis
15. Graduation
16. Reporting & PDDikti

## Setup

```bash
cp .env.example .env
php artisan key:generate
# isi DB_CONNECTION=mysql dan kredensial MySQL
php artisan migrate
php artisan make:filament-user
php artisan serve
```

Panel tersedia di `/admin`.
