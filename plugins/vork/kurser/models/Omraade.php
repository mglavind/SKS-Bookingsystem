<?php namespace Vork\Kurser\Models;

use Model;

/**
 * Omraade Model
 */
class Omraade extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'vork_kurser_omraader';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'pladser' => ['Vork\Kurser\Models\Plads', 'key' => 'omraade_id', 'otherKey' => 'id']
    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
