<?php namespace Vork\Kurser\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\DB;
use October\Rain\Support\Facades\Flash;
use Vork\Kurser\Models\Bestilling;
use Vork\Kurser\Models\Bookable;
use Vork\Kurser\Models\Booking;
use Vork\Kurser\Models\Kasse;
use Vork\Kurser\Models\Maaltid;
use Vork\Kurser\Models\Plads;

class Bookables extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Bookable Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        if($this->param('bookable') != ''){
            $this->bookable = $this->page->bookable = Bookable::findOrFail($this->param('bookable'));
//            $this->bookinger = $this->page->bookinger = Bookable::join('vork_kurser_bookinger', function ($join) {
//                $join->on('vork_kurser_bookable.id', '=', 'vork_kurser_bookinger.bookable_id')
//                    ->where('vork_kurser_bookable.id', '=', $this->param('bookable'));
//                })
//                ->join('vork_kurser_grupper', 'vork_kurser_bookinger.gruppe_id', '=', 'vork_kurser_grupper.id')
//                ->select('vork_kurser_bookinger.id', 'vork_kurser_bookinger.start', 'vork_kurser_bookinger.slut as end', 'vork_kurser_bookable.color', DB::raw('CONCAT(vork_kurser_grupper.nummer," ",vork_kurser_grupper.navn,"\n",vork_kurser_bookinger.kommentar) as title'))
//                ->get();
            $this->bookinger = $this->page->bookinger = Booking::where(function ($query) {
                $query->where('vork_kurser_bookinger.bookable_id', '=', $this->param('bookable'))
                    ->orWhere('vork_kurser_bookable.faelles', '=', 1);
                })->join('vork_kurser_bookable', 'vork_kurser_bookable.id', '=', 'vork_kurser_bookinger.bookable_id')
                ->join('vork_kurser_grupper', 'vork_kurser_bookinger.gruppe_id', '=', 'vork_kurser_grupper.id')
                ->select('vork_kurser_bookinger.id', 'vork_kurser_bookinger.start', 'vork_kurser_bookinger.slut as end', 'vork_kurser_bookable.color', DB::raw('CONCAT(vork_kurser_grupper.nummer," ",vork_kurser_grupper.navn,"\n",vork_kurser_bookinger.kommentar) as title'), DB::raw('IF(bookable_id = ' . $this->param('bookable') . ', "", "background") as rendering'), DB::raw('true as editable'))
                ->get();
        } elseif($this->param('gruppe') != ''){
//            $this->bookinger = $this->page->bookinger = Booking::join('vork_kurser_bookable', 'vork_kurser_bookable.id', '=', 'vork_kurser_bookinger.bookable_id')
//                ->join('vork_kurser_grupper', 'vork_kurser_bookinger.gruppe_id', '=', 'vork_kurser_grupper.id')
//                ->select('vork_kurser_bookinger.id', 'vork_kurser_bookinger.start', 'vork_kurser_bookinger.slut as end', DB::raw('IF(gruppe_id = ' . $this->param('gruppe') . ', true, false) as editable'), 'vork_kurser_bookable.color', DB::raw('CONCAT(vork_kurser_grupper.nummer," ",vork_kurser_grupper.navn,"\n",vork_kurser_bookinger.kommentar) as title'))
//                ->get();
//            $this->bookinger = $this->page->bookinger = Booking::where('vork_kurser_bookinger.bookable_id', '=', '1')
//                ->join('vork_kurser_bookable', 'vork_kurser_bookable.id', '=', 'vork_kurser_bookinger.bookable_id')
//                ->join('vork_kurser_grupper', 'vork_kurser_bookinger.gruppe_id', '=', 'vork_kurser_grupper.id')
//                ->select('vork_kurser_bookinger.id', 'vork_kurser_bookinger.start', 'vork_kurser_bookinger.slut as end', DB::raw('IF(gruppe_id = ' . $this->param('gruppe') . ', true, false) as editable'), 'vork_kurser_bookable.color', DB::raw('CONCAT(vork_kurser_grupper.nummer," ",vork_kurser_grupper.navn,"\n",vork_kurser_bookinger.kommentar) as title'))
//                ->get();
        } else {
            $this->bookables = $this->page->bookables = Bookable::all();
        }
    }

    public function onAddBookable()
    {
        $bookable = new Bookable();
        $bookable->navn = post('navn');
        $bookable->kursus_id = post('kursus_id');
        $bookable->color = post('color');
        $bookable->faelles = post('faelles');
        $bookable->overlap = post('overlap');
        $bookable->save();

        $this->bookables = $this->page->bookables = Bookable::all();

        Flash::success('Bookablen er oprettet!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }

    public function onUpdateBookable()
    {
        $bookable = Bookable::find(post('id'));
        $bookable->navn = post('navn');
        $bookable->color = post('color');
        $bookable->faelles = post('faelles');
        $bookable->overlap = post('overlap');
        $bookable->save();

        $this->bookables = $this->page->bookables = Bookable::all();

        Flash::success('Bookablen er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }


    public function onDeleteBookable()
    {
        $bookable = Bookable::find(post('id'));
        $bookable->delete();

        $this->bookables = $this->page->bookables = Bookable::all();

        Flash::success('Bookablen er slettet!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }


    public function onGetBookinger()
    {
        return Booking::where(function ($query) {
            $query->where('vork_kurser_bookinger.bookable_id', '=', post('bookable_id'))
                ->orWhere('vork_kurser_bookable.faelles', '=', 1);
            })->join('vork_kurser_bookable', 'vork_kurser_bookable.id', '=', 'vork_kurser_bookinger.bookable_id')
            ->join('vork_kurser_grupper', 'vork_kurser_bookinger.gruppe_id', '=', 'vork_kurser_grupper.id')
            ->select('vork_kurser_bookinger.id', 'vork_kurser_bookinger.start', 'vork_kurser_bookinger.slut as end', DB::raw('IF(gruppe_id = ' . $this->param('gruppe') . ', TRUE, FALSE) as editable'), DB::raw('IF(bookable_id = ' . post('bookable_id') . ', "", "background") as rendering'), 'vork_kurser_bookable.color', DB::raw('CONCAT(vork_kurser_grupper.nummer," ",vork_kurser_grupper.navn,"\n",vork_kurser_bookinger.kommentar) as title'))
            ->get();
    }
}
