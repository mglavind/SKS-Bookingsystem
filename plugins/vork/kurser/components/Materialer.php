<?php namespace Vork\Kurser\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use October\Rain\Support\Facades\Flash;
use Vork\Kurser\Models\Materiale;

class Materialer extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Materialer Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        if($this->param('materiale') != ''){
            $this->materiale = $this->page->materiale = Materiale::findOrFail($this->param('materiale'));
        } else {
            $this->materialer = $this->page->materialer = Materiale::orderBy('navn')->get();
        }
    }

    public function onAddMateriale()
    {
        $materiale = new Materiale();
        $materiale->navn = post('navn');
        $materiale->enhed = post('enhed');
        $materiale->skaffe = post('skaffe');
        $materiale->forbrug = post('forbrug');
        $materiale->save();

        Flash::success('Materialet er oprettet!');
        return Redirect::to('materialer');
    }

    public function onUpdateMateriale()
    {
        $materiale = Materiale::findOrFail(post('id'));
        $materiale->navn = post('navn');
        $materiale->enhed = post('enhed');
        $materiale->skaffe = post('skaffe');
        $materiale->forbrug = post('forbrug');
        $materiale->save();

        Flash::success('Materialet er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }


    public function onDeleteMateriale()
    {
        $materiale = Materiale::findOrFail(post('id'));
        $materiale->delete();

        $this->materialer = $this->page->materialer = Materiale::orderBy('navn')->get();

        Flash::success('Materialet er slettet!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }


    public function onSearch()
    {
        $this->soeg = $this->page->soeg = Materiale::where('navn', 'like', '%' . post('materiale') . '%')->orderBy('navn')->get();
    }
}
