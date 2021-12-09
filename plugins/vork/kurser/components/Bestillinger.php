<?php namespace Vork\Kurser\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\DB;
use October\Rain\Support\Facades\Flash;
use Vork\Kurser\Models\Bestilling;
use Vork\Kurser\Models\Materiale;

class Bestillinger extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Bestillinger Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
    }

    public function onAddBestilling()
    {

        $bestilling = new Bestilling();
        $bestilling->gruppe_id = post('gruppe_id');
        $bestilling->user_id = post('user_id');
        if(post('materiale_id')==''){
            $materiale = new Materiale();
            $materiale->navn = post('materiale_navn');
            $materiale->enhed = 'stk';
            $materiale->skaffe = 1;
            $materiale->save();

            $bestilling->materiale_id = $materiale->id;
        } else {
            $bestilling->materiale_id = post('materiale_id');
        }
        $bestilling->antal = post('antal');
        $bestilling->start = post('start');
        $bestilling->slut = post('slut');
        $bestilling->kommentar_user = post('kommentar_user');
        $bestilling->medbringer = post('medbringer');
        $bestilling->save();

        $this->bestillinger = $this->page->bestillinger = Bestilling::all();
        $this->gruppe = $this->page->gruppe = $bestilling->gruppe;

        Flash::success('Bestillingen er oprettet!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }

    public function onUpdateBestilling()
    {
        $bestilling = Bestilling::findOrFail(post('id'));
        $bestilling->antal = post('antal');
        $bestilling->start = post('start');
        $bestilling->slut = post('slut');
        $bestilling->kommentar_user = post('kommentar_user');
        $bestilling->medbringer = post('medbringer');
//        $bestilling->godkendt = 0;
        $bestilling->save();

        $this->bestillinger = $this->page->bestillinger = Bestilling::all();
        $this->gruppe = $this->page->gruppe = $bestilling->gruppe;

        Flash::success('Bestillingen er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }

    public function onBestillingUpdateKommentar()
    {
        $bestilling = Bestilling::findOrFail(post('id'));
        $bestilling->kommentar_smedene = post('kommentar_smedene');
        $bestilling->save();

        Flash::success('Bestillingen er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }

    public function onBestillingUpdateGodkendt()
    {
        $bestilling = Bestilling::findOrFail(post('id'));
        $bestilling->godkendt = post('godkendt');
        $bestilling->save();

        Flash::success('Bestillingen er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }

    public function onBestillingUpdateMateriale()
    {
        $bestilling = Bestilling::findOrFail(post('id'));
        $bestilling->materiale_id = post('materiale_id');
        $bestilling->save();

        Flash::success('Bestillingen er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }


    public function onDeleteBestilling()
    {
        $bestilling = Bestilling::findOrFail(post('id'));
        $bestilling->delete();

        $this->bestillinger = $this->page->bestillinger = Bestilling::all();
        $this->gruppe = $this->page->gruppe = $bestilling->gruppe;

        Flash::success('Bestillingen er slettet!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }


    public function onBestillingerAutoGodkend()
    {
        $bestillinger = Bestilling::join('vork_kurser_grupper', 'vork_kurser_grupper.id', '=', 'vork_kurser_bestillinger.gruppe_id')
            ->join('vork_kurser_kurser', 'vork_kurser_kurser.id', '=', 'vork_kurser_grupper.kursus_id')
            ->leftJoin('vork_kurser_materialer', 'vork_kurser_materialer.id', '=', 'vork_kurser_bestillinger.materiale_id')
            ->leftJoin('vork_kurser_forbindelser', 'vork_kurser_forbindelser.materiale_id', '=', 'vork_kurser_materialer.id')
            ->where('kursus_id', '=', post('kursus'))
            ->where('medbringer', '=', 0)
            ->select('vork_kurser_bestillinger.*', Db::raw('SUM(vork_kurser_forbindelser.antal) as antal2'))
            ->orderBy('materiale_id', 'asc')
            ->groupBy('vork_kurser_bestillinger.id')
            ->get();

        $bestilte = array();
        foreach($bestillinger AS $bestilling){
            if(!isset($bestilte[$bestilling['materiale_id']])){
                $bestilte[$bestilling['materiale_id']] = array("bestilt" => $bestilling['antal'], "lager" => $bestilling['antal2']);
            } else {
                $bestilte[$bestilling['materiale_id']]['bestilt'] = $bestilte[$bestilling['materiale_id']]['bestilt'] + $bestilling['antal'];
            }
        }

        foreach($bestillinger AS $key => $bestilling){
            if(($bestilte[$bestilling['materiale_id']]['lager']-$bestilte[$bestilling['materiale_id']]['bestilt']) >= 0){
                $bestilling->godkendt = 1;
                $bestilling->save();
            }
        }

        Flash::success('Auto-godkend er færdig!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }
}
