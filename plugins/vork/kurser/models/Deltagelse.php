<?php namespace Vork\Kurser\Models;

use Model;

/**
 * Model
 */
class Deltagelse extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'vork_kurser_deltagelse';
}