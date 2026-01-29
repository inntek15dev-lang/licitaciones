# Documentación Técnica y Funcional: Licitaciones RyCE

Este documento detalla los módulos del sistema Licitaciones RyCE bajo el formato de "Historia de Usuario" (Funcional) y "Ficha Técnica" (Técnico), describiendo qué hace el sistema y cómo lo logra internamente.

---

## Resumen del Sistema

**Licitaciones RyCE** es una plataforma web para gestión de licitaciones que permite:
- **Empresas Principales** (clientes de RyCE): Crear y gestionar licitaciones
- **Empresas Contratistas** (proveedores): Buscar y postular a licitaciones
- **RyCE (Admin)**: Supervisar el proceso y precalificar ofertas

### Arquitectura Tecnológica
- **Backend**: Laravel 12.x
- **Frontend Interactivo**: Livewire 3.x
- **Base de Datos**: SQLite (desarrollo) / MySQL (producción)
- **Estilos**: Tailwind CSS
- **Autenticación y Roles**: Laravel Breeze, Spatie/laravel-permission

---

## Módulo 1: Autenticación y Roles

### Historia de Usuario (El QUÉ)
"Como **Usuario del Sistema**, quiero identificarme con mi email y contraseña para acceder a las funcionalidades que corresponden a mi rol."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Paquetes**: Laravel Breeze (Livewire), Spatie/laravel-permission
*   **Modelo**: `User` con trait `HasRoles`
*   **Roles del Sistema**:
    | Rol | Descripción | Acceso |
    |-----|-------------|--------|
    | `admin_plataforma` | Administrador RyCE | Control total, precalificación |
    | `usuario_principal` | Empresa cliente | Crear/gestionar licitaciones |
    | `usuario_contratista` | Empresa proveedora | Buscar/postular licitaciones |

*   **Campos Adicionales en `users`**:
    - `nombre_completo` - Nombre completo del usuario
    - `empresa_principal_id` - FK a empresa principal
    - `empresa_contratista_id` - FK a empresa contratista
    - `activo` - Estado del usuario
    - `ultimo_login` - Fecha último acceso

---

## Módulo 2: Gestión de Empresas

### Historia de Usuario (El QUÉ)
"Como **Administrador RyCE**, quiero gestionar las empresas registradas (principales y contratistas) para mantener el directorio actualizado."

### Ficha Técnica / Blueprint (El CÓMO)

#### 2.1 Empresas Principales
*   **Modelo**: `EmpresaPrincipal`
*   **Tabla**: `empresas_principales`
*   **Campos Principales**:
    - `razon_social`, `rut` (único), `direccion`, `telefono`
    - `email_contacto_principal`, `persona_contacto_principal`
    - `logo_url`, `activo`
*   **Relaciones**:
    - `hasMany(User)` - Usuarios de la empresa
    - `hasMany(Licitacion)` - Licitaciones creadas

#### 2.2 Empresas Contratistas
*   **Modelo**: `EmpresaContratista`
*   **Tabla**: `empresas_contratistas`
*   **Campos Adicionales**:
    - `rubros_especialidad` - Áreas de especialización
    - `documentacion_validada` - Si RyCE validó sus documentos
*   **Relaciones**:
    - `hasMany(User)` - Usuarios de la empresa
    - `hasMany(Oferta)` - Ofertas presentadas
    - `hasMany(ConsultaRespuestaLicitacion)` - Consultas realizadas

---

## Módulo 3: Gestión de Licitaciones

### Historia de Usuario (El QUÉ)
"Como **Usuario Principal**, quiero crear licitaciones especificando requisitos, fechas y documentos base, para que los contratistas puedan postular."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Modelo**: `Licitacion`
*   **Tabla**: `licitaciones`

#### Estados de una Licitación (State Machine)
```
borrador → lista_para_publicar → publicada → cerrada_ofertas → adjudicada
                ↓                    ↓                            ↓
         observada_por_ryce    cerrada_consultas              desierta
                                     ↓
                               en_evaluacion
                                     ↓
                                 cancelada
```

