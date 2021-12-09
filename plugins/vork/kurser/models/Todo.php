<?php namespace Vork\Kurser\Models;

use Model;

/**
 * Plads Model
 */
class Todo extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'vork_kurser_todo';

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
        'gruppe' => ['Vork\Kurser\Models\Gruppe', 'key' => 'gruppe_id'],
        'user_done' => ['RainLab\User\Models\User', 'key' => 'done_by'],
        'user_created' => ['RainLab\User\Models\User', 'key' => 'created_by'],
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
