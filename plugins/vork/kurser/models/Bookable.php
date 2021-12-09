<?php namespace Vork\Kurser\Models;

use Model;

/**
 * Plads Model
 */
class Bookable extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'vork_kurser_bookable';

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
    public $hasMany = [
        'bookinger' => [
            'Vork\Kurser\Models\Booking',
            'table' => 'vork_kurser_bookinger',
            'key'      => 'bookable_id',
            'otherKey' => 'id',
        ]
    ];
    public $belongsTo = [
        'kursus' => ['Vork\Kurser\Models\Kursus', 'key' => 'kursus_id']
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