#### Campos Principales
| Campo | Tipo | Descripción |
|-------|------|-------------|
| `codigo_licitacion` | VARCHAR(50) UNIQUE | Código único (ej: LIC-2025-001) |
| `titulo` | VARCHAR(255) | Título de la licitación |
| `descripcion_corta` | TEXT | Resumen breve |
| `descripcion_larga` | LONGTEXT | Descripción completa |
| `tipo_licitacion` | ENUM | `publica` / `privada_invitacion` |
| `estado` | ENUM | Estado actual (10 posibles) |
| `presupuesto_referencial` | DECIMAL(15,2) | Presupuesto estimado |
| `moneda_presupuesto` | VARCHAR(5) | CLP, USD, etc. |

#### Fechas Clave
- `fecha_publicacion` - Cuándo se publicó
- `fecha_inicio_consultas` / `fecha_cierre_consultas` - Período de P&R
- `fecha_inicio_recepcion_ofertas` / `fecha_cierre_recepcion_ofertas` - Período de ofertas
- `fecha_adjudicacion_estimada` / `fecha_adjudicacion_real`

#### Relaciones
- `belongsTo(EmpresaPrincipal)` - Quien crea la licitación
- `belongsTo(User)` como `creador` y `revisorRyCE`
- `belongsToMany(CategoriaLicitacion)` - Categorías asignadas
- `hasMany(DocumentoLicitacion)` - Documentos base
- `hasMany(RequisitoDocumentoLicitacion)` - Requisitos para contratistas
- `hasMany(Oferta)` - Ofertas recibidas
- `hasMany(ConsultaRespuestaLicitacion)` - Preguntas y respuestas

#### Métodos de Negocio
```php
// Verificar si está en período de consultas
public function enPeriodoConsultas(): bool

// Verificar si acepta ofertas
public function aceptaOfertas(): bool
```

---

## Módulo 4: Documentos de Licitación

### Historia de Usuario (El QUÉ)
"Como **Usuario Principal**, quiero adjuntar documentos base (bases, anexos técnicos, planos) y definir qué documentos debe entregar el contratista."

### Ficha Técnica / Blueprint (El CÓMO)

#### 4.1 Documentos Base (del Principal)
*   **Modelo**: `DocumentoLicitacion`
*   **Tabla**: `documentos_licitacion`
*   **Tipos**: `bases`, `anexo_tecnico`, `anexo_economico`, `plano`, `aclaracion`, `otro`

#### 4.2 Requisitos para Contratistas
*   **Modelo**: `RequisitoDocumentoLicitacion`
*   **Tabla**: `requisitos_documentos_licitacion`
*   **Campos**:
    - `nombre_requisito` - Ej: "Certificado de Antecedentes Comerciales"
    - `descripcion_requisito` - Instrucciones adicionales
    - `es_obligatorio` - Si es mandatorio
    - `orden` - Para ordenar en formulario

---

## Módulo 5: Ofertas y Postulación

### Historia de Usuario (El QUÉ)
"Como **Usuario Contratista**, quiero postular a licitaciones enviando mi oferta económica y los documentos solicitados."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Modelo**: `Oferta`
*   **Tabla**: `ofertas`
*   **Restricción**: Una oferta por contratista por licitación (`UNIQUE(licitacion_id, contratista_id)`)

#### Estados de una Oferta
| Estado | Descripción |
|--------|-------------|
| `pendiente_precalificacion_ryce` | Esperando revisión de RyCE |
| `precalificada_por_ryce` | Aprobada por RyCE |
| `no_precalificada_ryce` | Rechazada por RyCE |
| `en_evaluacion_principal` | En revisión por el Principal |
| `adjudicada` | Ganó la licitación |
| `no_adjudicada` | No fue seleccionada |
| `retirada` | El contratista la retiró |

#### Campos Principales
- `monto_oferta_economica` - Monto ofertado
- `moneda_oferta` - Moneda
- `validez_oferta_dias` - Días de validez
- `comentarios_precalificacion_ryce` - Observaciones de RyCE

#### Documentos de Oferta
*   **Modelo**: `DocumentoOferta`
*   **Tipos**: `propuesta_tecnica`, `propuesta_economica`, `garantia_seriedad`, `certificado`, `otro`

---

## Módulo 6: Consultas y Respuestas (P&R)

