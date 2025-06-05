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
T4."SlpName" AS "Vendedor",

CASE WHEN  T0."GroupNum" = '-1' 
THEN  0
else 75000
END AS "Int y Adm" 

FROM     {?Schema@}.OINV T0      
INNER JOIN {?Schema@}.INV1 T1 ON T0."DocEntry" = T1."DocEntry"     
INNER JOIN {?Schema@}.OITM T2 ON T1."ItemCode" = T2."ItemCode"     
LEFT JOIN {?Schema@}.OSRN T3 ON T2."ItemCode" = T3."ItemCode"
INNER JOIN {?Schema@}.OSLP T4 ON T0."SlpCode" = T4."SlpCode"
WHERE (T0."DocDate" >= {?fechaIni} AND T0."DocDate" <= {?fechaFin}) ;

--#factuyra, cliente, item, pago en pesos



/**************************/
SELECT distinct T0."DocNum" as factura, T0."DocDate",DAYS_BETWEEN(T0."DocDueDate",B0."DocDate") as dias_a_pago ,B0."DocNum" as numero_de_pago , B0."SumApplied", T0."LicTradNum", T0."DocTotal", T0."PaidToDate" 
FROM OINV T0  INNER JOIN INV1 T1 ON T0."DocEntry" = T1."DocEntry" 

LEFT JOIN (SELECT B0."DocNum" , B2."SumApplied" , B2."DocEntry",B0."DocDate" FROM ORCT B0 INNER JOIN RCT2 B2 ON B0."DocEntry" = B2."DocNum" )B0 ON B0."DocEntry"=T0."DocEntry"


WHERE T0."DocDate" >= '20240101'  and   T0."DocStatus"  ='C'  and T0."CardCode" =[%0] and B0."SumApplied" > 0

/*************************************************
*********************************************
**********************************************/
/*docnum es el codigo de la factura
docentry es el id, no visible para user*/
SELECT DISTINCT 
    T0."DocNum" AS codigo_factura,
    T0."DocDate",
    T0."DocDueDate",
    DAYS_BETWEEN(T0."DocDueDate", T0."DocDate") AS dias_faltantes_vmto,--dias restantes para limite
    B0."DocNum" AS cod_pagos_recibidos,count( B0."DocNum") as repeticiones,
    B0."SumApplied" as pago_parcial_aplicado_x_doc,
   -- T0."LicTradNum",
  --  T0."CardCode" as codigo_cliente,
    T0."DocTotal" as totaldocumento,
    T0."PaidToDate"as pagado_hasta_fecha--sumatoria de sumapplied global
FROM 
    OINV T0
    INNER JOIN INV1 T1 ON T0."DocEntry" = T1."DocEntry"
    LEFT JOIN (
        SELECT 
            B0."DocNum",
            B2."SumApplied",
            B2."DocEntry"
           -- B0."DocDate"
        FROM 
            ORCT B0
            INNER JOIN RCT2 B2 ON B0."DocEntry" = B2."DocNum"
    ) B0 ON B0."DocEntry" = T0."DocEntry"
WHERE 
    T0."DocDate" >= '20240101'
    AND T0."DocStatus" = 'C'--doc procesado y cerrado , factura totalmente pagada
    AND B0."SumApplied" > 0

    group by B0."DocNum"
    limit 20;

    --------------



    SELECT DISTINCT 
    T0."DocNum" AS codigo_factura,
    T0."DocDate",
    T0."DocDueDate",
    DAYS_BETWEEN(T0."DocDueDate", T0."DocDate") AS dias_faltantes_vmto,--dias restantes para limite
    B0."DocNum" AS cod_pagos_recibidos,count( B0."DocNum") as repeticiones,
    B0."SumApplied" as pago_parcial_aplicado_x_doc,
    T0."DocTotal" as totaldocumento,
    T0."PaidToDate"as pagado_hasta_fecha--sumatoria de sumapplied global
FROM 
    OINV T0
    INNER JOIN INV1 T1 ON T0."DocEntry" = T1."DocEntry"
     JOIN (
        SELECT 
            B0."DocNum",
            B2."SumApplied",
            B2."DocEntry"
           -- B0."DocDate"
        FROM 
            ORCT B0
            INNER JOIN RCT2 B2 ON B0."DocEntry" = B2."DocNum"
    ) B0 ON B0."DocEntry" = T0."DocEntry"
