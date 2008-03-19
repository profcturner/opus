#
# Regular cron jobs for the opus package
#
#  m h  dom mon dow user command
# Every month
0 5 1 * * root  test -x /usr/sbin/opus && /usr/sbin/opus phone_home_install >/dev/null
0 5 1 * * root  test -x /usr/sbin/opus && /usr/sbin/opus phone_home_periodic >/dev/null
# Every hour.
0 * * * * root  test -x /usr/sbin/opus && /usr/sbin/opus update_timelines >/dev/null
# Every five minutes.
*/5 * * * * root  test -x /usr/sbin/opus && /usr/sbin/opus expire_vacancies >/dev/null