### Historia de Usuario (El QUÉ)
"Como **Usuario Contratista**, quiero hacer preguntas sobre la licitación durante el período habilitado y ver las respuestas."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Modelo**: `ConsultaRespuestaLicitacion`
*   **Tabla**: `consultas_respuestas_licitacion`

#### Lógica de Visibilidad
| Condición | Visibilidad |
|-----------|-------------|
| Antes de cierre + mi pregunta | Solo yo la veo |
| Después de cierre + `es_publica = true` | Todos los contratistas precalificados |
| `es_publica = false` | Solo quien preguntó y respondió |

#### Scopes
```php
// Consultas sin responder
public function scopePendientes($query)

// Consultas públicas
public function scopePublicas($query)
```

---

## Módulo 7: Notificaciones

### Historia de Usuario (El QUÉ)
"Como **Usuario del Sistema**, quiero recibir notificaciones cuando ocurran eventos importantes relacionados con mis licitaciones u ofertas."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Modelo**: `Notificacion`
*   **Tabla**: `notificaciones`

#### Tipos de Notificación
| Tipo | Destinatario | Trigger |
|------|--------------|---------|
| `LICITACION_APROBADA` | Principal | RyCE aprueba licitación |
| `LICITACION_OBSERVADA` | Principal | RyCE observa licitación |
| `NUEVA_CONSULTA_LICITACION` | Principal | Contratista hace pregunta |
| `CONSULTA_RESPONDIDA` | Contratista | Principal/RyCE responde |
| `OFERTA_PRECALIFICADA` | Contratista | RyCE precalifica oferta |
| `OFERTA_NO_PRECALIFICADA` | Contratista | RyCE rechaza oferta |
| `OFERTA_ADJUDICADA` | Contratista | Principal adjudica oferta |
| `OFERTA_NO_ADJUDICADA` | Contratista | Principal adjudica a otro |

#### Helper de Creación
```php
Notificacion::crear(
    usuarioId: $userId,
    tipo: 'LICITACION_APROBADA',
    mensaje: 'Su licitación ha sido aprobada',
    url: '/licitaciones/123'
);
```

---

## Modelo de Datos (ERD Simplificado)

```
┌─────────────────────┐     ┌─────────────────────┐
│  empresas_principales│     │ empresas_contratistas│
├─────────────────────┤     ├─────────────────────┤
│ id, razon_social    │     │ id, razon_social    │
│ rut, direccion      │     │ rut, documentacion  │
└─────────┬───────────┘     └──────────┬──────────┘
          │                            │
          │ 1:N                        │ 1:N
          ▼                            ▼
┌─────────────────────┐     ┌─────────────────────┐
│       users         │     │       users         │
└─────────┬───────────┘     └──────────┬──────────┘
          │                            │
          │ 1:N (creador)              │ 1:N (presenta)
          ▼                            ▼
┌─────────────────────────────────────────────────┐
│                   licitaciones                   │
├─────────────────────────────────────────────────┤
│ id, codigo, titulo, estado, fechas...           │
│ requiere_precalificacion, responsable_precal... │
└─────────────────────┬───────────────────────────┘
                      │
      ┌───────────────┼───────────────┬───────────────┐
      │ 1:N           │ 1:N           │ 1:N           │ 1:N
      ▼               ▼               ▼               ▼
┌──────────┐   ┌──────────┐   ┌──────────────┐ ┌───────────────┐
│ documentos│   │ ofertas  │   │ consultas_   │ │ precalific.   │
│ licitacion│   │          │   │ respuestas   │ │ contratistas  │
└──────────┘   └────┬─────┘   └──────────────┘ └───────────────┘
                    │
                    │ 1:N
                    ▼
              ┌──────────┐
              │documentos│
              │ oferta   │
              └──────────┘
```

---

## Módulo 8: Precalificación de Contratistas

### Historia de Usuario (El QUÉ)
"Como **Empresa Principal o Admin RyCE**, quiero que los contratistas se precalifiquen antes de postular a ciertas licitaciones, revisando su documentación y aprobando/rechazando su participación."

"Como **Usuario Contratista**, quiero solicitar precalificación para licitaciones que lo requieran, adjuntando los documentos necesarios."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Modelo**: `PrecalificacionContratista`
*   **Tabla**: `precalificaciones_contratistas`

