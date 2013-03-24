select c.id_licitacion,c.monto,c.monto_original,c.id_cobranza,
                              f.tipo_factura,f.id_entidad as id_cliente,
                              f.id_licitacion,f.nro_factura,f.id_factura, f.tipo_factura,f.iva_tasa,
                              entidad.nombre as nombre_cliente,moneda.simbolo,moneda.id_moneda                                             
                              from 
                              licitaciones.atadas join 
                              licitaciones.cobranzas c on c.id_cobranza=atadas.id_secundario
                              join licitaciones.entidad on (c.id_entidad=entidad.id_entidad)
                              join licitaciones.moneda using (id_moneda)
                              join facturacion.facturas f using(id_factura)
                              left join licitaciones.datos_ingresos using(id_cobranza)
                              left join licitaciones.pagos_ingreso  using(id_datos_ingreso)
                              left join moneda using (id_moneda) 
                              where atadas.id_primario=703
