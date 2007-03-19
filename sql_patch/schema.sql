-- MySQL dump 10.9
--
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

--
-- Table structure for table `adminactivity`
--

DROP TABLE IF EXISTS `adminactivity`;
CREATE TABLE `adminactivity` (
  `admin_id` int(10) unsigned NOT NULL default '0',
  `activity_id` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `admincourse`
--

DROP TABLE IF EXISTS `admincourse`;
CREATE TABLE `admincourse` (
  `admin_id` int(10) unsigned NOT NULL default '0',
  `course_id` int(10) unsigned NOT NULL default '0',
  `policy_id` int(10) unsigned default NULL
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
-- Table structure for table `adminschool`
--

DROP TABLE IF EXISTS `adminschool`;
CREATE TABLE `adminschool` (
  `admin_id` int(10) unsigned NOT NULL default '0',
  `school_id` int(10) unsigned NOT NULL default '0',
  `policy_id` int(10) unsigned default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `assessment`
--

DROP TABLE IF EXISTS `assessment`;
CREATE TABLE `assessment` (
  `description` tinytext NOT NULL,
  `student_description` tinytext,
  `template_filename` tinytext,
  `submission_url` tinytext,
  `results_url` tinytext,
  `assessment_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`assessment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `assessmentgroupcourse`
--

DROP TABLE IF EXISTS `assessmentgroupcourse`;
CREATE TABLE `assessmentgroupcourse` (
  `group_id` int(10) unsigned NOT NULL default '0',
  `startyear` year(4) default NULL,
  `endyear` year(4) default NULL,
  `course_id` int(10) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `assessmentgroups`
--

DROP TABLE IF EXISTS `assessmentgroups`;
CREATE TABLE `assessmentgroups` (
  `name` varchar(80) NOT NULL default '',
  `comments` text,
  `group_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
  `options` set('hidden','archive') default NULL,
  `cassessment_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`cassessment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `assessmentresults`
--

DROP TABLE IF EXISTS `assessmentresults`;
CREATE TABLE `assessmentresults` (
  `cassessment_id` int(10) unsigned NOT NULL default '0',
  `assessed_id` int(10) unsigned NOT NULL default '0',
  `name` tinytext NOT NULL,
  `contents` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
  `options` set('compulsory') default NULL,
  `varorder` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `assessmenttotals`
--

DROP TABLE IF EXISTS `assessmenttotals`;
CREATE TABLE `assessmenttotals` (
  `cassessment_id` int(10) unsigned NOT NULL default '0',
  `assessed_id` int(10) unsigned NOT NULL default '0',
  `assessor_id` int(10) unsigned NOT NULL default '0',
  `comments` text,
  `mark` int(10) unsigned NOT NULL default '0',
  `outof` int(10) unsigned NOT NULL default '0',
  `percentage` float default NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime default NULL,
  `assessed` datetime default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `assessorother`
--

DROP TABLE IF EXISTS `assessorother`;
CREATE TABLE `assessorother` (
  `assessed_id` int(10) unsigned NOT NULL default '0',
  `assessor_id` int(10) unsigned NOT NULL default '0',
  `cassessment_id` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `automail`
--

DROP TABLE IF EXISTS `automail`;
CREATE TABLE `automail` (
  `language` int(10) unsigned default NULL,
  `lookup` tinytext NOT NULL,
  `fromh` tinytext,
  `toh` tinytext,
  `cch` tinytext,
  `bcch` tinytext,
  `subject` tinytext,
  `description` tinytext,
  `contents` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `bugs`
--

DROP TABLE IF EXISTS `bugs`;
CREATE TABLE `bugs` (
  `type` set('bug','wishlist') NOT NULL default '',
  `component_id` int(10) unsigned NOT NULL default '0',
  `importance` set('minor','normal','grave','critical') NOT NULL default '',
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `accepted` timestamp NOT NULL default '0000-00-00 00:00:00',
  `resolve` timestamp NOT NULL default '0000-00-00 00:00:00',
  `status` set('open','closed') NOT NULL default '',
  `text` text NOT NULL,
  `reportedby` int(10) unsigned NOT NULL default '0',
  `acceptedby` int(10) unsigned NOT NULL default '0',
  `bug_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`bug_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `channelassociations`
--

DROP TABLE IF EXISTS `channelassociations`;
CREATE TABLE `channelassociations` (
  `permission` set('enable','disable') NOT NULL default '',
  `type` set('course','school','assessmentgroup','activity') NOT NULL default '',
  `object_id` int(10) unsigned default NULL,
  `priority` int(10) unsigned default NULL,
  `channel_id` int(10) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `channels`
--

DROP TABLE IF EXISTS `channels`;
CREATE TABLE `channels` (
  `name` tinytext NOT NULL,
  `description` tinytext,
  `channel_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`channel_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies` (
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
  `company_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`company_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
-- Table structure for table `companycontact`
--

DROP TABLE IF EXISTS `companycontact`;
CREATE TABLE `companycontact` (
  `company_id` int(10) unsigned NOT NULL default '0',
  `contact_id` int(10) unsigned NOT NULL default '0',
  `status` enum('normal','restricted','primary') NOT NULL default 'normal'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `companystudent`
--

DROP TABLE IF EXISTS `companystudent`;
CREATE TABLE `companystudent` (
  `company_id` int(10) unsigned NOT NULL default '0',
  `vacancy_id` int(10) unsigned default NULL,
  `student_id` int(10) unsigned NOT NULL default '0',
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `prefcvt` int(10) unsigned NOT NULL default '0',
  `archive_hash` tinytext,
  `archive_mime_type` tinytext,
  `cover` text,
  `status` enum('unseen','seen','invited to interview','missed interview','offered','unsuccessful') default 'unseen',
  `lastseen` datetime default NULL,
  `addedby` int(10) unsigned default NULL,
  KEY `comp_index` (`company_id`),
  KEY `stud_index` (`student_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `companyvacancy`
--

DROP TABLE IF EXISTS `companyvacancy`;
CREATE TABLE `companyvacancy` (
  `company_id` int(10) unsigned default NULL,
  `vacancy_id` int(10) unsigned default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses` (
  `course_code` varchar(10) NOT NULL default '',
  `course_name` varchar(50) NOT NULL default '',
  `www` varchar(100) default NULL,
  `status` set('archive') default NULL,
  `school_id` int(10) unsigned NOT NULL default '0',
  `course_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`course_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `cv_approval`
--

DROP TABLE IF EXISTS `cv_approval`;
CREATE TABLE `cv_approval` (
  `student_id` int(10) unsigned NOT NULL default '0',
  `template_id` int(10) unsigned NOT NULL default '0',
  `approver_id` int(10) unsigned NOT NULL default '0',
  `datestamp` datetime NOT NULL default '0000-00-00 00:00:00',
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `cv_cdetails`
--

DROP TABLE IF EXISTS `cv_cdetails`;
CREATE TABLE `cv_cdetails` (
  `home_add_l1` varchar(30) NOT NULL default '',
  `home_add_l2` varchar(30) default NULL,
  `home_add_l3` varchar(30) default NULL,
  `home_town` varchar(30) default NULL,
  `home_county` varchar(20) default NULL,
  `home_pcode` varchar(10) NOT NULL default '',
  `home_tele` varchar(20) default NULL,
  `term_add_l1` varchar(30) default NULL,
  `term_add_l2` varchar(30) default NULL,
  `term_add_l3` varchar(30) default NULL,
  `term_town` varchar(20) default NULL,
  `term_county` varchar(20) default NULL,
  `term_pcode` varchar(10) default NULL,
  `term_tele` varchar(20) default NULL,
  `mobile_no` varchar(20) default NULL,
  `id` int(10) unsigned NOT NULL default '0',
  `home_country` varchar(20) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1;

--
-- Table structure for table `cv_edetails`
--

DROP TABLE IF EXISTS `cv_edetails`;
CREATE TABLE `cv_edetails` (
  `id` int(10) unsigned NOT NULL default '0',
  `place` varchar(40) NOT NULL default '',
  `year` year(4) default NULL,
  `level` varchar(30) NOT NULL default '',
  `course` varchar(50) default NULL,
  `link_no` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`link_no`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1;

--
-- Table structure for table `cv_odetails`
--

DROP TABLE IF EXISTS `cv_odetails`;
CREATE TABLE `cv_odetails` (
  `id` int(10) unsigned NOT NULL default '0',
  `activities` blob,
  `achievements` blob,
  `career` blob,
  `ch1` int(4) unsigned default NULL,
  `ch2` int(4) unsigned default NULL,
  `ch3` int(4) unsigned default NULL,
  `ch4` int(4) unsigned default NULL,
  `ch5` int(4) unsigned default NULL,
  `ch6` int(4) unsigned default NULL,
  `ch7` int(4) unsigned default NULL,
  `ch8` int(4) unsigned default NULL,
  `ch9` int(4) unsigned default NULL,
  `ch10` int(4) unsigned default NULL,
  `ch11` int(4) unsigned default NULL,
  `ch12` int(4) unsigned default NULL,
  `ch13` int(4) unsigned default NULL,
  `ch14` int(4) unsigned default NULL,
  `ch15` int(4) unsigned default NULL,
  `ch16` int(4) unsigned default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1;

--
-- Table structure for table `cv_pdetails`
--

DROP TABLE IF EXISTS `cv_pdetails`;
CREATE TABLE `cv_pdetails` (
  `id` int(10) unsigned NOT NULL default '0',
  `surname` varchar(20) default NULL,
  `firstname` varchar(20) default NULL,
  `title` varchar(5) default NULL,
  `student_id` varchar(15) default NULL,
  `email` varchar(45) default NULL,
  `dob` date NOT NULL default '0000-00-00',
  `pob` varchar(20) default NULL,
  `nationality` varchar(20) default NULL,
  `course` int(10) unsigned default NULL,
  `course_start` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `course_end` timestamp NOT NULL default '0000-00-00 00:00:00',
  `expected_grade` varchar(15) default NULL,
  KEY `stud_index` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1;

--
-- Table structure for table `cv_results`
--

DROP TABLE IF EXISTS `cv_results`;
CREATE TABLE `cv_results` (
  `link` int(11) NOT NULL default '0',
  `subject` varchar(25) NOT NULL default '',
  `grade` varchar(15) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1;

--
-- Table structure for table `cv_work`
--

DROP TABLE IF EXISTS `cv_work`;
CREATE TABLE `cv_work` (
  `id` int(10) unsigned NOT NULL default '0',
  `place` varchar(40) default NULL,
  `start` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `finish` timestamp NOT NULL default '0000-00-00 00:00:00',
  `work` blob
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1;

--
-- Table structure for table `cvgroupcourse`
--

DROP TABLE IF EXISTS `cvgroupcourse`;
CREATE TABLE `cvgroupcourse` (
  `group_id` int(10) unsigned NOT NULL default '0',
  `course_id` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `cvgroups`
--

DROP TABLE IF EXISTS `cvgroups`;
CREATE TABLE `cvgroups` (
  `name` varchar(80) NOT NULL default '',
  `comments` text,
  `permissions` set('allowAllTemplates','allowCustom') default NULL,
  `default_template` int(10) unsigned NOT NULL default '0',
  `group_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `cvgrouptemplate`
--

DROP TABLE IF EXISTS `cvgrouptemplate`;
CREATE TABLE `cvgrouptemplate` (
  `group_id` int(10) unsigned NOT NULL default '0',
  `template_id` int(10) unsigned NOT NULL default '0',
  `settings` set('allow','requiresApproval') default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `cvviewpreferences`
--

DROP TABLE IF EXISTS `cvviewpreferences`;
CREATE TABLE `cvviewpreferences` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `howtoview` enum('ask','studentPref','customPref') default NULL,
  `template_id` int(10) unsigned default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `help`
--

DROP TABLE IF EXISTS `help`;
CREATE TABLE `help` (
  `language` int(10) unsigned default NULL,
  `lookup` tinytext NOT NULL,
  `channel_id` int(10) unsigned default NULL,
  `auth` tinytext,
  `description` tinytext,
  `contents` text,
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `id`
--

DROP TABLE IF EXISTS `id`;
CREATE TABLE `id` (
  `real_name` varchar(60) default NULL,
  `username` tinytext NOT NULL,
  `password` varchar(32) default NULL,
  `user` enum('student','root','company','staff','admin','supervisor') default NULL,
  `last_time` datetime default NULL,
  `last_index` datetime default NULL,
  `id_number` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id_number`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `language` tinytext NOT NULL,
  `language_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `mime_types`
--

DROP TABLE IF EXISTS `mime_types`;
CREATE TABLE `mime_types` (
  `type` tinytext NOT NULL,
  `extensions` tinytext,
  `comment` tinytext,
  `flags` set('uploadable') default NULL,
  `mime_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`mime_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `notelink`
--

DROP TABLE IF EXISTS `notelink`;
CREATE TABLE `notelink` (
  `link_type` enum('Student','Staff','Admin','Company','Contact') default NULL,
  `link_id` int(10) unsigned NOT NULL default '0',
  `note_id` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE `notes` (
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `comments` longtext NOT NULL,
  `summary` tinytext,
  `auth` tinytext,
  `author_id` int(10) unsigned NOT NULL default '0',
  `note_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`note_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `ocvcomponent`
--

DROP TABLE IF EXISTS `ocvcomponent`;
CREATE TABLE `ocvcomponent` (
  `componentid` int(11) NOT NULL auto_increment,
  `componentname` varchar(255) NOT NULL default '',
  `orientation` int(1) NOT NULL default '0',
  `singular` int(1) NOT NULL default '0',
  `creator` varchar(255) default NULL,
  `careers` int(1) NOT NULL default '0',
  `componenthelp` text,
  `rowwidths` varchar(25) default NULL,
  PRIMARY KEY  (`componentid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `ocvfield`
--

DROP TABLE IF EXISTS `ocvfield`;
CREATE TABLE `ocvfield` (
  `componentid` int(11) NOT NULL default '0',
  `fieldid` int(11) NOT NULL default '0',
  `fieldname` varchar(255) NOT NULL default '',
  `type` int(3) NOT NULL default '0',
  `data` text,
  `fieldhelp` text,
  `visible` int(1) default '1',
  `settings` text,
  PRIMARY KEY  (`componentid`,`fieldid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `ocvstudent2template`
--

DROP TABLE IF EXISTS `ocvstudent2template`;
CREATE TABLE `ocvstudent2template` (
  `studentid` varchar(25) NOT NULL default '',
  `templateid` int(11) NOT NULL default '0',
  `status` int(1) NOT NULL default '0',
  PRIMARY KEY  (`studentid`,`templateid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `ocvstudentdata`
--

DROP TABLE IF EXISTS `ocvstudentdata`;
CREATE TABLE `ocvstudentdata` (
  `studentid` varchar(25) NOT NULL default '',
  `componentid` int(11) NOT NULL default '0',
  `fieldid` int(11) NOT NULL default '0',
  `rowid` int(11) NOT NULL default '0',
  `data` text NOT NULL,
  PRIMARY KEY  (`studentid`,`componentid`,`fieldid`,`rowid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `ocvtemplate`
--

DROP TABLE IF EXISTS `ocvtemplate`;
CREATE TABLE `ocvtemplate` (
  `templateid` int(11) default NULL,
  `componentid` int(11) default NULL,
  `lefty` int(2) default NULL,
  `righty` int(2) default NULL,
  `settings` text,
  `data` blob
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `ocvtemplatedescription`
--

DROP TABLE IF EXISTS `ocvtemplatedescription`;
CREATE TABLE `ocvtemplatedescription` (
  `templateid` int(11) NOT NULL auto_increment,
  `templatename` text,
  `creator` varchar(125) default NULL,
  `settings` text,
  `templatehelp` text,
  PRIMARY KEY  (`templateid`)
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
  `supervisor_surname` varchar(80) default NULL,
  `supervisor_email` varchar(80) default NULL,
  `supervisor_voice` varchar(30) default NULL,
  `company_id` int(10) unsigned NOT NULL default '0',
  `vacancy_id` int(10) unsigned default NULL,
  `student_id` int(10) unsigned NOT NULL default '0',
  `placement_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`placement_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `policy`
--

DROP TABLE IF EXISTS `policy`;
CREATE TABLE `policy` (
  `descript` varchar(80) NOT NULL default '',
  `help` set('list','create','edit','delete') NOT NULL default '',
  `automail` set('list','create','edit','delete') NOT NULL default '',
  `resources` set('list','create','edit','delete') NOT NULL default '',
  `import` set('students','photos') NOT NULL default '',
  `status` set('user') NOT NULL default '',
  `log` set('access','admin','security','debug') NOT NULL default '',
  `school` set('create','edit','archive','list') NOT NULL default '',
  `course` set('create','edit','archive','list') NOT NULL default '',
  `company` set('create','edit','archive','note') NOT NULL default '',
  `vacancy` set('create','edit','archive','note','delete') default NULL,
  `contact` set('create','edit','archive','note','list','delete') default NULL,
  `staff` set('create','edit','archive','note','list') NOT NULL default '',
  `student` set('list','create','viewCV','editCV','viewStatus','editStatus','viewCompanies','editCompanies','viewAssessments','editAssessments','note') default NULL,
  `policy_id` int(10) unsigned NOT NULL auto_increment,
  `priority` int(10) unsigned default NULL,
  `channel` set('list','create','edit','delete','read','write') default NULL,
  `cvgroup` set('list','create','edit','delete') default NULL,
  `assessmentgroup` set('list','create','edit','delete') default NULL,
  PRIMARY KEY  (`policy_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `questionnaire_results`
--

DROP TABLE IF EXISTS `questionnaire_results`;
CREATE TABLE `questionnaire_results` (
  `username` tinytext,
  `name` tinytext NOT NULL,
  `contents` text,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `questions` tinytext
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `resourcelink`
--

DROP TABLE IF EXISTS `resourcelink`;
CREATE TABLE `resourcelink` (
  `resource_id` int(10) unsigned NOT NULL default '0',
  `company_id` int(10) unsigned NOT NULL default '0',
  `vacancy_id` int(10) unsigned default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `resources`
--

DROP TABLE IF EXISTS `resources`;
CREATE TABLE `resources` (
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
  `resource_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`resource_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `schools`
--

DROP TABLE IF EXISTS `schools`;
CREATE TABLE `schools` (
  `school_name` varchar(80) NOT NULL default '',
  `www` varchar(100) default NULL,
  `status` set('archive') default NULL,
  `school_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`school_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE `staff` (
  `initials` varchar(5) NOT NULL default '',
  `title` varchar(5) NOT NULL default '',
  `firstname` varchar(20) default NULL,
  `surname` varchar(20) NOT NULL default '',
  `position` varchar(40) default NULL,
  `room` varchar(10) default NULL,
  `department` varchar(100) default NULL,
  `address` varchar(100) default NULL,
  `voice` varchar(15) default NULL,
  `email` varchar(40) NOT NULL default '',
  `status` set('archive') NOT NULL default '',
  `staffno` int(10) unsigned default NULL,
  `school_id` int(10) unsigned NOT NULL default '0',
  `user_id` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `staffstudent`
--

DROP TABLE IF EXISTS `staffstudent`;
CREATE TABLE `staffstudent` (
  `staff_id` int(10) unsigned NOT NULL default '0',
  `student_id` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `year` year(4) NOT NULL default '0000',
  `status` enum('Required','Placed','Exempt Applied','Exempt Given','No Info','Left Course','Suspended','To final year','Not Eligible') default NULL,
  `progress` set('disclaimer') default NULL,
  `disability_code` int(10) unsigned default NULL,
  KEY `stud_index` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `timelines`
--

DROP TABLE IF EXISTS `timelines`;
CREATE TABLE `timelines` (
  `student_id` int(10) unsigned NOT NULL default '0',
  `last_updated` datetime default NULL,
  `image` blob
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `vacancies`
--

DROP TABLE IF EXISTS `vacancies`;
CREATE TABLE `vacancies` (
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
  `vacancy_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`vacancy_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `vacancyactivity`
--

DROP TABLE IF EXISTS `vacancyactivity`;
CREATE TABLE `vacancyactivity` (
  `vacancy_id` int(10) unsigned NOT NULL default '0',
  `activity_id` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `vacancytype`
--

DROP TABLE IF EXISTS `vacancytype`;
CREATE TABLE `vacancytype` (
  `name` varchar(40) default NULL,
  `vacancy_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`vacancy_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

