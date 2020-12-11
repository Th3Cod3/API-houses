<?php

namespace App\Models;

use \Phalcon\Mvc\Model;

class RolePermissions extends Model
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
    public $role_id;

    /**
     *
     * @var integer
     */
    public $permission_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema($_ENV["DB_NAME"]);
        $this->setSource("role_permissions");
        $this->belongsTo('permission_id', Permissions::class, 'id', ['alias' => 'Permissions']);
        $this->belongsTo('role_id', Roles::class, 'id', ['alias' => 'Roles']);
    }

}
