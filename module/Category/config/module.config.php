<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Category\Controller\Category' => 'Category\Controller\CategoryController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'category' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/admin/[category][/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Category\Controller\Category',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'category' => __DIR__ . '/../view',
        ),
    ),
);
