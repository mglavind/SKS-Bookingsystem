<?php namespace Vork\Kurser\Models;

use Model;

/**
 * Materiale Model
 */
class Bestilling extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'vork_kurser_bestillinger';

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
        'user' => ['RainLab\User\Models\User', 'key' => 'user_id'],
        'gruppe' => ['Vork\Kurser\Models\Gruppe', 'key' => 'gruppe_id'],
        'materiale' => ['Vork\Kurser\Models\Materiale', 'key' => 'materiale_id']
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
