<?php namespace Vork\Kurser\Components;

use Cms\Classes\ComponentBase;
use October\Rain\Support\Facades\Flash;
use Vork\Kurser\Models\Kasse;
use Vork\Kurser\Models\Maaltid;
use Vork\Kurser\Models\Plads;

class Maaltider extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Maaltider Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        if($this->param('maaltid') != ''){
            $this->maaltid = $this->page->maaltid = Maaltid::findOrFail($this->param('maaltid'));
        } else {
            $this->maaltider = $this->page->maaltider = Maaltid::all();
        }
    }

    public function onAddMaaltid()
    {
        $maaltid = new Maaltid();
        $maaltid->navn = post('navn');
        $maaltid->sortering = post('sortering');
        $maaltid->kursus_id = post('kursus_id');
        $maaltid->save();

        Flash::success('Måltidet er oprettet!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }

    public function onUpdateMaaltid()
    {
        $maaltid = Maaltid::find(post('id'));
        $maaltid->navn = post('navn');
        $maaltid->sortering = post('sortering');
        $maaltid->save();

        $this->maaltider = $this->page->maaltider = Maaltid::all();

        Flash::success('Måltidet er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }


    public function onDeleteMaaltid()
    {
        $maaltid = Maaltid::find(post('id'));
        $maaltid->delete();

        Flash::success('Måltidet er slettet!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }
}
