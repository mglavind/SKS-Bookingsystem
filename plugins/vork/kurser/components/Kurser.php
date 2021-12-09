<?php namespace Vork\Kurser\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Support\Facades\Flash;
use Vork\Kurser\Models;

class Kurser extends ComponentBase
{
    public $kurser;
    public $kursus;
    public $grupper;
    public $bestillinger;
    public $bestilte;

    public function componentDetails()
    {
        return [
            'name'        => 'Kurser Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function index()
    {
    }

    public function onRun()
    {
        if($this->param('kursus') != ''){
            $this->kursus = $this->page->kursus = Models\Kursus::find($this->param('kursus'));

            $this->todos = $this->page->todos = Models\Todo::join('vork_kurser_grupper', 'vork_kurser_grupper.id', '=', 'vork_kurser_todo.gruppe_id')
                ->where('kursus_id', '=', $this->kursus->id)
                ->select(Db::raw('GROUP_CONCAT(vork_kurser_todo.id) as ids'),'vork_kurser_todo.tekst', 'vork_kurser_todo.color', 'vork_kurser_todo.deadline', 'vork_kurser_todo.color', Db::raw('COUNT(*) as antal'), Db::raw('sum(case when done = 1 then 1 else 0 end) as antal_done'), Db::raw('GROUP_CONCAT(case when done = 1 then CONCAT(" ",vork_kurser_grupper.nummer," ",vork_kurser_grupper.navn) end) as grupper_done'), Db::raw('GROUP_CONCAT(case when done = 0 then CONCAT(" ",vork_kurser_grupper.nummer," ",vork_kurser_grupper.navn) end) as grupper_not_done'))
                ->where('vork_kurser_todo.faelles', '=', 1)
                ->groupBy('vork_kurser_todo.tekst')
                ->get();
                
            $this->andagter = $this->page->andagter = Models\Andagt::where('kursus_id', '=', $this->kursus->id)
                ->orderBy('sortering', 'asc')
                ->get();

            $this->bestillinger = $this->page->bestillinger = Models\Bestilling::join('vork_kurser_grupper', 'vork_kurser_grupper.id', '=', 'vork_kurser_bestillinger.gruppe_id')
                ->join('vork_kurser_kurser', 'vork_kurser_kurser.id', '=', 'vork_kurser_grupper.kursus_id')
                ->leftJoin('vork_kurser_materialer', 'vork_kurser_materialer.id', '=', 'vork_kurser_bestillinger.materiale_id')
                ->leftJoin('vork_kurser_forbindelser', 'vork_kurser_forbindelser.materiale_id', '=', 'vork_kurser_materialer.id')
                ->where('kursus_id', '=', $this->kursus->id)
                ->where('medbringer', '=', 0)
                ->select('vork_kurser_bestillinger.*', Db::raw('SUM(vork_kurser_forbindelser.antal) as antal2'))
                ->orderBy('materiale_id', 'asc')
                ->groupBy('vork_kurser_bestillinger.id')
                ->get();

            $this->indkoeb = $this->page->indkoeb = Models\Materiale::join('vork_kurser_bestillinger', 'vork_kurser_materialer.id', '=', 'vork_kurser_bestillinger.materiale_id')
                ->leftJoin('vork_kurser_grupper', 'vork_kurser_grupper.id', '=', 'vork_kurser_bestillinger.gruppe_id')
                ->join('vork_kurser_kurser', 'vork_kurser_kurser.id', '=', 'vork_kurser_grupper.kursus_id')
                ->leftJoin('vork_kurser_forbindelser', 'vork_kurser_forbindelser.materiale_id', '=', 'vork_kurser_materialer.id')
                ->where('kursus_id', '=', $this->kursus->id)
                ->where('medbringer', '=', 0)
                ->where('godkendt', '=', 2)
                ->select('vork_kurser_materialer.*', Db::raw('vork_kurser_forbindelser.antal as lager'), Db::raw('SUM(vork_kurser_bestillinger.antal) as bestilt'))
                ->orderBy('vork_kurser_materialer.navn', 'asc')
                ->groupBy('vork_kurser_materialer.id')
                ->get();

            $this->skaffe = $this->page->skaffe = Models\Materiale::join('vork_kurser_bestillinger', 'vork_kurser_materialer.id', '=', 'vork_kurser_bestillinger.materiale_id')
                ->leftJoin('vork_kurser_grupper', 'vork_kurser_grupper.id', '=', 'vork_kurser_bestillinger.gruppe_id')
                ->join('vork_kurser_kurser', 'vork_kurser_kurser.id', '=', 'vork_kurser_grupper.kursus_id')
                ->leftJoin('vork_kurser_forbindelser', 'vork_kurser_forbindelser.materiale_id', '=', 'vork_kurser_materialer.id')
                ->leftJoin('users', 'vork_kurser_bestillinger.user_id', '=', 'users.id')
                ->where('kursus_id', '=', $this->kursus->id)
                ->where('medbringer', '=', 0)
                ->where('godkendt', '=', 3)
                ->select('vork_kurser_materialer.*', Db::raw('vork_kurser_forbindelser.antal AS lager'), Db::raw('SUM(vork_kurser_bestillinger.antal) AS bestilt'), Db::raw('GROUP_CONCAT(vork_kurser_grupper.nummer, " ", users.name SEPARATOR ", ") AS grupper'))
                ->orderBy('vork_kurser_materialer.navn', 'asc')
                ->groupBy('vork_kurser_materialer.id')
                ->get();

            $maaltidsvar_temp = Models\Maaltid::leftJoin('vork_kurser_maaltider_svar', 'vork_kurser_maaltider.id', '=', 'vork_kurser_maaltider_svar.maaltid_id')
                ->leftJoin('vork_kurser_grupper', 'vork_kurser_grupper.id', '=', 'vork_kurser_maaltider_svar.gruppe_id')
                ->select('vork_kurser_maaltider.id', 'vork_kurser_maaltider.navn AS maaltid', 'vork_kurser_maaltider_svar.svar', 'vork_kurser_grupper.nummer', 'vork_kurser_grupper.navn AS gruppe', 'vork_kurser_maaltider.sortering')
                ->where('vork_kurser_maaltider.kursus_id', '=', $this->kursus->id)
                ->orderBy('vork_kurser_maaltider.sortering', 'asc')
                ->orderBy('vork_kurser_grupper.nummer', 'asc')
                ->get();

            $maaltidsvar = array();
            foreach($this->kursus->maaltider AS $maaltid) {
                foreach($this->kursus->grupper AS $gruppe) {
                    if($gruppe['nummer'] != 30) {
                        $maaltidsvar[$maaltid['navn']][$gruppe['nummer']] = '';
                    }
                }
            }

            foreach($maaltidsvar_temp AS $svar) {
                $maaltidsvar[$svar['maaltid']][$svar['nummer']] = $svar['svar'];
            }

            $this->maaltidsvar = $this->page->maaltidsvar = $maaltidsvar;

            $bestilte = array();
            foreach($this->bestillinger AS $bestilling){
                if(!isset($bestilte[$bestilling['materiale_id']])){
                    $bestilte[$bestilling['materiale_id']] = array("bestilt" => $bestilling['antal'], "lager" => $bestilling['antal2']);
                } else {
                    $bestilte[$bestilling['materiale_id']]['bestilt'] = $bestilte[$bestilling['materiale_id']]['bestilt'] + $bestilling['antal'];
                }
            }
            $this->bestilte = $this->page->bestilte = $bestilte;
        } else {
            $this->kurser = $this->page->kurser = Models\Kursus::orderBy('aar', 'desc')->orderBy('slags', 'desc')->get();
        }
    }

    public function onAddKursus()
    {
        $kursus = new Models\Kursus();
        $kursus->aar = post('aar');
        $kursus->slags = post('slags');
        $kursus->start = post('start');
        $kursus->slut = post('slut');
        $kursus->save();

        for($i = 1; $i <= 8;$i++){
            $gruppe = new Models\Gruppe();
            $gruppe->navn = 'Gruppe ' . $i;
            $gruppe->nummer = $i;
            $gruppe->kursus_id = $kursus->id;
            $gruppe->save();
        }

        $gruppe = new Models\Gruppe();
        $gruppe->navn = 'Køkken';
        $gruppe->nummer = '11';
        $gruppe->kursus_id = $kursus->id;
        $gruppe->save();

        $gruppe = new Models\Gruppe();
        $gruppe->navn = 'Fællesfolk';
        $gruppe->nummer = '12';
        $gruppe->kursus_id = $kursus->id;
        $gruppe->save();


        $gruppe = new Models\Gruppe();
        $gruppe->navn = 'Fællesfolk';
        $gruppe->nummer = '13';
        $gruppe->kursus_id = $kursus->id;
        $gruppe->save();

        $gruppe = new Models\Gruppe();
        $gruppe->navn = 'Fællesprogram';
        $gruppe->nummer = '30';
        $gruppe->kursus_id = $kursus->id;
        $gruppe->save();

        $bookingsted = new Models\Bookable();
        $bookingsted->navn = "Salen";
        $bookingsted->color = "#78e151";
        $bookingsted->faelles = false;
        $bookingsted->overlap = false;
        $bookingsted->kursus_id = $kursus->id;
        $bookingsted->save();

        $bookingsted = new Models\Bookable();
        $bookingsted->navn = "Foto";
        $bookingsted->color = "#FF1234";
        $bookingsted->faelles = false;
        $bookingsted->overlap = false;
        $bookingsted->kursus_id = $kursus->id;
        $bookingsted->save();

        $bookingsted = new Models\Bookable();
        $bookingsted->navn = "Inde grupperum";
        $bookingsted->color = "#888888";
        $bookingsted->faelles = false;
        $bookingsted->overlap = false;
        $bookingsted->kursus_id = $kursus->id;
        $bookingsted->save();

        $bookingsted = new Models\Bookable();
        $bookingsted->navn = "Fællesprogram";
        $bookingsted->color = "#5192d2";
        $bookingsted->faelles = true;
        $bookingsted->overlap = false;
        $bookingsted->kursus_id = $kursus->id;
        $bookingsted->save();

        $bookingsted = new Models\Bookable();
        $bookingsted->navn = "Smedene";
        $bookingsted->color = "#f596c8";
        $bookingsted->faelles = false;
        $bookingsted->overlap = true;
        $bookingsted->kursus_id = $kursus->id;
        $bookingsted->save();

        $bookingsted = new Models\Bookable();
        $bookingsted->navn = "Spisesalen";
        $bookingsted->color = "#008000";
        $bookingsted->faelles = false;
        $bookingsted->overlap = false;
        $bookingsted->kursus_id = $kursus->id;
        $bookingsted->save();

        $sort = 1;
        $ugedag = array("", "Mandag", "Tirsdag", "Onsdag", "Torsdag", "Fredag", "Lørdag", "Søndag");
        $maaltider = array('morgen', 'middag', 'aften');
        $startTime = strtotime( post('start') );
        $endTime = strtotime( post('slut') );

        // Loop between timestamps, 24 hours at a time
        for ( $i = $startTime+86400; $i < $endTime; $i = $i + 86400 ) {
            foreach($maaltider AS $maaltid){
                $m = new Models\Maaltid();
                $m->navn = $ugedag[date("N", $i)] . " " . $maaltid;
                $m->sortering = $sort;
                $m->kursus_id = $kursus->id;
                $m->save();

                $sort++;
            }
        }

        Flash::success('Kurset er oprettet!');
        return Redirect::to('kurser');
    }

    public function onUpdateKursus()
    {
        $kursus = Models\Kursus::find(post('id'));
        $kursus->aar = post('aar');
        $kursus->slags = post('slags');
        $kursus->start = post('start');
        $kursus->slut = post('slut');
        $kursus->save();

        $this->kurser = $this->page->kurser = Models\Kursus::all();

        Flash::success('Kurset er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }

    public function onDeleteKursus()
    {
        $kursus = Models\Kursus::find(post('id'));
        $kursus->delete();

        $this->kurser = $this->page->kurser = Models\Kursus::all();

        Flash::success('Kurset er slettet!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }
}
