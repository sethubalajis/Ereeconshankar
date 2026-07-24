import importlib.util

for name in ['reportlab', 'weasyprint']:
    print(name, '=>', importlib.util.find_spec(name))
