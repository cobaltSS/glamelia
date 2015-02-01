<?php

namespace Reviews;

use Reviews\Model\Reviews;
use Reviews\Model\ReviewsTable;
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
                'Reviews\Model\ReviewsTable' => function($sm) {
                    $tableGateway = $sm->get('ReviewsTableGateway');
                    $table = new ReviewsTable($tableGateway);
                    return $table;
                },
                'ReviewsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Reviews());
                    return new TableGateway('reviews', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }

}
