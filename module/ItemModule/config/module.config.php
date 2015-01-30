<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'ItemModule\Controller\Item' => 'ItemModule\Controller\ItemController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'item' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/admin/[item][/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'ItemModule\Controller\Item',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'module_config' => array(
        'upload_location' => __DIR__ . '/../../../public/images/item',
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'item' => __DIR__ . '/../view',
        ),
        'strategies' => array('ViewJsonStrategy'),
    ),
    
);
