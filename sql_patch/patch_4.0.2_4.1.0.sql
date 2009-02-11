-- TAKE A BACKUP WITH MYSQLDUMP!

-- update schema version --

update service set schema_version='4.1.0';

-- new columns in company --

alter table company add column healthsafety text after allocation;

-- new columns in student --

alter table student add column voice tinytext after academic_user_id;
alter table student add column address text after voice;
alter table student add column quick_note tinytext after address;
--alter table student add column vacancy_type int unsigned after quick_note;

-- a new automail template --

insert into `automail` (language_id, lookup, fromh, toh, cch, bcch, subject, description, contents) values (1,'NewPassword_Student','%afirstname% %asurname% <%aemail%>','%rfirstname% %rsurname% <%remail%>','','%afirstname% %asurname% <%aemail%>','Your account details for OPUS','Sent to new Student users on creation (if configured)','Dear %rtitle% %rsurname%,\r\n\r\nThis is an automated message from the OPUS Placement Management System at the University of Ulster. You can now login to begin looking for placements.\r\n\r\nYou are receiving this message either because a new password has just been generated for your account or because an account has just been created for you.\r\n\r\nTo use the web based system, please go to\r\n\r\nhttp://%conf_website%\r\n           \r\nand click on the option to login. When prompted please use the credentials shown below.\r\n\r\nusername : %username%\r\npassword : %password%\r\n\r\nOnce you login you will be able to change your password if you wish. Please feel free to ask for any further guidance.\r\n\r\n%atitle% %afirstname% %asurname%\r\n%aposition%');

-- a new student records system here in Ulster means a new mapping --

delete from csvmapping where id=1;
delete from csvmapping where id=2;

INSERT INTO `csvmapping` VALUES ('University of Ulster Module List','/^Y([0-9]*),(B[0-9]*),\"([A-Za-z\'\\-]*), ([A-Za-z\'\\-]*)\",([0-9]*),([A-Z]*),(.*),(.*),(.*)$/','\"${1}\",\"${2}\",\"\",\"${4}\",\"${3}\",\"${9}\",\"${5}\",\"\",\"${2}\"','/^Year,.*$/',1),('University of Ulster Programme List','/^Y([0-9]*),(B[0-9]*),\"([A-Za-z\'\\-]*), ([A-Za-z\'\\-]*)\",(.*),(.*),\"(.*)\"$/ ','\"${1}\",\"${2}\",\"\",\"${4}\",\"${3}\",\"${6}\",\"\",\"\",\"${2}\"','/^Year,.*$/',2);
  
-- update help for new csv code --

delete from help where lookup='RootManageCSVMapping' and language_id="1";
insert into help (language_id, lookup, channel_id, auth, description, contents) values(1,'RootManageCSVMapping',0,'admin','More detailed help on how to set up a CSV Mapping','<p>CSV Mappings are usually a way of mapping your own University format CSV files to a \"standard\" format used by OPUS. Note however, that your original file format can really be any text file which has one student per line. It\'s just as practical to use TSV or similar formats.</p>\r\n<p>To set up a mapping, you will need to understand <a href=\"http://www.php.net/manual/en/reference.pcre.pattern.syntax.php\" target=\"blank\">regular expressions</a>, as used by the <a href=\"http://www.php.net/manual/en/function.preg-replace.php\" target=\"blank\">preg_replace</a> function in PHP. If you don\'t, it is strongly recommended you file a service ticket or ask a member of IT staff to help you set this up; regular expressions are a relatively complex concept if your format requires anything but the simplest manipulation.</p>\r\n<p>OPUS expects the replacement line to map to the following:</p>\r\n<p>\"year_of_study\",\r\n    \r\n    \r\n    \r\n    \r\n    <br />\"student_reg_number\",\r\n    \r\n    \r\n    \r\n    \r\n    <br />\"title\",\r\n    \r\n    \r\n    \r\n    \r\n    <br />\"firstname\",\r\n    \r\n    \r\n    \r\n    \r\n    <br />\"lastname\",\r\n    \r\n    \r\n    \r\n    \r\n    <br />\"email\",\r\n    \r\n    \r\n    \r\n    \r\n    <br />\"programme_code\",\r\n    \r\n    \r\n    \r\n    \r\n    <br />\"disability_code\",<br />\"username\"</p>\r\n<p>all on one line. Note that if you don\'t have information about one of these, you should include the quotes with no content \"\". This functionality is on this menu since errors here will generate errors in the student import functionality. If the username is empty, the registration number will be used for this also.</p>');