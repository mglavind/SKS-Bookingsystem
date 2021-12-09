<?php namespace Vork\Kurser\Models;

use Model;

/**
 * Plads Model
 */
class Plads extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'vork_kurser_pladser';

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
        'kasser' => ['Vork\Kurser\Models\Kasse', 'key' => 'plads_id']
    ];
    public $belongsTo = [
        'omraade' => ['Vork\Kurser\Models\Omraade', 'key' => 'omraade_id']
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
