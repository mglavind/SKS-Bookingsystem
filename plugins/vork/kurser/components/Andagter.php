<?php namespace Vork\Kurser\Components;

use Cms\Classes\ComponentBase;
use October\Rain\Support\Facades\Flash;
use Vork\Kurser\Models\Kasse;
use Vork\Kurser\Models\Andagt;
use Vork\Kurser\Models\Plads;

class Andagter extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Andagter Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        if($this->param('andagt') != ''){
            $this->andagt = $this->page->andagt = Andagt::findOrFail($this->param('andagt'));
        } else {
            // $this->andagter = $this->page->andagter = Andagt::all();
        }
    }

    public function onAddAndagt()
    {
        $andagt = new Andagt();
        $andagt->navn = post('navn');
        $andagt->sortering = post('sortering');
        $andagt->kursus_id = post('kursus_id');
        $andagt->tovholder = '';
        $andagt->medlemmer = '';
        $andagt->sted = '';
        $andagt->musik = '';
        $andagt->koekken = '';
        $andagt->teknik = '';
        $andagt->materialer = '';
        $andagt->beskrivelse = '';
        $andagt->save();
        
        $this->andagter = $this->page->andagter = Andagt::where('kursus_id', '=', $andagt->kursus_id)
        ->orderBy('sortering', 'asc')
        ->get();

        Flash::success('Andagten er oprettet!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }

    public function onUpdateAndagt()
    {
        $andagt = Andagt::find(post('id'));
        $andagt->navn = post('navn');
        $andagt->sortering = post('sortering');
        $andagt->tovholder = post('tovholder');
        $andagt->save();

        $this->andagter = $this->page->andagter = Andagt::where('kursus_id', '=', $andagt->kursus_id)
        ->orderBy('sortering', 'asc')
        ->get();
        
        Flash::success('Andagten er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }

    public function onUpdateAndagtLarge()
    {
        $andagt = Andagt::find(post('id'));
        $andagt->tovholder = post('tovholder');
        $andagt->medlemmer = post('medlemmer');
        $andagt->sted = post('sted');
        $andagt->musik = post('musik');
        $andagt->koekken = post('koekken');
        $andagt->teknik = post('teknik');
        $andagt->materialer = post('materialer');
        $andagt->beskrivelse = post('beskrivelse');
        $andagt->save();

        $this->andagt = $this->page->andagt = $andagt;
        
        Flash::success('Andagten er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }


    public function onDeleteAndagt()
    {
        $andagt = Andagt::find(post('id'));
        $andagt->delete();

        Flash::success('Måltidet er slettet!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }
}
