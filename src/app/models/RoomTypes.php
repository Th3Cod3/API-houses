<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class RoomTypes extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("dtt");
        $this->setSource("room_types");
        $this->hasMany('id', 'Model\Rooms', 'type_id', ['alias' => 'Rooms']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return RoomTypes[]|RoomTypes|Model\ResultSetInterface
     */
    public static function find($parameters = null): Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

}
