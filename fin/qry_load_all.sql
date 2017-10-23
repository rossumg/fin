

SELECT 
load_all.TDPrimaryKey, 
@CurrentPeriodBegin := '10/01/2017',
@CurrentPeriodEnd := '10/31/2017',
@PayDate1 := '10/31/2017',
-- @txtbox_GFA := 'Amy',
-- @txtbox_GFA := 'Marlo',

case 
  when PCAProjectCodePosting='99999Z'
  or PCAProjectCodePosting='CODEME'
  or PCAProjectCodePosting='99999Y' then PCAProjectCodeOrig
  else PCAProjectCodePosting
end AS ProjectCode,

-- IIf(([PCAProjectCodePosting]="99999Z" 
-- IIf((PCAProjectCodePosting="99999Z" 
-- Or [PCAProjectCodePosting]="CODEME" 
-- Or [PCAProjectCodePosting]="99999Y"),[PCAProjectCodeOrig],[PCAProjectCodePosting]) AS ProjectCode, 

GFA_List.GFA, 
Now() AS AdjustmentLoadedDate, 
load_all.TranAmount, 
load_all.BudgetNbr, 
load_all.BudgetName, 
load_all.AccountCode, 

-- case 
--   when tbl_DescName is null then TranDesc 
--   else TranDesc
-- end AS TranDescMod,

-- IIf(IsNull([tbl_DescName]),[TranDesc],[TranDesc])

load_all.TranReference2, 
load_all.TranReference4, 

-- Left(CurrentPeriodBegin,2) & "/" & Mid(CurrentPeriodBegin,3,2) & "/" & Right(CurrentPeriodBegin,2) AS Budget_Begin, 
@CurrentPeriodBegin as Budget_Begin,

-- CDate(Left([CurrentPeriodEnd],2) & "/" & Mid([CurrentPeriodEnd],3,2) & "/" & Right([CurrentPeriodEnd],2)) AS Budget_End, 
@CurrentPeriodEnd as Budget_End,

load_all.TranDate1,
-- PCAProjectCodePosting,

case 
  when PCAProjectCodePosting='99999Z' 
  or PCAProjectCodePosting='CODEME' 
  or PCAProjectCodePosting='99999Y' then 'Modified'
  else case
         when TranDate1 > @PayDate1 then 'Modified'
		 else ''
       end
end AS Modified,
 
-- IIf(([PCAProjectCodePosting]="99999Z" 
-- Or [PCAProjectCodePosting]="CODEME" 
-- Or [PCAProjectCodePosting]="99999Y"),"Modified",IIf([TranDate1]>[Forms]![frm_Menu]![txtbox_PayDate1],"Modified","")) 
-- AS Modified, 

load_all.PCAProjectCodeOrig, 
load_all.PCAProjectCodePosting,

load_all.BudgetNbr,
-- load_all.TranDesc,
GFA_List.GFA, ' '


-- FROM ((sec_BudgetIndexCurrentBiennium 
-- RIGHT JOIN load_all ON sec_BudgetIndexCurrentBiennium.BudgetNbr = sec_BudgetActivityDetailCurrentBiennium.BudgetNbr) 
from load_all

-- LEFT JOIN GFA_List ON load_all.BudgetNbr = GFA_List.BudgetNbr) 
LEFT JOIN GFA_List ON trim(load_all.BudgetNbr) = trim(GFA_List.BudgetNbr)
-- RIGHT JOIN tbl_NameTranslation ON load_all.TranDesc = tbl_NameTranslation.tbl_TravelName

WHERE 1=1

-- and GFA_List.GFA = @txtBox_GFA
-- and GFA_List.GFA in (select distinct(GFA) from GFA_List)

-- WHERE (((GFA_List.GFA)=[Forms]![frm_Menu]![txtbox_GFA]) 

-- AND ((CDate(Left([CurrentPeriodEnd],2) & "/" & Mid([CurrentPeriodEnd],3,2) & "/" & Right([CurrentPeriodEnd],2)))>[Forms]![frm_Menu]![txtbox_ClosedDate1]) 
-- AND ((IIf((Left([load_all]![budgetnbr],2)="14" Or Left([sec_BudgetActivityDetailCurrentBiennium]![budgetnbr],2)="75"),"Core",""))="") 
-- AND ((Left([AccountCode],2)) Not Like "9*") 
-- AND ((load_all.DataSource)<>"XE1") 
/*
AND ((load_all.TranCode)="30" 
Or (load_all.TranCode)="35" 
Or (load_all.TranCode)="36" 
Or (load_all.TranCode)="44" 
Or (load_all.TranCode)="50" 
Or (load_all.TranCode)="52" 
Or (load_all.TranCode)="60" 
Or (load_all.TranCode)="61" 
Or (load_all.TranCode)="62" 
Or (load_all.TranCode)="65" 
Or (load_all.TranCode)="70" 
Or (load_all.TranCode)="73") 

AND ((load_all.OrgCode)="3040449020" 
Or (load_all.OrgCode)="3100049020" 
Or (load_all.OrgCode)="2540578000") 
*/
-- AND ((Right(IIf(Val([FiscalMonth])>6,Val([FiscalYear] & [FiscalMonth])-6,Val([FiscalYear] & [FiscalMonth])-94),2))=[Forms]![frm_Menu]![txtbox_Month]) 
-- AND ((Left(IIf(Val([FiscalMonth])>6,Val([FiscalYear] & [FiscalMonth])-6,Val([FiscalYear] & [FiscalMonth])-94),4))=[Forms]![frm_Menu]![txtbox_Year]))
ORDER BY load_all.BudgetNbr
;

