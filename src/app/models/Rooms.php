<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class Rooms extends Model
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
    public $house_id;

    /**
     *
     * @var integer
     */
    public $type_id;

    /**
     *
     * @var integer
     */
    public $width;

    /**
     *
     * @var integer
     */
    public $length;

    /**
     *
     * @var integer
     */
    public $height;

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
        $this->setSchema("dtt");
        $this->setSource("rooms");
        $this->belongsTo('house_id', 'Model\Houses', 'id', ['alias' => 'Houses']);
        $this->belongsTo('type_id', 'Model\RoomTypes', 'id', ['alias' => 'RoomTypes']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Rooms[]|Rooms|Model\ResultSetInterface
     */
    public static function find($parameters = null): Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

}
