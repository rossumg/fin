SELECT DISTINCT 
'projectcode' AS ProjectCode, 
GFA_List.GFA, Now() AS AdjustmentLoadedDate, sec_BudgetActivityDetailCurrentBiennium.TranAmount, sec_BudgetActivityDetailCurrentBiennium.BudgetNbr, 
sec_BudgetActivityDetailCurrentBiennium.BudgetName, sec_BudgetActivityDetailCurrentBiennium.AccountCode, sec_BudgetActivityDetailCurrentBiennium.TranDesc AS TranDescMod, 

sec_BudgetActivityDetailCurrentBiennium.TranReference1 AS TranReference1,
sec_BudgetActivityDetailCurrentBiennium.TranReference2 AS TranReference2,
sec_BudgetActivityDetailCurrentBiennium.TranReference3 AS TranReference3, 
sec_BudgetActivityDetailCurrentBiennium.TranReference4 AS TranReference4,

CDate(Left([CurrentPeriodBegin],2) & "/" & Mid([CurrentPeriodBegin],3,2) & "/" & Right([CurrentPeriodBegin],2)) AS Budget_Begin, 
CDate(Left([CurrentPeriodEnd],2) & "/" & Mid([CurrentPeriodEnd],3,2) & "/" & Right([CurrentPeriodEnd],2)) AS Budget_End, 

sec_BudgetActivityDetailCurrentBiennium.TranDate1,
 'modified' AS Modified, 
sec_BudgetActivityDetailCurrentBiennium.PCAProjectCodeOrig, sec_BudgetActivityDetailCurrentBiennium.PCAProjectCodePosting, sec_BudgetActivityDetailCurrentBiennium.TDPrimaryKey, sec_BudgetActivityDetailCurrentBiennium.FiscalMonth, sec_BudgetActivityDetailCurrentBiennium.FiscalYear,

sec_BudgetActivityDetailCurrentBiennium.PCAOptionCodeOrig,
sec_BudgetActivityDetailCurrentBiennium.PCAOptionCodePosting,
sec_BudgetActivityDetailCurrentBiennium.PCATaskCodeOrig,
sec_BudgetActivityDetailCurrentBiennium.PCATaskCodePosting,
sec_BudgetActivityDetailCurrentBiennium.TranFTE

FROM tbl_NameTranslation RIGHT JOIN ((sec_BudgetActivityDetailCurrentBiennium LEFT JOIN GFA_List ON sec_BudgetActivityDetailCurrentBiennium.BudgetNbr = GFA_List.BudgetNbr) LEFT JOIN sec_BudgetIndexCurrentBiennium ON sec_BudgetActivityDetailCurrentBiennium.BudgetNbr = sec_BudgetIndexCurrentBiennium.BudgetNbr) ON tbl_NameTranslation.tbl_TravelName = sec_BudgetActivityDetailCurrentBiennium.TranDesc
WHERE ((Left([AccountCode],2)) Not Like "9*") 

And ((sec_BudgetActivityDetailCurrentBiennium.FiscalMonth)="06")
And ((sec_BudgetActivityDetailCurrentBiennium.FiscalYear)="2018")
And ((sec_BudgetActivityDetailCurrentBiennium.DataSource)<>"XE1") 

And ((sec_BudgetActivityDetailCurrentBiennium.TranCode)="30" 
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

And ((sec_BudgetActivityDetailCurrentBiennium.OrgCode)="3040449020" 
  Or (sec_BudgetActivityDetailCurrentBiennium.OrgCode)="3100049020" 
  Or (sec_BudgetActivityDetailCurrentBiennium.OrgCode)="2540578000" 
  Or (sec_BudgetActivityDetailCurrentBiennium.OrgCode)="3060002030" 
  Or (sec_BudgetActivityDetailCurrentBiennium.OrgCode)="3040449061")
ORDER BY sec_BudgetActivityDetailCurrentBiennium.TDPrimaryKey DESC;

