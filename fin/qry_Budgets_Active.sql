

SELECT 
sec_BudgetActivityDetailCurrentBiennium.TDPrimaryKey, 
@CurrentPeriodBegin := '10/01/2017',
@CurrentPeriodEnd := '10/31/2017',
@PayDate1 := '10/31/2017',
-- @txtbox_GFA := 'Amy',
@txtbox_GFA := 'Marlo',

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
sec_BudgetActivityDetailCurrentBiennium.TranAmount, 
sec_BudgetActivityDetailCurrentBiennium.BudgetNbr, 
sec_BudgetActivityDetailCurrentBiennium.BudgetName, 
sec_BudgetActivityDetailCurrentBiennium.AccountCode, 

case 
  when tbl_DescName is null then TranDesc 
  else TranDesc
end AS TranDescMod,

-- IIf(IsNull([tbl_DescName]),[TranDesc],[TranDesc])

sec_BudgetActivityDetailCurrentBiennium.TranReference2, 
sec_BudgetActivityDetailCurrentBiennium.TranReference4, 

-- Left(CurrentPeriodBegin,2) & "/" & Mid(CurrentPeriodBegin,3,2) & "/" & Right(CurrentPeriodBegin,2) AS Budget_Begin, 
@CurrentPeriodBegin as Budget_Begin,

-- CDate(Left([CurrentPeriodEnd],2) & "/" & Mid([CurrentPeriodEnd],3,2) & "/" & Right([CurrentPeriodEnd],2)) AS Budget_End, 
@CurrentPeriodEnd as Budget_End,

sec_BudgetActivityDetailCurrentBiennium.TranDate1,
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

sec_BudgetActivityDetailCurrentBiennium.PCAProjectCodeOrig, 
sec_BudgetActivityDetailCurrentBiennium.PCAProjectCodePosting,

sec_BudgetActivityDetailCurrentBiennium.BudgetNbr,
sec_BudgetActivityDetailCurrentBiennium.TranDesc,
GFA_List.GFA, ' '


-- FROM ((sec_BudgetIndexCurrentBiennium 
-- RIGHT JOIN sec_BudgetActivityDetailCurrentBiennium ON sec_BudgetIndexCurrentBiennium.BudgetNbr = sec_BudgetActivityDetailCurrentBiennium.BudgetNbr) 
from sec_BudgetActivityDetailCurrentBiennium

-- LEFT JOIN GFA_List ON sec_BudgetActivityDetailCurrentBiennium.BudgetNbr = GFA_List.BudgetNbr) 
LEFT JOIN GFA_List ON sec_BudgetActivityDetailCurrentBiennium.BudgetNbr = GFA_List.BudgetNbr
RIGHT JOIN tbl_NameTranslation ON sec_BudgetActivityDetailCurrentBiennium.TranDesc = tbl_NameTranslation.tbl_TravelName

WHERE 1=1

-- and GFA_List.GFA = @txtBox_GFA
-- and GFA_List.GFA in (select distinct(GFA) from GFA_List)

-- WHERE (((GFA_List.GFA)=[Forms]![frm_Menu]![txtbox_GFA]) 

-- AND ((CDate(Left([CurrentPeriodEnd],2) & "/" & Mid([CurrentPeriodEnd],3,2) & "/" & Right([CurrentPeriodEnd],2)))>[Forms]![frm_Menu]![txtbox_ClosedDate1]) 
-- AND ((IIf((Left([sec_BudgetActivityDetailCurrentBiennium]![budgetnbr],2)="14" Or Left([sec_BudgetActivityDetailCurrentBiennium]![budgetnbr],2)="75"),"Core",""))="") 
-- AND ((Left([AccountCode],2)) Not Like "9*") 
AND ((sec_BudgetActivityDetailCurrentBiennium.DataSource)<>"XE1") 
AND ((sec_BudgetActivityDetailCurrentBiennium.TranCode)="30" 
Or (sec_BudgetActivityDetailCurrentBiennium.TranCode)="35" 
Or (sec_BudgetActivityDetailCurrentBiennium.TranCode)="36" 
Or (sec_BudgetActivityDetailCurrentBiennium.TranCode)="44" 
Or (sec_BudgetActivityDetailCurrentBiennium.TranCode)="50" 
Or (sec_BudgetActivityDetailCurrentBiennium.TranCode)="52" 
Or (sec_BudgetActivityDetailCurrentBiennium.TranCode)="60" 
Or (sec_BudgetActivityDetailCurrentBiennium.TranCode)="61" 
Or (sec_BudgetActivityDetailCurrentBiennium.TranCode)="62" 
Or (sec_BudgetActivityDetailCurrentBiennium.TranCode)="65" 
Or (sec_BudgetActivityDetailCurrentBiennium.TranCode)="70" 
Or (sec_BudgetActivityDetailCurrentBiennium.TranCode)="73") 
AND ((sec_BudgetActivityDetailCurrentBiennium.OrgCode)="3040449020" 
Or (sec_BudgetActivityDetailCurrentBiennium.OrgCode)="3100049020" 
Or (sec_BudgetActivityDetailCurrentBiennium.OrgCode)="2540578000") 
-- AND ((Right(IIf(Val([FiscalMonth])>6,Val([FiscalYear] & [FiscalMonth])-6,Val([FiscalYear] & [FiscalMonth])-94),2))=[Forms]![frm_Menu]![txtbox_Month]) 
-- AND ((Left(IIf(Val([FiscalMonth])>6,Val([FiscalYear] & [FiscalMonth])-6,Val([FiscalYear] & [FiscalMonth])-94),4))=[Forms]![frm_Menu]![txtbox_Year]))
ORDER BY sec_BudgetActivityDetailCurrentBiennium.BudgetNbr;
