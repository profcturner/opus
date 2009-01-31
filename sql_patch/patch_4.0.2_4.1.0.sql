-- TAKE A BACKUP WITH MYSQLDUMP!

-- update schema version --

update service set schema_version='4.1.0';

-- new columns in company --

alter table company add column healthsafety text after allocation;

-- new columns in student --

alter table student add column voice tinytext after academic_user_id;
alter table student add column address text after voice;
alter table student add column quick_note tinytext after address;
alter table student add column vacancy_type int unsigned after quick_note;

-- a new automail template --

insert into `automail` (language_id, lookup, fromh, toh, cch, bcch, subject, description, contents) values (1,'NewPassword_Student','%afirstname% %asurname% <%aemail%>','%rfirstname% %rsurname% <%remail%>','','%afirstname% %asurname% <%aemail%>','Your account details for OPUS','Sent to new Student users on creation (if configured)','Dear %rtitle% %rsurname%,\r\n\r\nThis is an automated message from the OPUS Placement Management System at the University of Ulster. You can now login to begin looking for placements.\r\n\r\nYou are receiving this message either because a new password has just been generated for your account or because an account has just been created for you.\r\n\r\nTo use the web based system, please go to\r\n\r\nhttp://%conf_website%\r\n           \r\nand click on the option to login. When prompted please use the credentials shown below.\r\n\r\nusername : %username%\r\npassword : %password%\r\n\r\nOnce you login you will be able to change your password if you wish. Please feel free to ask for any further guidance.\r\n\r\n%atitle% %afirstname% %asurname%\r\n%aposition%');