#### Estados de Precalificación
| Estado | Descripción |
|--------|-------------|
| `pendiente` | Solicitud enviada, esperando revisión |
| `aprobada` | Contratista puede postular |
| `rechazada` | Contratista no puede postular (puede rectificar) |
| `rectificando` | Contratista corrigió y reenvió |

#### Campos Principales
| Campo | Tipo | Descripción |
|-------|------|-------------|
| `licitacion_id` | FK | Licitación relacionada |
| `contratista_id` | FK | Empresa contratista |
| `estado` | ENUM | Estado actual |
| `fecha_solicitud` | DATETIME | Cuándo se solicitó |
| `fecha_resolucion` | DATETIME | Cuándo se aprobó/rechazó |
| `revisado_por_usuario_id` | FK | Quién revisó |
| `tipo_revisor` | ENUM | `ryce` / `principal` |
| `motivo_rechazo` | TEXT | Razón del rechazo |
| `comentarios_contratista` | TEXT | Comentarios del contratista |
| `comentarios_rectificacion` | TEXT | Comentarios de rectificación |

#### Campos en Licitación
- `requiere_precalificacion` (BOOLEAN) - Si la licitación requiere precalificación
- `responsable_precalificacion` (ENUM: `ryce`, `principal`, `ambos`) - Quién puede precalificar
- `fecha_inicio_precalificacion` / `fecha_fin_precalificacion` - Plazo para precalificarse

#### Requisitos de Precalificación
Los requisitos (`requisitos_documentos_licitacion`) ahora tienen campo `es_precalificacion`:
- Si `es_precalificacion = true`: Requisito para la etapa de precalificación
- Si `es_precalificacion = false`: Requisito para la postulación/oferta

#### Flujo de Precalificación
```
Contratista ve licitación con requiere_precalificacion = true
    ↓
No puede postular directamente → Debe "Solicitar Precalificación"
    ↓
Carga documentos requeridos (según requisitos con es_precalificacion = true)
    ↓
RyCE o Principal revisa → Aprueba o Rechaza
    ↓
Si Aprobada: Contratista puede postular oferta
Si Rechazada: Contratista puede Rectificar y Reenviar
```

#### Componentes Livewire
| Componente | Ubicación | Función |
|------------|-----------|---------|
| `SolicitudPrecalificacion` | `Contratista/Licitaciones/` | Formulario de solicitud |
| `Index` | `Admin/Precalificaciones/` | Listado de precalificaciones |
| `Revisar` | `Admin/Precalificaciones/` | Revisión con aprobar/rechazar |

#### Rutas
- `/contratista/licitaciones/{id}/precalificar` - Solicitud de precalificación
- `/admin/precalificaciones` - Panel de revisión Admin
- `/admin/precalificaciones/{id}` - Detalle y resolución

#### Métodos de Negocio en Licitación
```php
// Verificar si contratista puede postular (considera precalificación)
public function puedePostular(EmpresaContratista $contratista): bool

// Obtener precalificación de un contratista
public function getPrecalificacion(EmpresaContratista $contratista): ?PrecalificacionContratista
```

---

## Historial de Versiones

| Versión | Fecha | Cambios Principales |
| :--- | :--- | :--- |
| **v0.1** | 22/12/2025 | Creación proyecto Laravel. Instalación Breeze + Livewire + Spatie Permissions. |
| **v0.2** | 22/12/2025 | Creación de 5 migraciones y 11 modelos Eloquent. Renombrado "Mandante" → "Principal". |
| **v0.3** | 23/12/2025 | Módulo Contratista: Búsqueda y vista de licitaciones. Creación de ofertas. |
| **v0.4** | 24/12/2025 | **Módulo Precalificación completo**: Tabla `precalificaciones_contratistas`. Campo `responsable_precalificacion` en licitaciones. Formulario de solicitud para contratista con documentos por requisito. Panel Admin para revisar/aprobar/rechazar. Flujo de rectificación. Separación de requisitos de precalificación vs postulación. Filtro de precalificación en tabla Admin. Mejoras UI: cabeceras fijas, scroll, filas entramadas. |

