-- Policy

alter table policy change column descript name tinytext not null;
alter table policy change column policy_id id int unsigned not null auto_increment;
alter table policy change column resources resource set('list','create','edit','delete');
alter table policy change column course programme set('create','edit','archive','list');
alter table policy change column log log set('general','security','debug','panic','waf_debug');
alter table policy add column faculty set('create','edit','archive','list') after log;

-- CV groups --

rename table cvgroups to cvgroup;
alter table cvgroup change column group_id id int unsigned not null auto_increment;

-- assessmentgroup --

rename table assessmentgroups to assessmentgroup;
alter table assessmentgroup change column group_id id int unsigned not null auto_increment;


-- assessmentregime --


alter table assessmentregime change column cassessment_id id int unsigned not null auto_increment;
alter table assessmentregime drop column options;

-- assessment --

alter table assessment drop column submission_url;
alter table assessment drop column results_url;
alter table assessment change column assessment_id id int unsigned not null auto_increment;

-- assessmentstructure --

alter table assessmentstructure add column id int unsigned not null auto_increment primary key;
alter table assessmentstructure change column options options enum('compulsory', 'optional') not null;
-- WARNINGS AFTER THIS ONE! --

-- vacancytype to activitytype --

rename table vacancytype to activitytype;
alter table activitytype change column vacancy_id id int unsigned not null auto_increment;

-- faculty --

create table faculty
(
  name tinytext not null,
  www tinytext,
  srs_ident tinytext,
  status enum('active', 'archive'),
  id int unsigned not null auto_increment primary key
);

-- courses -> programmes --

rename table courses to programme;
alter table programme change column course_code srs_ident varchar(30) not null;
alter table programme change column course_name name tinytext not null;
alter table programme change column status status enum('active', 'archive') not null;
alter table programme change column course_id id int unsigned auto_increment not null;

-- schools --

rename table schools to school;
alter table school change column school_id id int unsigned not null auto_increment;
alter table school change column school_name name tinytext not null;
alter table school add column srs_ident tinytext null after www;
alter table school add column faculty_id int unsigned not null after status;

--WARNING! CHECK THIS
alter table school change column status status enum('active', 'archive') not null;


-- companystudent -> applications --

rename table companystudent to application;
alter table application add column cv_source enum('none', 'internal', 'pds_template', 'pds_custom') after modified;
alter table application change column prefcvt cv_id int unsigned null;
alter table application add column portfolio_source enum('none', 'pds') after archive_mime_type;
alter table application add column portfolio_hash tinytext after portfolio_source;
alter table application add column status_modified datetime after status;
alter table application add column id int unsigned not null auto_increment primary key;


-- vacancies --

rename table vacancies to vacancy;
alter table vacancy change column vacancy_id id int unsigned auto_increment not null;

-- companies --

rename table companies to company;
alter table company change column company_id id int unsigned not null auto_increment;

-- resources --

rename table resources to resource;
alter table resource change column resource_id id int unisgned auto_increment;

-- channels --

rename table channels to channel;
alter table channel change column channel_id id int unsigned auto_increment;

rename table channelassociations to channelassociation;
-- automail --

alter table automail add column id int unsigned auto_increment primary key;
 alter table automail change column language language_id int unsigned;
 alter table help change column language language_id int unsigned;

-- languages --

rename table languages to language;
alter table language change column language_id id int unsigned auto_increment;
alter table language change column language name tinytext not null;
alter table language add column ident varchar(10) not null after name;

-- mime types --

rename table mime_types to mimetype;
alter table mimetype change column mime_id id int unsigned not null auto_increment;
alter table mimetype change column flags uploadable enum('yes', 'no') not null;

-- WARNING NEED PHP TO FIX THIS LAST ONE --

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

-- this will need php conversion :-( --