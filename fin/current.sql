select distinct(Budget_Begin) from load_all order by Budget_Begin;
select distinct(Budget_End) from load_all;
select distinct(ProjectCode) from load_all;

select CAST('2017-11-21' as date);

select CAST('11/21/2017' as date);
select current_date();

select distinct id, GFA, BudgetNbr 
from load_all la
where trim(la.GFA) = 'Amy'
;

select distinct(BudgetName) from load_all;

SELECT  *
-- DISTINCT la.id as "id", la.GFA, la.BudgetNbr   
FROM load_all la 
WHERE  1=1  
-- and trim(la.GFA) like '%AMY%' 
-- and  trim(la.BudgetNbr) like  '%633418%'   
and la.Budget_Begin >= cast('2017-07-01' as date)        
and la.Budget_End  <= cast('2018-09-01' as date)        
;

select distinct(Budget_Begin) from load_all;
select distinct(Budget_End) from load_all;
select distinct(BudgetNbr) from load_all;
SELECT DISTINCT `GFA_List`.`BudgetNbr` FROM `GFA_List` ORDER BY `BudgetNbr` ASC;

select * from load_all;

update load_all set Budget_Begin = 
cast(
concat(trim(split_str(Budget_Begin, '/', 3)),'-',
concat(trim(split_str(Budget_Begin, '/', 1))),'-',
trim(split_str(Budget_Begin, '/', 2))) as date);

update load_all set Budget_End = 
cast(
concat(trim(split_str(Budget_End, '/', 3)),'-',
concat(trim(split_str(Budget_End, '/', 1))),'-',
trim(split_str(Budget_End, '/', 2))) as date);

update load_all set TranDate1 = 
cast(
concat(trim(split_str(TranDate1, '/', 3)),'-',
concat(trim(split_str(TranDate1, '/', 1))),'-',
trim(split_str(TranDate1, '/', 2))) as date);

select 
max(
cast(
concat(trim(split_str(Budget_Begin, '/', 3)),'-',
concat(trim(split_str(Budget_Begin, '/', 1))),'-',
trim(split_str(Budget_Begin, '/', 2))) as date))
from load_all;

select min(Budget_Begin), max(Budget_Begin) from load_all;
select min(Budget_End), max(Budget_End) from load_all;

CREATE FUNCTION split_str(
x VARCHAR(255),
delim VARCHAR(12),
pos INT
)
RETURNS VARCHAR(255)
RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(x, delim, pos),
LENGTH(SUBSTRING_INDEX(x, delim, pos -1)) + 1),
delim, '');


select * from user_to_acl where user_id = 24 and acl_id like '*user*';
select * from user_to_acl where user_id = 24 and acl_id like '%add%';
select * from user_to_acl where user_id = 24 and acl_id like '%report%';


delete from user_to_acl;
delete from user_to_acl where user_id = 24 and acl_id not like '%user%';
delete from user_to_acl where user_id = 24 and acl_id like 'display_country_reports';


insert into user_to_acl (acl_id, user_id, created_by, timestamp_created) values
('add_edit_fin', 24,1,now());

insert into user_to_acl (acl_id, user_id, created_by, timestamp_created) values
('display_country_reports', 24,1,now());

select * from acl;

insert into acl (id,acl) values
('add_edit_fin','add_edit_fin');

insert into acl (id,acl) values
('display_country_reports','display_country_reports');

delete from acl where id = 'display_country_reports';

select * from translation;


select display_country_reports from _system;
select * from file;
select * from site_styles;

SELECT DISTINCT `GFA_List`.`BudgetNbr` FROM `GFA_List` ORDER BY `GFA` ASC LIMIT 999;