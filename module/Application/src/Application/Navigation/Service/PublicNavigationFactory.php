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
            $i = 0;
            $j = 0;
            foreach ($fetchMenu['navigation'][$this->getName()] as $key => $row) {
                switch ($row['route']) {
                    case 'shops':
                        // $cities = $serviceLocator->get('ShopModule\Model\CityTable')->fetchAll();
                        $cities = $serviceLocator->get('ShopModule\Model\CityTable')->getCity2Shop();
                        foreach ($cities as $city) {
                            $configuration['navigation'][$this->getName()][$i]['pages'][$j] = array(
                                'label' => $city['name'],
                                'route' => $row['route'],
                            );
                            $address = explode(':', $city['address']);
                            $shop_id = explode(':', $city['shop_id']);

                            foreach ($address as $key => $shop) {
                                $configuration['navigation'][$this->getName()][$i]['pages'][$j]['pages'][] = array(
                                    'label' => $shop,
                                    'route' => $row['route'],
                                    'action' => $row['route'],
                                    'params'     => array('id' => $shop_id[$key]),
                                );
                            }
                            $j++;
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
