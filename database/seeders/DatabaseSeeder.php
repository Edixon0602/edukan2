<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Membership;
use App\Models\Coupon;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Roles with JSONB permissions
        $adminRole = Role::updateOrCreate(
            ['name' => 'admin'],
            [
                'description' => 'Administrador global del sistema',
                'permissions' => [
                    'access-admin',
                    'gift-courses',
                    'verify-payments',
                    'send-broadcasts',
                    'manage-courses',
                ]
            ]
        );

        $studentRole = Role::updateOrCreate(
            ['name' => 'estudiante'],
            [
                'description' => 'Estudiante inscrito',
                'permissions' => [],
            ]
        );

        // 1.2. Create Default Users
        User::updateOrCreate(
            ['email' => 'admin@edukan2.com'],
            [
                'name' => 'Edukan2 Admin',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,
                'membership' => 'vip',
                'phone' => '+584245318103',
                'country' => 'Venezuela'
            ]
        );

        User::updateOrCreate(
            ['email' => 'estudiante@edukan2.com'],
            [
                'name' => 'Estudiante Prueba',
                'password' => Hash::make('estudiante123'),
                'role_id' => $studentRole->id,
                'membership' => 'regular',
                'phone' => '+584241112233',
                'country' => 'Venezuela'
            ]
        );

        // 2. Seeding Categories
        $cat_0 = Category::updateOrCreate(['slug' => 'lo-logre'], ['name' => 'lo logre']);
        $cat_1 = Category::updateOrCreate(['slug' => 'luna'], ['name' => 'luna']);

        // 3. Seeding Memberships
        Membership::updateOrCreate(
            ['name' => 'Plan Estándar'],
            [
                'price_monthly' => 19,
                'price_yearly' => 299,
                'benefits' => json_decode('["Acceso ILIMITADO a Todo el Catálogo", "Nuevos Cursos Agregados Mensualmente", "Soporte VIP Directo por WhatsApp", "Descarga de Plantillas y Material de Trabajo", "Acceso a Masterclasses en Vivo"]', true),
                'visual_style' => 'regular',
            ]
        );
        Membership::updateOrCreate(
            ['name' => 'Academia Pro'],
            [
                'price_monthly' => 39,
                'price_yearly' => 499,
                'benefits' => json_decode('["Acceso ILIMITADO a Todo el Catálogo", "Nuevos Cursos Agregados Mensualmente", "Soporte VIP Directo por WhatsApp", "Descarga de Plantillas y Material de Trabajo", "Acceso a Masterclasses en Vivo"]', true),
                'visual_style' => 'pro',
            ]
        );
        Membership::updateOrCreate(
            ['name' => 'VIP Prestige'],
            [
                'price_monthly' => 99,
                'price_yearly' => 799,
                'benefits' => json_decode('["Acceso a la Bóveda de Cursos Premium VIP", "1 Videollamada de Mentoría 1-a-1 al mes", "Soporte e Intermediación Prioritaria", "Revisión Privada de Proyectos", "Insignia de Estatus VIP en tu Perfil"]', true),
                'visual_style' => 'vip',
            ]
        );

        // 4. Seeding Coupons
        Coupon::updateOrCreate(['code' => 'ACCION'], ['discount_percentage' => 50, 'status' => 'active']);

        // 5. Seeding Courses and Sub-elements
        $course_0 = Course::updateOrCreate(
            ['title' => 'importacion experta'],
            [
                'category_id' => $cat_1->id,
                'description' => 'domina e mundo de la importacion y no pierdas la oportunidad de aprovechar esta oferta',
                'short_description' => 'domina e mundo de la importacion y no pierdas la oportunidad de aprovechar esta oferta',
                'duration' => '10 horas',
                'flyer_path' => 'https://i.ibb.co/MFJcw9q/IMG-0236.png',
                'price' => 100,
                'required_membership' => 'regular',
                'status' => 'active'
            ]
        );
        $mod_0_0 = Module::updateOrCreate(
            ['course_id' => $course_0->id, 'title' => 'introduccion'],
            ['order_index' => 0]
        );
        Lesson::updateOrCreate(
            ['module_id' => $mod_0_0->id, 'title' => 'hola nuevamenre'],
            [
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'description' => 'hola este es el nuevo mundo y quiero que lo conozcas',
                'resources' => json_decode('[]', true),
                'order_index' => 0
            ]
        );
        Lesson::updateOrCreate(
            ['module_id' => $mod_0_0->id, 'title' => 'hola de nuevo 2'],
            [
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'description' => 'mira esta vez es la definitva',
                'resources' => json_decode('[]', true),
                'order_index' => 1
            ]
        );
        $mod_0_1 = Module::updateOrCreate(
            ['course_id' => $course_0->id, 'title' => 'primer paso'],
            ['order_index' => 1]
        );
        Lesson::updateOrCreate(
            ['module_id' => $mod_0_1->id, 'title' => 'una verdadera locura'],
            [
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'description' => 'eso vale lo estas logrando',
                'resources' => json_decode('[]', true),
                'order_index' => 0
            ]
        );
        Quiz::updateOrCreate(
            ['module_id' => $mod_0_1->id, 'question' => 'quien es el mejor'],
            [
                'type' => 'seleccion',
                'options' => json_decode('{"A": "samuel", "C": "enrique", "B": "colmenares", "D": "perez"}', true),
                'correct_answer' => 'A',
                'order_index' => 0
            ]
        );
        Quiz::updateOrCreate(
            ['module_id' => $mod_0_1->id, 'question' => 'esooo'],
            [
                'type' => 'verdadero_falso',
                'options' => json_decode('{}', true),
                'correct_answer' => 'Verdadero',
                'order_index' => 1
            ]
        );

        // 6. Seeding Default Settings
        Setting::setVal('tasas', ['euroBCV' => 36.50]);
        Setting::setVal('landing_metrics', [
            'alumnos' => '1,250+',
            'exito' => '98%',
            'paises' => '15+'
        ]);
        Setting::setVal('total_ventas_simuladas', 12450.00);
        Setting::setVal('contador_general', ['visitasTotales' => 1250]);
    }
}