-- TAKE A BACKUP WITH MYSQLDUMP!

-- update schema version --

update service set schema_version='4.1.1';

-- add a channel for automatically added students

insert into channel (name, description) values('AutoCreatedStudents', 'Students automatically added to OPUS');

