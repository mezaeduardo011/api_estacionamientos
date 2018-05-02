create table precinto(
	id int not null identity(1,1),
	codigo varchar(20),
	telefono varchar(10),
	apikey varchar(32),
	lectura varchar(16),
	escritura varchar(16),
	constraint id_precinto primary key clustered (id)
);

create table eventos(
	id int not null,
	fecha datetime not null,
	salida_1 int,
	salida_2 int,
	contenedor varchar(16),
	modo int,
	tiempo int,
	constraint evento_precinto foreign key (id) references precinto(id)
);

create table estados(
	id int not null,
	fecha datetime not null,
	entrada_1 int,
	entrada_2 int,
	entrada_3 int,
	entrada_4 int,
	salida_1 int,
	salida_2 int,
	constraint estados_precinto foreign key (id) references precinto(id)
);

create table lectura(
	fecha datetime not null,
	apikey varchar(16),
	modo int,
	datos varchar(72)	
);