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
        $this->setSchema($_ENV["DB_NAME"]);
        $this->setSource("room_types");
        $this->hasMany('id', Rooms::class, 'type_id', ['alias' => 'Rooms']);
    }

}
