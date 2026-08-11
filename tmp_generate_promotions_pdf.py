from pathlib import Path
from markdown_pdf import Section, MarkdownPdf

md_path = Path('Manual_Testing_Guides/6_CRM_Loyalty_Promotions_Flow_Exhaustive.md')
pdf_path = Path('Manual_Testing_Guides/6_CRM_Loyalty_Promotions_Flow_Exhaustive.pdf')
content = md_path.read_text(encoding='utf-8')
pdf = MarkdownPdf(toc_level=0)
pdf.add_section(Section(content))
pdf.save(str(pdf_path))
print(f'PDF created: {pdf_path}')
