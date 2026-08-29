from pathlib import Path
import re
root=Path('/home/ubuntu/siakad-laravel/app/Filament/Resources')
groups={
'GraduationDocuments':'Kelulusan','ThesisExaminers':'Tugas Akhir','ThesisRevisions':'Tugas Akhir',
}
for p in root.glob('**/*Resource.php'):
    if any(x in {'Pages','Schemas','Tables','RelationManagers'} for x in p.parts): continue
    s=p.read_text()
    # Repair malformed multiline icon declaration and restore group from directory taxonomy.
    pat=r"protected static string\|BackedEnum\|null \$navigationIcon\s*\n\s*Heroicon::([A-Za-z0-9_]+); protected static string\|UnitEnum\|null \$navigationGroup = '[^']*';"
    m=re.search(pat,s)
    if m:
        group=groups.get(p.parent.name)
        if group:
            repl=f"protected static string|BackedEnum|null $navigationIcon = Heroicon::{m.group(1)}; protected static string|UnitEnum|null $navigationGroup = '{group}';"
            s=s[:m.start()]+repl+s[m.end():]
    # Repair accidental duplicated group payloads on the same line.
    s=re.sub(r"protected static string\|UnitEnum\|null \$navigationGroup = 'Heroicon::[^']*'", lambda m: "protected static string|UnitEnum|null $navigationGroup = '"+groups.get(p.parent.name,'Administrasi Sistem')+"'", s)
    p.write_text(s)
print('repaired')
