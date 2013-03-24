/*
Created		14/05/2005
Modified		17/05/2005
Project		
Model		
Company		
Author		
Version		
Database		PostgreSQL 7.3 
*/













Create table "clientes"
(
	"id_cliente" Serial NOT NULL,
	"apellido_nombre" Text,
	"direccion" Text,
	"localidad" Text,
	"mail" Text,
	"telefono" Text,
	"login" Text,
	"password" Text,
	"id_distrito" integer NOT NULL,
	"id_firmas" integer NOT NULL,
	"activo" Smallint Default 1,
constraint "pk_clientes" primary key ("id_cliente")
);

Create table "distrito"
(
	"id_distrito" Serial NOT NULL,
	"nombre" Text,
constraint "pk_distrito" primary key ("id_distrito")
);

Create table "mensajes"
(
	"id_mensaje" Serial NOT NULL,
	"id_emisor" integer NOT NULL,
	"id_receptor" integer NOT NULL,
	"asunto" Text,
	"contenido" Text,
	"firmado" Smallint Default 0,
	"borrado" Smallint Default 0,
        "no_leido" Smallint Default 1  
constraint "pk_mensajes" primary key ("id_mensaje")
);

Create table "firmas"
(
	"id_firmas" Serial NOT NULL,
	"clave_publica" Text,
	"clave_privada" Text,
constraint "pk_firmas" primary key ("id_firmas")
);



Alter table "mensajes" add  foreign key ("id_emisor") references "clientes" ("id_cliente") on update restrict on delete restrict;
Alter table "mensajes" add  foreign key ("id_receptor") references "clientes" ("id_cliente") on update restrict on delete restrict;
Alter table "clientes" add  foreign key ("id_distrito") references "distrito" ("id_distrito") on update restrict on delete restrict;
Alter table "clientes" add  foreign key ("id_firmas") references "firmas" ("id_firmas") on update restrict on delete restrict;



/* Creating roles */



