<?php

namespace ShopModule;

use ShopModule\Model\Shop;
use ShopModule\Model\ShopTable;
use ShopModule\Model\City;
use ShopModule\Model\CityTable;
use ShopModule\Model\PhotoShop;
use ShopModule\Model\PhotoShopTable;
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
                'ShopModule\Model\ShopTable' => function($sm) {
                    $tableGateway = $sm->get('ShopTableGateway');
                    $table = new ShopTable($tableGateway);
                    return $table;
                },
                'ShopModule\Model\CityTable' => function($sm) {
                    $tableGateway = $sm->get('CityTableGateway');
                    $table = new CityTable($tableGateway);
                    return $table;
                },
                'ShopModule\Model\PhotoShopTable' => function($sm) {
                    $tableGateway = $sm->get('PhotoShopTableGateway');
                    $table = new PhotoShopTable($tableGateway);
                    return $table;
                },
                'ShopTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Shop());
                    return new TableGateway('shop', $dbAdapter, null, $resultSetPrototype);
                },
                'CityTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new City());
                    return new TableGateway('city', $dbAdapter, null, $resultSetPrototype);
                },
                'PhotoShopTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new PhotoShop());
                    return new TableGateway('shop_photo', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }

}
