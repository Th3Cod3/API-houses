<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class Houses extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $city;

    /**
     *
     * @var string
     */
    public $street;

    /**
     *
     * @var string
     */
    public $zip_code;

    /**
     *
     * @var integer
     */
    public $number;

    /**
     *
     * @var string
     */
    public $addition;

    /**
     *
     * @var string
     */
    public $deleted_at;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema($_ENV["DB_NAME"]);
        $this->setSource("houses");
        $this->hasMany('id', Rooms::class, 'house_id', ['alias' => 'Rooms']);
        $this->belongsTo('user_id', Users::class, 'id', ['alias' => 'Users']);
    }

}
