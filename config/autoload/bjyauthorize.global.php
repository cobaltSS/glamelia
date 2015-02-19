<?php

return array(
    'bjyauthorize' => array(
        'default_role' => 'guest',
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',
        // 'identity_provider'  => '\BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider::class'a,
        'authenticated_role' => 'user',
        'role_providers' => array(
// using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\Config' => array(
                'guest' => array(),
                'user' => array('children' => array(
                        'admin' => array(),
                    )),
            ),
        ),
        'guards' => array(

            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'zfcuser',
                    'action' => array('index'),
                    'roles' => array('guest', 'user'),
                ),
                array(
                    'controller' => 'zfcuser',
                    'action' => array('login', 'authenticate', 'register'),
                    'roles' => array('guest'),
                ),
                array(
                    'controller' => 'zfcuser',
                    'action' => array('logout'),
                    'roles' => array('user'),
                ),
                array('controller' => 'Application\Controller\Index', 'roles' => array('guest', 'user')),
              /*  array(
                    'controller' => 'MyBlog\Controller\BlogPost',
                    'action' => array('index', 'view'),
                    'roles' => array('guest', 'user'),
                ),*/
                
                array(
                    'controller' => 'ShopModule\Controller\Shop',
                  //  'action' => array('add', 'edit', 'delete','index'),
                    'roles' => array('user'),
                ),
                
                array(
                    'controller' => 'ShopModule\Controller\Shop',
                   // 'action' => array('add', 'edit', 'delete','index'),
                    'roles' => array('user'),
                ),
                
                array(
                    'controller' => 'About\Controller\About',
                 //   'action' => array('add', 'edit', 'delete','index'),
                    'roles' => array('user'),
                ),
                
                 array(
                    'controller' => 'ItemModule\Controller\Item',
                 //   'action' => array('add', 'edit', 'delete','index'),
                    'roles' => array('user'),
                ),
                
                 array(
                    'controller' => 'News\Controller\News',
                 //   'action' => array('add', 'edit', 'delete','index'),
                    'roles' => array('user'),
                ),
                
                 array(
                    'controller' => 'Reviews\Controller\Reviews',
                //    'action' => array('add', 'edit', 'delete','index'),
                    'roles' => array('user'),
                ),
                
               
                 array(
                    'controller' => 'Category\Controller\Category',
                    'action' => array('add', 'edit', 'delete','index'),
                    'roles' => array('user'),
                ),

            ),
        ),
    ),
);
