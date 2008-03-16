-- The upgrade of schema and data from OPUS 3.3.x to 4.0 is the most complex yet.
-- TAKE A BACKUP WITH MYSQLDUMP!

-- new service table --

create table service
(
  status enum('started', 'stopped') not null,
  schema_version tinytext not null,
  id int unsigned not null auto_increment primary key
);

-- new application is stopped --
insert into service values('stopped', '4.0.0', 1);

-- cache objects --

create table `cache_object` (
  `id` int(11) not null auto_increment,
  `type` text not null,
  `key` text not null,
  `cache` longtext not null,
  `timestamp` varchar(50) default null,
  `read_count` int(11) not null,
  `refresh_count` int(11) not null,
  primary key  (`id`)
);

-- artefacts --

create table `artefact` (
  `id` int(10) unsigned not null auto_increment,
  `group` text not null,
  `user_id` int(11) not null default '0',
  `type` varchar(50) not null default '',
  `file_name` varchar(250) not null default '',
  `file_size` varchar(250) not null default '',
  `file_type` varchar(250) not null default '',
  `data` text not null,
  `description` varchar(250) not null default '',
  `hash` varchar(250) not null default '',
  `thumb` text not null,
  primary key  (`id`),
  key `userid` (`user_id`)
) engine=myisam auto_increment=18602 default charset=latin1;

-- cvs (internal) --

create table `cv` (
  `id` int(10) unsigned not null auto_increment,
  `user_id` int(11) not null default '0',
  `artefact_id` int(10) unsigned not null,
  `title` text not null,
  `file_type` varchar(255) not null default '',
  `created` datetime not null,
  `modified` datetime not null,
  `description` text not null,
  primary key  (`id`),
  key `userid` (`user_id`)
) engine=myisam auto_increment=863 default charset=latin1 comment='//cv builder cv archive';


-- placement --

alter table placement change column placement_id id int unsigned auto_increment;
alter table placement change column supervisor_surname supervisor_lastname tinytext;

-- notes --

rename table notes to note;
alter table note change column note_id id int unsigned not null auto_increment;
alter table notelink add column id int unsigned not null auto_increment primary key;
alter table notelink add column main enum('yes', 'no') after note_id;
alter table notelink change column link_type link_type enum('Student','Staff','Admin','Company','Contact','Vacancy');

-- timelines --

rename table timelines to timeline;
alter table timeline change column last_updated last_updated timestamp;
alter table timeline add column id int unsigned auto_increment primary key;

-- policy --

alter table policy change column descript name tinytext not null;
alter table policy change column policy_id id int unsigned not null auto_increment;
alter table policy change column resources resource set('list','create','edit','delete');
alter table policy change column course programme set('create','edit','archive','list');
alter table policy change column log log set('general','admin','cron','security','debug','panic','waf_debug','php_error');
alter table policy add column faculty set('create','edit','archive','list') after log;

-- vacancyactivity --

alter table vacancyactivity add column id int unsigned not null auto_increment primary key;

-- companyactivity --
-- long ago, OPUS referred to what are now activity types as vacancy types --

rename table companyvacancy to companyactivity;
alter table companyactivity change column vacancy_id activity_id int unsigned;
alter table companyactivity add column id int unsigned not null auto_increment primary key;

-- vacancytype to activitytype --

rename table vacancytype to activitytype;
alter table activitytype change column vacancy_id id int unsigned not null auto_increment;

-- vacancytype is now used for a new, more appropriate purpose --

create table vacancytype
(
  name tinytext not null,
  priority int,
  help text,
  id int unsigned not null auto_increment primary key
);

insert into vacancytype values("One Year, Full Time", 10, NULL, 1);
insert into vacancytype values("Summer Job", 20, NULL, 2);
insert into vacancytype values("Graduate Job", 30, NULL, 3);

-- vacancies --

