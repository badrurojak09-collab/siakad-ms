from pathlib import Path
import re
root=Path('/home/ubuntu/siakad-laravel/app/Filament/Resources')
translations={
'Name':'Nama','Code':'Kode','Description':'Deskripsi','Status':'Status','Student':'Mahasiswa','Lecturer':'Dosen','Course':'Mata Kuliah','Academic Period':'Periode Akademik','Academic Year':'Tahun Akademik','Semester':'Semester','Faculty':'Fakultas','Study Program':'Program Studi','Department':'Departemen','Curriculum':'Kurikulum','Organization':'Organisasi','Attendance':'Presensi','Graduation':'Kelulusan','Thesis':'Tugas Akhir','Reporting':'Pelaporan','Payment':'Pembayaran','Created At':'Dibuat Pada','Updated At':'Diperbarui Pada','Category':'Kategori','Location':'Lokasi','Quota':'Kuota','Degree':'Gelar','Decree Number':'Nomor SK','Payment Status':'Status Pembayaran','Confirmation Status':'Status Konfirmasi','Gown Size':'Ukuran Toga','Letter Grade':'Nilai Huruf','Component':'Komponen','Purpose':'Keperluan','Due Date':'Jatuh Tempo','Amount':'Jumlah','Paid Amount':'Jumlah Dibayar','Method':'Metode','Reference':'Referensi','Date':'Tanggal','Time':'Waktu','Room':'Ruang','Building':'Gedung','Day':'Hari','Start Time':'Waktu Mulai','End Time':'Waktu Selesai','Credits':'SKS','NIM':'NIM','NIDN':'NIDN','Email':'Surel','Phone':'Telepon','Address':'Alamat','Unpaid':'Belum Lunas','Partial':'Sebagian','Paid':'Lunas','Confirmed':'Dikonfirmasi','Pending':'Menunggu','Void':'Dibatalkan','Active':'Aktif','Inactive':'Tidak Aktif','Approved':'Disetujui','Rejected':'Ditolak','Draft':'Draf','Submitted':'Diajukan','Completed':'Selesai','Cancelled':'Dibatalkan','Open':'Terbuka','Closed':'Ditutup','Male':'Laki-laki','Female':'Perempuan','Yes':'Ya','No':'Tidak',
}
for p in root.glob('**/*.php'):
    if any(x in {'Pages','Schemas','Tables','RelationManagers'} for x in p.parts) is False or True:
        s=p.read_text()
        original=s
        for old,new in translations.items():
            s=re.sub(r"(->label\(\s*|=>\s*|\btitle\s*:\s*)['\"]"+re.escape(old)+r"['\"]", lambda m: m.group(1)+"'"+new+"'", s)
        if s!=original: p.write_text(s)
print('translated explicit UI labels')
