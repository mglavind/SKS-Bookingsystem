<?php namespace Vork\Kurser\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Support\Facades\Flash;
use RainLab\User\Models\User;
use Vork\Kurser\Models;
use Vork\Kurser\Models\Booking;

class Grupper extends ComponentBase
{
    public $gruppe;
    public $grupper;
    public $users;
    public $selectUsers;
    public $bookable;

    public function componentDetails()
    {
        return [
            'name'        => 'Grupper Component',
            'description' => 'CRUD for Grupper'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        if($this->param('gruppe') != ''){
            $this->gruppe = $this->page->gruppe = Models\Gruppe::find($this->param('gruppe'));
            $this->faellesprogram = $this->page->faellesprogram = Models\Gruppe::where('nummer', 30)->where('kursus_id',$this->gruppe->kursus->id)->first();
            $this->andagter = $this->page->andagter = Models\Andagt::where('kursus_id',$this->gruppe->kursus->id)->get();
            $this->maaltider = $this->page->maaltider = Models\Maaltid::leftJoin('vork_kurser_maaltider_svar', function ($join) {
                $join->on('vork_kurser_maaltider.id', '=', 'vork_kurser_maaltider_svar.maaltid_id')
                    ->where('vork_kurser_maaltider_svar.gruppe_id', '=', $this->gruppe->id);
                })
                ->select('vork_kurser_maaltider.id', 'vork_kurser_maaltider.navn', 'vork_kurser_maaltider_svar.svar')
                ->where('vork_kurser_maaltider.kursus_id', '=', $this->gruppe->kursus->id)
                ->orderBy('vork_kurser_maaltider.sortering', 'asc')
                ->get();

        } elseif($this->param('bookable') != ''){
            $this->bookable =  Models\Bookable::findOrFail($this->param('bookable'));
            $this->grupper = $this->page->grupper = Models\Gruppe::where('kursus_id', '=', $this->bookable->kursus->id)->orderBy('nummer', 'asc')->get();
        } else {
            $this->grupper = $this->page->grupper = Models\Gruppe::all();
        }
    }

    public function onAddUser()
    {
        $gruppe = Models\Gruppe::find(post('gruppe_id'));
        $gruppe->users()->attach(post('user_id'), ['type' => post('type')]);

        Flash::success('Useren er tilføjet!');
        return Redirect::to('grupper/' . post('gruppe_id'));
    }

    public function onDeleteUser()
    {
        $gruppe = Models\Gruppe::find(post('gruppe_id'));
        $gruppe->users()->detach(post('user_id'));

        Flash::success('Useren er fjernet!');
        return Redirect::to('grupper/' . post('gruppe_id'));
    }

    public function onAddGruppe()
    {
        $gruppe = new Models\Gruppe();
        $gruppe->kursus_id = post('kursus_id');
        $gruppe->nummer = post('nummer');
        $gruppe->navn = post('navn');
        $gruppe->save();

        Flash::success('Gruppen er oprettet!');
        return Redirect::to('kurser/' . post('kursus_id'));
    }

    public function onUpdateGruppe()
    {
        $gruppe = Models\Gruppe::find(post('id'));
        $gruppe->nummer = post('nummer');
        $gruppe->navn = post('navn');
        $gruppe->save();

        $this->grupper = $this->page->grupper = Models\Gruppe::all();

        Flash::success('Gruppen er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }

    public function onDeleteGruppe()
    {
        $gruppe = Models\Gruppe::find(post('id'));
        $gruppe->delete();

        $this->grupper = $this->page->grupper = Models\Gruppe::all();

        Flash::success('Gruppen er slettet!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }


    public function onAddMaaltidSvar()
    {
        Models\Gruppe::find(post('id'))->maaltider()->attach(post('maaltid_id'), ['svar' => post('svar')]);

        Flash::success('Svaret er oprettet!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }
    public function onUpdateMaaltidSvar()
    {
        Models\Gruppe::find(post('id'))->maaltider()->updateExistingPivot(post('maaltid_id'), ['svar' => post('svar')]);

        Flash::success('Svaret er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }

    public function onAddBooking()
    {
        $booking = new Booking();
        $booking->start = post('start');
        $booking->slut = post('slut');
        $booking->kommentar = post('kommentar');
        $booking->gruppe_id = post('gruppe_id');
        $booking->bookable_id = post('bookable_id');
        $booking->save();

        Flash::success('Bookingen er oprettet!');
        return ['booking_id' => $booking->id,'#flash_alert' => $this->renderPartial('flash')];
    }


    public function onDeleteBooking()
    {
        $booking = Booking::findOrFail(post('booking_id'));
        $booking->delete();

        Flash::success('Bookingen er slettet!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }

    public function onUpdateBooking()
    {
        $booking = Booking::findOrFail(post('booking_id'));
        $booking->start = post('start');
        $booking->slut = post('slut');
        $booking->save();

        Flash::success('Bookingen er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }
}
