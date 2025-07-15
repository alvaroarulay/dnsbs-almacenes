SET DEFAULT TO c:\swp2012\data
SELECT unidad,aux.cta,p.des,aux.tipo,padrea,aux.aux,ff,of,razon,ciudad,tel1,tel2,ruc,cuenta ctabco,saldo sdoini,dbb debe,hbb haber,;
saldo+dbb,hbb sdofin ; 
FROM s3auxi aux,s3plan_c p WHERE NOT DELETED();
AND aux.cta=p.cta ;
ORDER BY unidad,aux.cta,aux.aux,padrea
