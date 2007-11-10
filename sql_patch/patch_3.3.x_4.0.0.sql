-- The upgrade from OPUS 3.3.x to 4.0.0 is profound at the database level
-- and for the first time ever consists of two parts.

-- new service table --

create table service
(
  status enum('started', 'stopped') not null,
  schema_version tinytext not null,
  id int unsigned not null auto_increment primary key
);

insert into service values('stopped', '4.0.0', 1);

-- placement --

alter table placement change column placement_id id int unsigned auto_increment;
alter table placement change column supervisor_surname supervisor_lastname tinytext;

-- notes --

rename table notes to note;
alter table note change column note_id id int unsigned not null auto_increment;
alter table notelink add column id int unsigned not null auto_increment primary key;
alter table notelink add column main enum('yes', 'no') after note_id;

-- timelines --

rename table timelines to timeline;
alter table timeline change column last_updated last_updated timestamp;
alter table timeline add column id int unsigned auto_increment primary key;

-- policy --

alter table policy change column descript name tinytext not null;
alter table policy change column policy_id id int unsigned not null auto_increment;
alter table policy change column resources resource set('list','create','edit','delete');
alter table policy change column course programme set('create','edit','archive','list');
alter table policy change column log log set('general','security','debug','panic','waf_debug');
alter table policy add column faculty set('create','edit','archive','list') after log;

-- vacancyactivity --

alter table vacancyactivity add column id int unsigned not null auto_increment primary key;

-- companyactivity --
-- long ago, OPUS referred to what are now acivity types as vacancy types --

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
insert into vacancytype values("Summer Job", NULL, 20, 2);
insert into vacancytype values("Graduate Job", NULL, 30, 3);

-- vacancies --

rename table vacancies to vacancy;
alter table vacancy change column vacancy_id id int unsigned auto_increment not null;
alter table vacancy add column vacancy_type int unsigned not null;
-- we need all these new types to have a sensible default --
update vacancy set vacancy_type=1;

-- companies --

rename table companies to company;
alter table company change column company_id id int unsigned not null auto_increment;

-- companycontact --

alter table companycontact add column id int unsigned not null auto_increment primary key;
alter table companycontact change column status status enum('normal','restricted','primary', 'archived') not null;

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

-- assessmenttotal --

rename table assessmenttotals to assessmenttotal;
alter table assessmenttotal change column cassessment_id regime_id int unsigned not null;
alter table assessmenttotal add column id int unsigned auto_increment not null primary key;

rename table assessmentresults to assessmentresult;
alter table assessmentresult change column cassessment_id regime_id int unsigned not null;
alter table assessmentresult add column id int unsigned not null auto_increment primary key;

-- assessmentstructure --

alter table assessmentstructure add column id int unsigned not null auto_increment primary key;
alter table assessmentstructure change column options options enum('compulsory', 'optional') not null;
-- TODO: WARNINGS AFTER THIS ONE! --

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
-- TODO: cvgroup information needs copied --

-- schools --

rename table schools to school;
alter table school change column school_id id int unsigned not null auto_increment;
alter table school change column school_name name tinytext not null;
alter table school add column srs_ident tinytext null after www;
alter table school add column faculty_id int unsigned not null after status;
--WARNING! CHECK THIS
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
alter table application add column cv_source enum('none', 'internal', 'pds_template', 'pds_custom') after modified;
alter table application change column prefcvt cv_id int unsigned null;
alter table application add column portfolio_source enum('none', 'pds') after archive_mime_type;
alter table application add column portfolio_hash tinytext after portfolio_source;
alter table application add column status_modified datetime after status;
alter table application add column id int unsigned not null auto_increment primary key;


-- resources --

rename table resources to resource;
alter table resource change column resource_id id int unsigned auto_increment;

-- channels --

rename table channels to channel;
alter table channel change column channel_id id int unsigned auto_increment;

rename table channelassociations to channelassociation;
-- automail --

alter table automail add column id int unsigned auto_increment primary key;
alter table automail change column language language_id int unsigned;

-- languages --

rename table languages to language;
alter table language change column language_id id int unsigned auto_increment;
alter table language change column language name tinytext not null;
alter table language add column ident varchar(10) not null after name;

-- mime types --

rename table mime_types to mimetype;
alter table mimetype change column mime_id id int unsigned not null auto_increment;
alter table mimetype change column flags uploadable enum('yes', 'no') not null;
-- WARNING ON THIS ONE --

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

-- NOW MAJOR CHANGES - USER TABLES --

-- users --

rename table id to user;
alter table user change column real_name real_name tinytext;
alter table user change column id_number id int unsigned not null auto_increment;
alter table user add column online enum('online', 'idle', 'offline') after last_index;
alter table user add column reg_number tinytext after user;
alter table user change column user user_type enum('student','root','company','staff','admin','supervisor') not null;
alter table user add column lastname tinytext after user_type;
alter table user add column firstname tinytext after user_type;
alter table user add column salutation tinytext after user_type;
alter table user add column email tinytext after lastname;
alter table user add column login_time datetime;

-- students --

rename table students to student;
alter table student change column year placement_year year not null;
alter table student change column status placement_status enum('Required','Placed','Exempt Applied','Exempt Given','No Info','Left Course','Suspended','To final year','Not Eligible');
alter table student add column programme_id int unsigned;
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



