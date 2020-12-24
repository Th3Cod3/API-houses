<?php

namespace App\Models;


use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\Uniqueness;

class Users extends Model
{

    const DEFAULT_ROLE = 1;
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
    public $jti;

    /**
     *
     * @var string
     */
    public $last_login;

    /**
     *
     * @var string
     */
    public $last_fail;

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
                    'model' => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        $validator->add(
            'email',
            new Uniqueness(
                [
                    'model' => $this,
                    'message' => 'This email is already in use',
                ]
            )
        );

        $validator->add(
            'username',
            new Uniqueness(
                [
                    'model' => $this,
                    'message' => 'this username is already in use',
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
        $this->useDynamicUpdate(true);
    }

    /**
     * Set all default values.
     */
    public function beforeValidationOnCreate()
    {
        $this->created_at = date("Y-m-d H:i:s");
        $this->updated_at = date("Y-m-d H:i:s");
        $this->role_id = self::DEFAULT_ROLE;
    }

    /**
     * Set all default values.
     */
    public function beforeValidationOnUpdate()
    {
        $this->updated_at = date("Y-m-d H:i:s");
    }

    /**
     * return the user and person data
     * 
     * @return array
     */
    public function toArrayWithPerson()
    {
        return array_merge(
            $this->toArray($this->getGetFormat()),
            $this->Persons->toArray($this->Persons->getGetFormat()),
            ["role" => $this->Roles->name]
        );
    }

    /**
     * Default format to assign into an update or insert
     *
     * @return array
     */
    public function getSetFormat()
    {
        return [
            "username",
            "email"
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
