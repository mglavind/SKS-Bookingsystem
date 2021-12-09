<?php namespace Vork\Kurser\Models;

use Model;

/**
 * Model
 */
class Gruppe extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    /*
     * Validation
     */
    public $rules = [
    ];

    public $belongsTo = [
        'kursus' => ['Vork\Kurser\Models\Kursus', 'key' => 'kursus_id']
    ];

    public $hasMany = [
        'bestillinger' => ['Vork\Kurser\Models\Bestilling', 'key' => 'gruppe_id', 'otherKey' => 'id'],
        'bookinger' => ['Vork\Kurser\Models\Booking', 'key' => 'gruppe_id', 'otherKey' => 'id'],
        'todos' => ['Vork\Kurser\Models\Todo', 'key' => 'gruppe_id', 'otherKey' => 'id', 'order' => 'deadline asc']
    ];

    public $belongsToMany = [
        'users' => [
            'RainLab\User\Models\User',
            'pivot' => ['type'],
            'table' => 'vork_kurser_deltagelser',
            'key'      => 'gruppe_id',
            'otherKey' => 'user_id'
        ],
        'maaltider' => [
            'Vork\Kurser\Models\Maaltid',
            'pivot' => ['svar'],
            'table' => 'vork_kurser_maaltider_svar',
            'key'      => 'gruppe_id',
            'otherKey' => 'maaltid_id'
        ]
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'vork_kurser_grupper';
}