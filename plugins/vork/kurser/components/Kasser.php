<?php namespace Vork\Kurser\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Support\Facades\Flash;
use Vork\Kurser\Models\Kasse;
use Vork\Kurser\Models\Materiale;

class Kasser extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Kasser Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        if($this->param('kasse') != ''){
            $this->kasse = $this->page->kasse = Kasse::findOrFail($this->param('kasse'));
        } else {
            $this->kasser = $this->page->kasser = Kasse::with('materialer')->get();
        }
    }

    public function onAddKasse()
    {
        $kasse = new Kasse();
        $kasse->navn = post('navn');
        $kasse->plads_id = post('plads_id');
        $kasse->save();

        Flash::success('Kassen er oprettet!');
        return Redirect::to('pladser/' . post('plads_id'));
    }

    public function onUpdateKasse()
    {
        $kasse = Kasse::findOrFail(post('id'));
        $kasse->navn = post('navn');
        $kasse->save();

        $this->kasser = $this->page['kasser'] = Kasse::all();

        Flash::success('Kassen er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }


    public function onDeleteKasse()
    {
        $kasse = Kasse::findOrFail(post('id'));
        $kasse->delete();

        $this->plads = $this->page->plads = $kasse->plads;
        $this->kasser = $this->page->kasser = Kasse::all();

        Flash::success('Kassen er slettet!');
        return Redirect::to('pladser/' . post('plads_id'));
    }


    public function onAddMateriale()
    {
        $kasse = Kasse::findOrFail(post('kasse_id'));
        $kasse->materialer()->attach(post('materiale_id'), ['antal' => post('antal')]);

        $this->kasse = $this->page->kasse = $kasse;
        $this->materiale = $this->page->materiale = Materiale::findOrFail(post('materiale_id'));

        Flash::success('Materialet er tilføjet til kassen!');
        if(post('goto')=='kasser'){
            return Redirect::to('kasser/' . post('kasse_id'));
        } elseif(post('goto')=='materialer'){
            return Redirect::to('materialer/' . post('materiale_id'));
        } else {
            return ['#flash_alert' => $this->renderPartial('flash')];
        }
    }


    public function onDeleteMateriale()
    {
        $kasse = Kasse::findOrFail(post('kasse_id'));
        $kasse->materialer()->detach(post('materiale_id'));

        $this->kasse = $this->page->kasse = $kasse;
        $this->materiale = $this->page->materiale = Materiale::findOrFail(post('materiale_id'));

        Flash::success('Materialet er fjernet fra kassen!');
        if(post('goto')=='kasser'){
            return Redirect::to('kasser/' . post('kasse_id'));
        } elseif(post('goto')=='materialer'){
            return Redirect::to('materialer/' . post('materiale_id'));
        } else {
            return ['#flash_alert' => $this->renderPartial('flash')];
        }
    }

    public function onUpdateMaterialeAntal()
    {
        DB::table('vork_kurser_forbindelser')->where('materiale_id', post('materiale_id'))->where('kasse_id',post('kasse_id'))->update(array('antal' => post('antal')));

        Flash::success('Antallet er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }
}
