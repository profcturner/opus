-- TAKE A BACKUP WITH MYSQLDUMP!

-- update schema version --

update service set schema_version='4.2.0';

-- add a mail template for password recovery

insert into automail (language_id, lookup, fromh, toh, cch, bcch, subject, description, contents) values(1,'StartPasswordRecovery','%afirstname% %asurname% <%aemail%>','%rfirstname% %rsurname% <%remail%>','','%afirstname% %asurname% <%aemail%>','UU OPUS : Password recovery information','Emails users with information to help them recover their passwords','Dear %rtitle% %rsurname%,\r\n\r\nThis is an automated message from the Placement Management System at the University of Ulster.\r\n\r\nSomeone (hopefully you) has requested password recovery for your account(s). If this was not you, you need take no action.\r\n\r\nOtherwise, you now have 24 hours to click on the link below to recover your password. After that time you will have to begin the process again.\r\n\r\n%block%\r\n\r\nThank you.\r\n\r\n%atitle% %afirstname% %asurname%\r\n%aposition%');




