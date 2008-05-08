# Makefile for OPUS

tar:
	# Usage
	@echo "Usage: rel=<version>; rel is set to ${rel}"
	@test ! -z ${rel}
	# Empty that target dir first
	rm -rf ../tarballs/opus-${rel}
	# takes rel= as argument
	mkdir -p ../tarballs/opus-${rel}
	# And any existing tarballs
	rm -rf ../tarballs/opus_${rel}.orig.tar.gz
	# Copy new content in
	cp -rf * ../tarballs/opus-$(rel)
	# Remove svn files, debian dir and this Makefile, since it
	# is very debian specific right now
	rm -rf `find ../tarballs/opus-$(rel) -type d -name ".svn"`
	rm -rf `find ../tarballs/opus-$(rel) -name "*~"`
	rm -rf ../tarballs/opus-$(rel)/debian
	# actually perform the gzip
	cd ../tarballs && tar cfz opus_$(rel).orig.tar.gz opus-$(rel)
	rm -rf ../tarballs/opus-$(rel)
	@echo "Targz build in ../tarballs"

clean:
	find . \( -name "#*#" -or -name ".#*" -or -name "*~" -or -name ".*~" \) -exec rm -rfv {} \;
	rm -fv *.cache
	rm -rf debian/opus
	rm -rf debian/files

# Make development documentation, you will need phpdoc installed
devdoc:
	mkdir -p ../phpdoc/
	phpdoc -d html,include -t ../phpdoc/ -dc OPUS --title "OPUS Development Documentation"


#
# Currently contains commands for making with Debian primarily, but you can
# override these paths with the call the make install to override.
#
debetc=/etc
debprefix=/usr
debwww=/var/www
debvarlib=/var/lib

install:
	# Make main directory and copy in contents
	mkdir -p ${debprefix}/share/opus
	cp -rf html ${debprefix}/share/opus
	cp -rf include ${debprefix}/share/opus
	cp -rf cron ${debprefix}/share/opus
	cp -rf templates ${debprefix}/share/opus
	cp -rf configs ${debprefix}/share/opus
	mkdir -p ${debprefix}/share/opus/templates_c
	mkdir -p ${debprefix}/share/opus/templates_cache
	chown -R www-data:root ${debprefix}/share/opus/
	chmod -R o-rwx ${debprefix}/share/opus/
	# Make documentation directory
	mkdir -p ${debprefix}/share/doc/opus
	cp -rf sql_patch ${debprefix}/share/doc/opus
	gzip -c -9 ChangeLog > ${debprefix}/share/doc/opus/changelog.gz

debs: deb-opus, deb-opus-doc

deb-opus: deb-opus-etc
	# Make main directory and copy in contents
	mkdir -p ${debprefix}/share/opus
	mkdir -p ${debvarlib}/opus/templates_c
	mkdir -p ${debvarlib}/opus/templates_cache
	mkdir -p ${debvarlib}/opus/sessions
	cp -rf html ${debprefix}/share/opus
	# Remove extra license for tiny_mce when doing debian package (it's elsewhere)
	rm ${debprefix}/share/opus/html/javascript/tiny_mce/license.txt
	cp -rf include ${debprefix}/share/opus
	cp -rf cron ${debprefix}/share/opus
	cp -rf templates ${debprefix}/share/opus
	cp -rf configs ${debprefix}/share/opus
	chown -R www-data:root ${debprefix}/share/opus/
	chmod -R o-rwx ${debprefix}/share/opus/
	chown -R www-data:root ${debvarlib}/opus/
	chmod -R o-rwx ${debvarlib}/opus/
	# Make documentation directory
	mkdir -p ${debprefix}/share/doc/opus
	cp -rf sql_patch ${debprefix}/share/doc/opus
	gzip -c -9 ChangeLog > ${debprefix}/share/doc/opus/changelog.gz
	# Copy material for dbconfig-common
	mkdir -p ${debprefix}/share/dbconfig-common/data/opus/install
	cp sql_patch/schema.sql ${debprefix}/share/dbconfig-common/data/opus/install/mysql
	cat sql_patch/data.sql >> ${debprefix}/share/dbconfig-common/data/opus/install/mysql
	mkdir -p ${debprefix}/share/dbconfig-common/data/opus/upgrade/mysql
	cp sql_patch/patch_3.3.x_4.0.0.sql ${debprefix}/share/dbconfig-common/data/opus/upgrade/mysql/4.0.0
	cp etc/load_old_database.php ${debprefix}/share/opus/include/

deb-opus-etc: 
	mkdir -p ${debetc}/opus
	cp etc/opus-local.config.php.debian ${debetc}/opus/opus-local.config.php
	cp etc/apache2.conf ${debetc}/opus/apache2.conf
	cp etc/local_en.conf ${debetc}/opus/local_en.conf


deb-opus-doc:
	mkdir -p $(debprefix)/share/doc/opus-doc
	cp -rf docs ${debprefix}/share/doc/opus-doc

build_debs:
	dpkg-buildpackage -rfakeroot
