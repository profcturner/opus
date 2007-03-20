
--
-- Test ID field
--
-- TODO: We will be coming back to debconf these first two...

INSERT INTO `id` VALUES('OPUS Front Desk', 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', 'root', NULL, NULL, 1);

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` VALUES ('OPUS','Front','Desk','Administrator',NULL,NULL,NULL,'',NULL,NULL,'help',NULL,1);

--
-- Dumping data for table `assessment`
--

INSERT INTO `assessment` VALUES ('First Visit','First Visit','assessment/seme/first_visit.tpl','first_visit.php','first_visit.php',1);
INSERT INTO `assessment` VALUES ('Technical Report','Technical Report','assessment/seme/technical_report.tpl','technical_report.php','technical_report.php',2);
INSERT INTO `assessment` VALUES ('Second Visit','Final Report','assessment/seme/final_visit.tpl','final_visit.php','final_visit.php',3);
INSERT INTO `assessment` VALUES ('Placement Report',NULL,'assessment/seme/placement_report.tpl',NULL,NULL,5);
INSERT INTO `assessment` VALUES ('Industrial Report',NULL,'assessment/seme/industrial_report.tpl',NULL,NULL,6);
INSERT INTO `assessment` VALUES ('Presentation','Presentation','assessment/seme/presentation.tpl','presentation.php','presentation.php',7);
INSERT INTO `assessment` VALUES ('Student Health & Safety',NULL,'assessment/student_healthsafety.tpl',NULL,NULL,8);
INSERT INTO `assessment` VALUES ('First Visit (Assessed)',NULL,'assessment/engineering/first_visit.tpl',NULL,NULL,9);

--
-- Dumping data for table `assessmentgroups`
--

INSERT INTO `assessmentgroups` VALUES ('Default Scheme',NULL,1);

--
-- Dumping data for table `assessmentstructure`
--

INSERT INTO `assessmentstructure` VALUES (2,'Description of Practice / Process mark','numeric',0,20,1,'mark1','compulsory',1);
INSERT INTO `assessmentstructure` VALUES (2,'Understanding of Principles / Theory mark','numeric',0,20,1,'mark2','compulsory',3);
INSERT INTO `assessmentstructure` VALUES (2,'Investigation / Research mark','numeric',0,20,1,'mark3','compulsory',5);
INSERT INTO `assessmentstructure` VALUES (2,'Spelling / Punctuation mark','numeric',0,10,1,'mark4','compulsory',7);
INSERT INTO `assessmentstructure` VALUES (2,'Use of English mark','numeric',0,10,1,'mark5','compulsory',9);
INSERT INTO `assessmentstructure` VALUES (2,'Structure of Ideas mark','numeric',0,10,1,'mark6','compulsory',11);
INSERT INTO `assessmentstructure` VALUES (2,'Presentation mark','numeric',0,10,1,'mark7','compulsory',13);
INSERT INTO `assessmentstructure` VALUES (2,'Description of Practice / Process comments','textual',NULL,500,0,'comment1',NULL,2);
INSERT INTO `assessmentstructure` VALUES (2,'Understanding of Principles / Theory comments','textual',NULL,500,0,'comment2',NULL,4);
INSERT INTO `assessmentstructure` VALUES (2,'Investigation / Research comments','textual',NULL,500,0,'comment3',NULL,6);
INSERT INTO `assessmentstructure` VALUES (2,'Spelling / Punctuation comment','textual',NULL,500,0,'comment4',NULL,8);
INSERT INTO `assessmentstructure` VALUES (2,'Use of English comments','textual',NULL,500,0,'comment5',NULL,10);
INSERT INTO `assessmentstructure` VALUES (2,'Structure of Ideas comments','textual',NULL,500,0,'comment6',NULL,12);
INSERT INTO `assessmentstructure` VALUES (2,'Presentation comments','textual',NULL,500,0,'comment7',NULL,14);
INSERT INTO `assessmentstructure` VALUES (2,'General Comments','textual',NULL,1000,0,'comments',NULL,15);
INSERT INTO `assessmentstructure` VALUES (3,'Ability to describe and understanding of tasks performed','numeric',NULL,5,1,'A1',NULL,2);
INSERT INTO `assessmentstructure` VALUES (1,'Training / Experience programme arranged','checkbox',NULL,NULL,0,'check1',NULL,1);
INSERT INTO `assessmentstructure` VALUES (3,'Analytical Skills','numeric',NULL,5,1,'A2',NULL,3);
INSERT INTO `assessmentstructure` VALUES (3,'Attempts to be creative/innovative/display initiative','numeric',NULL,5,1,'A3',NULL,4);
INSERT INTO `assessmentstructure` VALUES (3,'Date of visit','assesseddate',NULL,NULL,0,'assesseddate','compulsory',1);
INSERT INTO `assessmentstructure` VALUES (3,'Ability to describe the business of the employer','numeric',NULL,5,1,'B1',NULL,5);
INSERT INTO `assessmentstructure` VALUES (3,'Knowledge of the company/organisation and its structures','numeric',NULL,5,1,'B2',NULL,6);
INSERT INTO `assessmentstructure` VALUES (3,'Knowledge of role of others in the company/organisation.','numeric',NULL,5,1,'B3',NULL,7);
INSERT INTO `assessmentstructure` VALUES (3,'Health and Safety','numeric',NULL,5,1,'B4',NULL,8);
INSERT INTO `assessmentstructure` VALUES (3,'Environmental Awareness','numeric',NULL,5,1,'B5',NULL,9);
INSERT INTO `assessmentstructure` VALUES (3,'Integration within the company','numeric',NULL,5,1,'B6',NULL,10);
INSERT INTO `assessmentstructure` VALUES (3,'Flexibility and attitude to change','numeric',NULL,5,1,'C1',NULL,11);
INSERT INTO `assessmentstructure` VALUES (3,'Self Organisation','numeric',NULL,5,1,'C2',NULL,12);
INSERT INTO `assessmentstructure` VALUES (3,'Teamwork','numeric',NULL,5,1,'C3',NULL,13);
INSERT INTO `assessmentstructure` VALUES (3,'Leadership','numeric',NULL,5,1,'C4',NULL,14);
INSERT INTO `assessmentstructure` VALUES (3,'Information Technology','numeric',NULL,5,1,'D1',NULL,15);
INSERT INTO `assessmentstructure` VALUES (3,'Communication','numeric',NULL,5,1,'D2',NULL,16);
INSERT INTO `assessmentstructure` VALUES (3,'Career planning','numeric',NULL,5,1,'D3',NULL,17);
INSERT INTO `assessmentstructure` VALUES (3,'Log Book Inspection','checkbox',NULL,NULL,0,'logbook',NULL,18);
INSERT INTO `assessmentstructure` VALUES (3,'Comments on the student','textual',NULL,1000,0,'scomments',NULL,20);
INSERT INTO `assessmentstructure` VALUES (3,'Comments on the company','textual',NULL,1000,0,'ccomments',NULL,21);
INSERT INTO `assessmentstructure` VALUES (1,'Training / Experience programme comments','textual',NULL,500,0,'comment1',NULL,2);
INSERT INTO `assessmentstructure` VALUES (1,'Industrial Supervisor Appointed','checkbox',NULL,NULL,0,'check2',NULL,3);
INSERT INTO `assessmentstructure` VALUES (1,'Industrial Supervisor comments','textual',NULL,500,0,'comment2',NULL,4);
INSERT INTO `assessmentstructure` VALUES (1,'Student Interviewed','checkbox',NULL,NULL,0,'check3',NULL,5);
INSERT INTO `assessmentstructure` VALUES (1,'Student interview comments','textual',NULL,500,0,'comment3',NULL,6);
INSERT INTO `assessmentstructure` VALUES (1,'Company Representative Interviewed','checkbox',NULL,NULL,0,'check4',NULL,7);
INSERT INTO `assessmentstructure` VALUES (1,'Company Representative Comments','textual',NULL,500,0,'comment4',NULL,8);
INSERT INTO `assessmentstructure` VALUES (1,'Log Book Inspected','checkbox',NULL,NULL,0,'check5',NULL,9);
INSERT INTO `assessmentstructure` VALUES (1,'Log Book comments','textual',NULL,500,0,'comment5',NULL,10);
INSERT INTO `assessmentstructure` VALUES (1,'Health and Safety Checklist Inspected','checkbox',NULL,NULL,0,'check6',NULL,11);
INSERT INTO `assessmentstructure` VALUES (1,'Health and Safety Checklist comments','textual',NULL,500,0,'comment6',NULL,12);
INSERT INTO `assessmentstructure` VALUES (1,'Student Accomodation Satisfactory','checkbox',NULL,NULL,0,'check7',NULL,13);
INSERT INTO `assessmentstructure` VALUES (1,'Student Accomodation comments','textual',NULL,500,0,'comment7',NULL,14);
INSERT INTO `assessmentstructure` VALUES (1,'Changes to the Training / Experience Programme','textual',NULL,1000,0,'changes',NULL,15);
INSERT INTO `assessmentstructure` VALUES (1,'Comments on the Student and the Programme','textual',NULL,1000,0,'scomments',NULL,16);
INSERT INTO `assessmentstructure` VALUES (1,'Advice given to the student','textual',NULL,1000,0,'advice',NULL,17);
INSERT INTO `assessmentstructure` VALUES (1,'Assessment date','assesseddate',NULL,NULL,0,'assesseddate','compulsory',18);
INSERT INTO `assessmentstructure` VALUES (3,'Lookbook is satisfactory?','checkbox',NULL,NULL,0,'logbookok',NULL,19);
INSERT INTO `assessmentstructure` VALUES (7,'Content (Assessor A)','numeric',NULL,5,1,'ContentA',NULL,1);
INSERT INTO `assessmentstructure` VALUES (7,'Content (Assessor B)','numeric',NULL,5,1,'ContentB',NULL,2);
INSERT INTO `assessmentstructure` VALUES (7,'Content (Assessor C)','numeric',NULL,5,1,'ContentC',NULL,3);
INSERT INTO `assessmentstructure` VALUES (7,'Content (Assessor D)','numeric',NULL,5,1,'ContentD',NULL,4);
INSERT INTO `assessmentstructure` VALUES (7,'Structure (Assessor A)','numeric',NULL,5,1,'StructureA',NULL,5);
INSERT INTO `assessmentstructure` VALUES (7,'Structure (Assessor B)','numeric',NULL,5,1,'StructureB',NULL,6);
INSERT INTO `assessmentstructure` VALUES (7,'Structure (Assessor C)','numeric',NULL,5,1,'StructureC',NULL,7);
INSERT INTO `assessmentstructure` VALUES (7,'Structure (Assessor D)','numeric',NULL,5,1,'StructureD',NULL,8);
INSERT INTO `assessmentstructure` VALUES (7,'Visuals (Assessor A)','numeric',NULL,5,1,'VisualsA',NULL,9);
INSERT INTO `assessmentstructure` VALUES (7,'Visuals (Assessor B)','numeric',NULL,5,1,'VisualsB',NULL,10);
INSERT INTO `assessmentstructure` VALUES (7,'Visuals (Assessor C)','numeric',NULL,5,1,'VisualsC',NULL,11);
INSERT INTO `assessmentstructure` VALUES (7,'Visuals (Assessor D)','numeric',NULL,5,1,'VisualsD',NULL,12);
INSERT INTO `assessmentstructure` VALUES (7,'Delivery (Assessor A)','numeric',NULL,5,1,'DeliveryA',NULL,13);
INSERT INTO `assessmentstructure` VALUES (7,'Delivery (Assessor B)','numeric',NULL,5,1,'DeliveryB',NULL,14);
INSERT INTO `assessmentstructure` VALUES (7,'Delivery (Assessor C)','numeric',NULL,5,1,'DeliveryC',NULL,15);
INSERT INTO `assessmentstructure` VALUES (7,'Delivery (Assessor D)','numeric',NULL,5,1,'DeliveryD',NULL,16);
INSERT INTO `assessmentstructure` VALUES (7,'Questions (Assessor A)','numeric',NULL,5,1,'QuestionsA',NULL,17);
INSERT INTO `assessmentstructure` VALUES (7,'Questions (Assessor B)','numeric',NULL,5,1,'QuestionsB',NULL,18);
INSERT INTO `assessmentstructure` VALUES (7,'Questions (Assessor C)','numeric',NULL,5,1,'QuestionsC',NULL,19);
INSERT INTO `assessmentstructure` VALUES (7,'Questions (Assessor D)','numeric',NULL,5,1,'QuestionsD',NULL,20);
INSERT INTO `assessmentstructure` VALUES (7,'Notes','textual',NULL,400,0,'Notes',NULL,21);
INSERT INTO `assessmentstructure` VALUES (8,'Emergency Procedures','',NULL,NULL,0,'emergency_procedures','compulsory',1);
INSERT INTO `assessmentstructure` VALUES (8,'Health & Safety Procedure Received or Location Known','',NULL,NULL,0,'policy_received','compulsory',2);
INSERT INTO `assessmentstructure` VALUES (8,'Location of First Aid box or station','',NULL,NULL,0,'firstaid_location','compulsory',3);
INSERT INTO `assessmentstructure` VALUES (8,'First Aid Arrangements','',NULL,NULL,0,'firstaid_arrangements','compulsory',4);
INSERT INTO `assessmentstructure` VALUES (8,'Fire Procedures and location of extinguishers','',NULL,NULL,0,'fire_procedures','compulsory',5);
INSERT INTO `assessmentstructure` VALUES (8,'Accident reporting procedures ','',NULL,NULL,0,'accident_reporting','compulsory',6);
INSERT INTO `assessmentstructure` VALUES (8,'COSHH regulations','',NULL,NULL,0,'coshh_regulations','compulsory',7);
INSERT INTO `assessmentstructure` VALUES (8,'Manual handling procedures','',NULL,NULL,0,'handling_procedures','compulsory',8);
INSERT INTO `assessmentstructure` VALUES (8,'Display screen equipment regulations or procedures','',NULL,NULL,0,'display_equipment','compulsory',9);
INSERT INTO `assessmentstructure` VALUES (8,'Protective clothing arrangements','',NULL,NULL,0,'protective_clothing','compulsory',10);
INSERT INTO `assessmentstructure` VALUES (8,'Instructions on equipment to be used in your work','',NULL,NULL,0,'equipment_instructions','compulsory',11);
INSERT INTO `assessmentstructure` VALUES (8,'Any relevant risk assessments which have been notified to you','',NULL,NULL,0,'risk_assessments','compulsory',12);
INSERT INTO `assessmentstructure` VALUES (8,'Bullying and Harassment Policy & Procedure','',NULL,NULL,0,'bullying_policy','compulsory',13);
INSERT INTO `assessmentstructure` VALUES (8,'Read UU Health & Safety - guidance for students on placement','',NULL,NULL,0,'uu_safety','compulsory',14);
INSERT INTO `assessmentstructure` VALUES (8,'Other Health & Safety issues notified','',NULL,NULL,0,'other_hs','compulsory',15);
INSERT INTO `assessmentstructure` VALUES (8,'Other issues','textual',NULL,1000,0,'issues',NULL,16);
INSERT INTO `assessmentstructure` VALUES (8,'Confirmation all issues raised and dealt with','checkbox',NULL,NULL,0,'compliance','compulsory',17);
INSERT INTO `assessmentstructure` VALUES (6,'Interest in work','numeric',1,5,1,'interest','compulsory',1);
INSERT INTO `assessmentstructure` VALUES (6,'Enterprise','numeric',1,5,1,'enterprise','compulsory',2);
INSERT INTO `assessmentstructure` VALUES (6,'Organisation and Planning','numeric',NULL,5,1,'organisation','compulsory',3);
INSERT INTO `assessmentstructure` VALUES (6,'Ability to Learn','numeric',NULL,5,1,'learn','compulsory',4);
INSERT INTO `assessmentstructure` VALUES (6,'Quality of Work','numeric',NULL,5,1,'quality','compulsory',5);
INSERT INTO `assessmentstructure` VALUES (6,'Quantity of Work','numeric',NULL,5,1,'quantity','compulsory',6);
INSERT INTO `assessmentstructure` VALUES (6,'Judgement','numeric',NULL,5,1,'judgement','compulsory',7);
INSERT INTO `assessmentstructure` VALUES (6,'Dependability\r\n','numeric',NULL,5,1,'dependability','compulsory',8);
INSERT INTO `assessmentstructure` VALUES (6,'Relations with Others','numeric',NULL,5,1,'relations','compulsory',9);
INSERT INTO `assessmentstructure` VALUES (6,'Creativity','numeric',NULL,5,1,'creativity','compulsory',10);
INSERT INTO `assessmentstructure` VALUES (6,'Communication Skills - Written Expression','numeric',NULL,5,1,'comm_written','compulsory',11);
INSERT INTO `assessmentstructure` VALUES (6,'Communication Skills - Oral Expression','numeric',NULL,5,1,'comm_oral','compulsory',12);
INSERT INTO `assessmentstructure` VALUES (6,'Acceptance of Criticism','numeric',NULL,5,1,'accept_crit','compulsory',13);
INSERT INTO `assessmentstructure` VALUES (6,'Attendance','numeric',NULL,5,1,'attendance','compulsory',14);
INSERT INTO `assessmentstructure` VALUES (6,'Punctuality','numeric',1,5,1,'punctuality','compulsory',15);
INSERT INTO `assessmentstructure` VALUES (6,'Comments on the student\'s performance','textual',NULL,4000,0,'performance_comments',NULL,16);
INSERT INTO `assessmentstructure` VALUES (6,'Comments on the initial knowledge and skills of the student','textual',NULL,4000,0,'skills_comments',NULL,17);
INSERT INTO `assessmentstructure` VALUES (6,'Comments on the placement process','textual',NULL,4000,0,'process_comments',NULL,18);
INSERT INTO `assessmentstructure` VALUES (5,'Employer / Personal Work Mark','numeric',NULL,20,1,'mark1','compulsory',1);
INSERT INTO `assessmentstructure` VALUES (5,'Employer / Personal Work Comment','textual',NULL,200,0,'comment1',NULL,2);
INSERT INTO `assessmentstructure` VALUES (5,'Innovation in the Organisation Mark','numeric',NULL,20,1,'mark2','compulsory',3);
INSERT INTO `assessmentstructure` VALUES (5,'Innovation in the Organisation Comment','textual',NULL,200,0,'comment2',NULL,4);
INSERT INTO `assessmentstructure` VALUES (5,'Reflection on the Benefit of Placement Mark','numeric',NULL,30,1,'mark3','compulsory',5);
INSERT INTO `assessmentstructure` VALUES (5,'Reflection on the Benefit of Placement Comment','textual',NULL,200,0,'comment3',NULL,6);
INSERT INTO `assessmentstructure` VALUES (5,'Spelling and Punctuation Mark','numeric',NULL,10,1,'mark4','compulsory',7);
INSERT INTO `assessmentstructure` VALUES (5,'Spelling and Punctuation Comment','textual',NULL,200,0,'comment4',NULL,8);
INSERT INTO `assessmentstructure` VALUES (5,'Use of English Mark','numeric',NULL,10,1,'mark5','compulsory',9);
INSERT INTO `assessmentstructure` VALUES (5,'Use of English Comment','textual',NULL,200,0,'comment5',NULL,10);
INSERT INTO `assessmentstructure` VALUES (5,'Presentation Mark','numeric',NULL,10,1,'mark6','compulsory',11);
INSERT INTO `assessmentstructure` VALUES (5,'Presentation Comment','textual',NULL,200,0,'comment6',NULL,12);
INSERT INTO `assessmentstructure` VALUES (5,'General Comments','textual',NULL,600,0,'comments',NULL,13);
INSERT INTO `assessmentstructure` VALUES (9,'Personal Work Activities','numeric',NULL,5,1,'personal_work','compulsory',1);
INSERT INTO `assessmentstructure` VALUES (9,'Personal Development Plan','numeric',NULL,5,1,'personal_development','compulsory',2);
INSERT INTO `assessmentstructure` VALUES (9,'Placement Organisation Knowledge','numeric',NULL,5,1,'organisation_knowledge','compulsory',3);
INSERT INTO `assessmentstructure` VALUES (9,'Student Log Book','numeric',NULL,5,1,'log_book',NULL,4);
INSERT INTO `assessmentstructure` VALUES (9,'Health & Safety matters raised','textual',NULL,400,0,'hs_matters',NULL,10);
INSERT INTO `assessmentstructure` VALUES (9,'Health & Safety advice given','textual',NULL,400,0,'hs_advice',NULL,11);
INSERT INTO `assessmentstructure` VALUES (9,'Student Accommodation','textual',NULL,400,0,'accommodation','compulsory',12);
INSERT INTO `assessmentstructure` VALUES (9,'Comment on the student','textual',NULL,500,0,'comment_student',NULL,13);
INSERT INTO `assessmentstructure` VALUES (9,'Comment on the placement','textual',NULL,500,0,'comment_placement',NULL,14);
INSERT INTO `assessmentstructure` VALUES (9,'Student was visited','checkbox',NULL,NULL,0,'visit',NULL,15);
INSERT INTO `assessmentstructure` VALUES (9,'Visit date','assesseddate',NULL,NULL,0,'visit_date','compulsory',16);

--
-- Dumping data for table `automail`
--

INSERT INTO `automail` VALUES (1,'NewPassword','%afirstname% %asurname% <%aemail%>','%rfirstname% %rsurname% <%remail%>',NULL,'%afirstname% %asurname% <%aemail%>','Account information for University of Ulster placement system','Sent to users on creation, or when a password is changed','Dear %rtitle% %rsurname%,\r\n\r\nThis is an automated message from the Placement Management System at the University of Ulster.\r\n\r\nYou are receiving this message either because a new password has just been generated for your account or because an account has just been created for you.\r\n\r\nTo use the web based system, please go to\r\n\r\nhttp://%conf_website%\r\n           \r\nand click on the option to login. When prompted please use the credentials shown below.\r\n\r\nusername : %username%\r\npassword : %password%\r\n\r\nOnce you login you will be able to change your password if you wish. Please feel free to ask for any further guidance.\r\n\r\n%atitle% %afirstname% %asurname%\r\n%aposition%');
INSERT INTO `automail` VALUES (1,'StudentOnPlaced','%afirstname% %asurname% <%aemail%>','%rfirstname% %rsurname% <%remail%>',NULL,'%afirstname% %asurname% <%aemail%>','Congratulations, you have been placed','Sent to a student to confirm placed','Dear %rfirstname%,\r\n\r\nCongratulations, you have been successfully placed. Please see the details below for more information.\r\n\r\nCompany    : %pcompany%\r\nPosition   : %pposition%\r\nStart      : %pstart%\r\nEnd        : %pend%\r\nSupervisor : %psupervisor%\r\n\r\nPlease fill in any information missing above on the website as soon as possible.\r\n\r\nContinue to check the placement website\r\n\r\n%website%\r\n\r\nfor information on your assessment programme and academic tutor as it becomes available.\r\n\r\n%atitle% %afirstname% %asurname%\r\n%aposition%');
INSERT INTO `automail` VALUES (1,'CompanyOnClosed','Colin Turner <c.turner@ulster.ac.uk>','%rfirstname% %rsurname% <%remail%>',NULL,'Colin Turner <c.turner@ulster.ac.uk>, Ron Laird <rj.laird@ulster.ac.uk>','UU PMS: One of the your vacancies has closed','Sent to listed contact when a vacancy closes','Dear %rtitle% %rsurname%,\r\n\r\nThis is an automated message from the industrial placement system for %conf_institution%:\r\n\r\n%conf_website%\r\n\r\nOne of your vacancies listed on our system has just closed. If you wish to reopen it, please set a new closing date and change the status to \"open\" to allow automated applications, or \"special\" if you only allow applications via some other means.\r\n\r\nVacancy\r\n-------\r\n\r\n%custom_vacancydesc%\r\n\r\nCompany\r\n-------\r\n\r\n%custom_companyname%\r\n\r\n%atitle% %afirstname% %asurname%\r\n%aposition%');
INSERT INTO `automail` VALUES (1,'CourseStats',NULL,'%rtitle% %rsurname% <%remail%>',NULL,NULL,'Course statistics for %custom_coursename%','Send along with statistics for a course','Dear %rtitle% %rsurname%,\r\n\r\nPlease find enclosed....\r\n\r\n\r\n%custom_table%\r\n\r\n');
INSERT INTO `automail` VALUES (1,'NewPassword_Contact','%afirstname% %asurname% <%aemail%>','%rfirstname% %rsurname% <%remail%>',NULL,'%afirstname% %asurname% <%aemail%>','UU OPUS : Your account details for managing placement recruitment',NULL,'Dear %rtitle% %rsurname%,\r\n\r\nThis is an automated message from the Placement Management System at the University of Ulster. You will be able to use this account to examine your job advertisments and monitor student applications.\r\n\r\nYou are receiving this message either because a new password has just been generated for your account or because an account has just been created for you.\r\n\r\nTo use the web based system, please go to\r\n\r\nhttp://%conf_website%\r\n           \r\nand click on the option to login. When prompted please use the credentials shown below.\r\n\r\nusername : %username%\r\npassword : %password%\r\n\r\nOnce you login you will be able to change your password if you wish. Please feel free to ask for any further guidance.\r\n\r\n%atitle% %afirstname% %asurname%\r\n%aposition%');
INSERT INTO `automail` VALUES (1,'NewPassword_Staff','%afirstname% %asurname% <%aemail%>','%rfirstname% %rsurname% <%remail%>',NULL,'%afirstname% %asurname% <%aemail%>','OPUS: Your account details',NULL,'Dear %rtitle% %rsurname%,\r\n\r\nThis is an automated message from the Placement Management System at the University of Ulster. You can use this account to see details on the students you are allocated, as well as to record their assessment.\r\n\r\nYou are receiving this message either because a new password has just been generated for your account or because an account has just been created for you.\r\n\r\nTo use the web based system, please go to\r\n\r\nhttp://%conf_website%\r\n           \r\nand click on the option to login. When prompted please use the credentials shown below.\r\n\r\nusername : %username%\r\npassword : %password%\r\n\r\nOnce you login you will be able to change your password if you wish. Please feel free to ask for any further guidance.\r\n\r\n%atitle% %afirstname% %asurname%\r\n%aposition%');
INSERT INTO `automail` VALUES (1,'NewPassword_Supervisor','%afirstname% %asurname% <%aemail%>','%rfirstname% %rsurname% <%remail%>',NULL,'%afirstname% %asurname% <%aemail%>','Your account details for supervising your placement student','New Password Information for Workplace Supervisors','Dear %rtitle% %rsurname%,\r\n\r\nThis is an automated message from the Placement Management System at the University of Ulster. This account is to help you supervise one placement student; you may receive more than one set of details if you are looking after more than one student - so please retain any other username and password you may already have.\r\n\r\nYou can use this account to see details recorded about the placement, contact staff in the university who can assist with any issues, and often to assess the student at an appropriate time in the academic year when you are requested to do so.\r\n\r\nWe appreciate all your help with our placement operation.\r\n\r\nYou are receiving this message either because a new password has just been generated for your account or because an account has just been created for you.\r\n\r\nTo use the web based system, please go to\r\n\r\nhttp://%conf_website%\r\n           \r\nand click on the option to login. When prompted please use the credentials shown below.\r\n\r\nusername : %username%\r\npassword : %password%\r\n\r\nOnce you login you will be able to change your password if you wish. Please feel free to ask for any further guidance.\r\n\r\n%atitle% %afirstname% %asurname%\r\n%aposition%');

--
-- Dumping data for table `cvgroups`
--

INSERT INTO `cvgroups` VALUES ('Default Group','Placement CV or an Archived CV may be used','allowCustom',5,1);

--
-- Dumping data for table `help`
--

INSERT INTO `help` VALUES (1,'XMLSyntax',NULL,NULL,'Guidance on how to write XML code','In some cases where you may need to enter a considerable quantity of text in\r\nsome fields the website it is often desireable for you to have\r\nsome control over the formatting of that text.\r\n<BR/>\r\n\r\nFor this purpose some XML style tags (similar to HTML) are permitted in these fields.\r\nIf you have some familiarity with HTML you should find this very easy to deal\r\nwith. Of course you need not use any tags at all, but in this case the website\r\nwill format the text as it sees fit.\r\n<BR/>\r\n\r\n<TITLE>Tag format</TITLE>\r\n\r\nJust as in HTML, the tags take the form of a control word encased between &lt; and\r\n&gt;. For example\r\n\r\n<BR/><BR/><TT>\r\n&lt;CENTER&gt;\r\n</TT>\r\n<BR/><BR/>\r\n\r\nwill cause all text following it to appear centered in the screen. This effect will\r\nstop when the corresponding tag\r\n\r\n<BR/><BR/><TT>\r\n&lt;/CENTER&gt;\r\n</TT>\r\n<BR/><BR/>\r\n\r\nis met in the text.\r\n\r\n<TITLE>Centred text</TITLE>\r\n\r\nAs just desribed, to cause some text to appear in the center of the available space, place it between\r\nthe tags\r\n\r\n<BR/><BR/>\r\n<TT>&lt;CENTER&gt;This will appear in the centre.&lt;/CENTER&gt;</TT>\r\n<BR/><BR/>\r\n\r\nNote that although CENTER is spelt in the American manner here, the British\r\nEnglish CENTRE is also supported.\r\n\r\n<TITLE>Titles</TITLE>\r\n\r\nIf you wish to provide a subtitle for some text to follow, place it between the tags\r\n\r\n<BR/><BR/>\r\n<TT>&lt;TITLE&gt;This will appear as a title.&lt;/TITLE&gt;</TT>\r\n<BR/><BR/>\r\n\r\n<TITLE>Type-writer formatting</TITLE>\r\n\r\nIf you want a section of text to appear in fixed-width fonts (because you want\r\nsome elements to line up for example) use the tags\r\n\r\n<BR/><BR/>\r\n<TT>&lt;PRE&gt;This will appear in fixed width.&lt;/PRE&gt;</TT>\r\n<BR/><BR/>\r\n\r\nIn fact this set of tags means that text between them should appear exactly as\r\nyou have typed it, complete with new lines. For those people more familiar with HTML tags the tags <TT>&lt;TT&gt;</TT> and \r\n<TT>&lt;/TT&gt;</TT> are also\r\nsupported with their original meaning.\r\n\r\n<TITLE>Taking a new line</TITLE>\r\n\r\nWhen you take a new line in the field window this does not mean the displayed text\r\nwill take a new line. This is to allow you to split lines to make them easy to\r\nread in the small window.\r\nIf you want to force a new line simply use<BR/><BR/>\r\n\r\n<TT>&lt;BR&gt;</TT> or the more correct <TT>&lt;BR/&gt;</TT>\r\n\r\nbut both forms are supported.\r\n\r\n<TITLE>Bold text</TITLE>\r\n\r\nIf you wish to emphasis a small section of text by making it bold (not necessary for titles please note) then you can use the following.\r\n<BR/><BR/>\r\n\r\n<TT>&lt;B&gt;</TT>This will be bold.\r\n<TT>&lt;/B&gt;</TT><BR/>\r\n\r\n<TITLE>Example</TITLE>\r\n\r\nHere is a sample of how the notes field for a course might be formatted.\r\n\r\n<BR/><BR/>\r\n<TT>\r\n&lt;CENTER&gt;<BR/>\r\n&lt;TITLE&gt;Placement Opportunity&lt;/TITLE&gt;<BR/>\r\nWe are happy to announce that we at<BR/>\r\n&lt;B&gt;MicroBanana&lt;/B&gt; are looking<BR/>\r\nfor engineering students to help push<BR/>\r\nforward our ground-breaking research<BR/>\r\nin micro-integrated bananas.<BR/>\r\n&lt;/CENTER&gt;<BR/>\r\n&lt;BR/&gt;<BR/>\r\nMore information is given below.<BR/>\r\n&lt;PRE&gt;<BR/>\r\n<PRE>Job                             Salary\r\nBanana Manufacturing Engineer   20 bananas per month\r\nBanana Research Engineer        25 bananas per month</PRE>\r\n&lt;/PRE&gt;<BR>\r\n</TT>\r\n\r\nThis will appear as shown below.\r\n\r\n<CENTER>\r\n<TITLE>Placement Opportunity</TITLE>\r\nWe are happy to announce that we at\r\n<B>MicroBanana</B> are looking\r\nfor engineering students to help push\r\nforward our ground-breaking research\r\nin micro-integrated bananas.\r\n</CENTER>\r\n<BR/>\r\nMore information is given below.\r\n<PRE>\r\n\r\nJob                             Salary\r\nBanana Manufacturing Engineer   20 bananas per month\r\nBanana Research Engineer        25 bananas per month\r\n</PRE>\r\n',1);
INSERT INTO `help` VALUES (1,'ContactCompanyBasic',NULL,NULL,'Company Editor Basics tab (Contacts)','test',2);
INSERT INTO `help` VALUES (1,'CompanyBasic',NULL,NULL,'Company Editor Basic tab','You can use this form to edit the company brief.\r\nIt is possible to use some special codes to help\r\nimprove the layout of the information when it is shown.<BR/>\r\nNote that when you make alterations to your entry\r\nthe system will automatically notify students that\r\nlogin to the system that your information has\r\nbeen updated.',3);
INSERT INTO `help` VALUES (1,'StudentCVPersonal',NULL,'student','Student CV Personal details page','This page is used to enter some of your basic personal information.<BR/><BR/>\r\nYou should make sure that you have correctly spelt your email address and that it is able to receive mail at any time.<BR/><BR/>\r\nPlease take the time to replace your initials with your first name.<BR/><BR/>',4);
INSERT INTO `help` VALUES (1,'StudentCVContacts',NULL,'student','Student CV Contact Details','Please enter your contact details on this page. If your term time address is the same as your home address just leave those fields blank.\r\n',5);
INSERT INTO `help` VALUES (1,'StudentCVEducation',NULL,'student','Student CV Educational Details','Enter in your educational history. Please note \r\nthat at current the one page PDF view of your \r\nCV will only show the most recent two of your \r\neducational records, but you may enter in more \r\nfor the HTML view or for future expansion.\r\n<BR/><BR/>\r\nYou should enter results in groups based on \r\nwhere you achieved your qualifications and in \r\nwhat year you achieved them. For example, enter\r\nin the appropriate location and year for your\r\n\'A\' levels. Once you have done this you should\r\nthen choose <B>Edit Results</B> to record\r\nresults obtained at that place and time.\r\n',6);
INSERT INTO `help` VALUES (1,'StudentCVEducationResults',NULL,'student','Student CV Eductional Results Editing','Use this page to enter results for a particular\r\neducational period.<BR/><BR/>\r\nType in the subject and the grade and hit submit\r\nfor each grade.<BR/><BR/>\r\nGrades will be shown in alphabetical order, or \r\nin descending numerical order if percentages are\r\nentered on the actual produced CV.',7);
INSERT INTO `help` VALUES (1,'ContactHome',NULL,NULL,'Company Contact Home Page, first few paragraphs','Welcome to OPUS - the management system for all aspects of work-integrated learning and placement - developed at the University of Ulster, School of Electrical and Mechanical Engineering.<BR><BR>\r\n\r\nIf you need more information or help on finding students for your opportunity or advertising your vacancies please contact our staff by using the help directory link at the bottom of the page.<BR><BR>\r\n\r\nYou can use this site to:\r\n<ul>\r\n  <li>edit the details about your company that you wish to advertise to students;</li>\r\n  <li>add and edit information about placement vacancies you wish to offer;</li>\r\n  <li>view and edit information about the contacts who act for your company;</li>\r\n  <li>view lists of students applying for your vacancy online, invite a shortlist to interview and inform them of your decisions;</li>\r\n  <li>view RESOURCES using the menu on the left to get more information about our courses and placement procedures;</li>\r\n  <li>and more.</li>\r\n</ul>\r\nPlease do not hesitate to ask us for help if you need it.\r\n',8);
INSERT INTO `help` VALUES (1,'StudentCVWork',NULL,'student','Student CV Work Experience Help','This page is used for detailing any periods of \r\nwork experience you may have.<BR/><BR/>\r\n\r\nEach period should be entered as a separate\r\npiece of information using the form at the \r\nbottom of the page.<BR/><BR/>\r\n\r\nWork experience will be shown in your PDF CV\r\nordered so that the most recent experience \r\nappears at the top.',9);
INSERT INTO `help` VALUES (1,'StudentCVOther',NULL,'student','Student CV Other details page','You should use this page to store information\r\nthat does not fit into the other categories\r\nprovided. Make such information meaningful, \r\ndon\'t fill in fields where you have nothing\r\nuseful to say. Any empty fields are not printed\r\non the final CV.<BR/><BR/>\r\n\r\n<TITLE>Activities</TITLE><BR/><BR/>\r\n\r\nIn activities you should detail other hobbies\r\nand interests you might have, or memberships of\r\nuniversity societies or clubs.<BR/><BR/>\r\n\r\n<TITLE>Achievements</TITLE><BR/><BR/>\r\n\r\nAny awards or other achievements you may have\r\nshould be recorded here.<BR/><BR/>\r\n\r\n<TITLE>Career</TITLE><BR/><BR/>\r\n\r\nYou might use this field to discuss your future\r\ncareer plans, and where you would like to be in\r\nthe future.',10);
INSERT INTO `help` VALUES (1,'StudentCVView',NULL,'student','Student CV View (HTML)','This page allows you to view your CV as it is\r\ncurrently set up in HTML form. Note that the\r\ncompanies will (at least primarily) see your CV\r\nin a one page PDF form.<BR/><BR/>\r\n\r\nIt is possible to view your CV in PDF form using\r\nan option on your menu, and you should ensure the\r\nCV looks appropriate when viewed in this \r\nmanner.<BR/><BR/>\r\n\r\nIn the HTML view it is possible to easily check\r\nwhat data you have entered, and you can show or \r\nhide each section by clicking on the appropriate\r\nlink.<BR/><BR/>\r\n\r\nTo edit a specific section simply click on the\r\nedit link or the appropriate link on the \r\nmenu.<BR/><BR/>',11);
INSERT INTO `help` VALUES (1,'StudentHomePlaced2005',NULL,NULL,'Shown on Student Home Page,  2005-06,  ALL PLACED',NULL,12);
INSERT INTO `help` VALUES (1,'AdminLogViewer',NULL,'admin','Instructions for log viewing script','<B>Instructions</B><BR>\r\nThe placement system creates several log files\r\nin the course of its normal operation. These\r\nfiles are written to disk, but this utility\r\nallows limited viewing of these files.\r\n<BR><BR>\r\n<B>Log File</B><BR>\r\nThis specifies which file you wish to examine.\r\nThere is a separate log for general access,\r\nadmin usage, and reporting potential security\r\nbreaches. There is also a log for recording\r\ndebug information, but this isn\'t necessarily\r\nfor general usage.\r\n<BR><BR>\r\n<B>Search</B><BR>\r\nYou may wish to restrict output to show only\r\nlines that match a certain criterion. To do this\r\nenter the expression in this field, which may\r\nbe a regular expression. If the field is blank\r\nthen all lines will be matched.\r\n<BR><BR>\r\n<B>Lines</B><BR>\r\nThe number of lines of output are also limited,\r\nby default to 100, but this can be varied. The\r\nmost recent lines are always displayed.\r\n',13);
INSERT INTO `help` VALUES (1,'AdminStudentBroadsheet',NULL,NULL,'Help for generating student broadsheets','This option allows you to generate a broadsheet for students that are all nominally seeking placement in a given year, and who all share the same assessment regime. Due to the large amount of data that requires collation this procedure takes some time to complete.<BR><BR>\r\n\r\nThe HTML output will be very wide, but is useful for quick inspection of the broadsheet.<BR><BR>\r\n\r\nThe TSV output (Tab Separated Values) will allow you to download data to your PC, which can be loaded directly in Excel and similar programs, allowing you to do as much sorting, cropping and analysis as you wish.\r\n\r\n<BR>\r\n<BR>\r\n<B>Note</B> It is only possible to create \r\nbroadsheets for students that share the same assessment regime. Depending on your permissions some students may not be available to you on listings.\r\n',14);
INSERT INTO `help` VALUES (1,'AdminResourceAdd',NULL,NULL,'Guidance on adding resources for administrators','<TITLE>Resources</TITLE>\r\n\r\n<B>Filename to upload - </B>\r\n\r\nTo add a resource to the system you must first\r\nbrowse to the resource on your local computer. The system strictly controls the files that may be uploaded, although this is configurable.\r\n<BR/><BR/>\r\n\r\n<B>Filename shown to users - </B>\r\n\r\nThen, select a filename the user will see when they download the file. This filename need not be unique, but it <B>must</B> have an extension that is suitable for the type of file for security reasons. For example, an uploaded PDF file will\r\nnormally need to have a .pdf extension.\r\n<BR/><BR/>\r\n\r\n<B>Language - </B>\r\n\r\nSpecify the language of the uploaded resource.\r\n<BR/><BR/>\r\n\r\n<B>Lookup - </B>\r\n\r\nSpecify a lookup expression for the resource with\r\nno spaces. This lookup should be unique within the\r\nlanguage, and is to allow multi-language support.\r\n<BR/><BR/>\r\n\r\n<B>Description - </B>\r\n\r\nThis will be shown to the user when listing\r\nresources, so it should be clear, and you may use\r\nthe fact that descriptions are alphabetically\r\nordered when shown to users.\r\n<BR/><BR/>\r\n\r\n<B>Author - </B>\r\n\r\nList any author in here if necessary.\r\n<BR/><BR/>\r\n\r\n<B>Copyright - </B>\r\n\r\nPlace any required copyright in here.\r\n<BR/><BR/>\r\n\r\n<B>Authorisation - </B>\r\n\r\nThis clause controls who can download this file.\r\nUsers that cannot download the file will not even\r\nsee it in the list. The string should take the\r\nform of a list of categories that can download\r\nthe resource, followed by a list of exceptions\r\npreceded by a \"!\" character. The categories \r\ninclude \"all\", \"student\" and \"contact\". An empty\r\nfield authorises only administrators to download\r\na resource. Note that \"all\" still only permits\r\nauthenticated users to access the system.\r\n<BR/>\r\n\r\nFor example, the clause \"all !student\" will allow\r\nall logged in users to access the resources\r\nexcept for students.\r\n<BR/><BR/>\r\n',15);
INSERT INTO `help` VALUES (1,'XMLTest',NULL,NULL,'This is for testing the new XML code - NOT FOR USE','<TITLE>This should be a title</TITLE><BR>\r\n<UL>\r\n<LI> This should be a listed item;\r\n<LI> and so should this;\r\n<LI> This should <B>have some bold in it</B>\r\n</UL>\r\n<OL>\r\n<LI> This will be an ordered list, I think\r\n<LI> or I hope so\r\n</OL>\r\n<A HREF=\"www.piglets.com\">There should be no link here...</A>',16);
INSERT INTO `help` VALUES (1,'AdminAssessmentStructureEdit',NULL,NULL,'Shown for editing elements of an assessment structure','Warning<BR/><BR/>\r\n\r\nYou should only edit an assessment structure if you really understand what you are doing. Specifically, changing the <B>Name</B> of an element can break a script unless the corresponding name is changed in the front page.',17);
INSERT INTO `help` VALUES (1,'StaffHome',NULL,NULL,'Shown to staff on their home page','Welcome to OPUS - the management system for all aspects of work-integrated learning and placement - developed at the University of Ulster, School of Electrical and Mechanical Engineering.\r\n',18);
INSERT INTO `help` VALUES (1,'AdminImportCSV',NULL,NULL,'Guidance on importing students in bulk','<B>Please Note</B><BR><BR>\r\n\r\nThis script can now be used to import students directly from student records. Simply leave the filename blank and the system will attempt to load the students into the system - be patient, it may take some time.<BR><BR>\r\n\r\nThe old functionality is still present for now.\r\n\r\n',20);
INSERT INTO `help` VALUES (1,'StudentHome2006',NULL,NULL,'Shown on Student Home Page, 2006-07, ALL','Welcome to OPUS - the management system for all aspects of work-integrated learning and placement - developed at the University of Ulster, School of Electrical and Mechanical Engineering.<BR><BR>\r\n',40);
INSERT INTO `help` VALUES (1,'StudentHome2007',NULL,NULL,'Shown on Student Home Page, 2007-08, ALL','Welcome to OPUS - the management system for all aspects of work-integrated learning and placement - developed at the University of Ulster, School of Electrical and Mechanical Engineering.<BR><BR>\r\n',41);
INSERT INTO `help` VALUES (1,'OpeningScreen',NULL,NULL,'Page displayed at root of whole site','This OPUS website manages the entire placement process for companies, students and academic staff.<BR><BR>\r\n<UL>\r\n<LI> <B>Companies</B> may promote placement vacancies and monitor applications for each vacancy, contact students for interviews and appoint.<BR></LI>\r\n<LI><B>Students</B> may create their CV and make it available to their selection of vacancies (ie apply).<BR></LI>\r\n<LI><B>Academic Staff</B> may manage their visits to students on placement, complete reports, allocate marks and provide assessment feedback.<BR></LI>\r\n<LI><B>Industrial Placement Coordinators</B> may manage the entire placement process and provide detailed resources on-line, including company application forms.<BR><BR></LI>\r\n</UL>\r\nThis system arranges Industrial Placement for students with specialisation in:<BR><BR>\r\n<ul>\r\n<LI>  mechanical engineering<BR></LI>\r\n<LI>  electrical engineering<BR></LI>\r\n<LI>  electronic engineering<BR></LI>\r\n<LI>  software and IT skills<BR></LI>\r\n<LI>  internet communications<BR></LI>\r\n<LI>  biomedical engineering<BR></LI>\r\n<LI>  technology and design<BR></LI>\r\n<LI>  engineering management<BR>\r\n<LI>  and more<BR></LI>\r\n</ul>\r\n',21);
INSERT INTO `help` VALUES (1,'NoteAddGuidance',NULL,NULL,'Information about adding notes on items','<TITLE>Creating Notes - Please read carefully</TITLE>\r\nNotes can be added which can be linked to various items on the system. Information in notes is subject to the data protection act, and creating a note is a one way process. They cannot be edited or deleted, so check everything very carefully before submission. Notes should not be added for trivial reasons and as a substitute for normal emails.\r\n<BR><BR>\r\n<TITLE>Summary</TITLE>\r\nThe summary is a single line that should reflect \r\nthe nature of the main note, it is displayed in\r\nlistings of notes. Searches will match words\r\nin the summary or the main text.\r\n<TITLE>Contents</TITLE>\r\nThis is the main information for the note. A note\r\ncan be linked to several items so be specific\r\nwhich people or items you are referring to and\r\navoid pronouns that could be ambiguous. If the summary is sufficiently descriptive this field can be empty.\r\n<TITLE>Authorisation</TITLE>\r\nNotwithstanding the comments concerning the data protection act above, many notes have a limited readership. Admin users, and the author of a \r\nnote can always read that note. Other users can view the note only if (1) They are linked to the note (or their company is) AND (2) their category of user is mentioned in this field.\r\nThus the field could read \"contact staff\" and would mean that linked company contacts and staff members could view the note.\r\n\r\n',22);
INSERT INTO `help` VALUES (1,'AdminAdminRootList',NULL,'admin','Shown in Admin editor before root user listing','The following are root or super-admin users on the system. They have no defined default policy because they are not bound by the policy security system. Root users have access to all aspects of the system without limitation. Users should <B>not</B> be given root users without proper training and preferably experience with lesser users.<BR><BR>',23);
INSERT INTO `help` VALUES (1,'AdminHome',NULL,'admin','Home page preamble for admin and root users','Welcome to your administration home page. You will note the menu on the left hand side that supplies most of the functionality.<br><br>',24);
INSERT INTO `help` VALUES (1,'RootHome',NULL,NULL,'Home page preamble for root users','Welcome. You are a super-admin user on this system (root user). Be aware that you can perform almost any action on the database with this user, including unwise ones. You should not use a user of this power without appropriate training.',25);
INSERT INTO `help` VALUES (1,'StudentHomeRequired2006',NULL,NULL,'Shown on Student Home Page, 2006-07, ALL REQUIRED',NULL,26);
INSERT INTO `help` VALUES (1,'AdminAdminAdminList',NULL,'admin','Shown in Admin editor before Admin listing','The following users are admin users on the system. The extent of their powers is determined by both their default policy, which defines a maximum upper bound on their powers, and any additional policy that might be enforced over a specific school or course. Admin users only have powers over the specific schools and courses they are granted power over.<BR><BR>',28);
INSERT INTO `help` VALUES (1,'AdminAdminPolicyEdit',NULL,'admin','Show in the Admin editor during a policy edit session','<B>Warning! Note that policies are usually shared by many individuals, and so altering the policy will affect all those users.</B><BR><BR>\r\n\r\nThe security system used in the website is determined by a mixture of a specific hiararchy of users (super-admin and admin users) and policies. These policies restrict the powers of admin users, and in some cases other users like course directors. Only root users can change policies.<BR><BR>\r\n\r\nPolicies consist of <B>categories</B> which are shown here as divisions in the table above and <B>permissions</B> which allow a certain action within a category.<BR><BR>\r\n\r\nEven if an administrator has a certain permission in their default policy, they may not be able to exert it over a given student for example because\r\n<UL>\r\n<LI>they have no permission for the school or course the student belongs to; or</LI>\r\n<LI>there is an additional policy of lower level defined against them for that school or course.</LI>\r\n</UL>\r\n\r\n',29);
INSERT INTO `help` VALUES (1,'AdminAdminPolicyDelete',NULL,'admin','Shown in Admin Editor when a policy is to be deleted','You have selected to delete a policy from the system. This should not normally be done, be aware that removing the policy may cause many admin accounts to stop normal functioning.',30);
INSERT INTO `help` VALUES (1,'StudentHomePlaced',NULL,NULL,'Shown on Student Home Page,  All Yrs,  ALL PLACED',NULL,31);
INSERT INTO `help` VALUES (1,'StudentHomePlaced2006',NULL,NULL,'Shown on Student Home Page, 2006-07, ALL PLACED',NULL,53);
INSERT INTO `help` VALUES (1,'AdminNewStudent',NULL,NULL,'Used for creation of new student form','<B>Warning</B> This script now only creates new\r\nstudent users. Users of other categories are\r\ncreated in their appropriate directories.\r\nThis script is only intended for very occaisional use. The more powerful Import Data script should be used for most cases. Note that import data will not overwrite existing students and so can be used repeatedly as CSV files becomes more complete.\r\n<BR><BR>\r\n\r\nPlease be careful to use the correct course when creating a new student. Remember you will only have access to students in courses you are authorised for.<BR><BR>\r\n\r\nThe title, surname and student number are compulsory fields.\r\n',33);
INSERT INTO `help` VALUES (1,'StudentAddCompany',NULL,NULL,'Shown to students just prior to selecting a company','<B>Warning</B> You are about to select a company, so that this company will be able to see your CV details with immediate effect.<BR>\r\nIt is <B>essential</B> that your CV is in good order before you do this. Although you might continue to update your CV a company that downloads a poor or incomplete CV from you may not check for updates later for any student. If your CV is not in good order you could seriously damage your chances of employment with this company.<BR><BR>\r\n\r\nIt is possible for you to declare a <B>preferred</B> CV format you would like this company to view. Please note that in the future it will be possible for them to override this format, but they will be notified that you have a preference. You may enter text into a covering letter, but it is possible that some companies will ignore such letters.<BR><BR>\r\n',34);
INSERT INTO `help` VALUES (1,'StudentCompaniesEditApp',NULL,NULL,'Shown before a student edits an existing application','Please note that there is no guarantee that a\r\ncompany will read your cover letter.\r\n\r\n<BR><BR>\r\nPlease ensure that the CV template you are using\r\nis complete <B>before</B> updating your application.',35);
INSERT INTO `help` VALUES (1,'StudentApplicationsView',NULL,NULL,'Shown before list of applications for student.','Here is a list of your applications to companies.\r\nThis page gives you information about whether\r\na company has seen your application yet, and\r\nwhen they last looked at it.\r\n\r\n<BR><BR>\r\nIt is also possible to update your cover letter\r\nand preferred CV.\r\n<BR><BR>\r\n',36);
INSERT INTO `help` VALUES (1,'PDSCVFetchFailure',NULL,'all','Shown when a CV Fetch from the PDS Fails.','<title>Unable to fetch CV</title>\r\n\r\n',37);
INSERT INTO `help` VALUES (1,'StudentHome2005',NULL,NULL,'Shown on Student Home Page, 2005-06, ALL','Welcome to OPUS - the management system for all aspects of work-integrated learning and placement - developed at the University of Ulster, School of Electrical and Mechanical Engineering.<BR><BR>',38);
INSERT INTO `help` VALUES (1,'SupervisorHome',NULL,NULL,'Shown to Supervisors when they login','Welcome to OPUS - the management system for all aspects of work-integrated learning and placement - developed at the University of Ulster, School of Electrical and Mechanical Engineering.<BR><BR>\r\n\r\nYou can use this system to:\r\n<ul>\r\n  <li>check information recorded for the placement you are supervising;</li>\r\n  <li>see the details of the Academic Tutor who will be visiting your student once they are appointed;</li>\r\n  <li>see and carry out any assessments enabled online for your student;</li>\r\n  <li>access RESOURCES from the menu on the left which give more information about courses, assessment regimes and lots more.</li>\r\n</ul>\r\nIf you supervise more than one student you will need to use your seperate login details appropriate for each student, as only one student appears under one set of login details.',39);

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` VALUES ('English',1);

--
-- Dumping data for table `mime_types`
--

INSERT INTO `mime_types` VALUES ('application/pdf','pdf','Adobe\'s Portable Document Format','uploadable',1);
INSERT INTO `mime_types` VALUES ('application/msword','doc','Microsoft Word','uploadable',2);


--
-- Dumping data for table `policy`
--

INSERT INTO `policy` VALUES ('Placement Coordinator','list,create,edit','','list,create,edit,delete','students,photos','user','access','create,edit,archive,list','create,edit,archive,list','create,edit','create,edit,delete','create,edit,archive,list,delete','create,edit,archive,list','list,create,viewCV,editCV,viewStatus,editStatus,viewCompanies,editCompanies,viewAssessments,editAssessments',1,1000,'list,create,edit,read,write','list,create,edit','list,create,edit');
INSERT INTO `policy` VALUES ('Placement Tutor','list','','','','user','access','list','edit,list','edit','','create,edit,list','list','list,viewCV,editCV,viewStatus,editStatus,viewCompanies,editCompanies,viewAssessments,editAssessments',2,100,'list,edit,read,write','list','list');
INSERT INTO `policy` VALUES ('Course Director','','','','','','','','edit','',NULL,'','','list,viewStatus,viewCompanies,note',3,NULL,NULL,NULL,NULL);
INSERT INTO `policy` VALUES ('Viewer','list','list','list','','user','access','list','list','','','list','list','list,viewCV,viewStatus,viewCompanies,viewAssessments',4,10000,NULL,NULL,NULL);

--
-- Dumping data for table `vacancytype`
--

INSERT INTO `vacancytype` VALUES ('Mechanical Engineering',1);
INSERT INTO `vacancytype` VALUES ('Electrical Engineering',2);
INSERT INTO `vacancytype` VALUES ('Electronics/Computing',3);
INSERT INTO `vacancytype` VALUES ('Biomedical Engineering',4);
INSERT INTO `vacancytype` VALUES ('Design/Consulting',5);
INSERT INTO `vacancytype` VALUES ('Manufacture/Operations',6);
INSERT INTO `vacancytype` VALUES ('Software Engineering',7);
INSERT INTO `vacancytype` VALUES ('Multimedia and Web Design',8);
INSERT INTO `vacancytype` VALUES ('Computer Science',9);
INSERT INTO `vacancytype` VALUES ('Mathematics and Statistics',10);
INSERT INTO `vacancytype` VALUES ('Business and Management',11);
INSERT INTO `vacancytype` VALUES ('Hospitality, tourism & leisure',12);


