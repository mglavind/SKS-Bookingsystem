<?php namespace Vork\Kurser\Models;

use Model;

/**
 * Plads Model
 */
class Andagt extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'vork_kurser_andagter';

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
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
