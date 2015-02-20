<?php

namespace Application\Navigation\Service;

use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class CategoryNavigationFactory extends DefaultNavigationFactory {

    protected function getName() {
        return 'cat_navigation';
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
                    case 'categories':
                        $request = $serviceLocator->get('Application')->getMvcEvent()->getRouteMatch()->getParams();
                        $id_shop = $request['id'];
                        if ($request['action'] == 'shops')
                            $categories = $serviceLocator->get('Category\Model\CategoryTable')->getCategory2SubForShop($id_shop,array('category.status'=>1));
                        else
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
                        break;
                   
                    default:
                        break;
                }

                $i++;
            }
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

        return $this->pages;
    }

}
