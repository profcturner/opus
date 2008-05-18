# Makefile for OPUS Docs

clean:
	rm -rf *.idx *.ilg *.ind *.log *.out *.toc *.aux *.pdf

all:
	pdflatex opus_manual
	pdflatex opus_manual
	makeindex opus_manual
	pdflatex opus_manual