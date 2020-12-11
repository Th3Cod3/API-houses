<?php

namespace App\Models;


use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class Users extends Model
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
    public $username;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var integer
     */
    public $person_id;

    /**
     *
     * @var integer
     */
    public $role_id;

    /**
     *
     * @var string
     */
    public $last_login;

    /**
     *
     * @var string
     */
    public $first_fail;

    /**
     *
     * @var string
     */
    public $fail_counter;

    /**
     *
     * @var string
     */
    public $banned;

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
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema($_ENV["DB_NAME"]);
        $this->setSource("users");
        $this->hasMany('id', Houses::class, 'user_id', ['alias' => 'Houses']);
        $this->belongsTo('person_id', Persons::class, 'id', ['alias' => 'Persons']);
        $this->belongsTo('role_id', Roles::class, 'id', ['alias' => 'Roles']);
    }

    // /**
    //  * Set all default values.
    //  */
    // public function beforeCreate()
    // {
    //     $this->created_at = date("Y-m-d H:i:s");
    // }

}