rename table vacancies to vacancy;
alter table vacancy change column vacancy_id id int unsigned auto_increment not null;
alter table vacancy add column vacancy_type int unsigned not null;
-- we need all these new types to have a sensible default --
update vacancy set vacancy_type=1;

-- companies --

rename table companies to company;
alter table company change column company_id id int unsigned not null auto_increment;

-- companycontact -- this will be remapped later, since contact_id is not the user_id of the contacts in 3.3.x --

alter table companycontact add column id int unsigned not null auto_increment primary key;
alter table companycontact change column status status enum('primary','normal','restricted','archived') not null;

-- assessmentgroup --

rename table assessmentgroups to assessmentgroup;
alter table assessmentgroup change column group_id id int unsigned not null auto_increment;

alter table assessmentgroupcourse change column course_id programme_id int unsigned not null;
rename table assessmentgroupcourse to assessmentgroupprogramme;

-- assessmentregime --

alter table assessmentregime change column cassessment_id id int unsigned not null auto_increment;
alter table assessmentregime drop column options;

-- assessment --

alter table assessment drop column submission_url;
alter table assessment drop column results_url;
alter table assessment change column assessment_id id int unsigned not null auto_increment;

update assessment set student_description="First Visit", template_filename='assessment/uu/first_visit_nomark.tpl' where id = 1;
update assessment set student_description="Technical Report", template_filename='assessment/uu/technical_report.tpl' where id = 2;
update assessment set student_description="Final Visit", template_filename='assessment/uu/final_visit.tpl' where id = 3;
update assessment set student_description="Final Report", template_filename='assessment/uu/placement_report.tpl' where id = 5;
update assessment set student_description="Supervisor's Report", template_filename='assessment/uu/industrial_report.tpl' where id = 6;
update assessment set student_description="Presentation", template_filename='assessment/uu/presentation.tpl' where id = 7;
update assessment set student_description="Health & Safety", template_filename='assessment/uu/student_healthsafety.tpl' where id = 8;
update assessment set student_description="First Visit", template_filename='assessment/uu/first_visit.tpl' where id = 9;

-- assessmenttotal --

rename table assessmenttotals to assessmenttotal;
alter table assessmenttotal change column cassessment_id regime_id int unsigned not null;
alter table assessmenttotal add column id int unsigned auto_increment not null primary key;

rename table assessmentresults to assessmentresult;
alter table assessmentresult change column cassessment_id regime_id int unsigned not null;
alter table assessmentresult add column id int unsigned not null auto_increment primary key;

-- assessmentstructure --

alter table assessmentstructure add column id int unsigned not null auto_increment primary key;
alter table assessmentstructure add column temp enum('compulsory', 'optional') not null;
update assessmentstructure set temp='optional';
update assessmentstructure set temp='compulsory' where options='compulsory';
alter table assessmentstructure drop column options;
alter table assessmentstructure change column temp options enum('compulsory', 'optional') not null;

-- assessmentother --

alter table assessorother change column cassessment_id regime_id int unsigned not null;
alter table assessorother add column id int unsigned auto_increment primary key;

-- CV groups --

rename table cvgroups to cvgroup;
alter table cvgroup change column group_id id int unsigned not null auto_increment;

alter table cvgrouptemplate add column id int unsigned auto_increment primary key;

-- courses -> programmes --

rename table courses to programme;
alter table programme change column course_code srs_ident varchar(30) not null;
alter table programme change column course_name name tinytext not null;
alter table programme change column status status enum('active', 'archive') not null;
alter table programme change column course_id id int unsigned auto_increment not null;
alter table programme add column cvgroup_id int unsigned;

-- cvgroup information needs copied --
update programme, cvgroupcourse set programme.cvgroup_id = cvgroupcourse.group_id where programme.id = cvgroupcourse.course_id;

-- schools --

rename table schools to school;
alter table school change column school_id id int unsigned not null auto_increment;
alter table school change column school_name name tinytext not null;
alter table school add column srs_ident tinytext null after www;
alter table school add column faculty_id int unsigned not null after status;
alter table school change column status status enum('active', 'archive') not null;
-- need a sensible default faculty for schools --
update school set faculty_id=1;

