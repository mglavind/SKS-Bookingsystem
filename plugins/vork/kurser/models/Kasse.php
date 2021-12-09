<?php namespace Vork\Kurser\Models;

use Model;

/**
 * Kasse Model
 */
class Kasse extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'vork_kurser_kasser';

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
    public $hasMany = [];
    public $belongsTo = [
        'plads' => ['Vork\Kurser\Models\Plads', 'key' => 'plads_id']
    ];
    public $belongsToMany = [
        'materialer' => [
            'Vork\Kurser\Models\Materiale',
            'pivot' => ['antal'],
            'table' => 'vork_kurser_forbindelser',
            'key'      => 'kasse_id',
            'otherKey' => 'materiale_id'
        ]];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