WHERE 
    T0."DocDate" >= '20240101'
    AND T0."DocStatus" = 'C'--doc procesado y cerrado , factura totalmente pagada
    AND B0."SumApplied" > 0
GROUP BY 
    T0."DocNum", T0."DocDate", T0."DocDueDate", B0."DocNum", B0."SumApplied",T0."DocTotal", T0."PaidToDate"
ORDER BY 
    T0."DocNum";


    /*
    SELECT DISTINCT 
    T0."DocNum" AS codigo_factura,
    T0."DocDate",
    T0."DocDueDate",
    DAYS_BETWEEN(T0."DocDueDate", T0."DocDate") AS dias_faltantes_vmto,--dias restantes para limite
    B0."DocNum" AS cod_pagos_recibidos,count( distinct B0."DocNum) as repeticiones,
    B0."SumApplied" as pago_parcial_aplicado_x_doc,
    T0."DocTotal" as totaldocumento,
    T0."PaidToDate"as pagado_hasta_fecha--sumatoria de sumapplied global
FROM 
    OINV T0
    INNER JOIN INV1 T1 ON T0."DocEntry" = T1."DocEntry"
    LEFT JOIN (
        SELECT 
            B0."DocNum",
            B2."SumApplied",
            B2."DocEntry"
        FROM 
            ORCT B0
            INNER JOIN RCT2 B2 ON B0."DocEntry" = B2."DocNum"
    ) B0 ON B0."DocEntry" = T0."DocEntry"
WHERE 
    T0."DocNum" = '156092'
    AND T0."DocStatus" = 'C'--doc procesado y cerrado , factura totalmente pagada
    AND B0."SumApplied" > 0
GROUP BY 
    T0."DocEntry", T0."DocNum",T0."DocDate", T0."DocDueDate", B0."DocNum", B0."SumApplied",T0."DocTotal", T0."PaidToDate"
ORDER BY 
    T0."DocNum";
    
    */

/**
*Estado actual de informe
Permite calular el %comisiones (sin castigo) y el %descuento  aplicado a cada venta basandose en las condiciones socializadas por Norman Morales en reuniones previas; estos calculos se realizan internamente al evaluar diferentes variables y de acuerdo a estas se obtienen dichos %.

Variables involucradas en el calculo de %comisiones :
*Evaluamos si la venta es de un equipo o aditamento.
*El tipo de negocio credito/contado
*Evaluamos si la venta tiene servicios asociados
*Evaluamos si la venta es referida por call center
*Evaluamos estado inicial nuevo/usado

Aspectos a tener en cuenta.
* Actualmente el %comision asociado a la variable ref (0-0.1) esta calculado con condiciones por defecto debido a que no existe un campo asociado a la factura de donde podamos extraer dicha información.
* Actualmente el estado del equipo (nuevo/usado) esta calculado con condiciones por defecto debido a que no es posible visualizarlo en un campo asociado a la factura.
* Actualmente el Valor lista esta calculado con condiciones por defecto, debido a que no es posible visualizarlo en un campo asociado a la factura.

Como le habiamos comentado a Norman Morales en sesiones pasadas, es de suma importancia que podamos capturar estos valores desde SAP para que asi los avances realizados y futuros ajustes correspondan a información 100% real y precisa.

Quedamos muy atentos a esto.





Se debe tener en cuenta
*Calcula */
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

CASE WHEN T0."CardName" like '%Nuevo%'
then 'Nuevo'
else 'Usado'
end as "estadoinicial",

case
WHEN T0."GroupNum" = '-1' --contado
THEN 
    case 
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


/********/
SELECT T1000."cardcode",
       T1000."cardname",
       T1000."lictradnum",
       T1000."account",
       T1000."saldo",
       T1000."vencido",
       CASE T1000."transtype"
         WHEN '13' THEN 'Factura Cliente'
         WHEN '14' THEN 'Nota Credito Cliente  '
         WHEN '15' THEN 'Entrega Cliente  '
         WHEN '16' THEN 'Devolucion Cliente '
         WHEN '18' THEN 'Factura Proveedores  '
         WHEN '19' THEN 'Nota Credito Proveedores  '
         WHEN '20' THEN 'Entrada de Mercancia  '
         WHEN '21' THEN 'Devolucion compras  '
         WHEN '24' THEN 'Pago Recibido  '
         WHEN '321' THEN 'Reconciliacion Interna  '
         WHEN '30' THEN 'Asiento manual  '
         WHEN '46' THEN 'Pago Efectuado  '
         WHEN '67' THEN 'Trasferencia de stock  '
         WHEN '59' THEN 'Entrada de Mercancia  '
         WHEN '60' THEN 'Salida de Mercancia  '
         WHEN '69' THEN 'Precio de entrega  '
         WHEN '162' THEN 'Revalorización de inventario'
         WHEN '10000071' THEN 'Contabilizacion de stock  '
         ELSE T1000."transtype"
       END AS "Documento",
       T1000."numero",
       T1000."codigo",
       T1000."vendedor",
       T1000."refdate",
       T1000."taxdate",
       T1000."duedate",
       T2000."phone1",
       T2000."phone2",
       T2000."address"
