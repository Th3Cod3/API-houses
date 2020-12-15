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

    /**
     * Set all default values.
     */
    public function beforeValidationOnCreate()
    {
        $this->created_at = date("Y-m-d H:i:s");
        $this->updated_at = date("Y-m-d H:i:s");
    }

    /**
     * Set all default values.
     */
    public function beforeValidationOnUpdate()
    {
        $this->updated_at = date("Y-m-d H:i:s");
    }

    /**
     * return the house and rooms data
     * 
     * @return array
     */
    public function toArrayWithRooms()
    {
        return array_merge(
            $this->toArray([
                "id",
                "city",
                "street",
                "zip_code",
                "number",
                "addition"
            ]),
            [ "rooms" => $this->Rooms->filter(function($room) {
                    return $room->toArray([
                        "type_id",
                        "width",
                        "length",
                        "height"
                    ]);
                })
            ]
        );
    }

}
