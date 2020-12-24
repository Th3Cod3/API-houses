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
        $this->setSchema($_ENV["DB_NAME"]);
        $this->setSource("rooms");
        $this->belongsTo('house_id', Houses::class, 'id', ['alias' => 'Houses']);
        $this->belongsTo('type_id', RoomTypes::class, 'id', ['alias' => 'RoomTypes']);
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
     * Default format to assign into an update or insert
     *
     * @return array
     */
    public function getSetFormat()
    {
        return [
            "type_id",
            "width",
            "length",
            "height"
        ];
    }

    /**
     * Default format to assign into a select
     *
     * @return array
     */
    public function getGetFormat()
    {
        return [
            "id",
            "type_id",
            "width",
            "length",
            "height"
        ];
    }
}
