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

-- fix 1
update activitydetail set Budget_Begin = 
cast(
concat(trim(split_str(Budget_Begin, '/', 3)),'-',
concat(trim(split_str(Budget_Begin, '/', 1))),'-',
trim(split_str(Budget_Begin, '/', 2))) as date)
where trim(FiscalMonth)in('01','02','03');

-- fix 2
update activitydetail set Budget_End = 
cast(
concat(trim(split_str(Budget_End, '/', 3)),'-',
concat(trim(split_str(Budget_End, '/', 1))),'-',
trim(split_str(Budget_End, '/', 2))) as date)
where trim(FiscalMonth)in('01','02','03');

-- fix 3
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