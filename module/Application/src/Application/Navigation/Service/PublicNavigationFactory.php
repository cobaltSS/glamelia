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
            $k = 0;
            
            foreach ($fetchMenu['navigation'][$this->getName()] as $key => $row) {
                switch ($row['route']) {
                    case 'shops':
                        // $cities = $serviceLocator->get('ShopModule\Model\CityTable')->fetchAll();
                        $cities = $serviceLocator->get('ShopModule\Model\CityTable')->getCity2Shop(array('shop.status'=>'1'));
                        foreach ($cities as $city) {
                            $configuration['navigation'][$this->getName()][$i]['pages'][$j] = array(
                                'label' => $city['name'],
                                'route' => $row['route'],
                                'action' => $row['route'],
                                'params' => array('id' => $city['id']),
                            );
                            $address = explode(':', $city['address']);
                            $shop_id = explode(':', $city['shop_id']);
                            foreach ($address as $key => $shop) {
                                $shop_short=explode(',',$shop);
                                $configuration['navigation'][$this->getName()][$i]['pages'][$j]['pages'][] = array(
                                    'label' => $shop_short[0],
                                    'route' => $row['route'],
                                    'action' => 'shop',
                                    'params' => array('id' => $shop_id[$key]),
                                );
                            }
                            $j++;
                        }
                        break;

                    case 'categories':
                        $categories = $serviceLocator->get('Category\Model\CategoryTable')->getCategory2Sub(array('category.status'=>1));
                        foreach ($categories as $category) {
                            $configuration['navigation'][$this->getName()][$i]['pages'][$k] = array(
                                'label' => $category['name'],
                                'route' => $row['route'],
                                'action' => $row['route'],
                                'params' => array('id' => $category['id']),
                            );

                            if ($category['subname']) {
                                $name_sub = explode(':', $category['subname']);
                                $subcategory_id = explode(':', $category['subcategory_id']);

                                foreach ($name_sub as $key => $sub) {
                                    $configuration['navigation'][$this->getName()][$i]['pages'][$k]['pages'][] = array(
                                        'label' => $sub,
                                        'route' => $row['route'],
                                        'action' => $row['route'],
                                        'params' => array('id' => $category['id'], 'id_sub' => $subcategory_id[$key]),
                                    );
                                }
                            }
                            $k++;
                        }
                        $configuration['navigation'][$this->getName()][$i]['pages'][$k] = array(
                            'label' => 'Акции',
                            'route' => 'categories',
                            'action' => "categories",
                            'params' => array('action_p'=>'action=1'),
                        );
                        break;
                    default:
                        break;
                }

                $i++;
            }
        }
       /*  if (!isset($configuration['navigation'])) {
            throw new Exception\InvalidArgumentException('Could not find navigation configuration key');
        }
       if (!isset($configuration['navigation'][$this->getName()])) {
            throw new Exception\InvalidArgumentException(sprintf(
                    'Failed to find a navigation container by the name "%s"', $this->getName()
            ));
        }*/

        $application = $serviceLocator->get('Application');
        $routeMatch = $application->getMvcEvent()->getRouteMatch();
        $router = $application->getMvcEvent()->getRouter();
        $pages = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);

        $this->pages = $this->injectComponents($pages, $routeMatch, $router);

        return $this->pages;
    }

}
