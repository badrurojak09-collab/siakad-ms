from pathlib import Path
import re

root = Path('/home/ubuntu/siakad-laravel/app/Filament/Resources')

# Group taxonomy deliberately kept small and stable so the sidebar is predictable.
group_by_dir = {
    'Tenants': 'Administrasi Sistem',
    'Users': 'Administrasi Sistem',
    'Roles': 'Administrasi Sistem',
    'Permissions': 'Administrasi Sistem',
    'Students': 'Data Akademik',
    'Lecturers': 'Data Akademik',
    'AcademicPeriods': 'Data Akademik',
    'Courses': 'Kurikulum & Mata Kuliah',
    'Curriculums': 'Kurikulum & Mata Kuliah',
    'CurriculumCourses': 'Kurikulum & Mata Kuliah',
    'CoursePrerequisites': 'Kurikulum & Mata Kuliah',
    'AcademicPrograms': 'Organisasi Akademik',
    'Departments': 'Organisasi Akademik',
    'Faculties': 'Organisasi Akademik',
    'Buildings': 'Penjadwalan & Ruang',
    'Rooms': 'Penjadwalan & Ruang',
    'Schedules': 'Penjadwalan & Ruang',
    'CourseClasses': 'Perkuliahan',
    'TeachingAssignments': 'Perkuliahan',
    'AttendanceSessions': 'Presensi',
    'AttendanceRecords': 'Presensi',
    'AssessmentComponents': 'Penilaian',
    'StudentGrades': 'Penilaian',
    'GradeSubmissions': 'Penilaian',
    'KrsHeaders': 'KRS & Registrasi',
    'KrsDetails': 'KRS & Registrasi',
    'KrsLogs': 'KRS & Registrasi',
    'FeeTypes': 'Keuangan',
    'AcademicBills': 'Keuangan',
    'Payments': 'Keuangan',
    'AdmissionPeriods': 'PMB',
    'Applicants': 'PMB',
    'AdmissionBills': 'PMB',
    'AdmissionPayments': 'PMB',
    'Thesiss': 'Tugas Akhir',
    'ThesisExaminers': 'Tugas Akhir',
    'ThesisRevisions': 'Tugas Akhir',
    'ThesisGrades': 'Tugas Akhir',
    'Graduations': 'Kelulusan',
    'GraduationDocuments': 'Kelulusan',
    'GraduationCeremonys': 'Kelulusan',
    'CeremonyRegistrations': 'Kelulusan',
    'Leaves': 'Administrasi Mahasiswa',
    'LeaveRequests': 'Administrasi Mahasiswa',
    'Transfers': 'Administrasi Mahasiswa',
    'MbkmActivities': 'Administrasi Mahasiswa',
    'AcademicTranscripts': 'Pelaporan Akademik',
    'ReportDefinitions': 'Pelaporan Akademik',
    'GeneratedReports': 'Pelaporan Akademik',
    'PddiktiSyncLogs': 'Pelaporan Akademik',
}

labels = {
    'Dashboard': 'Beranda',
    'Student & Lecturer': 'Mahasiswa & Dosen',
    'Academic Period': 'Periode Akademik',
    'Academic Master Data': 'Data Master Akademik',
    'Organization Structure': 'Struktur Organisasi',
    'Curriculum': 'Kurikulum',
    'Scheduling': 'Penjadwalan',
    'Attendance': 'Presensi',
    'Course Classes': 'Kelas Perkuliahan',
    'Assessment Components': 'Komponen Penilaian',
    'Student Grades': 'Nilai Mahasiswa',
    'KRS': 'Kartu Rencana Studi',
    'PMB': 'Penerimaan Mahasiswa Baru',
    'Graduation': 'Kelulusan',
    'Thesis': 'Tugas Akhir',
    'Reporting & PDDikti': 'Pelaporan & PDDikti',
    'Identity & Authentication': 'Identitas & Autentikasi',
    'User Profiles': 'Profil Pengguna',
    'Roles & Permissions': 'Peran & Hak Akses',
    'Student': 'Mahasiswa',
    'Lecturer': 'Dosen',
    'Course': 'Mata Kuliah',
    'Class': 'Kelas',
    'Name': 'Nama',
    'Code': 'Kode',
    'Description': 'Deskripsi',
    'Status': 'Status',
    'Category': 'Kategori',
    'Location': 'Lokasi',
    'Quota': 'Kuota',
    'Degree': 'Gelar',
    'Decree Number': 'Nomor SK',
    'Payment Status': 'Status Pembayaran',
    'Confirmation Status': 'Status Konfirmasi',
    'Gown Size': 'Ukuran Toga',
    'Letter Grade': 'Nilai Huruf',
    'Component': 'Komponen',
    'Created At': 'Dibuat Pada',
    'Updated At': 'Diperbarui Pada',
}

for path in root.glob('**/*Resource.php'):
    if any(part in {'Pages', 'Schemas', 'Tables', 'RelationManagers'} for part in path.parts):
        continue
    text = path.read_text()
    directory = path.parent.name
    group = group_by_dir.get(directory)
    if group and 'navigationGroup' not in text:
        marker = 'protected static ?string $navigationLabel'
        if marker in text:
            text = text.replace(marker, "protected static string|UnitEnum|null $navigationGroup = '" + group + "';\n    " + marker, 1)
        else:
            marker = 'protected static string|BackedEnum|null $navigationIcon'
            text = text.replace(marker, marker + "\n    protected static string|UnitEnum|null $navigationGroup = '" + group + "';", 1)
    for old, new in labels.items():
        text = re.sub(r"(navigationLabel\s*=\s*)['\"]" + re.escape(old) + r"['\"]", r"\1'" + new + "'", text)
        text = re.sub(r"(->label\(\s*)['\"]" + re.escape(old) + r"['\"]", r"\1'" + new + "'", text)
        text = re.sub(r"(TextColumn::make\([^)]*\)->label\(\s*)['\"]" + re.escape(old) + r"['\"]", r"\1'" + new + "'", text)
    path.write_text(text)

print('Navigation normalization completed.')
