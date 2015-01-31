<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'ShopModule\Controller\Shop' => 'ShopModule\Controller\ShopController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'shop' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/admin/[shop][/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'ShopModule\Controller\Shop',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
  
    
    'module_config' => array(
        'upload_shop_location' => __DIR__ . '/../../../public/images/shop',
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'adminlayout' => __DIR__ . '/../view/layout/layout.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        
        'template_path_stack' => array(
            'shop' => __DIR__ . '/../view',
        ),
        'strategies' => array('ViewJsonStrategy'),
    ),
    
);
