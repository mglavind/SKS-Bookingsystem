<?php

namespace Vork\Kurser\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Support\Facades\Flash;
use RainLab\User\Models\User;
use Vork\Kurser\Models;

class Users extends ComponentBase
{
    public $users;
    public $user;
    public $grupper;

    public function componentDetails()
    {
        return [
            'name'        => 'Users Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        if ($this->param('user') != '') {

            $this->user = $this->page->user = User::find($this->param('user'));
        } else {
            $this->users = $this->page->users = User::all();
        }
    }

    public function onAddUser()
    {
        $pass = uniqid();
        $user = new User();
        $user->name = post('name');
        $user->surname = post('surname');
        $user->email = post('email');
        $user->phone = post('phone');
        $user->street_addr = post('street_addr');
        $user->zip = post('zip');
        $user->city = post('city');
        $user->kredsnavn = post('kredsnavn');
        $user->medlemsnummer = post('medlemsnummer');
        $user->is_activated = 1;
        $user->vdomah_role_id = 2;
        $user->password = $pass;
        $user->password_confirmation = $pass;
        $user->save();

        Flash::success('Useren er oprettet!');
        return Redirect::to('users');
    }

    public function onUpdateUser()
    {
        $user = User::find(post('id'));
        $user->name = post('name');
        $user->surname = post('surname');
        $user->email = post('email');
        $user->phone = post('phone');
        $user->street_addr = post('street_addr');
        $user->zip = post('zip');
        $user->city = post('city');
        $user->kredsnavn = post('kredsnavn');
        $user->medlemsnummer = post('medlemsnummer');
        $user->save();

        $this->users = $this->page->users = User::all();

        Flash::success('Useren er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }


    public function onDeleteUser()
    {
        $user = User::find(post('id'));
        $user->delete();

        $this->users = $this->page->users = User::all();

        Flash::success('Useren er slettet!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }
}
