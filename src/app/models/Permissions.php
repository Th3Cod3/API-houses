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
     *
     * @var integer
     */
    public $role_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("dtt");
        $this->setSource("permissions");
        $this->belongsTo('role_id', 'Model\Roles', 'id', ['alias' => 'Roles']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Permissions[]|Permissions|Model\ResultSetInterface
     */
    public static function find($parameters = null): Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

}
