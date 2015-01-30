<?php

namespace ShopModule\Navigation\Service;

use Zend\Navigation\Service\DefaultNavigationFactory;

class AdminNavigationFactory extends DefaultNavigationFactory {

    protected function getName() {
        return 'admin_navigation';
    }

}
