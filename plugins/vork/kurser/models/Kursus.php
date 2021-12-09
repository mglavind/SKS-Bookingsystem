<?php namespace Vork\Kurser\Models;

use Model;

/**
 * Model
 */
class Kursus extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    /*
     * Validation
     */
    public $rules = [
    ];

    public $hasMany = [
        'grupper' => ['Vork\Kurser\Models\Gruppe', 'key' => 'kursus_id', 'order' => 'nummer asc', 'softDelete' => true],
        'maaltider' => ['Vork\Kurser\Models\Maaltid', 'key' => 'kursus_id', 'order' => 'sortering asc', 'softDelete' => true],
        'andagter' => ['Vork\Kurser\Models\Andagt', 'key' => 'kursus_id', 'order' => 'sortering asc', 'softDelete' => true],
        'bookables' => ['Vork\Kurser\Models\Bookable', 'key' => 'kursus_id', 'softDelete' => true]
    ];

    public $hasManyThrough = [
        'bestillinger' => [
            'Vork\Kurser\Models\Bestilling',
            'key'        => 'kursus_id',
            'through'    => 'Vork\Kurser\Models\Gruppe',
            'throughKey' => 'gruppe_id'
        ],
        'todos' => [
            'Vork\Kurser\Models\Todo',
            'key'        => 'kursus_id',
            'through'    => 'Vork\Kurser\Models\Gruppe',
            'throughKey' => 'gruppe_id'
        ],
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'vork_kurser_kurser';


}