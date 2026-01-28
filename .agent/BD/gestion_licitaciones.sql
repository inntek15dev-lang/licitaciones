-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.4.3 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando estructura para tabla gestion_licitaciones.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.cache: ~4 rows (aproximadamente)
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1767801727),
	('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1767801727;', 1767801727),
	('laravel-cache-77de68daecd823babbb58edb1c8e14d7106e83bb', 'i:2;', 1767803607),
	('laravel-cache-77de68daecd823babbb58edb1c8e14d7106e83bb:timer', 'i:1767803607;', 1767803607);

-- Volcando estructura para tabla gestion_licitaciones.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.cache_locks: ~0 rows (aproximadamente)

-- Volcando estructura para tabla gestion_licitaciones.catalogo_requisitos_precalificacion
CREATE TABLE IF NOT EXISTS `catalogo_requisitos_precalificacion` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre_requisito` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `criterio_cumplimiento` text COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `catalogo_requisitos_precalificacion_nombre_requisito_unique` (`nombre_requisito`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.catalogo_requisitos_precalificacion: ~0 rows (aproximadamente)
INSERT INTO `catalogo_requisitos_precalificacion` (`id`, `nombre_requisito`, `criterio_cumplimiento`, `activo`, `created_at`, `updated_at`) VALUES
	(1, 'certificado de deuda tgr', 'que no tenga deudas', 1, '2026-01-07 19:08:28', '2026-01-07 19:08:28');

-- Volcando estructura para tabla gestion_licitaciones.categorias_licitaciones
CREATE TABLE IF NOT EXISTS `categorias_licitaciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre_categoria` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categorias_licitaciones_nombre_categoria_unique` (`nombre_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.categorias_licitaciones: ~6 rows (aproximadamente)
INSERT INTO `categorias_licitaciones` (`id`, `nombre_categoria`, `descripcion`, `created_at`, `updated_at`) VALUES
	(1, 'Construcción', 'Obras de construcción civil, edificación e infraestructura', '2025-12-23 18:32:49', '2025-12-23 18:32:49'),
	(2, 'Servicios', 'Servicios profesionales y técnicos', '2025-12-23 18:32:49', '2025-12-23 18:32:49'),
	(3, 'Suministros', 'Provisión de bienes y materiales', '2025-12-23 18:32:49', '2025-12-23 18:32:49'),
	(4, 'Tecnología', 'Equipos y sistemas tecnológicos', '2025-12-23 18:32:49', '2025-12-23 18:32:49'),
	(5, 'Consultoría', 'Servicios de asesoría y consultoría especializada', '2025-12-23 18:32:49', '2025-12-23 18:32:49'),
	(6, 'Mantención', 'Servicios de mantención y reparación', '2025-12-23 18:32:49', '2025-12-23 18:32:49');

-- Volcando estructura para tabla gestion_licitaciones.consultas_respuestas_licitacion
CREATE TABLE IF NOT EXISTS `consultas_respuestas_licitacion` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `licitacion_id` bigint unsigned NOT NULL,
  `contratista_id` bigint unsigned DEFAULT NULL,
  `usuario_pregunta_id` bigint unsigned DEFAULT NULL,
  `texto_pregunta` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_pregunta` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_respuesta_id` bigint unsigned DEFAULT NULL,
  `texto_respuesta` text COLLATE utf8mb4_unicode_ci,
  `fecha_respuesta` timestamp NULL DEFAULT NULL,
  `es_publica` tinyint(1) NOT NULL DEFAULT '0',
  `documento_adjunto_respuesta_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `consultas_respuestas_licitacion_licitacion_id_foreign` (`licitacion_id`),
  KEY `consultas_respuestas_licitacion_contratista_id_index` (`contratista_id`),
  KEY `consultas_respuestas_licitacion_usuario_pregunta_id_index` (`usuario_pregunta_id`),
  KEY `consultas_respuestas_licitacion_usuario_respuesta_id_index` (`usuario_respuesta_id`),
  KEY `consultas_respuestas_licitacion_es_publica_index` (`es_publica`),
  CONSTRAINT `consultas_respuestas_licitacion_contratista_id_foreign` FOREIGN KEY (`contratista_id`) REFERENCES `empresas_contratistas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `consultas_respuestas_licitacion_licitacion_id_foreign` FOREIGN KEY (`licitacion_id`) REFERENCES `licitaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `consultas_respuestas_licitacion_usuario_pregunta_id_foreign` FOREIGN KEY (`usuario_pregunta_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `consultas_respuestas_licitacion_usuario_respuesta_id_foreign` FOREIGN KEY (`usuario_respuesta_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.consultas_respuestas_licitacion: ~0 rows (aproximadamente)

-- Volcando estructura para tabla gestion_licitaciones.documentos_licitacion
CREATE TABLE IF NOT EXISTS `documentos_licitacion` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `licitacion_id` bigint unsigned NOT NULL,
  `nombre_documento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion_documento` text COLLATE utf8mb4_unicode_ci,
  `ruta_archivo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` enum('bases','anexo_tecnico','anexo_economico','plano','aclaracion','otro') COLLATE utf8mb4_unicode_ci NOT NULL,
  `es_precalificacion` tinyint(1) NOT NULL DEFAULT '0',
  `subido_por_usuario_id` bigint unsigned DEFAULT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `documentos_licitacion_licitacion_id_foreign` (`licitacion_id`),
  KEY `documentos_licitacion_subido_por_usuario_id_foreign` (`subido_por_usuario_id`),
  CONSTRAINT `documentos_licitacion_licitacion_id_foreign` FOREIGN KEY (`licitacion_id`) REFERENCES `licitaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documentos_licitacion_subido_por_usuario_id_foreign` FOREIGN KEY (`subido_por_usuario_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.documentos_licitacion: ~9 rows (aproximadamente)
INSERT INTO `documentos_licitacion` (`id`, `licitacion_id`, `nombre_documento`, `descripcion_documento`, `ruta_archivo`, `tipo_documento`, `es_precalificacion`, `subido_por_usuario_id`, `fecha_subida`) VALUES
	(1, 1, 'fwpl30 2c.pdf', NULL, 'licitaciones/1/documentos/LqjbqCe7r1zU9NYKpGfhBRapXJHd50pUQ0QBQPrZ.pdf', 'bases', 0, 2, '2025-12-23 14:35:22'),
	(2, 2, 'fwpl30 2c.pdf', NULL, 'licitaciones/2/documentos/SOhbljhTYBhnTp3oS7pT0dbm4J8pbZnpTj5EyjFu.pdf', 'bases', 0, 2, '2025-12-23 15:53:27'),
	(3, 1, 'CERTIFICADO DE DEUDA', NULL, 'licitaciones/1/documentos/80j8temyYok3iwyPESpffgKkE04HQnf22PzgIGGy.pdf', 'bases', 0, 2, '2025-12-23 20:42:27'),
	(4, 1, 'FORMAULARIO DE EXPERIENCIA', NULL, 'licitaciones/1/documentos/fguo9STKPKV9xZVhOGkYK8V95QLRK775YQPVce0b.pdf', 'bases', 0, 2, '2025-12-23 20:42:27'),
	(5, 3, 'plano del terreno', NULL, 'licitaciones/3/documentos/BAgxNdYMQM86wgJqpjMEzpVzyfkAbaljs0m4cVBE.pdf', 'bases', 0, 1, '2025-12-24 13:40:07'),
	(6, 4, 'formato ficha de cargos ', NULL, 'licitaciones/4/documentos/yfL1JcZ9JzhMCtjhTFa05jA1XO6YEgPebVCAH1zU.pdf', 'bases', 0, 1, '2025-12-24 13:45:55'),
	(7, 4, 'ietm cerificado de experiencia', NULL, 'licitaciones/4/precalificacion/ZxqCS44h73BeItoUrTb1FJB7NfybY5K1YDTWr0gW.pdf', 'otro', 0, 1, '2025-12-24 13:45:55'),
	(8, 6, 'plano de las intalaciones', NULL, 'licitaciones/6/documentos/4Cemmp8tiqw80mqkwFVU5hee7yuof2yEJGgziDpK.pdf', 'bases', 0, 1, '2025-12-24 15:43:47'),
	(9, 6, 'certifica de antiguedads', NULL, 'licitaciones/6/precalificacion/1UzL8fNOtg7kDWwTZBYvLqnbAKYPkxtq67h7eUee.pdf', 'otro', 1, 1, '2025-12-24 15:43:47'),
	(10, 6, '[PRECAL-1] no tener deudad trubutarias, se acreidta con xcetifacofof  deuda  u otr', NULL, 'precalificaciones/1/documentos/zYhHmhwG0Vll0cvxXXlyQsdnQH9JiVi5kKLPN8kP.pdf', 'otro', 1, 3, '2025-12-24 15:46:54'),
	(11, 6, '[PRECAL-1] no tener acicdentes fatales , se acreita con certifofof  mutaual', NULL, 'precalificaciones/1/documentos/aIRTLALnXvDjVvBkDL5H8IwtXCcSInHa05OfYa3H.jpg', 'otro', 1, 3, '2025-12-24 15:46:54'),
	(12, 7, 'plano de las instalaciones ', NULL, 'licitaciones/7/documentos/JWs0YQN1ozOrZ98LpwbGFFOEFAFaX4xC8Uh76YMr.pdf', 'bases', 0, 1, '2026-01-07 16:02:24');

-- Volcando estructura para tabla gestion_licitaciones.documentos_oferta
CREATE TABLE IF NOT EXISTS `documentos_oferta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `oferta_id` bigint unsigned NOT NULL,
  `nombre_documento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion_documento` text COLLATE utf8mb4_unicode_ci,
  `ruta_archivo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` enum('propuesta_tecnica','propuesta_economica','garantia_seriedad','certificado','otro') COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `documentos_oferta_oferta_id_foreign` (`oferta_id`),
  CONSTRAINT `documentos_oferta_oferta_id_foreign` FOREIGN KEY (`oferta_id`) REFERENCES `ofertas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.documentos_oferta: ~0 rows (aproximadamente)

-- Volcando estructura para tabla gestion_licitaciones.empresas_contratistas
CREATE TABLE IF NOT EXISTS `empresas_contratistas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rut` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_contacto_principal` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `persona_contacto_principal` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rubros_especialidad` text COLLATE utf8mb4_unicode_ci,
  `documentacion_validada` tinyint(1) NOT NULL DEFAULT '0',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `empresas_contratistas_rut_unique` (`rut`),
  KEY `empresas_contratistas_razon_social_index` (`razon_social`),
  KEY `empresas_contratistas_activo_index` (`activo`),
  KEY `empresas_contratistas_documentacion_validada_index` (`documentacion_validada`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.empresas_contratistas: ~2 rows (aproximadamente)
INSERT INTO `empresas_contratistas` (`id`, `razon_social`, `rut`, `direccion`, `telefono`, `email_contacto_principal`, `persona_contacto_principal`, `rubros_especialidad`, `documentacion_validada`, `activo`, `created_at`, `updated_at`) VALUES
	(1, 'Proveedores Industriales Ltda.', '77.111.222-3', 'Av. Industrial 890, Concepción', '+56 41 234 5678', 'ventas@proveedores-ind.cl', 'Carlos Muñoz', 'Equipos industriales, maquinaria pesada', 0, 1, '2025-12-23 18:32:49', '2025-12-23 18:32:49'),
	(2, 'Servicios Técnicos del Norte SpA', '77.333.444-5', 'Calle Técnica 123, Calama', '+56 55 876 5432', 'contacto@serviciosnorte.cl', 'Luisa Fernández', 'Mantención industrial, servicios eléctricos', 0, 1, '2025-12-23 18:32:49', '2025-12-23 18:32:49');

-- Volcando estructura para tabla gestion_licitaciones.empresas_principales
CREATE TABLE IF NOT EXISTS `empresas_principales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rut` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_contacto_principal` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `persona_contacto_principal` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `empresas_principales_rut_unique` (`rut`),
  KEY `empresas_principales_razon_social_index` (`razon_social`),
  KEY `empresas_principales_activo_index` (`activo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.empresas_principales: ~2 rows (aproximadamente)
INSERT INTO `empresas_principales` (`id`, `razon_social`, `rut`, `direccion`, `telefono`, `email_contacto_principal`, `persona_contacto_principal`, `logo_url`, `activo`, `created_at`, `updated_at`) VALUES
	(1, 'Constructora ABC S.A.', '76.123.456-7', 'Av. Providencia 1234, Santiago', '+56 2 2345 6789', 'contacto@constructora-abc.cl', 'Pedro López', NULL, 1, '2025-12-23 18:32:49', '2025-12-23 18:32:49'),
	(2, 'Minera Grande Chile S.A.', '76.789.012-3', 'Calle Los Mineros 567, Antofagasta', '+56 55 234 5678', 'licitaciones@mineragrande.cl', 'Ana Torres', NULL, 1, '2025-12-23 18:32:49', '2025-12-23 18:32:49');

-- Volcando estructura para tabla gestion_licitaciones.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.failed_jobs: ~0 rows (aproximadamente)

-- Volcando estructura para tabla gestion_licitaciones.formularios_precalificacion
CREATE TABLE IF NOT EXISTS `formularios_precalificacion` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `empresa_principal_id` bigint unsigned NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `formularios_precalificacion_empresa_principal_id_nombre_unique` (`empresa_principal_id`,`nombre`),
  CONSTRAINT `formularios_precalificacion_empresa_principal_id_foreign` FOREIGN KEY (`empresa_principal_id`) REFERENCES `empresas_principales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.formularios_precalificacion: ~0 rows (aproximadamente)
INSERT INTO `formularios_precalificacion` (`id`, `empresa_principal_id`, `nombre`, `descripcion`, `activo`, `created_at`, `updated_at`) VALUES
	(1, 1, 'precalificacion  tecnologia', 'se pre ddjdjhdfhfhffgfff', 1, '2026-01-07 19:51:57', '2026-01-07 19:51:57');

-- Volcando estructura para tabla gestion_licitaciones.formulario_requisitos_precalificacion
CREATE TABLE IF NOT EXISTS `formulario_requisitos_precalificacion` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `formulario_precalificacion_id` bigint unsigned NOT NULL,
  `catalogo_requisito_id` bigint unsigned NOT NULL,
  `obligatorio` tinyint(1) NOT NULL DEFAULT '1',
  `orden` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `form_req_unique` (`formulario_precalificacion_id`,`catalogo_requisito_id`),
  KEY `fk_form_req_catalogo` (`catalogo_requisito_id`),
  CONSTRAINT `fk_form_req_catalogo` FOREIGN KEY (`catalogo_requisito_id`) REFERENCES `catalogo_requisitos_precalificacion` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_form_req_formulario` FOREIGN KEY (`formulario_precalificacion_id`) REFERENCES `formularios_precalificacion` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.formulario_requisitos_precalificacion: ~0 rows (aproximadamente)
INSERT INTO `formulario_requisitos_precalificacion` (`id`, `formulario_precalificacion_id`, `catalogo_requisito_id`, `obligatorio`, `orden`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 1, 0, '2026-01-07 19:51:57', '2026-01-07 19:51:57');

-- Volcando estructura para tabla gestion_licitaciones.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.jobs: ~0 rows (aproximadamente)

-- Volcando estructura para tabla gestion_licitaciones.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.job_batches: ~0 rows (aproximadamente)

-- Volcando estructura para tabla gestion_licitaciones.licitaciones
CREATE TABLE IF NOT EXISTS `licitaciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `principal_id` bigint unsigned NOT NULL,
  `usuario_creador_id` bigint unsigned NOT NULL,
  `codigo_licitacion` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion_corta` text COLLATE utf8mb4_unicode_ci,
  `descripcion_larga` longtext COLLATE utf8mb4_unicode_ci,
  `tipo_licitacion` enum('publica','privada_invitacion') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'publica',
  `estado` enum('borrador','lista_para_publicar','observada_por_ryce','publicada','cerrada_consultas','cerrada_ofertas','en_evaluacion','adjudicada','desierta','cancelada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'borrador',
  `fecha_publicacion` datetime DEFAULT NULL,
  `fecha_inicio_consultas` datetime DEFAULT NULL,
  `fecha_cierre_consultas` datetime DEFAULT NULL,
  `fecha_inicio_recepcion_ofertas` datetime DEFAULT NULL,
  `fecha_cierre_recepcion_ofertas` datetime DEFAULT NULL,
  `fecha_adjudicacion_estimada` date DEFAULT NULL,
  `fecha_adjudicacion_real` datetime DEFAULT NULL,
  `presupuesto_referencial` decimal(15,2) DEFAULT NULL,
  `moneda_presupuesto` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lugar_ejecucion_trabajos` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requiere_visita_terreno` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_visita_terreno` datetime DEFAULT NULL,
  `contacto_visita_terreno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lugar_visita_terreno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_contacto_visita` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono_contacto_visita` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visita_terreno_obligatoria` tinyint(1) NOT NULL DEFAULT '0',
  `requiere_precalificacion` tinyint(1) NOT NULL DEFAULT '0',
  `responsable_precalificacion` enum('ryce','principal','ambos') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ryce',
  `fecha_inicio_precalificacion` datetime DEFAULT NULL,
  `fecha_fin_precalificacion` datetime DEFAULT NULL,
  `requiere_entrevista` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_entrevista` datetime DEFAULT NULL,
  `lugar_entrevista` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notas_precalificacion` text COLLATE utf8mb4_unicode_ci,
  `comentarios_revision_ryce` text COLLATE utf8mb4_unicode_ci,
  `usuario_revisor_ryce_id` bigint unsigned DEFAULT NULL,
  `motivo_cancelacion` text COLLATE utf8mb4_unicode_ci,
  `motivo_desierta` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `formulario_precalificacion_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `licitaciones_codigo_licitacion_unique` (`codigo_licitacion`),
  KEY `licitaciones_principal_id_foreign` (`principal_id`),
  KEY `licitaciones_usuario_creador_id_foreign` (`usuario_creador_id`),
  KEY `licitaciones_usuario_revisor_ryce_id_foreign` (`usuario_revisor_ryce_id`),
  KEY `licitaciones_estado_index` (`estado`),
  KEY `licitaciones_titulo_index` (`titulo`),
  KEY `licitaciones_fecha_cierre_recepcion_ofertas_index` (`fecha_cierre_recepcion_ofertas`),
  CONSTRAINT `licitaciones_principal_id_foreign` FOREIGN KEY (`principal_id`) REFERENCES `empresas_principales` (`id`) ON DELETE CASCADE,
  CONSTRAINT `licitaciones_usuario_creador_id_foreign` FOREIGN KEY (`usuario_creador_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `licitaciones_usuario_revisor_ryce_id_foreign` FOREIGN KEY (`usuario_revisor_ryce_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.licitaciones: ~7 rows (aproximadamente)
INSERT INTO `licitaciones` (`id`, `principal_id`, `usuario_creador_id`, `codigo_licitacion`, `titulo`, `descripcion_corta`, `descripcion_larga`, `tipo_licitacion`, `estado`, `fecha_publicacion`, `fecha_inicio_consultas`, `fecha_cierre_consultas`, `fecha_inicio_recepcion_ofertas`, `fecha_cierre_recepcion_ofertas`, `fecha_adjudicacion_estimada`, `fecha_adjudicacion_real`, `presupuesto_referencial`, `moneda_presupuesto`, `lugar_ejecucion_trabajos`, `requiere_visita_terreno`, `fecha_visita_terreno`, `contacto_visita_terreno`, `lugar_visita_terreno`, `email_contacto_visita`, `telefono_contacto_visita`, `visita_terreno_obligatoria`, `requiere_precalificacion`, `responsable_precalificacion`, `fecha_inicio_precalificacion`, `fecha_fin_precalificacion`, `requiere_entrevista`, `fecha_entrevista`, `lugar_entrevista`, `notas_precalificacion`, `comentarios_revision_ryce`, `usuario_revisor_ryce_id`, `motivo_cancelacion`, `motivo_desierta`, `created_at`, `updated_at`, `formulario_precalificacion_id`) VALUES
	(1, 1, 2, 'LIC-2025-0001', 'COMPAR COMPUTADORES', 'WWWWWWWWWWWWWWWWWWWWWWWWWWWWW.   si ya esta liosir', NULL, 'publica', 'lista_para_publicar', NULL, '2025-12-24 00:00:00', '2025-12-30 00:00:00', NULL, '2026-01-06 00:00:00', '2026-01-22', NULL, 2000000.00, 'CLP', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 'ryce', NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2025-12-23 18:35:22', '2026-01-07 20:03:55', NULL),
	(2, 1, 2, 'LIC-2025-0002', 'SSSSSSSSSSSSS', 'SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', NULL, 'publica', 'publicada', '2025-12-23 19:35:35', '2025-12-24 00:00:00', '2025-12-30 00:00:00', NULL, '2026-01-06 00:00:00', '2026-01-22', NULL, 222222.00, 'CLP', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 'ryce', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 19:53:27', '2025-12-23 23:35:35', NULL),
	(3, 1, 1, 'LIC-LJI21KZW', 'qqqqqqqqqqqqqqqqqqq', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', NULL, 'publica', 'publicada', '2025-12-24 13:40:07', '2025-12-25 13:35:00', '2025-12-31 13:35:00', NULL, '2026-01-07 13:35:00', '2026-01-23', NULL, 2222222.00, 'CLP', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 'ryce', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 17:40:07', '2025-12-24 17:40:07', NULL),
	(4, 1, 1, 'LIC-YJIHVMRO', 'eeeeeeeeeeeeeeeeeeeee', 'eeeeeeeeeeeeeeeeeeeeeeeeeeeeeee', NULL, 'publica', 'publicada', '2025-12-24 13:45:55', '2025-12-25 13:41:00', '2025-12-31 13:41:00', NULL, '2026-01-07 13:41:00', '2026-01-23', NULL, 33333.00, 'CLP', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 'ryce', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 17:45:55', '2025-12-24 17:45:55', NULL),
	(5, 1, 1, 'LIC-RCHNTJGS', 'con precalificacion', 'precalificar  primero  d¿se de debe enviar  todos  lsososss', NULL, 'publica', 'publicada', '2025-12-24 14:52:43', '2025-12-25 14:48:00', '2025-12-31 14:48:00', NULL, '2026-01-07 14:48:00', '2026-01-23', NULL, 20000.00, 'CLP', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 1, 'ambos', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 18:52:43', '2025-12-24 18:52:43', NULL),
	(6, 1, 1, 'LIC-LYMIRTOJ', 'compra de camiones', 'deben ser marca mercedes  benz   mino  año 2008', NULL, 'publica', 'publicada', '2025-12-24 15:43:47', '2025-12-25 15:33:00', '2025-12-31 15:33:00', NULL, '2026-01-07 15:33:00', '2026-01-23', NULL, 5000.00, 'CLP', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 1, 'ryce', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 19:43:47', '2025-12-24 19:43:47', NULL),
	(7, 1, 1, 'LIC-K0OYN2BN', 'ddddddddddddddddddddddddd', 'dddddddddddddddddddddddddddddd', NULL, 'publica', 'publicada', '2026-01-07 16:02:23', '2026-01-08 15:52:00', '2026-01-14 15:52:00', NULL, '2026-01-21 15:52:00', '2026-02-06', NULL, 33333.00, 'CLP', NULL, 1, '2026-01-22 11:53:00', 'sssssssssss', NULL, NULL, NULL, 0, 1, 'ryce', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-07 20:02:23', '2026-01-07 20:02:23', 1);

-- Volcando estructura para tabla gestion_licitaciones.licitacion_categorias
CREATE TABLE IF NOT EXISTS `licitacion_categorias` (
  `licitacion_id` bigint unsigned NOT NULL,
  `categoria_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`licitacion_id`,`categoria_id`),
  KEY `licitacion_categorias_categoria_id_foreign` (`categoria_id`),
  CONSTRAINT `licitacion_categorias_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_licitaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `licitacion_categorias_licitacion_id_foreign` FOREIGN KEY (`licitacion_id`) REFERENCES `licitaciones` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.licitacion_categorias: ~6 rows (aproximadamente)
INSERT INTO `licitacion_categorias` (`licitacion_id`, `categoria_id`) VALUES
	(6, 1),
	(1, 4),
	(2, 4),
	(7, 4),
	(3, 6),
	(4, 6),
	(5, 6);

-- Volcando estructura para tabla gestion_licitaciones.licitacion_contratistas_invitados
CREATE TABLE IF NOT EXISTS `licitacion_contratistas_invitados` (
  `licitacion_id` bigint unsigned NOT NULL,
  `contratista_id` bigint unsigned NOT NULL,
  `fecha_invitacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado_invitacion` enum('enviada','vista','aceptada','rechazada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'enviada',
  PRIMARY KEY (`licitacion_id`,`contratista_id`),
  KEY `licitacion_contratistas_invitados_contratista_id_foreign` (`contratista_id`),
  CONSTRAINT `licitacion_contratistas_invitados_contratista_id_foreign` FOREIGN KEY (`contratista_id`) REFERENCES `empresas_contratistas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `licitacion_contratistas_invitados_licitacion_id_foreign` FOREIGN KEY (`licitacion_id`) REFERENCES `licitaciones` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.licitacion_contratistas_invitados: ~0 rows (aproximadamente)

-- Volcando estructura para tabla gestion_licitaciones.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.migrations: ~19 rows (aproximadamente)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_12_22_213346_create_permission_tables', 1),
	(5, '2025_12_22_220000_add_licitaciones_fields_to_users_table', 1),
	(6, '2025_12_22_221000_create_empresas_tables', 1),
	(7, '2025_12_22_222000_create_licitaciones_tables', 1),
	(8, '2025_12_22_223000_create_documentos_licitacion_tables', 1),
	(9, '2025_12_22_224000_create_ofertas_tables', 1),
	(10, '2025_12_22_225000_create_comunicacion_tables', 1),
	(11, '2025_12_23_205228_create_observaciones_licitacion_table', 2),
	(12, '2025_12_23_205756_add_visita_terreno_fields_to_licitaciones', 3),
	(13, '2025_12_23_210735_add_precalificacion_fields_to_licitaciones', 4),
	(14, '2025_12_24_132532_add_es_precalificacion_to_requisitos_documentos_licitacion_table', 5),
	(16, '2025_12_24_132536_add_es_precalificacion_to_documentos_licitacion_table', 6),
	(17, '2025_12_24_135430_create_precalificaciones_contratistas_table', 6),
	(18, '2025_12_24_135434_add_responsable_precalificacion_to_licitaciones_table', 6),
	(20, '2025_12_24_163000_create_catalogo_requisitos_precalificacion_table', 7),
	(21, '2026_01_07_100000_create_formularios_precalificacion_table', 8);

-- Volcando estructura para tabla gestion_licitaciones.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.model_has_permissions: ~0 rows (aproximadamente)

-- Volcando estructura para tabla gestion_licitaciones.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.model_has_roles: ~3 rows (aproximadamente)
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(2, 'App\\Models\\User', 2),
	(3, 'App\\Models\\User', 3);

-- Volcando estructura para tabla gestion_licitaciones.notificaciones
CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usuario_destino_id` bigint unsigned NOT NULL,
  `tipo_notificacion` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mensaje` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_destino` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `leida` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `notificaciones_usuario_destino_id_foreign` (`usuario_destino_id`),
  KEY `notificaciones_leida_index` (`leida`),
  CONSTRAINT `notificaciones_usuario_destino_id_foreign` FOREIGN KEY (`usuario_destino_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.notificaciones: ~0 rows (aproximadamente)
INSERT INTO `notificaciones` (`id`, `usuario_destino_id`, `tipo_notificacion`, `mensaje`, `url_destino`, `leida`, `created_at`) VALUES
	(1, 2, 'licitacion_observada', 'Tu licitación tiene observaciones: La licitación "COMPAR COMPUTADORES" requiere correcciones: ESTO FALTA qQQ', 'http://localhost:8000/principal/licitaciones/1', 0, '2025-12-23 20:51:16');

-- Volcando estructura para tabla gestion_licitaciones.observaciones_licitacion
CREATE TABLE IF NOT EXISTS `observaciones_licitacion` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `licitacion_id` bigint unsigned NOT NULL,
  `usuario_revisor_id` bigint unsigned NOT NULL,
  `observacion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_observacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `resuelta` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_resolucion` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `observaciones_licitacion_licitacion_id_index` (`licitacion_id`),
  KEY `observaciones_licitacion_usuario_revisor_id_index` (`usuario_revisor_id`),
  CONSTRAINT `observaciones_licitacion_licitacion_id_foreign` FOREIGN KEY (`licitacion_id`) REFERENCES `licitaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `observaciones_licitacion_usuario_revisor_id_foreign` FOREIGN KEY (`usuario_revisor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.observaciones_licitacion: ~0 rows (aproximadamente)

-- Volcando estructura para tabla gestion_licitaciones.ofertas
CREATE TABLE IF NOT EXISTS `ofertas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `licitacion_id` bigint unsigned NOT NULL,
  `contratista_id` bigint unsigned NOT NULL,
  `usuario_presenta_id` bigint unsigned NOT NULL,
  `fecha_presentacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `monto_oferta_economica` decimal(15,2) NOT NULL,
  `moneda_oferta` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `validez_oferta_dias` int unsigned DEFAULT NULL,
  `comentarios_oferta` text COLLATE utf8mb4_unicode_ci,
  `estado_oferta` enum('pendiente_precalificacion_ryce','precalificada_por_ryce','no_precalificada_ryce','presentada','en_evaluacion_principal','adjudicada','no_adjudicada','retirada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente_precalificacion_ryce',
  `comentarios_precalificacion_ryce` text COLLATE utf8mb4_unicode_ci,
  `usuario_precalificador_ryce_id` bigint unsigned DEFAULT NULL,
  `fecha_precalificacion_ryce` timestamp NULL DEFAULT NULL,
  `fecha_actualizacion_estado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ofertas_licitacion_id_contratista_id_unique` (`licitacion_id`,`contratista_id`),
  KEY `ofertas_contratista_id_foreign` (`contratista_id`),
  KEY `ofertas_usuario_presenta_id_foreign` (`usuario_presenta_id`),
  KEY `ofertas_usuario_precalificador_ryce_id_foreign` (`usuario_precalificador_ryce_id`),
  KEY `ofertas_estado_oferta_index` (`estado_oferta`),
  CONSTRAINT `ofertas_contratista_id_foreign` FOREIGN KEY (`contratista_id`) REFERENCES `empresas_contratistas` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `ofertas_licitacion_id_foreign` FOREIGN KEY (`licitacion_id`) REFERENCES `licitaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ofertas_usuario_precalificador_ryce_id_foreign` FOREIGN KEY (`usuario_precalificador_ryce_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ofertas_usuario_presenta_id_foreign` FOREIGN KEY (`usuario_presenta_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.ofertas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla gestion_licitaciones.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.password_reset_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla gestion_licitaciones.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.permissions: ~0 rows (aproximadamente)

-- Volcando estructura para tabla gestion_licitaciones.precalificaciones_contratistas
CREATE TABLE IF NOT EXISTS `precalificaciones_contratistas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `licitacion_id` bigint unsigned NOT NULL,
  `contratista_id` bigint unsigned NOT NULL,
  `estado` enum('pendiente','aprobada','rechazada','rectificando') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `fecha_solicitud` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_resolucion` timestamp NULL DEFAULT NULL,
  `revisado_por_usuario_id` bigint unsigned DEFAULT NULL,
  `tipo_revisor` enum('ryce','principal') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `motivo_rechazo` text COLLATE utf8mb4_unicode_ci,
  `comentarios_contratista` text COLLATE utf8mb4_unicode_ci,
  `comentarios_rectificacion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `precal_lic_cont_unique` (`licitacion_id`,`contratista_id`),
  KEY `precalificaciones_contratistas_contratista_id_foreign` (`contratista_id`),
  KEY `precalificaciones_contratistas_revisado_por_usuario_id_foreign` (`revisado_por_usuario_id`),
  CONSTRAINT `precalificaciones_contratistas_contratista_id_foreign` FOREIGN KEY (`contratista_id`) REFERENCES `empresas_contratistas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `precalificaciones_contratistas_licitacion_id_foreign` FOREIGN KEY (`licitacion_id`) REFERENCES `licitaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `precalificaciones_contratistas_revisado_por_usuario_id_foreign` FOREIGN KEY (`revisado_por_usuario_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.precalificaciones_contratistas: ~2 rows (aproximadamente)
INSERT INTO `precalificaciones_contratistas` (`id`, `licitacion_id`, `contratista_id`, `estado`, `fecha_solicitud`, `fecha_resolucion`, `revisado_por_usuario_id`, `tipo_revisor`, `motivo_rechazo`, `comentarios_contratista`, `comentarios_rectificacion`, `created_at`, `updated_at`) VALUES
	(1, 6, 1, 'aprobada', '2025-12-24 19:46:54', '2026-01-07 20:23:39', 1, 'ryce', NULL, '', NULL, '2025-12-24 19:46:54', '2026-01-07 20:23:39'),
	(2, 7, 1, 'aprobada', '2026-01-07 20:13:46', '2026-01-07 20:23:31', 1, 'ryce', NULL, '', NULL, '2026-01-07 20:13:46', '2026-01-07 20:23:31');

-- Volcando estructura para tabla gestion_licitaciones.requisitos_documentos_licitacion
CREATE TABLE IF NOT EXISTS `requisitos_documentos_licitacion` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `licitacion_id` bigint unsigned NOT NULL,
  `nombre_requisito` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion_requisito` text COLLATE utf8mb4_unicode_ci,
  `es_obligatorio` tinyint(1) NOT NULL DEFAULT '1',
  `es_precalificacion` tinyint(1) NOT NULL DEFAULT '0',
  `orden` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `requisitos_documentos_licitacion_licitacion_id_index` (`licitacion_id`),
  CONSTRAINT `requisitos_documentos_licitacion_licitacion_id_foreign` FOREIGN KEY (`licitacion_id`) REFERENCES `licitaciones` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.requisitos_documentos_licitacion: ~17 rows (aproximadamente)
INSERT INTO `requisitos_documentos_licitacion` (`id`, `licitacion_id`, `nombre_requisito`, `descripcion_requisito`, `es_obligatorio`, `es_precalificacion`, `orden`, `created_at`, `updated_at`) VALUES
	(1, 1, 'EEEEEEEEEEEE', NULL, 1, 0, 1, '2025-12-23 18:35:22', '2026-01-07 20:03:55'),
	(2, 2, 'DDDDDDDDDDDD', NULL, 1, 0, 1, '2025-12-23 19:53:27', '2025-12-23 19:53:27'),
	(3, 1, 'TENER ANTIGUEDAD  4 AÑOS', NULL, 1, 0, 2, '2025-12-24 00:42:27', '2026-01-07 20:03:55'),
	(4, 1, 'ADJUNTAR FORMAULARIO DE EXPWERIENCA', NULL, 1, 0, 3, '2025-12-24 00:42:27', '2026-01-07 20:03:55'),
	(5, 3, 'vaucher de pago de bases', NULL, 1, 0, 1, '2025-12-24 17:40:07', '2025-12-24 17:40:07'),
	(6, 3, 'pago de iva', NULL, 1, 0, 2, '2025-12-24 17:40:07', '2025-12-24 17:40:07'),
	(7, 4, 'cargar vaucher de compra de bases', NULL, 1, 0, 1, '2025-12-24 17:45:55', '2025-12-24 17:45:55'),
	(8, 4, 'oferta economica', NULL, 1, 0, 2, '2025-12-24 17:45:55', '2025-12-24 17:45:55'),
	(9, 4, 'adjuntar certificado de experiencia', NULL, 1, 1, 1, '2025-12-24 17:45:55', '2025-12-24 17:45:55'),
	(10, 5, 'cargar vaucher de pago de bases', NULL, 1, 0, 1, '2025-12-24 18:52:43', '2025-12-24 18:52:43'),
	(11, 5, 'oferta economica com pleta', NULL, 1, 0, 2, '2025-12-24 18:52:43', '2025-12-24 18:52:43'),
	(12, 5, 'antiguedad de 5 años,   descargar fromulario como guia', NULL, 1, 1, 1, '2025-12-24 18:52:43', '2025-12-24 18:52:43'),
	(13, 6, 'en viar  el copmnptobante de compra de bases', NULL, 1, 0, 1, '2025-12-24 19:43:47', '2025-12-24 19:43:47'),
	(14, 6, 'no tener deudad trubutarias, se acreidta con xcetifacofof  deuda  u otr', NULL, 1, 1, 1, '2025-12-24 19:43:47', '2025-12-24 19:43:47'),
	(15, 6, 'no tener acicdentes fatales , se acreita con certifofof  mutaual', NULL, 1, 1, 2, '2025-12-24 19:43:47', '2025-12-24 19:43:47'),
	(16, 7, 'sin requi', NULL, 1, 0, 1, '2026-01-07 20:02:24', '2026-01-07 20:02:24'),
	(17, 7, 'certificado de deuda tgr', 'que no tenga deudas', 1, 1, 1, '2026-01-07 20:02:24', '2026-01-07 20:02:24');

-- Volcando estructura para tabla gestion_licitaciones.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.roles: ~2 rows (aproximadamente)
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'admin_plataforma', 'web', '2025-12-23 18:32:49', '2025-12-23 18:32:49'),
	(2, 'usuario_principal', 'web', '2025-12-23 18:32:49', '2025-12-23 18:32:49'),
	(3, 'usuario_contratista', 'web', '2025-12-23 18:32:49', '2025-12-23 18:32:49');

-- Volcando estructura para tabla gestion_licitaciones.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.role_has_permissions: ~0 rows (aproximadamente)

-- Volcando estructura para tabla gestion_licitaciones.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.sessions: ~1 rows (aproximadamente)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('phPI5sNtz6Gs9BY59Put6EQUn3WBVQRaQ2FXK8zC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiS1BIQms3NkhDNEJLejg1c1lDNGg1akNidkMyV2lDNGFENTZka2plcSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1NzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2NvbnRyYXRpc3RhL2xpY2l0YWNpb25lcy83L3Bvc3R1bGFyIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1767813551);

-- Volcando estructura para tabla gestion_licitaciones.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_completo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `empresa_principal_id` bigint unsigned DEFAULT NULL,
  `empresa_contratista_id` bigint unsigned DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `ultimo_login` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_empresa_principal_id_foreign` (`empresa_principal_id`),
  KEY `users_empresa_contratista_id_foreign` (`empresa_contratista_id`),
  CONSTRAINT `users_empresa_contratista_id_foreign` FOREIGN KEY (`empresa_contratista_id`) REFERENCES `empresas_contratistas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_empresa_principal_id_foreign` FOREIGN KEY (`empresa_principal_id`) REFERENCES `empresas_principales` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gestion_licitaciones.users: ~3 rows (aproximadamente)
INSERT INTO `users` (`id`, `name`, `nombre_completo`, `email`, `email_verified_at`, `password`, `empresa_principal_id`, `empresa_contratista_id`, `activo`, `ultimo_login`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin RyCE', 'Administrador de Plataforma RyCE', 'admin@ryce.cl', NULL, '$2y$12$NRYDDpdfLgkOHj6.kWbIIOZ6JotpAVuUjo9flAJuK12cPGFYFbNRi', NULL, NULL, 1, NULL, NULL, '2025-12-23 18:32:50', '2025-12-23 18:32:50'),
	(2, 'Usuario Principal', 'Juan Pérez (Empresa Principal)', 'principal@empresa.cl', NULL, '$2y$12$UDIPgy9kBSELZohgpPGBY..TX3b3OkG99ueL/c1/AiP/TEjpGNC6C', 1, NULL, 1, NULL, NULL, '2025-12-23 18:32:50', '2025-12-23 18:32:50'),
	(3, 'Usuario Contratista', 'María González (Empresa Contratista)', 'contratista@proveedor.cl', NULL, '$2y$12$xkbAZJa2f9xYRWipoq3w9e.pQu8rHVLPUQorJse389qdcrFgeeU.C', NULL, 1, 1, NULL, 'TRderfiRJtzOIdT9HE2Q5DUuYrHwriq4i4dGhjMUgppdRldZlbm3OHcyj8el', '2025-12-23 18:32:50', '2025-12-23 18:32:50');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
