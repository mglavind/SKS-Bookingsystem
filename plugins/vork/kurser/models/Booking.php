<?php namespace Vork\Kurser\Models;

use Model;

/**
 * Plads Model
 */
class Booking extends Model
{
    use \October\Rain\Database\Traits\SoftDelete;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'vork_kurser_bookinger';

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
        'bookable' => ['Vork\Kurser\Models\Bookable', 'key' => 'bookable_id']
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    protected $dates = ['deleted_at'];
}
