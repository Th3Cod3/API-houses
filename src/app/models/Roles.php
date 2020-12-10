<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class Roles extends Model
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
        $this->setSource("roles");
        $this->hasMany('id', 'Model\Permissions', 'role_id', ['alias' => 'Permissions']);
        $this->hasMany('id', 'Model\Users', 'role_id', ['alias' => 'Users']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Roles[]|Roles|Model\ResultSetInterface
     */
    public static function find($parameters = null): Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

}
