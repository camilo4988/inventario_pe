/*****/


SELECT DISTINCT T0."DocEntry" AS "Llave interna",
T0."DocNum" AS "Factura",    T0."DocDate",     T0."CardCode",     T0."CardName",       
         

     T1."ItemCode",     T2."ItemName",     T1."PriceAfVAT" AS "Valor Venta",    T0."BaseAmnt" AS "Venta Antes de IVA",     T0."DocTotal",     T0."PaidToDate",     T1."PriceAfVAT" / 1.19 AS "sin_iva",    
 CASE         
 WHEN LENGTH(T1."ItemCode") <= 2        
 THEN T1."PriceAfVAT"         
 ELSE T1."PriceAfVAT" + 820000    
  END AS "VrLISTA",
  
  CASE        
   WHEN LENGTH(T1."ItemCode") <= 2         THEN 0        
    ELSE ((T1."PriceAfVAT" + 820000) - T1."PriceAfVAT") / (T1."PriceAfVAT" / 100)    
    END AS "P_DTO",  

CASE WHEN T2."ItmsGrpCod" IN (117,118) THEN 5 ELSE 19 END AS "%Iva",

CASE WHEN T0."CardCode" like '%00%'
then 0
else 0.1
end as "ref",

case
WHEN T0."GroupNum" = '-1' --contado
THEN 
    case 
    --
    when T1."ItemCode" like 'ZR%' then 1.6
    else 1.1
    END
when T0."GroupNum" != '-1'--CREDITO 
THEN 
    case 
    when T1."ItemCode" like 'ZR%' then 1.5
    else 0.9
    END
END AS "ti",
T4."SlpName" AS "Vendedor"

FROM     {?Schema@}.OINV T0      
INNER JOIN {?Schema@}.INV1 T1 ON T0."DocEntry" = T1."DocEntry"     
INNER JOIN {?Schema@}.OITM T2 ON T1."ItemCode" = T2."ItemCode"     
LEFT JOIN {?Schema@}.OSRN T3 ON T2."ItemCode" = T3."ItemCode"
INNER JOIN {?Schema@}.OSLP T4 ON T0."SlpCode" = T4."SlpCode"
WHERE (T0."DocDate" >= {?fechaIni} AND T0."DocDate" <= {?fechaFin}) ;

--#factuyra, cliente, item, pago en pesos