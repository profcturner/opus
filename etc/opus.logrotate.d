/var/log/opus/*.log {
	weekly
	missingok
	rotate 52
	compress
	notifempty
	create 660 www-data root
}