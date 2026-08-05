from markdown_pdf import Section, MarkdownPdf

pdf = MarkdownPdf(toc_level=2)
pdf.add_section(Section(open("flows.md").read()))
pdf.save("Textile_ERP_Flows.pdf")
