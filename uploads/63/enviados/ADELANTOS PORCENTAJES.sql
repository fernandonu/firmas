
     /* traigo la cantidad pagada por id de pago */
     select sum(op.monto) as total_pagado,op.id_pago
           from
	               compras.ordenes_pagos op  
	              where ( (not op."númeroch" is null) or (not op."iddébito" is null) or (not id_ingreso_egreso is null))
	              group by op.id_pago order by op.id_pago

    
   /* traigo la cantidad pagada por oc */

     select sum(fila.cantidad*fila.precio_unitario) as total, id_pago,oc.id_moneda,oc.nro_orden
                     from compras.orden_de_compra oc
                     join general.proveedor using (id_proveedor)
                     join compras.fila using(nro_orden)
                     join compras.pago_orden using (nro_orden)
                     join compras.ordenes_pagos op using (id_pago) 
                     where  (oc.estado='g' or oc.estado='d')
                               and   proveedor.razon_social not  ilike '%stock%'
                               and  fila.es_agregado=0

                     group by id_pago,oc.id_moneda,oc.nro_orden 
                     order by id_pago,oc.nro_orden


    /* traigo la cantidad que TENDRIA 	que pagar por cada id_pago */
            select sum(fila.cantidad*fila.precio_unitario) as total, id_pago,oc.id_moneda--,oc.nro_orden
                     from compras.orden_de_compra oc
                     join general.proveedor using (id_proveedor)
                     join compras.fila using(nro_orden)
                     join compras.pago_orden using (nro_orden)
                     join compras.ordenes_pagos op using (id_pago) 
                     where  (oc.estado='g' or oc.estado='d')
                               and   proveedor.razon_social not  ilike '%stock%'
                               and  fila.es_agregado=0

                     group by id_pago,oc.id_moneda 
                     order by id_pago