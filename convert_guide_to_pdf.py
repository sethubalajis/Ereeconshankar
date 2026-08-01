from markdown_pdf import Section, MarkdownPdf

pdf = MarkdownPdf(toc_level=0)
pdf.add_section(Section(open("structure_testing_guide.md").read()))
pdf.save("Structure_Testing_Guide.pdf")
