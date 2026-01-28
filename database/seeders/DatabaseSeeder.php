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

        $this->command->info('✅ Roles creados: ' . implode(', ', $roles));
        $this->command->info('✅ Usuarios de prueba creados (password: password)');
        $this->command->info('   - admin@ryce.cl (Admin)');
        $this->command->info('   - principal@empresa.cl (Principal) → Constructora ABC S.A.');
        $this->command->info('   - contratista@proveedor.cl (Contratista) → Proveedores Industriales Ltda.');
    }
}