-- faculty --

create table faculty
(
  name tinytext not null,
  www tinytext,
  srs_ident tinytext,
  status enum('active', 'archive'),
  id int unsigned not null auto_increment primary key
);
-- need to create a new dummy faculty as a container --
insert into faculty (name, status, id) value("Default Faculty", "active", 1);

-- admin link tables --

rename table adminschool to schooladmin;
alter table schooladmin add column id int unsigned not null auto_increment primary key;

rename table admincourse to programmeadmin;
alter table programmeadmin change column course_id programme_id int unsigned not null;
alter table programmeadmin add column id int unsigned not null auto_increment primary key;

create table facultyadmin
(
  admin_id int unsigned not null,
  faculty_id int unsigned not null,
  policy_id int unsigned,
  id int unsigned not null auto_increment primary key
);

-- companystudent -> applications --

rename table companystudent to application;
alter table application add column cv_ident tinytext not null after modified;
update application set cv_ident = concat('pdsystem:template:', prefcvt) where archive_hash is null;
update application set cv_ident = concat('pdsystem:hash:', archive_hash) where archive_hash is not null;
alter table application drop column prefcvt;
alter table application drop column archive_hash;
alter table application add column portfolio_ident tinytext not null after archive_mime_type;
alter table application add column status_modified datetime after status;
alter table application add column id int unsigned not null auto_increment primary key;

-- cv approval --

rename table cv_approval to cvapproval;
alter table cvapproval add column cv_ident tinytext not null after student_id;
update cvapproval set cv_ident = concat('pdsystem:template:', template_id);
alter table cvapproval drop column template_id;

-- resources --

rename table resources to resource;
alter table resource change column resource_id id int unsigned auto_increment;
alter table resource add column company_id int unsigned NULL after status;
alter table resource add column vacancy_id int unsigned NULL after company_id;
update resource, resourcelink set resource.company_id = resourcelink.company_id, resource.vacancy_id = resourcelink.vacancy_id where resource.id = resourcelink.resource_id;
drop table resourcelink;

-- channels --

rename table channels to channel;
alter table channel change column channel_id id int unsigned auto_increment;

rename table channelassociations to channelassociation;
alter table channelassociation change column type type set('course','school','assessmentgroup','activity','user') not null;

-- automail --

alter table automail add column id int unsigned auto_increment primary key;
alter table automail change column language language_id int unsigned;

insert into `automail` (language_id, lookup, fromh, toh, cch, bcch, subject, description, contents) values(1,'PhoneHome_Install','%afirstname% %asurname% <%aemail%>','opus-stats@foss.ulster.ac.uk','','','New OPUS installation','This is used to send an email to UU upon installation, only with your permission','New OPUS install\r\n\r\ninstitution: %conf_institution%\r\nurl: %conf_website%\r\nversion: %conf_version%\r\n');
insert into `automail` (language_id, lookup, fromh, toh, cch, bcch, subject, description, contents) values(1,'PhoneHome_Periodic','%afirstname% %asurname% <%aemail%>','opus-stats@foss.ulster.ac.uk','','','OPUS Periodic Statistics','Non confidential information, send periodically with your consent, to the University of Ulster','institution: %conf_institution%\r\nurl: %conf_website%\r\nversion: %conf_version%\r\n\r\nstudents: %custom_students%\r\nroots: %custom_roots%\r\nadmins: %custom_admins%\r\ncontacts: %custom_contacts%\r\nstaff: %custom_staff%\r\nsupervisors: %custom_supervisors%\r\ncompanies: %custom_companies%\r\nvacancies: %custom_vacancies%\r\n');


-- languages --

rename table languages to language;
alter table language change column language_id id int unsigned auto_increment;
alter table language change column language name tinytext not null;
alter table language add column ident varchar(10) not null after name;

-- mime types --

