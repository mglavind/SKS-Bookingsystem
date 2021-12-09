<?php namespace Vork\Kurser\Models;

use Model;

/**
 * Plads Model
 */
class Maaltid extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'vork_kurser_maaltider';

    /**
     * @var array Guarded fields
     */
    protected $guarded = [];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'kursus' => ['Vork\Kurser\Models\Kursus', 'key' => 'kursus_id']
    ];
    public $belongsToMany = [
        'grupper' => [
            'Vork\Kurser\Models\Gruppe',
            'pivot' => ['svar'],
            'table' => 'vork_kurser_maaltider_svar',
            'key'      => 'maaltid_id',
            'otherKey' => 'gruppe_id',
            'order' => 'nummer asc'
        ]
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