FROM   (SELECT T22."cardcode",
               T22."cardname",
               T22."lictradnum",
               T22."account",
               T22."saldo",
               T22."vencido",
               T22."transtype",
               T22."numero",
               CASE
                 WHEN T33."slpcode" IS NULL THEN T22."slpcode"
                 ELSE T33."slpcode"
               END AS "codigo",
               CASE
                 WHEN T33."slpname" IS NULL THEN T22."slpname"
                 ELSE T33."slpname"
               END AS "vendedor",
               T22."refdate",
               T22."taxdate",
               T22."duedate"
        FROM   (SELECT T13."ref3",
                       T13."ref2",
                       T13."ref1",
                       T1."lictradnum",
                       T1."cardcode",
                       T1."cardname",
                       T13."transtype",
                       T13."createdby",
                       T1."phone1",
                       T1."address",
                       T1."city",
                       T1."slpcode",
                       T11."slpname",
                       T0."account",
                       T12."acctname",
                       CASE T13."transtype"
                         WHEN '13' THEN 'Factura Cliente'
                         WHEN '14' THEN 'Nota Credito Cliente  '
                         WHEN '15' THEN 'Entrega Cliente  '
                         WHEN '16' THEN 'Devolucion Cliente '
                         WHEN '18' THEN 'Factura Proveedores  '
                         WHEN '19' THEN 'Nota Credito Proveedores  '
                         WHEN '20' THEN 'Entrada de Mercancia  '
                         WHEN '21' THEN 'Devolucion compras  '
                         WHEN '24' THEN 'Pago Recibido  '
                         WHEN '321' THEN 'Reconciliacion Interna  '
                         WHEN '30' THEN 'Asiento manual  '
                         WHEN '46' THEN 'Pago Efectuado  '
                         WHEN '67' THEN 'Trasferencia de stock  '
                         WHEN '59' THEN 'Entrada de Mercancia  '
                         WHEN '60' THEN 'Salida de Mercancia  '
                         WHEN '69' THEN 'Precio de entrega  '
                         WHEN '162' THEN 'Revalorización de inventario'
                         WHEN '10000071' THEN 'Contabilizacion de stock  '
                         ELSE T0."transtype"
                       END                                AS "Documento",
                       T13."baseref"                      AS "Numero",
                       T0."refdate",
                       T0."taxdate",
                       T0."duedate",
                       Ifnull((SELECT Sum(CASE WHEN T100."iscredit"='C' THEN
                       -T100."reconsum"
                               ELSE
                       T100."reconsum" END) FROM itr1 T100 INNER JOIN oitr T101
                       ON
                               T100."reconnum" =
                       T101."reconnum" AND T101."recondate">{?@FechaCorte}AND
                               T101."canceled"='N' WHERE
                       T100."transid"=T0."transid" AND
                       T100."transrowid"=T0."line_id"),
                       0)
                       + T0."balduedeb" - T0."balduecred" AS "SALDO",
                       CASE
                         WHEN Days_between(T0."refdate", T0."duedate") <= 0 THEN
                         Ifnull((SELECT Sum(CASE WHEN T100."iscredit"='C' THEN
                         -T100."reconsum"
                         ELSE
                         T100."reconsum" END) FROM itr1 T100 INNER JOIN oitr
                         T101 ON
                         T100."reconnum" =
                         T101."reconnum" AND T101."recondate">{?@FechaCorte}AND
                         T101."canceled"='N'
                         WHERE
                         T100."transid"=T0."transid" AND
                         T100."transrowid"=T0."line_id"), 0)
                         + T0."balduedeb" - T0."balduecred"
                         ELSE 0
                       END                                AS VENCIDO,
                       T0."fccurrency",
                       Ifnull((SELECT Sum(CASE WHEN T100."iscredit"='C' THEN
                       -T100."reconsumfc"
                               ELSE
                       T100."reconsumfc" END) FROM itr1 T100 INNER JOIN oitr
                       T101 ON
                               T100."reconnum" =
                       T101."reconnum" AND T101."recondate">{?@FechaCorte} AND
                               T101."canceled"='N'
                       WHERE T100."transid"=T0."transid" AND
                       T100."transrowid"=T0."line_id"), 0
                               )
                       + T0."balfcdeb" - T0."balfccred"   AS SALDOFC,
                       CASE
                         WHEN Days_between(T0."refdate", T0."duedate") <= 0 THEN
                         Ifnull((SELECT Sum(CASE WHEN T100."iscredit"='C' THEN
                         -T100."reconsumfc" ELSE
                         T100."reconsumfc" END) FROM itr1 T100 INNER JOIN oitr
                         T101 ON
                         T100."reconnum"
                         =
                         T101."reconnum" AND T101."recondate">{?@FechaCorte} AND
                         T101."canceled"='N'
                         WHERE T100."transid"=T0."transid" AND
                         T100."transrowid"=T0."line_id"),
                         0)
                         + T0."balfcdeb" - T0."balfccred"
                         ELSE 0
                       END                                AS VENCIDOFC,
                       T2."groupname"
                FROM   jdt1 T0
                       INNER JOIN ojdt T13
                               ON T13. "transid" = T0."transid"
                       INNER JOIN oact T12
                               ON T12."acctcode" = T0."account"
                       INNER JOIN ortt T10
                               ON T10."ratedate" = T0."refdate"
                                  AND T10."currency" = 'USD'
                       INNER JOIN ocrd T1
                               ON T1."cardcode" = T0."shortname"
                       INNER JOIN oslp T11
                               ON T11."slpcode" = T1."slpcode"
                       INNER JOIN ocrg T2
                               ON T1."groupcode" = T2."groupcode"
                WHERE  T1."cardtype" = 'C'
                       AND ( ( T0."balduedeb" - T0."balduecred" ) <> 0
                              OR ( T0."balfcdeb" - T0."balfccred" ) <> 0
                              OR ( T0."balscdeb" - T0."balsccred" ) <> 0
                              OR EXISTS (SELECT 1
                                         FROM   itr1 T100
                                                INNER JOIN oitr T101
                                                        ON T100."reconnum" =
                                                           T101."reconnum"
                                                           AND
T101."recondate" > {?@FechaCorte}
AND
t101."canceled" = 'N' WHERE t100."transid" = t0."transid"
AND
t100."transrowid" = t0."line_id") )
AND
t0."refdate" <= {?@FechaCorte}) t22 LEFT JOIN
(
           SELECT     t24."slpcode",
                      t24."docentry",
                      t24."objtype",
                      t11."slpname"
           FROM       oinv t24
           INNER JOIN oslp t11
           ON         t24."slpcode" = t11."slpcode"
           UNION ALL
           SELECT     t24."slpcode",
                      t24."docentry",
                      t24."objtype",
                      t11."slpname"
           FROM       orin t24
           INNER JOIN oslp t11
           ON         t24."slpcode" = t11."slpcode") t33 ON t22."transtype" = t33."objtype"
AND
t22."createdby" = t33."docentry") t1000 LEFT JOIN
(
           SELECT     t2."linenum" + 1 AS consecutivo,
                      t2."itemcode",
                      t2."dscription",
                      t0."docdate",
                      t0."docduedate",
                      cast(t0."docnum" AS varchar(20)) AS "Numero" ,
                      'NC',
                      t0."cardcode",
                      t0."cardname",
                      -t2."quantity",
                      ( -t2."pricebefdi" * t0."docrate" ),
                      ( ( -t2."pricebefdi" * t0."docrate" ) * t2."quantity" ) AS subtotal,
                      ( ( ( t2."pricebefdi" * t0."docrate" ) * t2."quantity" ) / 100 * (
                      CASE
                                 WHEN t2."discprcnt" IS NULL THEN 0
                                 WHEN t0."discprcnt" = 0 THEN t2."discprcnt"
                                 WHEN t0."discprcnt" IS NULL THEN t2."discprcnt"
                                 WHEN t0."discprcnt" > 0 THEN t0."discprcnt"
                      END ) ) AS descuento,
                      -( ( ( t2."pricebefdi" * t0."docrate" ) * t2."quantity" ) - ( ( ( t2."pricebefdi" * t0."docrate" ) * t2."quantity" ) / 100 * (
                      CASE
                                 WHEN t2."discprcnt" IS NULL THEN 0
                                 WHEN t0."discprcnt" = 0 THEN t2."discprcnt"
                                 WHEN t0."discprcnt" IS NULL THEN t2."discprcnt"
                                 WHEN t0."discprcnt" > 0 THEN t0."discprcnt"
                      END ) ) ) AS afecto,
                      t0."doctotal",
                      t0."paidtodate",
                      ( t0."doctotal" - t0."paidtodate" ) AS saldo,
                      t0."docstatus",
                      t0."canceled",
                      t0."numatcard",
                      days_between (t0."docduedate", now ()),
                      t0."ctlaccount",
                      t0."objtype",
                      t2."visorder",
                      t6."ocrname",
                      t3."phone1",
                      t3."phone2",
                      t3."address"
           FROM       orin t0
           INNER JOIN rin1 t2
           ON         t0."docentry" = t2."docentry"
           INNER JOIN ocrd t3
           ON         t0."cardcode" = t3."cardcode"
           INNER JOIN oact t10
           ON         t2."acctcode" = t10."acctcode"
           LEFT JOIN  oocr t6
           ON         t2."ocrcode3" = t6."ocrcode"
           WHERE      t0."canceled" = 'N'
           AND        (
                                 t0."doctotal" - t0."paidtodate" ) <> 0
           AND        t2."visorder" = 0
           UNION ALL
           SELECT     t2."linenum" + 1 AS consecutivo,
                      t2."itemcode",
                      t2."dscription",
                      t0."docdate",
                      t0."docduedate",
                      cast(t0."docnum" AS varchar(20)) AS "Numero",
                      'FAC',
                      t0."cardcode",
                      t0."cardname",
                      t2."quantity",
                      ( t2."pricebefdi" * t0."docrate" ),
                      ( ( t2."pricebefdi" * t0."docrate" ) * t2."quantity" ) AS subtotal,
                      ( ( ( t2."pricebefdi" * t0."docrate" ) * t2."quantity" ) / 100 * (
                      CASE
                                 WHEN t2."discprcnt" IS NULL THEN 0
                                 WHEN t0."discprcnt" = 0 THEN t2."discprcnt"
                                 WHEN t0."discprcnt" IS NULL THEN t2."discprcnt"
                                 WHEN t0."discprcnt" > 0 THEN t0."discprcnt"
                      END ) ) AS descuento,
                      ( ( ( t2."pricebefdi" * t0."docrate" ) * t2."quantity" ) - ( ( ( t2."pricebefdi" * t0."docrate" ) * t2."quantity" ) / 100 * (
                      CASE
                                 WHEN t2."discprcnt" IS NULL THEN 0
                                 WHEN t0."discprcnt" = 0 THEN t2."discprcnt"
                                 WHEN t0."discprcnt" IS NULL THEN t2."discprcnt"
                                 WHEN t0."discprcnt" > 0 THEN t0."discprcnt"
                      END ) ) ) AS afecto,
                      t0."doctotal",
                      t0."paidtodate",
                      ( t0."doctotal" - t0."paidtodate" ) AS saldo,
                      t0."docstatus",
                      t0."canceled",
                      t0."numatcard",
                      days_between (t0."docduedate", now ()),
                      t0."ctlaccount",
                      t0."objtype",
                      t2."visorder",
                      t6."ocrname",
                      t3."phone1",
                      t3."phone2",
                      t3."address"
           FROM       oinv t0
           INNER JOIN inv1 t2
           ON         t0."docentry" = t2."docentry"
           INNER JOIN ocrd t3
           ON         t0."cardcode" = t3."cardcode"
           INNER JOIN oact t10
           ON         t2."acctcode" = t10."acctcode"
           LEFT JOIN  oocr t6
           ON         t2."ocrcode3" = t6."ocrcode"
           WHERE      t0."canceled" = 'N'
           AND        (
                                 t0."doctotal" - t0."paidtodate" ) <> 0
           AND        t2."visorder" = 0) t2000 ON t1000."cardcode" = t2000."cardcode"
AND
t1000."transtype" = t2000."objtype"
AND
t1000."numero" = t2000."numero"
and T1000."vendedor" like {%?@NombreVendedor%}