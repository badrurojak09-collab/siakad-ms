from pathlib import Path
import re
root=Path('/home/ubuntu/siakad-laravel/app/Filament/Resources')
for p in root.glob('**/*Resource.php'):
    if any(x in {'Pages','Schemas','Tables','RelationManagers'} for x in p.parts): continue
    s=p.read_text()
    # Repair the specific malformed pattern produced when a one-line icon declaration was matched.
    s=re.sub(r"(protected static string\|UnitEnum\|null \$navigationGroup = '[^']+';) = (Heroicon::[^;]+;)", r"\2 protected static string|UnitEnum|null $navigationGroup = '\g<0>';", s)
    # The previous replacement is intentionally normalized below by extracting group from malformed text.
    bad=re.search(r"protected static string\|UnitEnum\|null \$navigationGroup = '([^']+)'; = (Heroicon::[^;]+;)",s)
    if bad:
        group, icon=bad.group(1),bad.group(2)
        s=s.replace(bad.group(0), f"{icon} protected static string|UnitEnum|null $navigationGroup = '{group}';")
    p.write_text(s)
print('fixed')
