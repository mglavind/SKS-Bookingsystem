<?php namespace Vork\Kurser\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Support\Facades\Flash;
use Vork\Kurser\Models\Omraade;
use Vork\Kurser\Models\Plads;

class Omraader extends ComponentBase
{
    public $omraader;
    public $omraade;
    public $pladser;

    public function componentDetails()
    {
        return [
            'name'        => 'Omraader Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        if($this->param('omraade') != ''){
            $this->omraade = $this->page->omraade = Omraade::find($this->param('omraade'));
        } else {
            $this->omraader = $this->page->omraader = Omraade::all();
        }
    }

    public function onAddOmraade()
    {
        $omraade = new Omraade();
        $omraade->navn = post('navn');
        $omraade->save();

        Flash::success('Området er oprettet!');
        return Redirect::to('omraader');
    }

    public function onUpdateOmraade()
    {
        $omraade = Omraade::find(post('id'));
        $omraade->navn = post('navn');
        $omraade->save();

        $this->omraader = $this->page->omraader = Omraade::all();

        Flash::success('Området er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }


    public function onDeleteOmraade()
    {
        $omraade = Omraade::find(post('id'));
        $omraade->delete();

        $this->omraader = $this->page->omraader = Omraade::all();

        Flash::success('Området er slettet!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }
}
