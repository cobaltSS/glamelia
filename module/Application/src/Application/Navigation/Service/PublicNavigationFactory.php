<?php

namespace Application\Navigation\Service;

use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class PublicNavigationFactory extends DefaultNavigationFactory {

    protected function getName() {
        return 'public_navigation';
    }

    protected function getPages(ServiceLocatorInterface $serviceLocator) {

        if (null === $this->pages) {
            $fetchMenu = $serviceLocator->get('Config');
            $configuration['navigation'][$this->getName()] = $fetchMenu['navigation'][$this->getName()];
            $i=0;
            foreach ($fetchMenu['navigation'][$this->getName()] as $key => $row) {
                switch ($row['route']) {
                    case 'shops':
                       // $cities = $serviceLocator->get('ShopModule\Model\CityTable')->fetchAll();
                         $cities = $serviceLocator->get('ShopModule\Model\CityTable')->getCity2Shop();
                        foreach ($cities as $city) {
                            $configuration['navigation'][$this->getName()][$i]['pages'][] = array(
                                'label' => $city['name'],
                                'route' => $row['route'],
                            );
                        
                            
                            
                            
                        }
                        break;
                    default:
                        break;
                }
                $i++;
            }
            if (!isset($configuration['navigation'])) {
                throw new Exception\InvalidArgumentException('Could not find navigation configuration key');
            }
            if (!isset($configuration['navigation'][$this->getName()])) {
                throw new Exception\InvalidArgumentException(sprintf(
                        'Failed to find a navigation container by the name "%s"', $this->getName()
                ));
            }

            $application = $serviceLocator->get('Application');
            $routeMatch = $application->getMvcEvent()->getRouteMatch();
            $router = $application->getMvcEvent()->getRouter();
            $pages = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);

            $this->pages = $this->injectComponents($pages, $routeMatch, $router);
        }
        return $this->pages;
    }

}
