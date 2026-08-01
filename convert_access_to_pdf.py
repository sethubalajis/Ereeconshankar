from markdown_pdf import Section, MarkdownPdf

pdf = MarkdownPdf(toc_level=0)
pdf.add_section(Section(open("access_control_testing_guide.md").read()))
pdf.save("Access_Control_Testing_Guide.pdf")
