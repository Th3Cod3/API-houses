<?php

declare(strict_types=1);

use App\Modules\JWT;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Enum;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Url as UrlResolver;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    return new $class($params);
});

/**
 * Shared jwt
 */
$di->setShared('jwt', function () {
    $config = $this->getConfig();
    return new JWT($config->jwt->issuedBy, $config->jwt->timeout);
});

/**
 * Shared acl
 */
$di->setShared('acl', function () {

    $acl = new Memory();

    $acl->setDefaultAction(Enum::DENY);

    $acl->addRole('User');
    $acl->addRole('Admin');

    $acl->addInherit('Admin', 'User');

    $arrResources = [
        'User' => [
            'User' => ['permissions'],
            'House' => ['add', 'get', 'edit', 'remove', 'search', "types"],
        ],
        'Admin' => [
            'User' => ['add', 'get'],
        ],
    ];

    foreach ($arrResources as $arrControllers) {
        foreach ($arrControllers as $controller => $methods) {
            $acl->addComponent($controller, $methods);
        }
    }

    foreach ($acl->getRoles() as $objRole) {
        $roleName = $objRole->getName();
        foreach ($arrResources[$roleName] as $controller => $methods) {
            $acl->allow($roleName, $controller, $methods);
        }
    }

    return $acl;
});
