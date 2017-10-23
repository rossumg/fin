-- qry_BudgetNumbers

SELECT tbl_DateTranslation.ActualYear, tbl_DateTranslation.ActualMonth, sec_BudgetActivityDetailCurrentBiennium.BudgetNbr
FROM tbl_DateTranslation 
RIGHT JOIN sec_BudgetActivityDetailCurrentBiennium ON (tbl_DateTranslation.FiscalMonth = sec_BudgetActivityDetailCurrentBiennium.FiscalMonth) 
AND (tbl_DateTranslation.FiscalYear = sec_BudgetActivityDetailCurrentBiennium.FiscalYear)
-- WHERE (((Left([AccountCode],2)) Not Like "9*") 
WHERE  1=1
-- and (((Left(123,2)) Not Like "9*") 
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
Or (sec_BudgetActivityDetailCurrentBiennium.OrgCode)="3100049020")
-- )
GROUP BY tbl_DateTranslation.ActualYear, tbl_DateTranslation.ActualMonth, sec_BudgetActivityDetailCurrentBiennium.BudgetNbr
-- HAVING (((tbl_DateTranslation.ActualYear)=[Year]) AND ((tbl_DateTranslation.ActualMonth)=[Month]))
-- HAVING (((tbl_DateTranslation.ActualYear)='2017') AND ((tbl_DateTranslation.ActualMonth)='05'))
ORDER BY sec_BudgetActivityDetailCurrentBiennium.BudgetNbr;
