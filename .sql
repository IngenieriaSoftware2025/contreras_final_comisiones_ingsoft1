CREATE TABLE macs_usuario(
usuario_id SERIAL PRIMARY KEY,
usuario_nom1 VARCHAR (50) NOT NULL,
usuario_nom2 VARCHAR (50) NOT NULL,
usuario_ape1 VARCHAR (50) NOT NULL,
usuario_ape2 VARCHAR (50) NOT NULL,
usuario_tel INT NOT NULL, 
usuario_direc VARCHAR (150) NOT NULL,
usuario_dpi VARCHAR (13) NOT NULL,
usuario_correo VARCHAR (100) NOT NULL,
usuario_contra LVARCHAR (1056) NOT NULL,
usuario_token LVARCHAR (1056) NOT NULL,
usuario_fecha_creacion DATE DEFAULT TODAY,
usuario_fecha_contra DATE DEFAULT TODAY,
usuario_fotografia LVARCHAR (2056),
usuario_situacion SMALLINT DEFAULT 1
);

CREATE TABLE macs_aplicacion(
app_id SERIAL PRIMARY KEY,
app_nombre_largo VARCHAR (250) NOT NULL,
app_nombre_medium VARCHAR (150) NOT NULL,
app_nombre_corto VARCHAR (50) NOT NULL,
app_fecha_creacion DATE DEFAULT TODAY,
app_situacion SMALLINT DEFAULT 1
);

CREATE TABLE macs_permiso (
permiso_id SERIAL PRIMARY KEY,
usuario_id INTEGER NOT NULL,
app_id INTEGER NOT NULL,
permiso_nombre VARCHAR(150) NOT NULL,
permiso_clave VARCHAR(250) NOT NULL,
permiso_desc VARCHAR(250) NOT NULL,
permiso_tipo VARCHAR(50) DEFAULT 'FUNCIONAL',  
permiso_fecha DATE DEFAULT TODAY,
permiso_usuario_asigno INTEGER NOT NULL,   
permiso_motivo VARCHAR(250),                   
permiso_situacion SMALLINT DEFAULT 1,
FOREIGN KEY (usuario_id) REFERENCES macs_usuario(usuario_id),
FOREIGN KEY (app_id) REFERENCES macs_aplicacion(app_id),
FOREIGN KEY (permiso_usuario_asigno) REFERENCES macs_usuario(usuario_id)
);

CREATE TABLE macs_asig_permisos(
asignacion_id SERIAL PRIMARY KEY,
asignacion_usuario_id INT NOT NULL,
asignacion_app_id INT NOT NULL,
asignacion_permiso_id INT NOT NULL,
asignacion_fecha DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND,
asignacion_quitar_fechaPermiso DATETIME YEAR TO SECOND DEFAULT NULL,
asignacion_usuario_asigno INT NOT NULL,
asignacion_motivo VARCHAR (250) NOT NULL,
asignacion_situacion SMALLINT DEFAULT 1,
FOREIGN KEY (asignacion_usuario_id) REFERENCES macs_usuario(usuario_id),
FOREIGN KEY (asignacion_app_id) REFERENCES macs_aplicacion(app_id),
FOREIGN KEY (asignacion_permiso_id) REFERENCES macs_permiso(permiso_id)
);

CREATE TABLE macs_comision(
comision_id SERIAL PRIMARY KEY,
comision_titulo VARCHAR (250) NOT NULL,
comision_descripcion LVARCHAR (1056) NOT NULL,
comision_tipo VARCHAR (50) NOT NULL,
comision_fecha_inicio DATE NOT NULL,
comision_duracion INT NOT NULL,
comision_duracion_tipo VARCHAR (10) NOT NULL,
comision_fecha_fin DATE NOT NULL,
comision_ubicacion VARCHAR (250) NOT NULL,
comision_observaciones LVARCHAR (1056),
comision_estado VARCHAR (50) DEFAULT 'PROGRAMADA',
comision_fecha_creacion DATE DEFAULT TODAY,
comision_usuario_creo INT NOT NULL,
comision_situacion SMALLINT DEFAULT 1,
FOREIGN KEY (comision_usuario_creo) REFERENCES macs_usuario(usuario_id)
);

CREATE TABLE macs_comision_personal(
comision_personal_id SERIAL PRIMARY KEY,
comision_id INT NOT NULL,
usuario_id INT NOT NULL,
comision_personal_fecha_asignacion DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND,
comision_personal_usuario_asigno INT NOT NULL,
comision_personal_observaciones VARCHAR (250),
comision_personal_situacion SMALLINT DEFAULT 1,
FOREIGN KEY (comision_id) REFERENCES macs_comision(comision_id),
FOREIGN KEY (usuario_id) REFERENCES macs_usuario(usuario_id),
FOREIGN KEY (comision_personal_usuario_asigno) REFERENCES macs_usuario(usuario_id)
);

CREATE TABLE macs_historial_act(
historial_id SERIAL PRIMARY KEY,
historial_usuario_id INT NOT NULL,
historial_usuario_nombre VARCHAR(150) NOT NULL,
historial_modulo VARCHAR(50) NOT NULL,
historial_accion VARCHAR(50) NOT NULL,
historial_descripcion VARCHAR(250) NOT NULL,
historial_ip VARCHAR(45),
historial_ruta VARCHAR(250),
historial_fecha_creacion DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND,
historial_situacion SMALLINT DEFAULT 1,
FOREIGN KEY (historial_usuario_id) REFERENCES macs_usuario(usuario_id)
);



INSERT INTO macs_usuario (usuario_id,usuario_nom1,usuario_nom2,usuario_ape1,usuario_ape2,usuario_tel,usuario_direc,usuario_dpi,usuario_correo,usuario_contra,usuario_token,usuario_fecha_creacion,usuario_fecha_contra,usuario_fotografia,usuario_situacion) VALUES(8,'Martin','Alexander','Contreras','Samayoa',49219554,'Zona 5 Guatemala','3005134880101','martin@gmail.com','$2y$10$b/gVGr0IwuRtBmPgHBQKJOEnVcVaIT3NFdAvPc07yBT1ucmFCE2Ri','685365bd00b9d',null,null,'storage/fotosUsuarios/3005134880101.jpg',1)
GO