<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class Permissions extends Model
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
        $this->setSource("permissions");
    }

}
