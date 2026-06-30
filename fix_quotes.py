import os, re

base_dir = r'd:\e-reckon-erp\app\Filament\Resources'

for root, _, files in os.walk(base_dir):
    for file in files:
        if file.endswith('Table.php') or file.endswith('Form.php'):
            path = os.path.join(root, file)
            with open(path, 'r', encoding='utf-8') as f:
                content = f.read()
            
            # Replace \\' with '
            new_content = content.replace("\\'", "'")
            
            # Form still has ->numeric() for some because of line breaks.
            new_content = re.sub(r"(->relationship\('[^']+', '[^']+'\)(?:\s*->(?:required|label)\([^)]*\))*)(\s*->numeric\(\))", r'\1', new_content)
            
            if new_content != content:
                with open(path, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print(f'Fixed {file}')
