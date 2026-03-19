<?php

namespace Database\Seeders;

use App\Models\Regulation;
use Illuminate\Database\Seeder;

class RegulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regulations = [
            // NOMs de Seguridad y Salud en el Trabajo
            [
                'code' => 'NOM-035-STPS-2018',
                'name' => 'NOM-035-STPS-2018 - Factores de riesgo psicosocial en el trabajo',
                'description' => 'Identificación, análisis y prevención de factores de riesgo psicosocial',
                'type' => 'NOM-STPS',
                'authority' => 'STPS',
                'scope' => 'Federal',
                'effective_date' => '2019-10-23',
                'url' => 'https://www.gob.mx/stps/documentos/norma-oficial-mexicana-nom-035-stps-2018',
            ],
            [
                'code' => 'NOM-030-STPS-2009',
                'name' => 'NOM-030-STPS-2009 - Servicios preventivos de seguridad y salud en el trabajo',
                'description' => ' Servicios preventivos de seguridad y salud en el trabajo',
                'type' => 'NOM-STPS',
                'authority' => 'STPS',
                'scope' => 'Federal',
                'effective_date' => '2010-08-10',
                'url' => null,
            ],
            [
                'code' => 'NOM-001-STPS-2008',
                'name' => 'NOM-001-STPS-2008 - Edificios, locales, instalaciones y áreas en los centros de trabajo',
                'description' => 'Condiciones de seguridad en edificaciones',
                'type' => 'NOM-STPS',
                'authority' => 'STPS',
                'scope' => 'Federal',
                'effective_date' => '2009-01-14',
                'url' => null,
            ],
            [
                'code' => 'NOM-002-STPS-2010',
                'name' => 'NOM-002-STPS-2010 - Condiciones de seguridad-Prevención y protección contra incendios en los centros de trabajo',
                'description' => 'Prevención y protección contra incendios',
                'type' => 'NOM-STPS',
                'authority' => 'STPS',
                'scope' => 'Federal',
                'effective_date' => '2011-05-13',
                'url' => null,
            ],
            [
                'code' => 'NOM-009-STPS-2011',
                'name' => 'NOM-009-STPS-2011 - Actividades de capacitación',
                'description' => 'Actividades de capacitación en seguridad y salud en el trabajo',
                'type' => 'NOM-STPS',
                'authority' => 'STPS',
                'scope' => 'Federal',
                'effective_date' => '2012-05-11',
                'url' => null,
            ],
            [
                'code' => 'NOM-019-STPS-2011',
                'name' => 'NOM-019-STPS-2011 - Constitución, integración, organización y funcionamiento de las comisiones de seguridad e higiene',
                'description' => 'Comisiones de seguridad e higiene',
                'type' => 'NOM-STPS',
                'authority' => 'STPS',
                'scope' => 'Federal',
                'effective_date' => '2012-06-08',
                'url' => null,
            ],
            [
                'code' => 'NOM-024-STPS-2008',
                'name' => 'NOM-024-STPS-2008 - Señales y/devoluciones de seguridad e higiene en los centros de trabajo',
                'description' => 'Señales y/devoluciones de seguridad',
                'type' => 'NOM-STPS',
                'authority' => 'STPS',
                'scope' => 'Federal',
                'effective_date' => '2009-06-05',
                'url' => null,
            ],
            [
                'code' => 'NOM-031-STPS-2011',
                'name' => 'NOM-031-STPS-2011 - Construcción, especificaciones, funcionalidad y mantenimiento de梦见',
                'description' => '梦见 de seguridad',
                'type' => 'NOM-STPS',
                'authority' => 'STPS',
                'scope' => 'Federal',
                'effective_date' => '2012-08-24',
                'url' => null,
            ],
        ];

        foreach ($regulations as $regulation) {
            Regulation::updateOrCreate(
                ['code' => $regulation['code']],
                $regulation
            );
        }
    }
}