rename table mime_types to mimetype;
alter table mimetype change column mime_id id int unsigned not null auto_increment;
alter table mimetype add column uploadable enum('yes', 'no') not null;
update mimetype set uploadable='no';
update mimetype set uploadable='yes' where flags='uploadable';
alter table mimetype drop column flags;

-- help --
-- this is moving to the new XML framework long used for vacancies and companies --

alter table help change column language language_id int unsigned;
update help set contents = replace(contents,'<CENTER>','');
update help set contents = replace(contents,'</CENTER>','');
update help set contents = replace(contents,'<TITLE>','<h4>');
update help set contents = replace(contents,'</TITLE>','</h4>');

-- phonehome --

create table phonehome
(
  send_install enum('Ask', 'Yes', 'No'),
  send_periodic enum('Ask', 'Yes', 'No'),
  cc_on_email enum('Yes', 'No'),
  timestamp_install timestamp,
  timestamp_periodic timestamp,
  admin_id int unsigned,
  id int unsigned primary key
);

-- make sure we initially ask --
insert into phonehome (send_install, send_periodic, cc_on_email, timestamp_install, timestamp_periodic, admin_id, id) values('Ask', 'Ask', 'No', NULL, NULL, 0, 1);

-- CSV Mapping --

create table csvmapping
(
  name tinytext not null,
  pattern tinytext not null,
  replacement tinytext not null,
  exclude tinytext not null,
  id int unsigned not null auto_increment primary key
);

-- University of Ulster examples -- should be harmless to have these for other institutions, and they'll help explain the idea --

INSERT INTO `csvmapping` VALUES ('University of Ulster Module List','/^\"(.*)\",\"(.*)\",\"(.*), (.*) (.*)\",\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\"$/','\"${1}\",\"${2}\",\"${5}\",\"${4}\",\"${3}\",\"${11}\",\"${6}\",\"\"','',1),('University of Ulster Programme List','/^\"(.*)\",\"(.*)\",\"(.*), (.*) (.*)\",\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\",\"(.*)\"$/','\"${1}\",\"${2}\",\"${5}\",\"${4}\",\"${3}\",\"${11}\",\"\",\"\"','',2);

-- NOW MAJOR CHANGES - USER TABLES --

-- users --

rename table id to user;
alter table user change column real_name real_name tinytext;
alter table user change column id_number id int unsigned not null auto_increment;
alter table user add column online enum('online', 'idle', 'offline') after last_index;
alter table user add column reg_number tinytext after user;
alter table user change column user user_type enum('student','root','company','staff','admin','supervisor','application') not null;
alter table user add column lastname tinytext after user_type;
alter table user add column firstname tinytext after user_type;
alter table user add column salutation tinytext after user_type;
alter table user add column email tinytext after lastname;
alter table user add column login_time datetime;
alter table user add column session_hash tinytext after `online`;

-- students --

rename table students to student;
alter table student change column year placement_year year not null;
alter table student change column status placement_status enum('Required','Placed','Exempt Applied','Exempt Given','No Info','Left Course','Suspended','To final year','Not Eligible');
alter table student add column programme_id int unsigned;
alter table student add column academic_user_id int unsigned after programme_id;
alter table student add column id int unsigned not null auto_increment primary key;

-- need to copy information to user --

create table contact 
(
  position tinytext,
  voice tinytext,
  fax tinytext,
  user_id int unsigned not null,
  id int unsigned not null auto_increment primary key
);

create table admin
(
  position tinytext,
  voice tinytext,
  fax tinytext,
  signature text,
  address text,
  help_directory enum('yes', 'no'),
  status enum('active', 'archive'),
  policy_id int unsigned null,
  inst_admin enum('no', 'yes'),
  user_id int unsigned not null,
  id int unsigned not null auto_increment primary key
);

alter table staff add column id int unsigned auto_increment primary key;
alter table staff change column status status enum('active', 'archive');

