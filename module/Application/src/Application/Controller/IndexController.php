<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ShopModule\Form\ShopForm;
use Reviews\Model\Reviews;
use Reviews\Form\ReviewsForm;
use About\Model\About;
use About\Form\AboutForm;

class IndexController extends AbstractActionController {

    protected $shopTable;
    protected $cityTable;
    protected $photoShopTable;
    protected $reviewsTable;
    protected $categoryTable;
    protected $subcategoryTable;
    protected $items2shopTable;
    protected $itemTable;
    protected $photoItemTable;
    public $limit = '5';

    public function indexAction() {
        $reviews = $this->getReviewsTable()->getReviewsRandom($this->limit);
        $action_items = $this->getItemTable()->getActionItemsRandom(array('action' => '1'), $this->limit);
        $items = $this->getItemTable()->getItems('12');
        $shops = $this->getShopTable()->getShops('5');

        return array(
            'reviews' => $reviews,
            'action_items' => $action_items,
            'items' => $items,
            'shops' => $shops,
        );
    }

    // Show Shop
    // return array

    public function shopsAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('home', array(
            ));
        }

        try {
            $shop = $this->getShopTable()->getShop($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('home', array(
            ));
        }

        $form = new ShopForm();
        $form->bind($shop);

        $options = $this->GetListCity($shop->city_id);
        $form->get('city_id')->setAttribute('options', $options);

        if ($shop->patch) {
            $patch = explode(',', $shop->patch);
        }

        return array(
            'id' => $id,
            'form' => $form,
            'photos' => $patch,
            'key_map' => $this->getkeyApiLocation(),
        );
    }

    // Show Item
    // return array

    public function itemsAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('home', array(
            ));
        }

        try {
            $item = $this->getItemTable()->getItem($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('home', array(
            ));
        }

        return array(
            'item' => $item,
        );
    }
    
    public function newAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('home', array(
            ));
        }

        try {
            $new = $this->getNewsTable()->getNew($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('home', array(
            ));
        }

        return array(
            'new' => $new,
        );
    }
    
     public function newsAction() {
        try {
            $news = $this->getNewsTable()->getNews();
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('home', array(
            ));
        }

        return array(
            'news' => $news,
        );
    }

    // Show Shop
    // return array

    public function categoriesAction() {
        $id_cat = (int) $this->params()->fromRoute('id', 0);
        $id_sub = (int) $this->params()->fromRoute('id_sub', 0);
        if (!$id_cat && !$id_sub) {
            return $this->redirect()->toRoute('home', array(
            ));
        }
        try {
            if ($id_cat && !$id_sub)
                $items = $this->getItemTable()->getItems2Category($id_cat);
            else if ($id_sub)
                $items = $this->getItemTable()->getItems2SubCategory($id_sub);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('home', array(
            ));
        }

        /*  foreach ($items as $item) {
          if ($item['patch']) {
          $item['patch'] = explode(',', $item['patch']);
          $item['id_shop'] = explode(',', $item['id_shop']);
          }
          } */
        return array(
            'items' => $items,
        );
    }

    public function getkeyApiLocation() {
        // Получение конфигурации из конфигурационных данных модуля
        $config = $this->getServiceLocator()->get('config');
        return $config['module_config']['key_map'];
    }

    public function GetListCity($city_id) {
        $citys = $this->getCityTable()->getCityList();
        for ($i = 0; $i < sizeof($citys); $i++) {
            if ($citys[$i]['id'] == $city_id) {
                $selected = true;
            } else {
                $selected = false;
            }
            $options[] = (
                    array(
                        'value' => $citys[$i]['id'],
                        'label' => $citys[$i]['name'],
                        'selected' => $selected,
            ));
        }
        return $options;
    }

    // connect shop table
    public function getShopTable() {
        if (!$this->shopTable) {
            $sm = $this->getServiceLocator();
            $this->shopTable = $sm->get('ShopModule\Model\ShopTable');
        }
        return $this->shopTable;
    }

    // connect city table
    public function getCityTable() {
        if (!$this->cityTable) {
            $sm = $this->getServiceLocator();
            $this->cityTable = $sm->get('ShopModule\Model\CityTable');
        }
        return $this->cityTable;
    }

    public function getItemTable() {
        if (!$this->itemTable) {
            $sm = $this->getServiceLocator();
            $this->itemTable = $sm->get('ItemModule\Model\ItemTable');
        }
        return $this->itemTable;
    }

    public function getItems2ShopTable() {
        if (!$this->items2shopTable) {
            $sm = $this->getServiceLocator();
            $this->items2shopTable = $sm->get('ItemModule\Model\Items2ShopTable');
        }
        return $this->items2shopTable;
    }

    public function getCategoryTable() {
        if (!$this->categoryTable) {
            $sm = $this->getServiceLocator();
            $this->categoryTable = $sm->get('Category\Model\CategoryTable');
        }
        return $this->categoryTable;
    }

    public function getSubcategoryTable() {
        if (!$this->subcategoryTable) {
            $sm = $this->getServiceLocator();
            $this->subcategoryTable = $sm->get('Category\Model\SubcategoryTable');
        }
        return $this->subcategoryTable;
    }

    public function getPhotoItemTable() {
        if (!$this->photoItemTable) {
            $sm = $this->getServiceLocator();
            $this->photoItemTable = $sm->get('ItemModule\Model\PhotoItemTable');
        }
        return $this->photoItemTable;
    }

    // add review
    public function reviewAction() {
        $form = new ReviewsForm();
        $form->get('submit')->setValue('Добавить');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $reviews = new Reviews();
            $form->setInputFilter($reviews->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $reviews->exchangeArray($form->getData());
                $this->getReviewsTable()->saveReviews($reviews);
                // Redirect to  review
                return $this->redirect()->toRoute('review');
            }
        }
        return array('form' => $form);
    }

    // connect Reviews table
    public function getReviewsTable() {
        if (!$this->reviewsTable) {
            $sm = $this->getServiceLocator();
            $this->reviewsTable = $sm->get('Reviews\Model\ReviewsTable');
        }
        return $this->reviewsTable;
    }

    public function aboutAction() {

        $about = $this->getAboutTable()->getAbout();
        $form = new AboutForm();
        $form->bind($about);
        return array(
            'form' => $form,
        );
    }

    // connect Reviews table
    public function getAboutTable() {
        if (!$this->aboutTable) {
            $sm = $this->getServiceLocator();
            $this->aboutTable = $sm->get('About\Model\AboutTable');
        }
        return $this->aboutTable;
    }

}
