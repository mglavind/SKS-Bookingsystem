<?php namespace Vork\Kurser;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Vork\Kurser\Components\Grupper' => 'Grupper',
            'Vork\Kurser\Components\Kurser' => 'Kurser',
            'Vork\Kurser\Components\Users' => 'Users',
            'Vork\Kurser\Components\Omraader' => 'Omraader',
            'Vork\Kurser\Components\Pladser' => 'Pladser',
            'Vork\Kurser\Components\Kasser' => 'Kasser',
            'Vork\Kurser\Components\Materialer' => 'Materialer',
            'Vork\Kurser\Components\Bestillinger' => 'Bestillinger',
            'Vork\Kurser\Components\Maaltider' => 'Maaltider',
            'Vork\Kurser\Components\Bookables' => 'Bookables',
            'Vork\Kurser\Components\Kompendier' => 'Kompendier',
            'Vork\Kurser\Components\Todos' => 'Todos',
            'Vork\Kurser\Components\Andagter' => 'Andagter'
        ];
    }

    public function registerSettings()
    {
    }
}
