CREATE TABLE cat_tipos_licitacion(id INT PRIMARY KEY, nombre VARCHAR(50));
CREATE TABLE cat_estados(id INT PRIMARY KEY, nombre_estado VARCHAR(50));
CREATE TABLE cat_motivos_rechazo(id INT PRIMARY KEY, motivo VARCHAR(100), etapa_aplicable VARCHAR(50));
CREATE TABLE empresas_principales(id INT PRIMARY KEY, razon_social VARCHAR(100), rut VARCHAR(20) UNIQUE);
CREATE TABLE empresas_contratistas(id INT PRIMARY KEY, razon_social VARCHAR(100), rut VARCHAR(20) UNIQUE);
CREATE TABLE licitaciones(id INT PRIMARY KEY, titulo VARCHAR(255), principal_id INT, tipo_id INT, estado_id INT, es_interesante BOOLEAN, FOREIGN KEY(principal_id) REFERENCES empresas_principales(id), FOREIGN KEY(tipo_id) REFERENCES cat_tipos_licitacion(id), FOREIGN KEY(estado_id) REFERENCES cat_estados(id));
CREATE TABLE ofertas(id INT PRIMARY KEY, licitacion_id INT, contratista_id INT, monto DECIMAL(15,2), estado ENUM('pendiente','precalificada','adjudicada','no_adjudicada'), FOREIGN KEY(licitacion_id) REFERENCES licitaciones(id), FOREIGN KEY(contratista_id) REFERENCES empresas_contratistas(id));
CREATE TABLE revisiones_calidad(id INT PRIMARY KEY, licitacion_id INT, contiene_errores BOOLEAN, observaciones TEXT, FOREIGN KEY(licitacion_id) REFERENCES licitaciones(id));
CREATE TABLE lecciones_aprendidas(id INT PRIMARY KEY, licitacion_id INT, motivo_id INT, analisis_detalle TEXT, FOREIGN KEY(licitacion_id) REFERENCES licitaciones(id), FOREIGN KEY(motivo_id) REFERENCES cat_motivos_rechazo(id));
