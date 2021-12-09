<?php namespace Vork\Kurser\Models;

use Model;

/**
 * Materiale Model
 */
class Materiale extends Model
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['total_antal'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'vork_kurser_materialer';

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
    public $belongsTo = [];
    public $belongsToMany = [
        'kasser' => [
            'Vork\Kurser\Models\Kasse',
            'pivot' => ['antal'],
            'table' => 'vork_kurser_forbindelser',
            'key'      => 'materiale_id',
            'otherKey' => 'kasse_id'
        ]
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function getTotalAntalAttribute(){
        return $this->kasser->sum('pivot.antal');
    }
}
