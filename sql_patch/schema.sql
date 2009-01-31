-- MySQL dump 10.11
--
-- Host: localhost    Database: opusproduction
-- ------------------------------------------------------
-- Server version	5.0.32-Debian_7etch5-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activitytype`
--

DROP TABLE IF EXISTS `activitytype`;
CREATE TABLE `activitytype` (
  `name` varchar(40) default NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `position` tinytext,
  `voice` tinytext,
  `fax` tinytext,
  `signature` text,
  `address` text,
  `help_directory` enum('yes','no') default NULL,
  `status` enum('active','archive') default NULL,
  `policy_id` int(10) unsigned default NULL,
  `inst_admin` enum('no','yes') default NULL,
  `user_id` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;

--
-- Table structure for table `adminactivity`
--

DROP TABLE IF EXISTS `adminactivity`;
CREATE TABLE `adminactivity` (
  `admin_id` int(10) unsigned NOT NULL default '0',
  `activity_id` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `title` varchar(10) NOT NULL default '',
  `firstname` varchar(80) NOT NULL default '',
  `surname` varchar(80) NOT NULL default '',
  `position` varchar(80) NOT NULL default '',
  `voice` varchar(30) default NULL,
  `fax` varchar(30) default NULL,
  `address` text,
  `email` varchar(80) NOT NULL default '',
  `signature` text,
  `staffno` varchar(15) default NULL,
  `status` set('help') default NULL,
  `policy_id` int(10) unsigned default NULL,
  `user_id` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `application`
--

DROP TABLE IF EXISTS `application`;
CREATE TABLE `application` (
  `company_id` int(10) unsigned NOT NULL default '0',
  `vacancy_id` int(10) unsigned default NULL,
  `student_id` int(10) unsigned NOT NULL default '0',
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `cv_ident` tinytext NOT NULL,
  `archive_mime_type` tinytext,
  `portfolio_ident` tinytext NOT NULL,
  `cover` text,
  `status` enum('unseen','seen','invited to interview','missed interview','offered','unsuccessful') default 'unseen',
  `status_modified` datetime default NULL,
  `lastseen` datetime default NULL,
  `addedby` int(10) unsigned default NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`),
  KEY `comp_index` (`company_id`),
  KEY `stud_index` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22562 DEFAULT CHARSET=latin1;

--
-- Table structure for table `artefact`
--

DROP TABLE IF EXISTS `artefact`;
CREATE TABLE `artefact` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `group` text NOT NULL,
  `user_id` int(11) NOT NULL default '0',
  `type` varchar(50) NOT NULL default '',
  `file_name` varchar(250) NOT NULL default '',
  `file_size` varchar(250) NOT NULL default '',
  `file_type` varchar(250) NOT NULL default '',
  `data` text NOT NULL,
  `description` varchar(250) NOT NULL default '',
  `hash` varchar(250) NOT NULL default '',
  `thumb` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `userid` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18602 DEFAULT CHARSET=latin1;

--
-- Table structure for table `assessment`
--

