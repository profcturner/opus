This is the OPUS command line tool, often used in cron tasks. On many systems,
you can obtain more help with man opus.

Usage:

<scriptname> <function>

where function is one of

  user_count            show information on how many users are installed
  company_count         show how many companies are stored
  vacancy_count         show how many vacancies are stored
  expire_vacancies      mark vacancies past their close date as expired
  phone_home_install    send an email to UU notifying of this install
                        (if permitted)
  phone_home_periodic   send non confidential item counts to UU for analysis
  update_perl_config    rewrite the perl configuration needed for some other
                        tasks
  update_timelines      update timelines for the current, previous and next
                        year
  change_password       change_password "username=foo&password=bar"
  auto_add_students     auto_add_students "filename=..."
  start                 start OPUS
  stop                  stop OPUS (only root users can log in)
  dev_help              show command line options for developers

