<?php namespace Acme\Users\Updates;

use Seeder;
use Vork\Kurser\Models\Bestilling;
use Vork\Kurser\Models\Gruppe;
use Vork\Kurser\Models\Kasse;
use Vork\Kurser\Models\Kursus;
use Vork\Kurser\Models\Materiale;
use Vork\Kurser\Models\Omraade;
use Vork\Kurser\Models\Plads;

class SeedUsersTable extends Seeder
{
    public function run()
    {
        $kursus = Kursus::create([
            'aar' => '2017',
            'slags' => 'Påske',
            'start' => '2017-04-12',
            'slut' => '2017-04-17'
        ]);

        $gruppe = Gruppe::create([
            'kursus_id' => $kursus->id,
            'navn' => 'Fællesfolk',
            'nummer' => '12'
        ]);

        $gruppe->users()->attach(1, ['type' => 'FPF']);

        $omraade = Omraade::create([
            'navn' => 'Halle'
        ]);

        $plads = Plads::create([
            'navn' => 'H1',
            'omraade_id' => $omraade->id
        ]);

        $kasse = Kasse::create([
            'navn' => 'Gaskassen',
            'plads_id' => $plads->id
        ]);

        $materiale = Materiale::create([
            'navn' => 'Gas',
            'enhed' => 'flasker',
            'skaffe' => false,
            'forbrug' => true
        ]);

        $materiale2 = Materiale::create([
            'navn' => 'Tændstikker',
            'enhed' => 'æsker',
            'skaffe' => false,
            'forbrug' => true
        ]);

        $materiale->kasser()->attach($kasse->id, ['antal' => '4']);
        $materiale2->kasser()->attach($kasse->id, ['antal' => '1']);

        $bestilling = Bestilling::create([
            'gruppe_id' => $gruppe->id,
            'user_id' => 1,
            'materiale_id' => $materiale->id,
            'antal' => 1,
            'start' => '2017-02-17 18:00',
            'slut' => '2017-02-19 13:30',
            'kommentar_user' => 'test',
            'medbringer' => false
        ]);
    }
}