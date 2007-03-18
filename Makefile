# Makefile for OPUS

clean:
#	find . \( -name "Makefile" -or -name "#*#" -or -name ".#*" -or -name "*~" -or -name ".*~" \) -exec rm -rfv {} \;
	rm -fv *.cache
	rm -rf debian/opus
	rm -rf debian/files

#
# Currently contains commands for making with Debian
#

debetc=/etc
debprefix=/usr
debwww=/var/www

debs: deb-opus, deb-opus-doc

deb-opus: deb-opus-etc
	# Make main directory and copy in contents
	mkdir -p ${debprefix}/share/opus
	cp -rf html ${debprefix}/share/opus
	cp -rf include ${debprefix}/share/opus
	cp -rf cron ${debprefix}/share/opus
	cp -rf templates ${debprefix}/share/opus
	mkdir ${debprefix}/share/opus/templates_c
	mkdir ${debprefix}/share/opus/templates_cache
	chown -R www-data:root ${debprefix}/share/opus/
	chmod -R o-rwx ${debprefix}/share/opus/
	# Make documentation directory
	mkdir -p ${debprefix}/share/doc/opus
	cp -rf sql_patch ${debprefix}/share/doc/opus


deb-opus-etc: 
	mkdir -p ${debetc}/opus
	cp include/config.php.dist ${debetc}/opus/config.php
	cp etc/apache2.conf ${debetc}/opus/apache2.conf


deb-opus-doc:
	mkdir -p $(debprefix)/share/doc/opus-doc
	cp -rf docs ${debprefix}/share/doc/opus-doc

build_debs:
	dpkg-buildpackage -rfakeroot
