<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ShopModule\Form\ShopForm;
use Reviews\Model\Reviews;
use Reviews\Form\ReviewsForm;

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

    public function indexAction() {
        return new ViewModel();
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

    // Show Shop
    // return array

    public function categoriesAction() {
        $id_cat = (int) $this->params()->fromRoute('id_cat', 0);
        $id_sub = (int) $this->params()->fromRoute('id_sub', 0);
        if (!$id_cat && !$id_sub) {
            return $this->redirect()->toRoute('home', array(
            ));
        }

        try {
            if ($id_cat)
                $items = $this->getItemTable()->getItems2Category($id_cat);
            if ($id_sub)
                $items = $this->getItemTable()->getItems2SubCategory($id_sub);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('home', array(
            ));
        }

        $form = new ItemForm();
        $form->bind($items);

        if ($items->patch) {
            $patch = explode(',', $items->patch);
        }

        return array(
            'id' => $id,
            'form' => $form,
            'photos' => $patch,
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

}
