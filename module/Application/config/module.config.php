<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'shops' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/[:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'items' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/[:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'news' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/[:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'about' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/[:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'categories' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/[:action][/:id][/:id_sub]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'item' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/[:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'review' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/[:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
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
        'factories' => array(
            'public_navigation' => 'Application\Navigation\Service\PublicNavigationFactory', // <-- add this
            'admin_navigation' => 'ShopModule\Navigation\Service\AdminNavigationFactory', // <-- add this
            'cat_navigation' => 'Application\Navigation\Service\CategoryNavigationFactory', // <-- add this
            
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'navigation' => array(
        'public_navigation' => array(
            array(
                'label' => 'ГЛАВНАЯ',
                'route' => 'home',
            ),
            array(
                'label' => 'НАШИ МАГАЗИНЫ',
                'route' => 'shops',
                'action' => 'shops',
            ),
            array(
                'label' => 'КАТАЛОГ',
                'route' => 'categories',
            ),
            array(
                'label' => 'НОВОСТИ',
                'route' => 'news',
                'action' => 'news',
            ),
            array(
                'label' => 'ОТЗЫВЫ',
                'route' => 'review',
                'action' => 'review',
            ),
            array(
                'label' => 'О НАС',
                'route' => 'about',
                'action' => 'about',
            ),
        ),
        'admin_navigation' => array(
            array(
                'label' => 'Главная',
                'route' => 'home',
            ),
            array(
                'label' => 'Магазины',
                'route' => 'shop',
                'pages' => array(
                    array(
                        'label' => 'Add',
                        'route' => 'shop',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit',
                        'route' => 'shop',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'Delete',
                        'route' => 'shop',
                        'action' => 'delete',
                    ),
                ),
            ),
            array(
                'label' => 'Категории',
                'route' => 'category',
                'pages' => array(
                    array(
                        'label' => 'Add',
                        'route' => 'category',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit',
                        'route' => 'category',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'Delete',
                        'route' => 'category',
                        'action' => 'delete',
                    ),
                ),
            ),
            array(
                'label' => 'Товары',
                'route' => 'item',
                'pages' => array(
                    array(
                        'label' => 'Add',
                        'route' => 'item',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit',
                        'route' => 'item',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'Delete',
                        'route' => 'item',
                        'action' => 'delete',
                    ),
                ),
            ),
                        array(
                'label' => 'Новости',
                'route' => 'listnews',
                'pages' => array(
                    array(
                        'label' => 'Add',
                        'route' => 'listnews',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit',
                        'route' => 'listnews',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'Delete',
                        'route' => 'listnews',
                        'action' => 'delete',
                    ),
                ),
            ),
            array(
                'label' => 'Отзывы',
                'route' => 'reviews',
                'pages' => array(
                    array(
                        'label' => 'Add',
                        'route' => 'reviews',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit',
                        'route' => 'reviews',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'Delete',
                        'route' => 'reviews',
                        'action' => 'delete',
                    ),
                ),
            ),
            array(
                'label' => 'О нас',
                'route' => 'abouts',
            ),
        ),
        'cat_navigation' => array(
            array(
                'label' => 'Категории',
                'route' => 'categories',
            ),
            ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
    ),
    'module_config' => array(
        'key_map' => 'AIzaSyBzkHsMvKJuoRI_bfNnWqb070Gr30WNbOY',
    //'key_map' => 'AIzaSyB60oeZayvUJM36_ygEfnKLybrTSrKUv0o',
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
