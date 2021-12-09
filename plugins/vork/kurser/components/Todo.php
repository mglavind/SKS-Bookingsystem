<?php namespace Vork\Kurser\Components;

use Cms\Classes\ComponentBase;
use October\Rain\Support\Facades\Flash;
use Vork\Kurser\Models\Kasse;
use Vork\Kurser\Models\Maaltid;
use Vork\Kurser\Models\Plads;

class Todo extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'ToDo Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        if($this->param('todo') != ''){
            $this->todo = $this->page->todo = Maaltid::findOrFail($this->param('todo'));
        } else {
            $this->todos = $this->page->todos = Todo::all();
        }
    }

    public function onAddTodo()
    {
        $todo = new Todo();
        $todo->tekst = post('tekst');
        $todo->sortering = post('sortering');
        $todo->done = false;
        $todo->gruppe_id = post('gruppe_id');
        $todo->user_id = post('user_id');
        $todo->save();

        Flash::success('Todoen er oprettet!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }

    public function onUpdateTodo()
    {
        $todo = Maaltid::find(post('id'));
        $todo->tekst = post('tekst');
        $todo->color = post('color');
        $todo->save();

        $this->todos = $this->page->todos = Todo::all();

        Flash::success('Todoen er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }

    public function onDoneTodo()
    {
        $todo = Maaltid::find(post('id'));
        $todo->done = post('done');
        $todo->save();

        $this->todos = $this->page->todos = Todo::all();

        Flash::success('Todoen er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }


    public function onDeleteTodo()
    {
        $todo = Todo::find(post('id'));
        $todo->delete();

        Flash::success('Todoen er slettet!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }
}
