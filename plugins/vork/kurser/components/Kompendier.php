<?php namespace Vork\Kurser\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use October\Rain\Support\Facades\Flash;
use Vork\Kurser\Models\Materiale;

class Kompendier extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Kompendier Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $dir = "storage/app/media";

        // Sort in ascending order - this is default
//        $a = scandir($dir);


        function listFolderFiles($dir, $folder){
            $files = array();
            $i = 0;
            $ffs = scandir($dir.'/'.$folder);
            foreach($ffs as $ff){
                if($ff != '.' && $ff != '..'){
                    if(!isset($files[$folder])) $files[$folder] = array();
                    if(is_dir($dir.'/'.$folder.'/'.$ff)){
                        array_push($files[$folder], listFolderFiles($dir.'/'.$folder,$ff));
                    } else {
                        array_push($files[$folder], array($ff, pathinfo($ff, PATHINFO_EXTENSION),$dir.'/'.$folder.'/'.$ff));
                    }
                }
                $i++;
            }
            return $files;
        }
        $a = listFolderFiles($dir, 'Kompendier');

//        dd($a);
        $this->root = $this->page->root = $a;
    }


    public function onSearch()
    {
        return true;
    }
}
