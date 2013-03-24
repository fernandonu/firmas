Create table "public"."clientes"
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
constraint "pk_clientes" primary key ("id_cliente")
);

Create table "distrito"
(
	"id_distrito" Serial NOT NULL,
	"nombre" Text,
constraint "pk_distrito" primary key ("id_distrito")
);



Alter table "clientes" add  foreign key ("id_distrito") references "distrito" ("id_distrito") on update restrict on delete restrict;
