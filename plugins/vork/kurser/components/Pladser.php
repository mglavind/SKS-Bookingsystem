<?php namespace Vork\Kurser\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Support\Facades\Flash;
use Vork\Kurser\Models\Kasse;
use Vork\Kurser\Models\Plads;

class Pladser extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Pladser Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        if($this->param('plads') != ''){
            $this->plads = $this->page->plads = Plads::findOrFail($this->param('plads'));
        } else {
            $this->pladser = $this->page->pladser = Plads::all();
        }
    }

    public function onAddPlads()
    {
        $plads = new Plads();
        $plads->navn = post('navn');
        $plads->omraade_id = post('omraade_id');
        $plads->save();

        Flash::success('Pladsen er oprettet!');
        return Redirect::to('omraader/' . post('omraade_id'));
    }

    public function onUpdatePlads()
    {
        $plads = Plads::find(post('id'));
        $plads->navn = post('navn');
        $plads->save();

        $this->pladser = $this->page->pladser = Plads::all();

        Flash::success('Pladsen er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }


    public function onDeletePlads()
    {
        $plads = Plads::find(post('id'));
        $plads->delete();

        Flash::success('Pladsen er slettet!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }
}
