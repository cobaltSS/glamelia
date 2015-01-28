<?php

namespace ShopModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ShopModule\Model\Shop;
use ShopModule\Form\ShopForm;
use Zend\View\Model\JsonModel;

class ShopController extends AbstractActionController {

    protected $shopTable;
    protected $cityTable;
    protected $photoShopTable;

    public function indexAction() {

        // grab the paginator from the ShopTable
        $paginator = $this->getShopTable()->fetchAll(true);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage(10);

        return new ViewModel(array(
            'paginator' => $paginator
        ));
    }

    public function addAction() {
        $form = new ShopForm();
        $form->get('submit')->setValue('Добавить');
        $options = $this->GetListCity();
        $form->get('city_id')->setAttribute('options', $options);
        $adapter = new \Zend\File\Transfer\Adapter\Http();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $shop = new Shop();
            $form->setInputFilter($shop->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $shop->exchangeArray($form->getData());
                $uploadFile = $this->params()->fromFiles('id_photo');
                if ($uploadFile) {
                    $uploadPath = $this->getFileUploadLocation();
                    // Сохранение выгруженного файла
                    $adapter = new \Zend\File\Transfer\Adapter\Http();
                    $adapter->addValidator('Size', false, array('max' => '15242880'));
                    if (!$adapter->isValid()) {
                        $dataError = $adapter->getMessages();
                        $error = array();
                        foreach ($dataError as $key => $row) {
                            $error[] = $row;
                        }
                        $form->setMessages(array('fileupload' => $error));
                    } else {

                        $adapter->setDestination($uploadPath);
                        if ($adapter->receive($uploadFile['name'])) {
                            $this->resizePhoto($uploadFile['name']);
                            $shop->id = $this->getShopTable()->saveShop($shop);
                            $data = array(
                                'id_shop' => $shop->id,
                                'patch' => $uploadFile['name'],
                                'status' => $shop->status,
                            );
                            $shop->id_photo = $this->getPhotoShopTable()->savePhoto($data);
                        }
                    }
                }
                $this->getShopTable()->saveShop($shop);

                // Redirect to list of shop
                return $this->redirect()->toRoute('shop');
            }
        }
        return array('form' => $form,
            'key_map' => $this->getkeyApiLocation()
        );
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('shop', array(
                        'action' => 'add'
            ));
        }

        // Get the Shop with the specified id. An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $shop = $this->getShopTable()->getShop($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('shop', array(
                        'action' => 'index'
            ));
        }

        $form = new ShopForm();
        $form->bind($shop);
        $form->get('submit')->setAttribute('value', 'Редактировать');

        $options = $this->GetListCity($shop->city_id);
        $form->get('city_id')->setAttribute('options', $options);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($shop->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $uploadFile = $this->params()->fromFiles('id_photo');
                $uploadPath = $this->getFileUploadLocation();

                if ($uploadFile) {
                    // Сохранение выгруженного файла
                    $adapter = new \Zend\File\Transfer\Adapter\Http();
                    $adapter->addValidator('Size', false, array('max' => '15242880'));
                    if (!$adapter->isValid()) {
                        $dataError = $adapter->getMessages();
                        $error = array();
                        foreach ($dataError as $key => $row) {
                            $error[] = $row;
                        }
                        $form->setMessages(array('fileupload' => $error));
                    } else {
                        $adapter->setDestination($uploadPath);
                        if ($adapter->receive($uploadFile['name'])) {
                            $this->resizePhoto($uploadFile['name']);
                            $data = array(
                                'id_shop' => $id,
                                'patch' => $uploadFile['name'],
                                'status' => $shop->status,
                            );
                            $shop->id_photo = $this->getPhotoShopTable()->savePhoto($data);
                        }
                    }
                }
                $this->getShopTable()->saveShop($shop);

                // Redirect to list of $shops
                return $this->redirect()->toRoute('shop');
            }
        }
        if ($shop->patch) {
            $patch = explode(',', $shop->patch);
        }

        return array(
            'id' => $id,
            'form' => $form,
            'photos' => $patch,
            'key_map' => $this->getkeyApiLocation()
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

    public function deletephotoAction() {
        $request = $this->getRequest();
        $patch = $request->getPost('qString');
        $data = array(
            'patch' => $patch,
        );
        $this->getPhotoShopTable()->deletePhotoWhere($data);
        $uploadPath = $this->getFileUploadLocation();
        try {
            unlink($uploadPath . '/' . $patch);
            unlink($uploadPath . '/small_' . $patch);
            unlink($uploadPath . '/big_' . $patch);
        } catch (Exception $ex) {
            
        }
        #do something with the data
        $text = $text . "successfully processed";
        $result = new JsonModel(array(
            'text' => $text
        ));
        return $result;
    }

    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('shop');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Да') {
                $id = (int) $request->getPost('id');
                $this->getShopTable()->deleteShop($id);
            }

            // Redirect to list of shop
            return $this->redirect()->toRoute('shop');
        }
        return array(
            'id' => $id,
            'shop' => $this->getShopTable()->getShop($id)
        );
    }

    public function getShopTable() {
        if (!$this->shopTable) {
            $sm = $this->getServiceLocator();
            $this->shopTable = $sm->get('ShopModule\Model\ShopTable');
        }
        return $this->shopTable;
    }

    public function getCityTable() {
        if (!$this->cityTable) {
            $sm = $this->getServiceLocator();
            $this->cityTable = $sm->get('ShopModule\Model\CityTable');
        }
        return $this->cityTable;
    }

    public function getPhotoShopTable() {
        if (!$this->photoShopTable) {
            $sm = $this->getServiceLocator();
            $this->photoShopTable = $sm->get('ShopModule\Model\PhotoShopTable');
        }
        return $this->photoShopTable;
    }

    public function getFileUploadLocation() {
        // Получение конфигурации из конфигурационных данных модуля
        $config = $this->getServiceLocator()->get('config');
        return $config['module_config']['upload_location'];
    }

    public function resizePhoto($name) {
        $uploadPath = $this->getFileUploadLocation();
        $filename = $uploadPath . '/' . $name;
        $small_filename = $uploadPath . '/small_' . $name;
        $big_filename = $uploadPath . '/big_' . $name;
        $cmd = "/usr/bin/convert -resize 150 {$filename} {$small_filename}";
        exec($cmd . " 2>&1", $out, $retVal);

        $cmd = "/usr/bin/convert -resize 800 {$filename} {$big_filename}";
        exec($cmd . " 2>&1", $out, $retVal);

        $cmd = "/usr/bin/convert -resize 500 {$filename} {$filename}";
        exec($cmd . " 2>&1", $out, $retVal);
    }

}