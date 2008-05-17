# Makefile for OPUS Docs

clean:
	rm -rf *.idx *.ilg *.ind *.log *.out *.pdf *.toc *.aux

all:
	pdflatex opus_manual
	pdflatex opus_manual
	makeindex opus_manual
	pdflatex opus_manual