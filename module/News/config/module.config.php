<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'News\Controller\News' => 'News\Controller\NewsController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'listnews' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/admin/[news][/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'News\Controller\News',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'news' => __DIR__ . '/../view',
        ),
    ),
);
