-- TAKE A BACKUP WITH MYSQLDUMP!

-- update schema version --

update service set schema_version='4.2.0';

-- add a mail template for password recovery

insert into automail (language_id, lookup, fromh, toh, cch, bcch, subject, description, contents) values(1,'StartPasswordRecovery','%afirstname% %asurname% <%aemail%>','%rfirstname% %rsurname% <%remail%>','','%afirstname% %asurname% <%aemail%>','UU OPUS : Password recovery information','Emails users with information to help them recover their passwords','Dear %rtitle% %rsurname%,\r\n\r\nThis is an automated message from the Placement Management System at the University of Ulster.\r\n\r\nSomeone (hopefully you) has requested password recovery for your account(s). If this was not you, you need take no action.\r\n\r\nOtherwise, you now have 24 hours to click on the link below to recover your password. After that time you will have to begin the process again.\r\n\r\n%block%\r\n\r\nThank you.\r\n\r\n%atitle% %afirstname% %asurname%\r\n%aposition%');

update `automail` set `subject` = 'Your account details for supervising your placement student %student_title% %student_firstname% %student_surname%',
 `contents` = 'Dear %rtitle% %rsurname%,\r\n\r\nThis is an automated message from the Placement Management System at the University of Ulster. This account is to help you supervise one placement student; you may receive more than one set of details if you are looking after more than one student - so please retain any other username and password you may already have.\r\n\r\nYou can use this account to see details recorded about the placement, contact staff in the university who can assist with any issues, and often to assess the student at an appropriate time in the academic year when you are requested to do so.\r\n\r\nWe appreciate all your help with our placement operation.\r\n\r\nYou are receiving this message either because a new password has just been generated for your account or because an account has just been created for you.\r\n\r\nTo use the web based system, please go to\r\n\r\nhttp://%conf_website%\r\n           \r\nand click on the option to login. When prompted please use the credentials shown below.\r\n\r\nusername : %username%\r\npassword : %password%\r\n\r\nThis account is specifically for managing the placement of %student_title% %student_firstname% %student_surname%. It is possible you may have more accounts associated with other placements, so please keep the details for all of these carefully.\r\n\r\nOnce you login you will be able to change your password if you wish. Please feel free to ask for any further guidance.\r\n\r\n%atitle% %afirstname% %asurname%\r\n%aposition%' where `lookup`='NewPassword_Supervisor';



