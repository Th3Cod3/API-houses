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

}
