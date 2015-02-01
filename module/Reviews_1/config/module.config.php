<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Reviews\Controller\Reviews' => 'Reviews\Controller\ReviewsController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'reviews' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/admin/[reviews][/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Reviews\Controller\Reviews',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'reviews' => __DIR__ . '/../view',
        ),
    ),
);
