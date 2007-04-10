#
# Regular cron jobs for the opus package
#
#  m h  dom mon dow user command
5 *	* * *	www-data	/usr/bin/php -q /usr/share/opus/cron/update_vacancy_status.php > /dev/null
