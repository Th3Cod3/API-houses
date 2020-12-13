<?php

use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Enum;

$acl = new Memory();

$acl->setDefaultAction(Enum::DENY);

$acl->addRole('User');
$acl->addRole('Admin');

$acl->addInherit('Admin', 'User');

$arrComponent = [
    'User' => [
        'User' => ['permissions'],
        'House' => ['add', 'get', 'edit', 'remove', 'list'],
    ],
    'Admin' => [
        'User' => ['add', 'get'],
    ],
];

foreach ($arrComponent as $arrResource) {
    foreach ($arrResource as $controller => $arrMethods) {
        $acl->addComponent($controller, $arrMethods);
    }
}

foreach ($acl->getRoles() as $objRole) {
    $roleName = $objRole->getName();
    foreach ($arrComponent[$roleName] as $controller => $method) {
        $acl->allow($roleName, $controller, $method);
    }
}