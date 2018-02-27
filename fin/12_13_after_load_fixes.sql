CREATE FUNCTION split_str(
x VARCHAR(255),
delim VARCHAR(12),
pos INT
)
RETURNS VARCHAR(255)
RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(x, delim, pos),
LENGTH(SUBSTRING_INDEX(x, delim, pos -1)) + 1),
delim, '');



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
PCAOptionCodeOrig = trim( PCAOptionCodeOrig),
PCAOptionCodePosting = trim( PCAOptionCodePosting),
PCATaskCodeOrig = trim( PCATaskCodeOrig),
PCATaskCodePosting = trim( PCATaskCodePosting),
TranFTE = trim( TranFTE),
TDPrimaryKey = trim( TDPrimaryKey),
FiscalMonth = trim( FiscalMonth),
FiscalYear = trim(FiscalYear);

-- fix 1
update activitydetail set Budget_Begin = 
cast(
concat(trim(split_str(Budget_Begin, '/', 3)),'-',
concat(trim(split_str(Budget_Begin, '/', 1))),'-',
trim(split_str(Budget_Begin, '/', 2))) as date)
where trim(FiscalMonth)in('04','05');
-- and year

-- fix 2
update activitydetail set Budget_End = 
cast(
concat(trim(split_str(Budget_End, '/', 3)),'-',
concat(trim(split_str(Budget_End, '/', 1))),'-',
trim(split_str(Budget_End, '/', 2))) as date)
where trim(FiscalMonth)in('04','05');
-- and year

-- fix 3
update activitydetail set TranDate1 = 
cast(
concat(trim(split_str(TranDate1, '/', 3)),'-',
concat(trim(split_str(TranDate1, '/', 1))),'-',
trim(split_str(TranDate1, '/', 2))) as date)
where trim(FiscalMonth)in('04','05');
-- and year

-- fix 4
update activitydetail ad set ProjectCode = 
case 
  when 
  ( select distinct Project_Code from Project_Codes pc  
      where 1=1
      and pc.Project_Code = ad.PCAProjectCodeOrig
	  and pc.BudgetNbr = ad.BudgetNbr
	  and pc.Project_Status = 'Active') is not null then PCAProjectCodeOrig
  else ''
end
where 1=1
-- and ad.id in (89056,89057,89064,89065,89124, 89125)
-- and trim(FiscalMonth)in('04','05')
-- and trim(FiscalYear)in('2018')
;

-- fix 5, TranDate1 convert - 7 months
select FiscalMonth, FiscalYear,
month( 
date_sub(
cast(
concat(FiscalYear,'-',
concat(FiscalMonth,'-',
concat(1))) as date), interval 7 month)) as 'Month',
year( 
date_sub(
cast(
concat(FiscalYear,'-',
concat(FiscalMonth,'-',
concat(1))) as date), interval 7 month)) as 'Year'
from activitydetail
where id = 89056;

update activitydetail set
FiscalYear = 
year( 
date_sub(
cast(
concat(FiscalYear,'-',
concat(FiscalMonth,'-',
concat(1))) as date), interval 7 month)), 

FiscalMonth = 
month( 
date_sub(
cast(
concat(FiscalYear,'-',
concat(FiscalMonth,'-',
concat(1))) as date), interval 7 month)) 

where 1=1
and trim(FiscalMonth)in('01','02','03','04','05')
and trim(FiscalYear)in('2018')
;

-- fix 6, Back Dating if trandate1 m/y > BudgetEnd m/y then m/y = BudgetEnd m/y
select id, month(budget_end), year(budget_end), fiscalmonth, fiscalyear from activitydetail
where fiscalmonth > month(budget_end) and fiscalyear > year(budget_end)
;

-- fix 6, if fMY > budget_end than aMY = budget_end, fix 9 -> '09'
select id, tdprimarykey, fiscalmonth, fiscalyear, lpad(itechmonth,2,'0'), itechyear, 
budget_end, month(budget_end), year(budget_end)
from activitydetail
where budget_end is not null
and 
cast(
concat(FiscalYear,'-',
concat(FiscalMonth,'-',
concat(1))) as date) >= budget_end
and fiscalmonth in ('09', '10','11','12','01')
-- and fiscalmonth in ('10','11','12')
-- and fiscalmonth in ('01')
order by fiscalmonth
;

select tdprimarykey, budget_end, fiscalmonth, fiscalyear, itechmonth, itechyear from activitydetail
where budget_end is not null
and 
cast(
concat(FiscalYear,'-',
concat(FiscalMonth,'-',
concat(1))) as date) >= budget_end
-- and fiscalmonth in ('09','10','11','12','01')
;

update activitydetail set itechmonth = lpad(month(budget_end),2,'0'), itechyear = year(budget_end)
where budget_end is not null
and 
cast(
concat(FiscalYear,'-',
concat(FiscalMonth,'-',
concat(1))) as date) >= budget_end
and fiscalmonth in ('09','10','11','12','01')
;

-- fix 7, when fMY = budget_beginMY and day(budget_begin) > 25 than aMY = fMY + 1 month
select id, tdprimarykey, fiscalmonth, fiscalyear, itechmonth, itechyear, budget_begin
from activitydetail where 1=1
and budget_begin is not null
and year(budget_begin) = fiscalyear
and month(budget_begin) = fiscalmonth
and day(budget_begin) > 25
-- and fiscalmonth in ('10','11','12','01')
order by fiscalmonth
;

select itechmonth, count(*) from activitydetail 
-- where itechmonth in ( '3', '03', '7', '07', '08', '6', '06', '09', '9' )
group by itechmonth
;

update activitydetail set itechmonth = '06' where itechmonth = '6';


