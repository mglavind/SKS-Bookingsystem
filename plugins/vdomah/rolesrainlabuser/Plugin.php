<?php namespace Vdomah\RolesRainLabUser;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public $require = ['RainLab.User', 'Vdomah.Roles'];

    public function registerComponents()
    {
        return [
        ];
    }

    public function registerSettings()
    {
        return [
        ];
    }
}
