select sum(total_pagado - total_recibido) as total from (

select sum(pagos.total/cantidad_pagos.cantidad) as total_pagado, recibidos.total_recibido,filas.nro_orden
--select filas.nro_orden
      from
          (
           --Obtengo la cantidad de elementos de la oc , y filtro por las condiciones 
           --traigo las ordenes de compra asociadas a la licitacion
           select sum(fila.cantidad) as cantidad, oc.nro_orden 
               from compras.orden_de_compra oc
                join licitaciones.licitacion using(id_licitacion)
                join licitaciones.estado using(id_estado)
                join general.proveedor using (id_proveedor)
                join compras.fila using(nro_orden)
                where  (oc.estado='g' or oc.estado='d')
                       and estado.nombre<>'Entregada'
                       and (estado.nombre='En curso' or estado.nombre='Presuntamente ganada' or estado.nombre='Preadjudicada' or estado.nombre='Orden de compra')
                       and   proveedor.razon_social not  ilike '%stock%'
                       and  fila.es_agregado=0
                       

               group by nro_orden
               
               --traigo las ordenes de compra tipo stock
               union
               select sum(fila.cantidad) as cantidad, oc.nro_orden
                     from compras.orden_de_compra oc
                     join general.proveedor using (id_proveedor)
                     join compras.fila using(nro_orden)
                     where  (oc.estado='g' or oc.estado='d')
                               and   proveedor.razon_social not  ilike '%stock%'
                               and  fila.es_agregado=0
                               and flag_stock=1

               group by nro_orden
               union
               select sum(fila.cantidad) as cantidad, oc.nro_orden
                     from compras.orden_de_compra oc
                     join general.proveedor using (id_proveedor)
                     join compras.fila using(nro_orden)
                     where  (oc.estado='g' or oc.estado='d')
                               and   proveedor.razon_social not  ilike '%stock%'
                               and  fila.es_agregado=0
                               and  internacional=1

               group by nro_orden               

               
            ) as filas join
	    (
              --obtengo cuandos productos se recibieron de la oc
		      select sum(case when ent_rec is null then 0 else recibido_entregado.cantidad end ) as cantidad_rec,
                     (sum(case when ent_rec is null then 0 else recibido_entregado.cantidad end *fila.precio_unitario * case when oc.id_moneda=2 then oc.valor_dolar else 1 end)) as total_recibido,                     
                     oc.nro_orden
	       from compras.orden_de_compra oc
	       join compras.fila using(nro_orden)
	       left join compras.recibido_entregado using(id_fila)
               where (ent_rec=1 or ent_rec is null) 
	       group by oc.nro_orden order by nro_orden
	     ) as recibidos using (nro_orden)
             
             join
             --parte nueva de adelantos oc comunes
             (
              --obetengo cuanto pague de la oc
	              select sum(op.monto * case when oc.id_moneda=2 then op.valor_dolar else 1 end) as total, oc.nro_orden,op.id_pago
	                     from
	              compras.orden_de_compra oc
	              join general.proveedor using (id_proveedor)
	              join compras.pago_orden on (oc.nro_orden=pago_orden.nro_orden)
	              join compras.ordenes_pagos op using(id_pago) 
	              where ( (not op."númeroch" is null) or (not op."iddébito" is null) or (not id_ingreso_egreso is null))
	              group by oc.nro_orden,op.id_pago
             ) as  pagos using (nro_orden)		

             join 
             (
              --obetengo cuandos pagos multiples esta la oc
 	          select count(pago_orden.nro_orden) as cantidad, pago_orden.id_pago
	          from
		          compras.orden_de_compra oc
		          join compras.pago_orden on (oc.nro_orden=pago_orden.nro_orden)
		          join compras.ordenes_pagos op using(id_pago) 
		          where ( (not op."númeroch" is null) or (not op."iddébito" is null) or (not id_ingreso_egreso is null))
		    group by pago_orden.id_pago
	      ) as cantidad_pagos using(id_pago)
             -- fin de parte nueva de adelantos         
       where recibidos.cantidad_rec<filas.cantidad
group by recibidos.total_recibido,filas.nro_orden
 ) as principal