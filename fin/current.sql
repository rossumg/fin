delete from activitydetail;

select count(*) from activitydetail where trim(FiscalMonth) = '01';

insert into activitydetail (

ProjectCode,
GFA,
AdjustmentLoadedDate,
TranAmount,
BudgetNbr,
BudgetName,
AccountCode,
TranDescMod,
TranReference2,
TranReference4,
Budget_Begin,
Budget_End,
TranDate1,
Modified,
PCAProjectCodeOrig,
PCAProjectCodePosting,
TranReference1,
TranReference2,
TranReference3,
TranReference4,
TDPrimaryKey,
FiscalMonth,
FiscalYear
 ) values
;

select * from Project_Codes where Project_Code = 'HTNADH';

select distinct(concat(ad.ProjectCode, ' ', pc.BudgetNbr))
from Project_Codes pc 
left join activitydetail ad on ad.ProjectCode = pc.Project_Code
where ad.BudgetNbr = '611460'
and pc.Project_Status = 'Active'
;



select * from GFA_List;

select * from activitydetail   where id in (272,277,289);
select * from activitydetail where id in (2,3,4,5,6);

select * from activitydetail   where GFA = 'Maymouna gnr';
update activitydetail set GFA = 'Maymouna' where id = 68;
commit;

select distinct(projectCode) from activitydetail  ;
select distinct(accountCode) from activitydetail  ;
select distinct(pcaprojectcodeorig) from activitydetail;
select distinct(pcaprojectcodeposting) from activitydetail;
select distinct(trandate1) from activitydetail;
select distinct(trandescmod) from activitydetail;
select distinct(tranreference2) from activitydetail;
select distinct(tranreference4) from activitydetail;
select distinct(modified) from activitydetail;

update load_all set trandescmod = left(trandescmod, 6);
delete from load_all where id > 2000;

select distinct(Budget_Begin) from activitydetail order by Budget_Begin;
select distinct(Budget_End) from activitydetail;
select distinct(ProjectCode) from activitydetail;

select CAST('2017-11-21' as date);

select CAST('11/21/2017' as date);
select current_date();

select distinct id, GFA, BudgetNbr 
from load_all la
where trim(la.GFA) = 'Amy'
;

select distinct(BudgetName) from activitydetail;

SELECT  *
-- DISTINCT la.id as "id", la.GFA, la.BudgetNbr   
FROM load_all la 
WHERE  1=1  
-- and trim(la.GFA) like '%AMY%' 
-- and  trim(la.BudgetNbr) like  '%633418%'   
and la.Budget_Begin >= cast('2017-07-01' as date)        
and la.Budget_End  <= cast('2018-09-01' as date)        
;

select distinct(Budget_Begin) from activitydetail;
select distinct(Budget_End) from activitydetail;
select distinct(BudgetNbr) from activitydetail;
SELECT DISTINCT `GFA_List`.`BudgetNbr` FROM `GFA_List` ORDER BY `BudgetNbr` ASC;

select count(*) from activitydetail;

-- fix 0
update activitydetail ad set
ProjectCode = trim( ProjectCode),
GFA = trim( GFA),
AdjustmentLoadedDate = trim( AdjustmentLoadedDate),
TranAmount = trim( TranAmount),
BudgetNbr = trim( BudgetNbr),
BudgetName = trim( BudgetName),
AccountCode = trim( AccountCode), 
TranDescMod = trim( TranDescMod),
TranReference1 = trim( TranReference1),
TranReference2 = trim( TranReference2),
TranReference3 = trim( TranReference3),
TranReference4 = trim( TranReference4),
Budget_Begin = trim( Budget_Begin),
Budget_End = trim( Budget_End),
TranDate1 = trim( TranDate1),
Modified = trim( Modified),
PCAProjectCodeOrig = trim( PCAProjectCodeOrig),
PCAProjectCodePosting = trim( PCAProjectCodePosting),
TDPrimaryKey = trim( TDPrimaryKey),
FiscalMonth = trim( FiscalMonth),
FiscalYear = trim(FiscalYear);

-- fix 1, run once
update activitydetail set Budget_Begin = 
cast(
concat(trim(split_str(Budget_Begin, '/', 3)),'-',
concat(trim(split_str(Budget_Begin, '/', 1))),'-',
trim(split_str(Budget_Begin, '/', 2))) as date)
where trim(FiscalMonth)in('01','02','03');

-- fix 2, run once
update activitydetail set Budget_End = 
cast(
concat(trim(split_str(Budget_End, '/', 3)),'-',
concat(trim(split_str(Budget_End, '/', 1))),'-',
trim(split_str(Budget_End, '/', 2))) as date)
where trim(FiscalMonth)in('01','02','03');

-- fix 3, run once
update activitydetail set TranDate1 = 
cast(
concat(trim(split_str(TranDate1, '/', 3)),'-',
concat(trim(split_str(TranDate1, '/', 1))),'-',
trim(split_str(TranDate1, '/', 2))) as date)
where trim(FiscalMonth)in('01','02','03');

-- fix 4
update activitydetail ad set ProjectCode = 
case 
  when PCAProjectCodePosting='99999Z'
  or PCAProjectCodePosting='CODEME'
  or PCAProjectCodePosting='99999Y' then 
	( select distinct Project_Code from Project_Codes pc  
      where 1=1
      and pc.Project_Code = ad.PCAProjectCodeOrig
	  and pc.BudgetNbr = ad.BudgetNbr
	  and pc.Project_Status = 'Active')
  else PCAProjectCodePosting
end 
-- where trim(TDPrimaryKey) in ('170322039580', '170325035040')
; 

-- fix 5
update activitydetail set Modified = 
case 
  when PCAProjectCodePosting='99999Z' 
  or PCAProjectCodePosting='CODEME' 
  or PCAProjectCodePosting='99999Y' then 'Modified'
  -- else case
         -- when TranDate1 > @PayDate1 then 'Modified' -- temp out
		 else ''
       -- end
end;


select 
max(
cast(
concat(trim(split_str(Budget_Begin, '/', 3)),'-',
concat(trim(split_str(Budget_Begin, '/', 1))),'-',
trim(split_str(Budget_Begin, '/', 2))) as date))
from activitydetail;

select min(Budget_Begin), max(Budget_Begin) from activitydetail;
select min(Budget_End), max(Budget_End) from activitydetail;

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