-- add some relevant indices --
alter table student add index user_id (user_id);
alter table admin add index user_id (user_id);
alter table contact add index user_id (user_id);
alter table staff add index user_id (user_id);
create index username on user (username(15));
create index name on company (name(10));
create index description on vacancy (description(10));


-- complex queries to move data between tables --

-- old student data migratation -- data moves from cv_pdetails to student and user --
update user, cv_pdetails set user.salutation = cv_pdetails.title, user.firstname = cv_pdetails.firstname, user.lastname = cv_pdetails.surname, user.reg_number = user.username, user.email = cv_pdetails.email where user.id = cv_pdetails.id;
update student, cv_pdetails set student.programme_id = cv_pdetails.course where student.user_id = cv_pdetails.id;

-- admin migration -- move some data into user, and some into the new admin table (not admin_s_) --
update user, admins set user.salutation = admins.title, user.firstname = admins.firstname, user.lastname = admins.surname, user.email = admins.email, user.reg_number = concat('e', admins.staffno) where admins.user_id = user.id;
insert into admin (position, voice, fax, signature, policy_id, user_id) select position, voice, fax, signature, policy_id, user_id from admins;

-- staff migration -- move some data into user --
update user, staff set user.salutation = staff.title, user.firstname = staff.firstname, user.lastname = staff.surname, user.reg_number = concat('e', staff.staffno) user.email = staff.email where user.id=staff.user_id;

-- contact_id in these tables used to be the id from the contact, not user table --
update companycontact, contacts set companycontact.contact_id = contacts.user_id where companycontact.contact_id = contacts.contact_id;
update vacancy, contacts set vacancy.contact_id = contacts.user_id where vacancy.contact_id = contacts.contact_id;

-- and now move date to the user and new contact table --
update user, contacts set user.salutation = contacts.title, user.firstname = contacts.firstname, user.lastname = contacts.surname, user.email = contacts.email where user.id = contacts.user_id;
insert into contact (position, voice, fax, user_id) select position, voice, fax, user_id from contacts;

-- supervisors need more data copied in --
update user, placement set user.salutation = supervisor_title, user.firstname = supervisor_firstname, user.lastname = supervisor_lastname, user.email = supervisor_email where user.username = CONCAT('supervisor_', placement.id);

-- information about academic tutors is now in the student table --
update student, staffstudent set student.academic_user_id = staffstudent.staff_id where student.user_id = staffstudent.student_id; 

-- we should now be safe to remove columns, and add others --
-- staff table -- drop columns moves to user, add some more --
alter table staff drop column initials;
alter table staff drop column title;
alter table staff drop column firstname;
alter table staff drop column surname;
alter table staff drop column department;
alter table staff drop column staffno;
alter table staff drop column email;
alter table staff add column postcode tinytext not null after address;

-- add some more help --

insert into help (language_id, lookup, channel_id, auth, description, contents) values(1,'RootManageCSVMapping',0,'admin','More detailed help on how to set up a CSV Mapping','<p>\r\nCSV Mappings are usually a way of mapping your own University format CSV files to a &quot;standard&quot; format used by OPUS. Note however, that your original file format can really be any text file which has one student per line. It\'s just as practical to use TSV or similar formats.­</p>\r\n  <p> To set up a mapping, you will need to understand <a target=\"blank\" href=\"http://www.php.net/manual/en/reference.pcre.pattern.syntax.php\">regular expressions</a>, as used by the <a target=\"blank\" href=\"http://www.php.net/manual/en/function.preg-replace.php\">preg_replace</a> function in PHP. If you don\'t, it is strongly recommended you file a service ticket or ask a member of IT staff to help you set this up; regular expressions are a relatively complex concept if your format requires anything but the simplest manipulation.\r\n    <br /></p>\r\n  <p>OPUS expects the replacement line to map to the following:</p>\r\n  <p>&quot;year_of_study&quot;,\r\n    \r\n    \r\n    \r\n    \r\n    <br />&quot;student_reg_number&quot;,\r\n    \r\n    \r\n    \r\n    \r\n    <br />&quot;title&quot;,\r\n    \r\n    \r\n    \r\n    \r\n    <br />&quot;firstname&quot;,\r\n    \r\n    \r\n    \r\n    \r\n    <br />&quot;lastname&quot;,\r\n    \r\n    \r\n    \r\n    \r\n    <br />&quot;email&quot;,\r\n    \r\n    \r\n    \r\n    \r\n    <br />&quot;programme_code&quot;,\r\n    \r\n    \r\n    \r\n    \r\n    <br />&quot;disability_code&quot;\r\n    \r\n    \r\n    \r\n    \r\n    \r\n    <br /></p>\r\n  <p>&nbsp;</p>\r\n  <p>all on one line. Note that if you don\'t have information about one of these, you should include the quotes with no content &quot;&quot;. This functionality is on this menu since errors here will generate errors in the student import functionality. </p>');

