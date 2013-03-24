/*listado de las filas recibidas pero no confirmadas (montos en dolares)*/
select fila.nro_orden,log_rec_ent.cant,producto_especifico.descripcion,producto_especifico.precio_stock,
       (log_rec_ent.cant*producto_especifico.precio_stock) as monto_total --<===Como precio deberia mostrar el promedio 
									      --como cuando actualiza el precio del stock al confirmar reserva
        from compras.fila
		join compras.recibido_entregado using(id_fila)
		join compras.log_rec_ent using(id_recibido)
		join general.producto_especifico on log_rec_ent.id_prod_esp=producto_especifico.id_prod_esp
		where log_rec_ent.recepcion_confirmada=0
		order by nro_orden

/*Monto total de productos recibidos pero no confirmados (monto en dolares)*/
select sum(log_rec_ent.cant*producto_especifico.precio_stock) as monto_total
        from compras.fila
		join compras.recibido_entregado using(id_fila)
		join compras.log_rec_ent using(id_recibido)
		join general.producto_especifico on log_rec_ent.id_prod_esp=producto_especifico.id_prod_esp
		where log_rec_ent.recepcion_confirmada=0
