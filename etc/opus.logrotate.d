/var/log/opus/*.log {
	weekly
	missingok
	rotate 52
	compress
	delaycompress
	notifempty
	create 660 www-data root
}