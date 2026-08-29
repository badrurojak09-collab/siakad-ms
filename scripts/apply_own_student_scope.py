from pathlib import Path
import re
root = Path('/home/ubuntu/siakad-laravel/app/Filament/Resources')
paths = []
for path in root.rglob('*Resource.php'):
    text = path.read_text()
    if "make('student_id')" in text or "student_id" in text:
        if 'ScopesOwnStudentRecords' not in text:
            paths.append(path)
            text = text.replace('namespace ', 'namespace ', 1)
            ns_end = text.find(';', text.find('namespace ')) + 1
            text = text[:ns_end] + "\n\nuse App\\Filament\\Resources\\Concerns\\ScopesOwnStudentRecords;" + text[ns_end:]
            match = re.search(r'class\s+(\w+)\s+extends\s+Resource\s*\{', text)
            if not match:
                raise RuntimeError(f'No Resource class found: {path}')
            insert = match.end()
            text = text[:insert] + "\n    use ScopesOwnStudentRecords;" + text[insert:]
            path.write_text(text)
print('\n'.join(str(p) for p in paths))
print(f'updated={len(paths)}')