-- change some help --

update help set description="Welcome message for Root Users", contents = '<p>\r\nWelcome. You are a super-admin user on this system (root user). This user is unconstrained by the policy system, and can perform almost any action within OPUS, including unwise ones. You should not use a user of this power without appropriate training.</p>\r\n  <p>Above, you will see you main menu, which you will use to manipulate OPUS. The sections are:</p>\r\n  <ul>\r\n    <li><strong>home</strong> which contains this page, an ability to look at company activity and change your password;</li>\r\n    <li><strong>directories</strong> which will be the main menu you use day-to-day to work with students, companies and vacancies;</li>\r\n    <li><strong>information</strong> which is used to look as status and get reports;</li>\r\n    <li><strong>configuration</strong> which will be needed to set up OPUS and modify it\'s behaviour;</li>\r\n    <li><strong>advanced</strong> contains more configuration options usually only needed to larger OPUS systems;</li>\r\n    <li><strong>superuser</strong> contains options to maintain OPUS and sensitive configuration.</li>\r\n  </ul>From time to time other menus will appear as you manipulate objects, most notably students and companies, to help you deal with these. Also a <strong>recent</strong> menu will appear to allow you to more rapidly get back to objects you have been working with in this session.\r\n  <br />' where lookup="RootHome";

update help set description="Welcome message for Admin Users", contents = '<p>\r\nWelcome. You are an administrator user on this system. This user is constrained by a security policy, and additionally may only be able to interact with given programmes and schools. Contact a super-administrator if you feel you do not have the access you need.</p>\r\n  <p>Above, you will see you main menu, which you will use to manipulate OPUS. The sections are:</p>\r\n  <ul>\r\n    <li><strong>home</strong> which contains this page, an ability to look at company activity and change your password;</li>\r\n    <li><strong>directories</strong> which will be the main menu you use day-to-day to work with students, companies and vacancies;</li>\r\n    <li><strong>information</strong> which is used to look as status and get reports;</li>\r\n    <li><strong>configuration</strong> which will be needed to set up OPUS and modify it\'s behaviour;</li>\r\n    <li><strong>advanced</strong> contains more configuration options usually only needed to larger OPUS systems;</li>\r\n    </ul>From time to time other menus will appear as you manipulate objects, most notably students and companies, to help you deal with these. Also a <strong>recent</strong> menu will appear to allow you to more rapidly get back to objects you have been working with in this session.\r\n  <br />' where lookup="AdminHome";

-- lots of tables are now obselete, or unused, get rid --

-- drop table admins;
-- drop table contacts;
-- drop table ocvcomponent;
-- drop table ocvfield;
-- drop table ocvstudent2template;
-- drop table ocvstudentdata;
-- drop table ocvtemplate;
-- drop table ocvtemplatedescription;
-- drop table cv_pdetails;
-- drop table cv_edetails;
-- drop table cv_cdetails;
-- drop table cv_edetails;
-- drop table cv_odetails;
-- drop table cv_pdetails;
-- drop table cv_results;
-- drop table cv_work;
-- drop table staffstudent;
-- drop table cvgroupcourse;