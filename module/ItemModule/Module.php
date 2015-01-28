<?php

namespace ItemModule;

use ItemModule\Model\Item;
use ItemModule\Model\ItemTable;
use ItemModule\Model\PhotoItem;
use ItemModule\Model\PhotoItemTable;
use ItemModule\Model\Items2Shop;
use ItemModule\Model\Items2ShopTable;
use Category\Model\Category;
use Category\Model\CategoryTable;
use ShopModule\Model\Shop;
use ShopModule\Model\ShopTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\ModuleManager;

class Module {

    public function init(ModuleManager $moduleManager) {
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        $sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {
            $controller = $e->getTarget();
            $controller->layout('adminlayout');
        }, 100);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'ItemModule\Model\ItemTable' => function($sm) {
                    $tableGateway = $sm->get('ItemTableGateway');
                    $table = new ItemTable($tableGateway);
                    return $table;
                },
                'ShopModule\Model\ShopTable' => function($sm) {
                    $tableGateway = $sm->get('ShopTableGateway');
                    $table = new ShopTable($tableGateway);
                    return $table;
                },
                'Category\Model\CategoryTable' => function($sm) {
                    $tableGateway = $sm->get('CategoryTableGateway');
                    $table = new CategoryTable($tableGateway);
                    return $table;
                },
                'ItemModule\Model\PhotoItemTable' => function($sm) {
                    $tableGateway = $sm->get('PhotoItemTableGateway');
                    $table = new PhotoItemTable($tableGateway);
                    return $table;
                },
                'ItemModule\Model\Items2ShopTable' => function($sm) {
                    $tableGateway = $sm->get('Items2ShopTableGateway');
                    $table = new Items2ShopTable($tableGateway);
                    return $table;
                },
                'ItemTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Item());
                    return new TableGateway('item', $dbAdapter, null, $resultSetPrototype);
                },
                'ShopTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Shop());
                    return new TableGateway('shop', $dbAdapter, null, $resultSetPrototype);
                },
                'CategoryTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Category());
                    return new TableGateway('category', $dbAdapter, null, $resultSetPrototype);
                },
                'PhotoItemTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new PhotoItem());
                    return new TableGateway('item_photo', $dbAdapter, null, $resultSetPrototype);
                },
                'Items2ShopTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Items2Shop());
                    return new TableGateway('items2shop', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }

}
