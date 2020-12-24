<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class Persons extends Model
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
    public $first_name;

    /**
     *
     * @var string
     */
    public $last_name;

    /**
     *
     * @var string
     */
    public $middle_name;

    /**
     *
     * @var string
     */
    public $birthdate;

    /**
     *
     * @var string
     */
    public $gender;

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
        $this->setSource("persons");
        $this->hasOne('id', Users::class, 'person_id', ['alias' => 'Users']);
    }

    /**
     * Default format to assign into an update or insert
     *
     * @return array
     */
    public function getSetFormat()
    {
        return [
            "first_name",
            "last_name",
            "middle_name",
            "birthdate",
            "gender",
        ];
    }

    /**
     * Default format to assign into a select
     *
     * @return array
     */
    public function getGetFormat()
    {
        return $this->getSetFormat();
    }

}
