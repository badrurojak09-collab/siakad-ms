from pathlib import Path
root=Path('/home/ubuntu/siakad-laravel/app/Filament/Resources')
groups={
'UserProfiles':'Administrasi Sistem',
'Facultys':'Organisasi Akademik',
'StudyPrograms':'Organisasi Akademik',
'AcademicYears':'Data Akademik',
'Semesters':'Data Akademik',
'CurriculumTemplates':'Kurikulum & Mata Kuliah',
'CourseClasss':'Perkuliahan',
'AcademicAdvisors':'Perkuliahan',
'CourseEquivalencys':'Kurikulum & Mata Kuliah',
'AttendanceCorrections':'Presensi',
'Assessments':'Penilaian',
'MbkmActivitys':'Administrasi Mahasiswa',
'Supervisions':'Tugas Akhir',
}
for directory, group in groups.items():
    for p in (root/directory).glob('*Resource.php'):
        s=p.read_text()
        if 'navigationGroup' in s: continue
        marker='protected static ?string $navigationLabel'
        if marker in s:
            s=s.replace(marker, f"protected static string|UnitEnum|null $navigationGroup = '{group}';\n    {marker}",1)
        else:
            marker='protected static string|BackedEnum|null $navigationIcon'
            s=s.replace(marker, marker+f"\n    protected static string|UnitEnum|null $navigationGroup = '{group}';",1)
        if 'use UnitEnum;' not in s:
            s=s.replace('use BackedEnum;','use BackedEnum;\nuse UnitEnum;',1)
        p.write_text(s)
        print(p)
