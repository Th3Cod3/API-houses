<?php

namespace App\Models;

use \Phalcon\Mvc\Model;

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
        $this->setSchema($_ENV["DB_NAME"]);
        $this->setSource("roles");
        $this->hasManyToMany(
            'id',
            RolePermissions::class,
            'role_id',
            'permission_id',
            Permissions::class,
            'id',
            [
                'reusable' => true,
                'alias'    => 'products',
            ]
        );
    }

}
