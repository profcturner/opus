-- second part -- deletion!! --

-- staff table --

alter table staff drop column initials;
alter table staff drop column title;
alter table staff drop column firstname;
alter table staff drop column surname;
alter table staff drop column department;
alter table staff drop column staffno;
alter table staff drop column email;
alter table staff add column postcode tinytext not null after address;
alter table staff add column id int unsigned not null auto_increment primary key;

-- admin --

rename table admins to admin;
alter table staff drop column title;
alter table staff drop column firstname;
alter table staff drop column surname;
alter table staff drop column staffno;
alter table staff drop column email;
