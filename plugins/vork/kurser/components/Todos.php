<?php namespace Vork\Kurser\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Support\Facades\Flash;
use RainLab\User\Facades\Auth;
use Vork\Kurser\Models\Todo;

class Todos extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Todos Component',
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
            $this->todo = $this->page->todo = Todo::findOrFail($this->param('todo'));
        }
    }

    public function onAddTodo()
    {
        $count = 0;
        foreach (post('gruppe_id') AS $gruppe) {
            $todo = new Todo();
            $todo->tekst = post('tekst');
            $todo->done = false;
            $todo->gruppe_id = $gruppe;
            $todo->created_by = post('user_id');
            $todo->deadline = post('deadline');
            $todo->faelles = post('faelles');
            $todo->color = post('color');
			$todo->kommentar = '';
			$todo->ansvarlig = '';
            $todo->save();
            $count++;
        }


        $this->todos = $this->page->todos = Todo::join('vork_kurser_grupper', 'vork_kurser_grupper.id', '=', 'vork_kurser_todo.gruppe_id')
            ->select(Db::raw('GROUP_CONCAT(vork_kurser_todo.id) as ids'),'vork_kurser_todo.tekst', 'vork_kurser_todo.color', 'vork_kurser_todo.deadline', Db::raw('COUNT(*) as antal'), Db::raw('sum(case when done = 1 then 1 else 0 end) as antal_done'), Db::raw('GROUP_CONCAT(case when done = 1 then CONCAT(" ",vork_kurser_grupper.nummer," ",vork_kurser_grupper.navn) end) as grupper_done'), Db::raw('GROUP_CONCAT(case when done = 0 then CONCAT(" ",vork_kurser_grupper.nummer," ",vork_kurser_grupper.navn) end) as grupper_not_done'))
            ->where('vork_kurser_todo.faelles', '=', 1)
            ->where('kursus_id', '=', post('kursus_id'))
            ->groupBy('vork_kurser_todo.tekst')
            ->get();

        Flash::success('Todos er oprettet på ' . $count . ' grupper!');
        return ['#flash_alert' => $this->renderPartial('flash')];


    }

    public function onAddTodoGruppe()
    {
        $todo = new Todo();
        $todo->tekst = post('tekst');
        $todo->done = false;
        $todo->gruppe_id = post('gruppe_id');
        $todo->created_by = post('user_id');
        $todo->deadline = post('deadline');
        $todo->faelles = post('faelles');
        $todo->color = post('color');
        $todo->save();


        $this->todos = $this->page->todos = Todo::where('vork_kurser_todo.gruppe_id', '=', post('gruppe_id'));

        Flash::success('Todon er oprettet!');
        return Redirect::to('min_gruppe/' . $todo->gruppe_id);


    }

    public function onUpdateTodo()
    {
        foreach (explode(',', post('ids')) AS $id) {
            $todo = Todo::find($id);
            $todo->tekst = post('tekst');
            $todo->ansvarlig = post('ansvarlig');
            $todo->kommentar = post('kommentar');
            $todo->deadline = post('deadline');
            $todo->color = post('color');
            $todo->save();
        }

        $this->todos = $this->page->todos = Todo::where('vork_kurser_todo.gruppe_id', '=', post('gruppe_id'));


        Flash::success('Todosne er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }

    public function onDoneTodo()
    {
        if(post('done') == "true") {
            $todo = Todo::find(post('id'));
            $todo->done = 1;
            $todo->done_by = Auth::getUser()->id;
            $todo->done_at = date("Y-m-d H:i:s");
        } else {
            $todo = Todo::find(post('id'));
            $todo->done = 0;
            $todo->done_by = null;
            $todo->done_at = null;
        }
        $todo->save();

        Flash::success('Todoen er opdateret!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }


    public function onDeleteTodo()
    {
        foreach (post('ids') AS $id) {
            $todo = Todo::find($id);
            $todo->delete();
        }


        $this->todos = $this->page->todos = Todo::join('vork_kurser_grupper', 'vork_kurser_grupper.id', '=', 'vork_kurser_todo.gruppe_id')
            ->select(Db::raw('GROUP_CONCAT(vork_kurser_todo.id) as ids'),'vork_kurser_todo.tekst', 'vork_kurser_todo.color', 'vork_kurser_todo.deadline', Db::raw('COUNT(*) as antal'), Db::raw('sum(case when done = 1 then 1 else 0 end) as antal_done'), Db::raw('GROUP_CONCAT(case when done = 1 then CONCAT(" ",vork_kurser_grupper.nummer," ",vork_kurser_grupper.navn) end) as grupper_done'), Db::raw('GROUP_CONCAT(case when done = 0 then CONCAT(" ",vork_kurser_grupper.nummer," ",vork_kurser_grupper.navn) end) as grupper_not_done'))
            ->where('vork_kurser_todo.faelles', '=', 1)
            ->where('kursus_id', '=', post('kursus_id'))
            ->groupBy('vork_kurser_todo.tekst')
            ->get();

        Flash::success('Todoen er slettet!');
        return ['#flash_alert' => $this->renderPartial('flash')];
    }


    public function onDeleteTodoGruppe()
    {
        $todo = Todo::find(post('id'));

        $gruppe_id = $todo->gruppe_id;

        $todo->delete();


        $this->todos = $this->page->todos = Todo::where('vork_kurser_todo.gruppe_id', '=', $gruppe_id)->get();

        Flash::success('Todoen er slettet!');
        return Redirect::to('min_gruppe/' . $todo->gruppe_id);
    }
}
