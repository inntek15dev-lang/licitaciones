<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\EmpresaPrincipal;
use App\Models\EmpresaContratista;
use App\Models\CategoriaLicitacion;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear roles del sistema
        $roles = [
            'admin_plataforma',
            'usuario_principal',      // Empresas principales (clientes de RyCE)
            'usuario_contratista',    // Empresas contratistas (proveedores)
            'usuario_contratista_operativo', // Contratista operativo
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // === Crear Categorías de Licitaciones ===
        $categorias = [
            ['nombre_categoria' => 'Construcción', 'descripcion' => 'Obras de construcción civil, edificación e infraestructura'],
            ['nombre_categoria' => 'Servicios', 'descripcion' => 'Servicios profesionales y técnicos'],
            ['nombre_categoria' => 'Suministros', 'descripcion' => 'Provisión de bienes y materiales'],
            ['nombre_categoria' => 'Tecnología', 'descripcion' => 'Equipos y sistemas tecnológicos'],
            ['nombre_categoria' => 'Consultoría', 'descripcion' => 'Servicios de asesoría y consultoría especializada'],
            ['nombre_categoria' => 'Mantención', 'descripcion' => 'Servicios de mantención y reparación'],
        ];

        foreach ($categorias as $cat) {
            CategoriaLicitacion::firstOrCreate(
                ['nombre_categoria' => $cat['nombre_categoria']],
                $cat
            );
        }
        $this->command->info('✅ Categorías de licitaciones creadas');

        // === MASTER REQ [6]: Catálogo Motivos Rechazo (Orden Crítico) ===
        $motivos = [
            ['id' => 1, 'motivo' => 'Precio Elevado', 'etapa_aplicable' => 'Cierre'],
            ['id' => 2, 'motivo' => 'Falla Técnica', 'etapa_aplicable' => 'Cierre'],
            ['id' => 3, 'motivo' => 'No Factible', 'etapa_aplicable' => 'Inicial'],
        ];
        foreach ($motivos as $m) {
            \App\Models\CatMotivoRechazo::updateOrCreate(['id' => $m['id']], $m);
        }
        $this->command->info('✅ Catálogo de Motivos de Rechazo creado');

        // === MASTER REQ [6]: Tipos y Estados ===
        $tipos = [
            ['id' => 1, 'nombre' => 'Estratégica'],
            ['id' => 2, 'nombre' => 'No Estratégica'],
        ];
        foreach ($tipos as $t) {
            \App\Models\CatTipoLicitacion::updateOrCreate(['id' => $t['id']], $t);
        }

        $estados = [
            ['id' => 1, 'nombre_estado' => 'Borrador'],
            ['id' => 2, 'nombre_estado' => 'Publicada'],
            ['id' => 3, 'nombre_estado' => 'En Evaluación'],
            ['id' => 4, 'nombre_estado' => 'Adjudicada'],
            ['id' => 5, 'nombre_estado' => 'Perdida'],
        ];
        foreach ($estados as $e) {
            \App\Models\CatEstado::updateOrCreate(['id' => $e['id']], $e);
        }
        $this->command->info('✅ Catálogos Tipos y Estados creados');

        // === Crear Empresas Principales (Clientes RyCE) ===
        $empresaPrincipal1 = EmpresaPrincipal::firstOrCreate(
            ['rut' => '76.123.456-7'],
            [
                'razon_social' => 'Constructora ABC S.A.',
                'direccion' => 'Av. Providencia 1234, Santiago',
                'telefono' => '+56 2 2345 6789',
                'email_contacto_principal' => 'contacto@constructora-abc.cl',
                'persona_contacto_principal' => 'Pedro López',
                'activo' => true,
            ]
        );

        $empresaPrincipal2 = EmpresaPrincipal::firstOrCreate(
            ['rut' => '76.789.012-3'],
            [
                'razon_social' => 'Minera Grande Chile S.A.',
                'direccion' => 'Calle Los Mineros 567, Antofagasta',
                'telefono' => '+56 55 234 5678',
                'email_contacto_principal' => 'licitaciones@mineragrande.cl',
                'persona_contacto_principal' => 'Ana Torres',
                'activo' => true,
            ]
        );
        $this->command->info('✅ Empresas principales creadas');

        // === Crear Empresas Contratistas (Proveedores) ===
        $empresaContratista1 = EmpresaContratista::firstOrCreate(
            ['rut' => '77.111.222-3'],
            [
                'razon_social' => 'Proveedores Industriales Ltda.',
                'direccion' => 'Av. Industrial 890, Concepción',
                'telefono' => '+56 41 234 5678',
                'email_contacto_principal' => 'ventas@proveedores-ind.cl',
                'persona_contacto_principal' => 'Carlos Muñoz',
                'rubros_especialidad' => 'Equipos industriales, maquinaria pesada',
                'activo' => true,
            ]
        );

        $empresaContratista2 = EmpresaContratista::firstOrCreate(
            ['rut' => '77.333.444-5'],
            [
                'razon_social' => 'Servicios Técnicos del Norte SpA',
                'direccion' => 'Calle Técnica 123, Calama',
                'telefono' => '+56 55 876 5432',
                'email_contacto_principal' => 'contacto@serviciosnorte.cl',
                'persona_contacto_principal' => 'Luisa Fernández',
                'rubros_especialidad' => 'Mantención industrial, servicios eléctricos',
                'activo' => true,
            ]
        );
        $this->command->info('✅ Empresas contratistas creadas');

        // === Crear Usuarios de Prueba ===

        // Usuario Admin de prueba
        $admin = User::firstOrCreate(
            ['email' => 'admin@ryce.cl'],
            [
                'name' => 'Admin RyCE',
                'nombre_completo' => 'Administrador de Plataforma RyCE',
                'password' => bcrypt('password'),
                'activo' => true,
            ]
        );
        $admin->assignRole('admin_plataforma');

        // Usuario Principal de prueba (con empresa asignada)
        $principal = User::firstOrCreate(
            ['email' => 'principal@empresa.cl'],
            [
                'name' => 'Usuario Principal',
                'nombre_completo' => 'Juan Pérez (Empresa Principal)',
                'password' => bcrypt('password'),
                'empresa_principal_id' => $empresaPrincipal1->id,
                'activo' => true,
            ]
        );
        // Asegurar que tenga la empresa asignada (en caso de que ya exista)
        if (!$principal->empresa_principal_id) {
            $principal->update(['empresa_principal_id' => $empresaPrincipal1->id]);
        }
        $principal->assignRole('usuario_principal');

        // Usuario Contratista de prueba (con empresa asignada)
        $contratista = User::firstOrCreate(
            ['email' => 'contratista@proveedor.cl'],
            [
                'name' => 'Usuario Contratista',
                'nombre_completo' => 'María González (Empresa Contratista)',
                'password' => bcrypt('password'),
                'empresa_contratista_id' => $empresaContratista1->id,
                'activo' => true,
            ]
        );
        // Asegurar que tenga la empresa asignada (en caso de que ya exista)
        if (!$contratista->empresa_contratista_id) {
            $contratista->update(['empresa_contratista_id' => $empresaContratista1->id]);
        }
        $contratista->assignRole('usuario_contratista');

        // === Usuario Super-Admin de Pruebas (Inntek) ===
        // REGLA: Este usuario debe existir siempre para pruebas de sistema con máximos privilegios
        $inntek = User::firstOrCreate(
            ['email' => 'inntek@inntek.cl'],
            [
                'name' => 'inntek',
                'nombre_completo' => 'Usuario Pruebas Inntek',
                'password' => bcrypt('inntek'),
                'activo' => true,
            ]
        );
        $inntek->assignRole('admin_plataforma');
        $this->command->info('✅ Usuario Inntek verificado (user: inntek / pass: inntek)');

        // === Empresa Contratista para Inntek ===
        $empresaInntek = \App\Models\EmpresaContratista::firstOrCreate(
            ['rut' => '77.999.888-K'],
            [
                'razon_social' => 'Inntek Test SpA',
                'direccion' => 'Av. Test 123, Santiago',
                'telefono' => '+56 2 9999 8888',
                'email_contacto_principal' => 'test@inntek.cl',
                'persona_contacto_principal' => 'Inntek Tester',
                'rubros_especialidad' => 'Testing, QA, Servicios Generales',
                'activo' => true,
            ]
        );
        // Vincular Inntek al contratista de prueba
        if (!$inntek->empresa_contratista_id) {
            $inntek->update(['empresa_contratista_id' => $empresaInntek->id]);
        }
        $this->command->info('✅ Empresa Inntek Test SpA creada y vinculada');

        // ===================================================================
        // SAMPLE DATA GENERATION - INNTEK TESTING FLOWS
        // ===================================================================
        $this->command->info('🔄 Generando datos de muestra para exploración completa...');

        $categoriaConstruccion = \App\Models\CategoriaLicitacion::where('nombre_categoria', 'Construcción')->first();
        $categoriaServicios = \App\Models\CategoriaLicitacion::where('nombre_categoria', 'Servicios')->first();

        // === Sample Licitaciones (3) ===
        $licitacion1 = \App\Models\Licitacion::firstOrCreate(
            ['codigo_licitacion' => 'LIC-INNTEK-001'],
            [
                'titulo' => '[TEST] Construcción de Planta Industrial',
                'descripcion_corta' => 'Licitación de prueba para construcción de nueva planta industrial.',
                'descripcion_larga' => 'Licitación de prueba para construcción de nueva planta industrial en zona norte. Incluye obras civiles, instalaciones eléctricas y sanitarias.',
                'principal_id' => $empresaPrincipal1->id,
                'estado' => 'publicada',
                'tipo_licitacion' => 'publica',
                'presupuesto_referencial' => 150000000,
                'moneda_presupuesto' => 'CLP',
                'fecha_publicacion' => now()->subDays(10),
                'fecha_cierre_recepcion_ofertas' => now()->addDays(20),
                'requiere_precalificacion' => true,
                'responsable_precalificacion' => 'ambos',
                'usuario_creador_id' => $principal->id,
                'es_interesante' => true,
            ]
        );

        $licitacion2 = \App\Models\Licitacion::firstOrCreate(
            ['codigo_licitacion' => 'LIC-INNTEK-002'],
            [
                'titulo' => '[TEST] Servicios de Mantención Anual',
                'descripcion_corta' => 'Licitación de prueba para contrato de mantención preventiva.',
                'descripcion_larga' => 'Licitación de prueba para contrato de mantención preventiva y correctiva de equipos industriales.',
                'principal_id' => $empresaPrincipal2->id,
                'estado' => 'adjudicada',
                'tipo_licitacion' => 'privada_invitacion',
                'presupuesto_referencial' => 50000000,
                'moneda_presupuesto' => 'CLP',
                'fecha_publicacion' => now()->subDays(30),
                'fecha_cierre_recepcion_ofertas' => now()->subDays(10),
                'requiere_precalificacion' => false,
                'usuario_creador_id' => $principal->id,
                'es_interesante' => false,
            ]
        );

        $licitacion3 = \App\Models\Licitacion::firstOrCreate(
            ['codigo_licitacion' => 'LIC-INNTEK-003'],
            [
                'titulo' => '[TEST] Suministro de Equipos (Borrador)',
                'descripcion_corta' => 'Licitación en borrador para adquisición de equipos industriales.',
                'descripcion_larga' => 'Licitación en borrador para adquisición de equipos industriales pesados y maquinaria de construcción.',
                'principal_id' => $empresaPrincipal1->id,
                'estado' => 'borrador',
                'tipo_licitacion' => 'publica',
                'presupuesto_referencial' => 80000000,
                'moneda_presupuesto' => 'CLP',
                'requiere_precalificacion' => true,
                'usuario_creador_id' => $admin->id,
            ]
        );
        $this->command->info('   📋 3 Licitaciones de muestra creadas');

        // === MASTER REQ [3.10]: Revisiones Calidad ===
        \App\Models\RevisionCalidad::create([
            'licitacion_id' => $licitacion1->id,
            'contiene_errores' => false,
            'observaciones' => 'Revisión de calidad aprobada. Bases completas.',
        ]);

        \App\Models\RevisionCalidad::create([
            'licitacion_id' => $licitacion3->id, // Borrador
            'contiene_errores' => true,
            'observaciones' => 'Faltan anexos técnicos. Corregir antes de publicar.',
        ]);
        $this->command->info('   ✅ Revisiones de Calidad creadas');

        // === MASTER REQ [3.11]: Lecciones Aprendidas (Caso Perdida) ===
        // Creamos una Licitación Perdida para testing
        $licitacionPerdida = \App\Models\Licitacion::firstOrCreate(
            ['codigo_licitacion' => 'LIC-INNTEK-LOST'],
            [
                'titulo' => '[TEST] Proyecto Cancelado/Perdido',
                'principal_id' => $empresaPrincipal1->id,
                'estado' => 'perdida', // Estado válido en MASTER REQ
                'tipo_licitacion' => 'publica',
                'usuario_creador_id' => $principal->id,
                'es_interesante' => true,
            ]
        );
        \App\Models\LeccionAprendida::create([
            'licitacion_id' => $licitacionPerdida->id,
            'motivo_id' => 1, // Precio Elevado
            'analisis_detalle' => 'El presupuesto excedió el Capex disponible por un 20%.',
        ]);
        $this->command->info('   ✅ Lecciones Aprendidas creadas');

        // === Sample Ofertas (2 por licitación publicada/adjudicada) ===
        foreach ([$licitacion1, $licitacion2] as $lic) {
            // Oferta de Inntek
            \App\Models\Oferta::firstOrCreate(
                ['licitacion_id' => $lic->id, 'contratista_id' => $empresaInntek->id],
                [
                    'monto_oferta_economica' => $lic->presupuesto_referencial * 0.95,
                    'moneda_oferta' => 'CLP',
                    'validez_oferta_dias' => 90,
                    'estado_oferta' => $lic->estado === 'adjudicada' ? 'adjudicada' : 'presentada',
                    'fecha_presentacion' => now()->subDays(5),
                    'comentarios_oferta' => 'Oferta de prueba Inntek - ' . $lic->titulo,
                    'usuario_presenta_id' => $inntek->id,
                ]
            );
            // Oferta de otro contratista
            \App\Models\Oferta::firstOrCreate(
                ['licitacion_id' => $lic->id, 'contratista_id' => $empresaContratista1->id],
                [
                    'monto_oferta_economica' => $lic->presupuesto_referencial * 1.05,
                    'moneda_oferta' => 'CLP',
                    'validez_oferta_dias' => 120,
                    'estado_oferta' => 'presentada',
                    'fecha_presentacion' => now()->subDays(3),
                    'comentarios_oferta' => 'Oferta competidora - ' . $lic->titulo,
                    'usuario_presenta_id' => $contratista->id,
                ]
            );
        }
        $this->command->info('   📝 Ofertas de muestra creadas');

        // === Sample Consultas (2 por licitación) ===
        foreach ([$licitacion1, $licitacion2] as $lic) {
            \App\Models\ConsultaRespuestaLicitacion::firstOrCreate(
                ['licitacion_id' => $lic->id, 'texto_pregunta' => '¿Cuál es el plazo máximo de ejecución?'],
                [
                    'usuario_pregunta_id' => $inntek->id,
                    'contratista_id' => $empresaInntek->id,
                    'texto_respuesta' => 'El plazo máximo es de 180 días corridos desde la firma del contrato.',
                    'usuario_respuesta_id' => $principal->id,
                    'fecha_respuesta' => now()->subDays(6),
                    'es_publica' => true,
                ]
            );
            \App\Models\ConsultaRespuestaLicitacion::firstOrCreate(
                ['licitacion_id' => $lic->id, 'texto_pregunta' => '¿Se aceptan garantías bancarias internacionales?'],
                [
                    'usuario_pregunta_id' => $contratista->id,
                    'contratista_id' => $empresaContratista1->id,
                    'texto_respuesta' => 'Sí, se aceptan garantías de bancos con clasificación A o superior.',
                    'usuario_respuesta_id' => $admin->id,
                    'fecha_respuesta' => now()->subDays(5),
                    'es_publica' => true,
                ]
            );
        }
        $this->command->info('   💬 Consultas de muestra creadas');

        // === Sample Precalificaciones (para licitación con precalificación) ===
        \App\Models\PrecalificacionContratista::firstOrCreate(
            ['licitacion_id' => $licitacion1->id, 'contratista_id' => $empresaInntek->id],
            [
                'estado' => 'aprobada',
                'fecha_solicitud' => now()->subDays(12),
                'fecha_resolucion' => now()->subDays(10),
                'revisado_por_usuario_id' => $admin->id,
                'tipo_revisor' => 'ryce',
                'comentarios_contratista' => 'Solicitud de prueba Inntek',
                // Corporate Fields
                'nro_trabajadores' => 150,
                'anios_experiencia' => 10,
                'capital_social' => 500000000,
                'patrimonio_neto' => 350000000,
                'ventas_ultimo_anio' => 1200000000,
                'moneda_financiera' => 'CLP',
                'tasa_accidentabilidad' => 2.5,
                'tasa_siniestralidad' => 1.2,
                'tiene_programa_prevencion' => true,
                'tiene_iso_9001' => true,
                'tiene_iso_14001' => true,
                'tiene_iso_45001' => true,
                'nombre_representante_legal' => 'Pablo Inntek',
                'rut_representante_legal' => '12.345.678-9',
                // Advanced Matrix Data (Good Contractor)
                'ind_liquidez' => 4.88,
                'ind_leverage' => 0.18,
                'monto_ebitda' => 350000000,
                'deuda_comercial_monto' => 0, // Clean
                'deuda_tributaria_al_dia' => true,
                'hse_tat_anterior' => 2.0, // Improved
                'hse_tst_anterior' => 1.0,
                'hse_tat_actual' => 1.5,
                'hse_tst_actual' => 0.5,
                'cumple_legal_vigencia' => true,
                'cumple_laboral_multas' => true,
                'cumple_laboral_deuda' => true,
                'score_ranking' => 95.5,
                'score_seguridad' => 100,
            ]
        );
        \App\Models\PrecalificacionContratista::firstOrCreate(
            ['licitacion_id' => $licitacion1->id, 'contratista_id' => $empresaContratista1->id],
            [
                'estado' => 'pendiente',
                'fecha_solicitud' => now()->subDays(5),
                'comentarios_contratista' => 'Solicitud pendiente de revisión',
                'nro_trabajadores' => 80,
                'anios_experiencia' => 5,
                'capital_social' => 100000000,
                'patrimonio_neto' => 50000000,
                'ventas_ultimo_anio' => 300000000,
                'moneda_financiera' => 'CLP',
                'tasa_accidentabilidad' => 6.5, // High - should trigger alert
                'tasa_siniestralidad' => 3.0,
                'tiene_programa_prevencion' => false,
                'tiene_iso_9001' => false,
                'tiene_iso_14001' => false,
                'tiene_iso_45001' => false,
                'nombre_representante_legal' => 'Carlos Muñoz',
                'rut_representante_legal' => '11.222.333-4',
                // Advanced Matrix Data (Bad Contractor)
                'ind_liquidez' => 0.8, // Bad liquidity
                'ind_leverage' => 2.5, // High leverage
                'monto_ebitda' => -5000000, // Negative EBITDA
                'deuda_comercial_monto' => 73000000, // High Commercial Debt (Red Alert)
                'deuda_tributaria_al_dia' => false, // Tax Issues
                'hse_tat_anterior' => 5.0,
                'hse_tst_anterior' => 4.0,
                'hse_tat_actual' => 6.5, // Worsening Safety
                'hse_tst_actual' => 5.0,
                'cumple_legal_vigencia' => true,
                'cumple_laboral_multas' => false, // Fines
                'cumple_laboral_deuda' => true,
                'score_ranking' => 45.0, // Low Score
                'score_seguridad' => 30.0, // Danger Zone
            ]
        );
        $this->command->info('   ✅ Precalificaciones de muestra creadas');

        // === Sample Documentos ===
        foreach ([$licitacion1, $licitacion2, $licitacion3] as $lic) {
            \App\Models\DocumentoLicitacion::firstOrCreate(
                ['licitacion_id' => $lic->id, 'nombre_documento' => 'Bases Administrativas'],
                [
                    'tipo_documento' => 'bases',
                    'descripcion_documento' => 'Documento de bases administrativas de la licitación',
                    'ruta_archivo' => 'documentos/bases_dummy.pdf', // Mandatory field
                    'subido_por_usuario_id' => $principal->id,
                ]
            );
        }
        $this->command->info('   📄 Documentos de muestra creados');

        $this->command->info('✅ Roles creados: ' . implode(', ', $roles));
        $this->command->info('✅ Usuarios de prueba creados (password: password)');
        $this->command->info('   - admin@ryce.cl (Admin)');
        $this->command->info('   - principal@empresa.cl (Principal) → Constructora ABC S.A.');
        $this->command->info('   - contratista@proveedor.cl (Contratista) → Proveedores Industriales Ltda.');
        $this->command->info('🎉 DATOS DE MUESTRA INNTEK GENERADOS - Sistema listo para exploración completa');
    }
}