DROP TABLE IF EXISTS `assessment`;
CREATE TABLE `assessment` (
  `description` tinytext NOT NULL,
  `student_description` tinytext,
  `template_filename` tinytext,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Table structure for table `assessmentgroup`
--

DROP TABLE IF EXISTS `assessmentgroup`;
CREATE TABLE `assessmentgroup` (
  `name` varchar(80) NOT NULL default '',
  `comments` text,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Table structure for table `assessmentgroupprogramme`
--

DROP TABLE IF EXISTS `assessmentgroupprogramme`;
CREATE TABLE `assessmentgroupprogramme` (
  `group_id` int(10) unsigned NOT NULL default '0',
  `startyear` year(4) default NULL,
  `endyear` year(4) default NULL,
  `programme_id` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=latin1;

--
-- Table structure for table `assessmentregime`
--

DROP TABLE IF EXISTS `assessmentregime`;
CREATE TABLE `assessmentregime` (
  `group_id` int(10) unsigned NOT NULL default '0',
  `assessment_id` int(10) unsigned NOT NULL default '0',
  `weighting` float NOT NULL default '0',
  `start` varchar(4) default NULL,
  `end` varchar(4) NOT NULL default '',
  `year` int(11) NOT NULL default '0',
  `student_description` tinytext,
  `outcomes` text,
  `assessor` enum('academic','industrial','student','other') default NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

--
-- Table structure for table `assessmentresult`
--

DROP TABLE IF EXISTS `assessmentresult`;
CREATE TABLE `assessmentresult` (
  `regime_id` int(10) unsigned NOT NULL,
  `assessed_id` int(10) unsigned NOT NULL default '0',
  `name` tinytext NOT NULL,
  `contents` text,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=70532 DEFAULT CHARSET=latin1;

--
-- Table structure for table `assessmentstructure`
--

DROP TABLE IF EXISTS `assessmentstructure`;
CREATE TABLE `assessmentstructure` (
  `assessment_id` int(10) unsigned NOT NULL default '0',
  `human` tinytext NOT NULL,
  `type` enum('textual','numeric','checkbox','assesseddate') NOT NULL default 'textual',
  `min` int(10) unsigned default NULL,
  `max` int(10) unsigned default NULL,
  `weighting` float NOT NULL default '0',
  `name` tinytext NOT NULL,
  `varorder` int(10) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL auto_increment,
  `options` enum('compulsory','optional') NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=135 DEFAULT CHARSET=latin1;

--
-- Table structure for table `assessmenttotal`
--

DROP TABLE IF EXISTS `assessmenttotal`;
CREATE TABLE `assessmenttotal` (
  `regime_id` int(10) unsigned NOT NULL,
  `assessed_id` int(10) unsigned NOT NULL default '0',
  `assessor_id` int(10) unsigned NOT NULL default '0',
  `comments` text,
  `mark` int(10) unsigned NOT NULL default '0',
  `outof` int(10) unsigned NOT NULL default '0',
  `percentage` float default NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime default NULL,
  `assessed` datetime default NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4550 DEFAULT CHARSET=latin1;

--
-- Table structure for table `assessorother`
--

DROP TABLE IF EXISTS `assessorother`;
CREATE TABLE `assessorother` (
  `assessed_id` int(10) unsigned NOT NULL default '0',
  `assessor_id` int(10) unsigned NOT NULL default '0',
  `regime_id` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=185 DEFAULT CHARSET=latin1;

--
-- Table structure for table `automail`
--

DROP TABLE IF EXISTS `automail`;
CREATE TABLE `automail` (
  `language_id` int(10) unsigned default NULL,
  `lookup` tinytext NOT NULL,
  `fromh` tinytext,
  `toh` tinytext,
  `cch` tinytext,
  `bcch` tinytext,
  `subject` tinytext,
  `description` tinytext,
  `contents` text,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Table structure for table `cache_object`
--

DROP TABLE IF EXISTS `cache_object`;
CREATE TABLE `cache_object` (
  `id` int(11) NOT NULL auto_increment,
  `type` text NOT NULL,
  `key` text NOT NULL,
  `cache` longtext NOT NULL,
  `timestamp` varchar(50) default NULL,
  `read_count` int(11) NOT NULL,
  `refresh_count` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=463 DEFAULT CHARSET=latin1;

--
-- Table structure for table `channel`
--

DROP TABLE IF EXISTS `channel`;
CREATE TABLE `channel` (
  `name` tinytext NOT NULL,
  `description` tinytext,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Table structure for table `channelassociation`
--

DROP TABLE IF EXISTS `channelassociation`;
CREATE TABLE `channelassociation` (
  `permission` set('enable','disable') NOT NULL default '',
  `type` set('course','school','assessmentgroup','activity','user') NOT NULL,
  `object_id` int(10) unsigned default NULL,
  `priority` int(10) unsigned default NULL,
  `channel_id` int(10) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=87 DEFAULT CHARSET=latin1;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `name` varchar(60) NOT NULL default '',
  `address1` varchar(60) NOT NULL default '',
  `address2` varchar(60) default NULL,
  `address3` varchar(60) default NULL,
  `town` varchar(60) NOT NULL default '',
  `locality` varchar(60) NOT NULL default '',
  `country` varchar(60) NOT NULL default '',
  `postcode` varchar(15) default NULL,
  `www` varchar(80) default NULL,
  `voice` varchar(30) default NULL,
  `fax` varchar(30) default NULL,
  `brief` text,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `allocation` int(10) unsigned default NULL,
  `healthsafety` text,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`(10))
) ENGINE=MyISAM AUTO_INCREMENT=1733 DEFAULT CHARSET=latin1;

--
-- Table structure for table `company_id`
--

DROP TABLE IF EXISTS `company_id`;
CREATE TABLE `company_id` (
  `username` varchar(10) NOT NULL default '',
  `password` varchar(10) NOT NULL default '',
  `comp_id` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1;

--
-- Table structure for table `companyactivity`
--

DROP TABLE IF EXISTS `companyactivity`;
CREATE TABLE `companyactivity` (
  `company_id` int(10) unsigned default NULL,
  `activity_id` int(10) unsigned default NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3451 DEFAULT CHARSET=latin1;

--
-- Table structure for table `companycontact`
--

DROP TABLE IF EXISTS `companycontact`;
CREATE TABLE `companycontact` (
  `company_id` int(10) unsigned NOT NULL default '0',
  `contact_id` int(10) unsigned NOT NULL default '0',
  `status` enum('primary','normal','restricted','archived') NOT NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1838 DEFAULT CHARSET=latin1;

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE `contact` (
  `position` tinytext,
  `voice` tinytext,
  `fax` tinytext,
  `user_id` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2129 DEFAULT CHARSET=latin1;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts` (
  `title` varchar(5) default NULL,
  `firstname` varchar(20) default NULL,
  `surname` varchar(30) default NULL,
  `position` varchar(40) default NULL,
  `voice` varchar(30) default NULL,
  `fax` varchar(30) default NULL,
  `email` varchar(60) default NULL,
  `contact_id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`contact_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2122 DEFAULT CHARSET=latin1;

--
-- Table structure for table `coursedirectors`
--

DROP TABLE IF EXISTS `coursedirectors`;
CREATE TABLE `coursedirectors` (
  `course_id` int(10) unsigned NOT NULL default '0',
  `staff_id` int(10) unsigned NOT NULL default '0',
  `policy_id` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `coursegroup`
--

DROP TABLE IF EXISTS `coursegroup`;
CREATE TABLE `coursegroup` (
  `group_id` int(10) unsigned NOT NULL default '0',
  `course_id` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `coursegrouping`
--

DROP TABLE IF EXISTS `coursegrouping`;
CREATE TABLE `coursegrouping` (
  `descript` varchar(100) NOT NULL default '',
  `details` text NOT NULL,
  `group_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Table structure for table `csvmapping`
--

DROP TABLE IF EXISTS `csvmapping`;
CREATE TABLE `csvmapping` (
  `name` tinytext NOT NULL,
  `pattern` tinytext NOT NULL,
  `replacement` tinytext NOT NULL,
  `exclude` tinytext NOT NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Table structure for table `cv`
--

DROP TABLE IF EXISTS `cv`;
CREATE TABLE `cv` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `artefact_id` int(10) unsigned NOT NULL,
  `title` text NOT NULL,
  `file_type` varchar(255) NOT NULL default '',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `userid` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=863 DEFAULT CHARSET=latin1 COMMENT='//cv builder cv archive';

--
-- Table structure for table `cvapproval`
--

DROP TABLE IF EXISTS `cvapproval`;
CREATE TABLE `cvapproval` (
  `student_id` int(10) unsigned NOT NULL default '0',
  `cv_ident` tinytext NOT NULL,
  `approver_id` int(10) unsigned NOT NULL default '0',
  `datestamp` datetime NOT NULL default '0000-00-00 00:00:00',
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=228 DEFAULT CHARSET=latin1;

--
-- Table structure for table `cvgroup`
--

DROP TABLE IF EXISTS `cvgroup`;
CREATE TABLE `cvgroup` (
  `name` varchar(80) NOT NULL default '',
  `comments` text,
  `permissions` set('allowAllTemplates','allowCustom') default NULL,
  `default_template` int(10) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Table structure for table `cvgroupcourse`
--

DROP TABLE IF EXISTS `cvgroupcourse`;
CREATE TABLE `cvgroupcourse` (
  `group_id` int(10) unsigned NOT NULL default '0',
  `course_id` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `cvgrouptemplate`
--

DROP TABLE IF EXISTS `cvgrouptemplate`;
CREATE TABLE `cvgrouptemplate` (
  `group_id` int(10) unsigned NOT NULL default '0',
  `template_id` int(10) unsigned NOT NULL default '0',
  `settings` set('allow','requiresApproval') default NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Table structure for table `faculty`
--

DROP TABLE IF EXISTS `faculty`;
CREATE TABLE `faculty` (
  `name` tinytext NOT NULL,
  `www` tinytext,
  `srs_ident` tinytext,
  `status` enum('active','archive') default NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Table structure for table `facultyadmin`
--

DROP TABLE IF EXISTS `facultyadmin`;
CREATE TABLE `facultyadmin` (
  `admin_id` int(10) unsigned NOT NULL,
  `faculty_id` int(10) unsigned NOT NULL,
  `policy_id` int(10) unsigned default NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `help`
--

DROP TABLE IF EXISTS `help`;
CREATE TABLE `help` (
  `language_id` int(10) unsigned default NULL,
  `lookup` tinytext NOT NULL,
  `channel_id` int(10) unsigned default NULL,
  `auth` tinytext,
  `description` tinytext,
  `contents` text,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=latin1;

--
-- Table structure for table `language`
--

DROP TABLE IF EXISTS `language`;
CREATE TABLE `language` (
  `name` tinytext NOT NULL,
  `ident` varchar(10) NOT NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Table structure for table `mimetype`
--

DROP TABLE IF EXISTS `mimetype`;
CREATE TABLE `mimetype` (
  `type` tinytext NOT NULL,
  `extensions` tinytext,
  `comment` tinytext,
  `id` int(10) unsigned NOT NULL auto_increment,
  `uploadable` enum('yes','no') NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Table structure for table `note`
--

DROP TABLE IF EXISTS `note`;
CREATE TABLE `note` (
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `comments` longtext NOT NULL,
  `summary` tinytext,
  `auth` tinytext,
  `author_id` int(10) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2630 DEFAULT CHARSET=latin1;

--
-- Table structure for table `notelink`
--

DROP TABLE IF EXISTS `notelink`;
CREATE TABLE `notelink` (
  `link_type` enum('Student','Staff','Admin','Company','Contact','Vacancy') default NULL,
  `link_id` int(10) unsigned NOT NULL default '0',
  `note_id` int(10) unsigned NOT NULL default '0',
  `main` enum('yes','no') default NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3432 DEFAULT CHARSET=latin1;

--
-- Table structure for table `phonehome`
--

DROP TABLE IF EXISTS `phonehome`;
CREATE TABLE `phonehome` (
  `send_install` enum('Ask','Yes','No') default NULL,
  `send_periodic` enum('Ask','Yes','No') default NULL,
  `cc_on_email` enum('Yes','No') default NULL,
  `timestamp_install` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `timestamp_periodic` timestamp NOT NULL default '0000-00-00 00:00:00',
  `admin_id` int(10) unsigned default NULL,
  `id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `placement`
--

DROP TABLE IF EXISTS `placement`;
CREATE TABLE `placement` (
  `position` varchar(40) default NULL,
  `jobstart` date default NULL,
  `jobend` date default NULL,
  `salary` varchar(20) default NULL,
  `voice` varchar(15) default NULL,
  `email` varchar(40) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `supervisor_title` varchar(10) default NULL,
  `supervisor_firstname` varchar(80) default NULL,
  `supervisor_lastname` tinytext,
  `supervisor_email` varchar(80) default NULL,
  `supervisor_voice` varchar(30) default NULL,
  `company_id` int(10) unsigned NOT NULL default '0',
  `vacancy_id` int(10) unsigned default NULL,
  `student_id` int(10) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2010 DEFAULT CHARSET=latin1;

--
-- Table structure for table `policy`
--

DROP TABLE IF EXISTS `policy`;
CREATE TABLE `policy` (
  `name` tinytext NOT NULL,
  `help` set('list','create','edit','delete') NOT NULL default '',
  `automail` set('list','create','edit','delete') NOT NULL default '',
  `resource` set('list','create','edit','delete') default NULL,
  `import` set('students','photos') NOT NULL default '',
  `status` set('user') NOT NULL default '',
  `log` set('general','admin','cron','security','debug','panic','waf_debug','php_error') default NULL,
  `faculty` set('create','edit','archive','list') default NULL,
  `school` set('create','edit','archive','list') NOT NULL default '',
  `programme` set('create','edit','archive','list') default NULL,
  `company` set('create','edit','archive','note') NOT NULL default '',
  `vacancy` set('create','edit','archive','note','delete') default NULL,
  `contact` set('create','edit','archive','note','list','delete') default NULL,
  `staff` set('create','edit','archive','note','list') NOT NULL default '',
  `student` set('list','create','viewCV','editCV','viewStatus','editStatus','viewCompanies','editCompanies','viewAssessments','editAssessments','note') default NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  `priority` int(10) unsigned default NULL,
  `channel` set('list','create','edit','delete','read','write') default NULL,
  `cvgroup` set('list','create','edit','delete') default NULL,
  `assessmentgroup` set('list','create','edit','delete') default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Table structure for table `programme`
--

DROP TABLE IF EXISTS `programme`;
CREATE TABLE `programme` (
  `srs_ident` varchar(30) NOT NULL,
  `name` tinytext NOT NULL,
  `www` varchar(100) default NULL,
  `status` enum('active','archive') NOT NULL,
  `school_id` int(10) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL auto_increment,
  `cvgroup_id` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=143 DEFAULT CHARSET=latin1;

--
-- Table structure for table `programmeadmin`
--

DROP TABLE IF EXISTS `programmeadmin`;
CREATE TABLE `programmeadmin` (
  `admin_id` int(10) unsigned NOT NULL default '0',
  `programme_id` int(10) unsigned NOT NULL,
  `policy_id` int(10) unsigned default NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

--
-- Table structure for table `resource`
--

DROP TABLE IF EXISTS `resource`;
CREATE TABLE `resource` (
  `lookup` tinytext,
  `language_id` int(10) unsigned default NULL,
  `category_id` int(10) unsigned default NULL,
  `channel_id` int(10) unsigned default NULL,
  `description` tinytext NOT NULL,
  `author` tinytext,
  `copyright` text,
  `auth` tinytext,
  `mime` int(10) unsigned NOT NULL default '0',
  `filename` tinytext NOT NULL,
  `dcounter` int(10) unsigned NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime default NULL,
  `downloaded` datetime default NULL,
  `uploader` int(10) unsigned default NULL,
  `status` set('archive','private') default NULL,
  `company_id` int(10) unsigned default NULL,
  `vacancy_id` int(10) unsigned default NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=653 DEFAULT CHARSET=latin1;

--
-- Table structure for table `school`
--

DROP TABLE IF EXISTS `school`;
CREATE TABLE `school` (
  `name` tinytext NOT NULL,
  `www` varchar(100) default NULL,
  `srs_ident` tinytext,
  `status` enum('active','archive') NOT NULL,
  `faculty_id` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Table structure for table `schooladmin`
--

DROP TABLE IF EXISTS `schooladmin`;
CREATE TABLE `schooladmin` (
  `admin_id` int(10) unsigned NOT NULL default '0',
  `school_id` int(10) unsigned NOT NULL default '0',
  `policy_id` int(10) unsigned default NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=latin1;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
CREATE TABLE `service` (
  `status` enum('started','stopped') NOT NULL,
  `schema_version` tinytext NOT NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE `staff` (
  `position` varchar(40) default NULL,
  `room` varchar(10) default NULL,
  `address` varchar(100) default NULL,
  `postcode` tinytext NOT NULL,
  `voice` varchar(15) default NULL,
  `status` enum('active','archive') default NULL,
  `school_id` int(10) unsigned NOT NULL default '0',
  `user_id` int(10) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=156 DEFAULT CHARSET=latin1;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `placement_year` year(4) NOT NULL,
  `placement_status` enum('Required','Placed','Exempt Applied','Exempt Given','No Info','Left Course','Suspended','To final year','Not Eligible') default NULL,
  `progress` set('disclaimer') default NULL,
  `disability_code` int(10) unsigned default NULL,
  `programme_id` int(10) unsigned default NULL,
  `academic_user_id` int(10) unsigned default NULL,
  `voice` tinytext default NULL,
  `address` text default NULL,
  `quick_note` tinytext default NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`),
  KEY `stud_index` (`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4358 DEFAULT CHARSET=latin1;

--
-- Table structure for table `timeline`
--

DROP TABLE IF EXISTS `timeline`;
CREATE TABLE `timeline` (
  `student_id` int(10) unsigned NOT NULL default '0',
  `last_updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `image` blob,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16867 DEFAULT CHARSET=latin1;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `real_name` tinytext,
  `username` tinytext NOT NULL,
  `password` varchar(32) default NULL,
  `user_type` enum('student','root','company','staff','admin','supervisor','application') default NULL,
  `salutation` tinytext,
  `firstname` tinytext,
  `lastname` tinytext,
  `email` tinytext,
  `reg_number` tinytext,
  `last_time` datetime default NULL,
  `last_index` datetime default NULL,
  `online` enum('online','idle','offline') default NULL,
  `session_hash` tinytext,
  `id` int(10) unsigned NOT NULL auto_increment,
  `login_time` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `username` (`username`(15))
) ENGINE=MyISAM AUTO_INCREMENT=7795 DEFAULT CHARSET=latin1 PACK_KEYS=1;

--
-- Table structure for table `vacancy`
--

DROP TABLE IF EXISTS `vacancy`;
CREATE TABLE `vacancy` (
  `company_id` int(10) unsigned NOT NULL default '0',
  `description` tinytext NOT NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime default NULL,
  `closedate` datetime default NULL,
  `jobstart` date default NULL,
  `jobend` date default NULL,
  `address1` tinytext,
  `address2` tinytext,
  `address3` tinytext,
  `town` tinytext,
  `locality` tinytext,
  `country` tinytext,
  `postcode` tinytext,
  `www` tinytext,
  `salary` tinytext,
  `brief` text,
  `status` enum('open','closed','special') NOT NULL default 'open',
  `contact_id` int(10) unsigned default NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  `vacancy_type` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `description` (`description`(10))
) ENGINE=MyISAM AUTO_INCREMENT=2162 DEFAULT CHARSET=latin1;

--
-- Table structure for table `vacancyactivity`
--

DROP TABLE IF EXISTS `vacancyactivity`;
CREATE TABLE `vacancyactivity` (
  `vacancy_id` int(10) unsigned NOT NULL default '0',
  `activity_id` int(10) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4972 DEFAULT CHARSET=latin1;

--
-- Table structure for table `vacancytype`
--

DROP TABLE IF EXISTS `vacancytype`;
CREATE TABLE `vacancytype` (
  `name` tinytext NOT NULL,
  `priority` int(11) default NULL,
  `help` text,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2008-03-18  2:26:56
