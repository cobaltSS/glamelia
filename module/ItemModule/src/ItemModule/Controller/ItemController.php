<?php

namespace ItemModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ItemModule\Model\Item;
use ItemModule\Form\ItemForm;
use Zend\View\Model\JsonModel;

class ItemController extends AbstractActionController {

    protected $itemTable;
    protected $photoItemTable;
    protected $shopTable;
    protected $items2shopTable;
    protected $categoryTable;

    public function indexAction() {

        // grab the paginator from the ItemTable
        $paginator = $this->getItemTable()->fetchAll(true);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage(10);

        return new ViewModel(array(
            'paginator' => $paginator
        ));
    }

    public function addAction() {
        $form = new ItemForm();
        $form->get('submit')->setValue('Добавить');
        $options = $this->GetListCategory();
        $form->get('category_id')->setAttribute('options', $options);


        $options2shop = $this->GetListShop();
        $form->get('shop_id')->setValueOptions($options2shop);

        $adapter = new \Zend\File\Transfer\Adapter\Http();

        $request = $this->getRequest();

        if ($request->isPost()) {
            $item = new Item();
            $form->setInputFilter($item->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $item->exchangeArray($form->getData());
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
                            $item->id = $this->getItemTable()->saveItem($item);
                            $data = array(
                                'id_item' => $item->id,
                                'patch' => $uploadFile['name'],
                                'status' => $item->status,
                            );
                            $item->id_photo = $this->getPhotoItemTable()->savePhoto($data);
                        }
                    }
                }
                $item->id = $this->getItemTable()->saveItem($item);
                $shop = $form->get('shop_id')->getValueOptions();
                $this->getItems2ShopTable()->saveItem2Shop($item->id, $shop);

                // Redirect to list of item
                return $this->redirect()->toRoute('item');
            }
        }
        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);


        if (!$id) {
            return $this->redirect()->toRoute('item', array(
                        'action' => 'add'
            ));
        }

        // Get the Item with the specified id. An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $item = $this->getItemTable()->getItem($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('item', array(
                        'action' => 'index'
            ));
        }

        $form = new ItemForm();
        $form->bind($item);
        $form->get('submit')->setAttribute('value', 'Редактировать');

        $options = $this->GetListCategory($item->category_id);
        $form->get('category_id')->setAttribute('options', $options);

        $options2shop = $this->GetListShop($item);
        $form->get('shop_id')->setValueOptions($options2shop);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($item->getInputFilter());
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
                                'id_item' => $id,
                                'patch' => $uploadFile['name'],
                                'status' => $item->status,
                            );
                            $item->id_photo = $this->getPhotoItemTable()->savePhoto($data);
                        }
                    }
                }
                $this->getItemTable()->saveItem($item);
                $shop = $form->get('shop_id')->getValue();
                $this->getItems2ShopTable()->saveItem2Shop($item->id, $shop);

                // Redirect to list of $items
                return $this->redirect()->toRoute('item');
            }
        }
        if ($item->patch) {
            $patch = explode(',', $item->patch);
        }

        return array(
            'id' => $id,
            'form' => $form,
            'photos' => $patch,
        );
    }

    public function GetListCategory($category_id) {
        $categories = $this->getCategoryTable()->getCategoryList();
        for ($i = 0; $i < sizeof($categories); $i++) {
            if ($categories[$i]['id'] == $category_id) {
                $selected = true;
            } else {
                $selected = false;
            }
            $options[] = (
                    array(
                        'value' => $categories[$i]['id'],
                        'label' => $categories[$i]['name'],
                        'selected' => $selected,
            ));
        }
        return $options;
    }

    public function GetListShop($item) {
        $shops = $this->getShopTable()->getShopList();
        $id_shop = explode(',', $item->id_shop);
        for ($i = 0; $i < sizeof($shops); $i++) {
            if (in_array($shops[$i]['id'], (array) $id_shop)) {
                $selected = true;
            } else {
                $selected = false;
            }
            $options[] = (
                    array(
                        'value' => $shops[$i]['id'],
                        'label' => $shops[$i]['address'],
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
        $this->getPhotoItemTable()->deletePhotoWhere($data);
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
            return $this->redirect()->toRoute('item');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Да') {
                $id = (int) $request->getPost('id');
                $this->getItemTable()->deleteItem($id);
            }

            // Redirect to list of item
            return $this->redirect()->toRoute('item');
        }
        return array(
            'id' => $id,
            'item' => $this->getItemTable()->getItem($id)
        );
    }

    public function getItemTable() {
        if (!$this->itemTable) {
            $sm = $this->getServiceLocator();
            $this->itemTable = $sm->get('ItemModule\Model\ItemTable');
        }
        return $this->itemTable;
    }

    public function getShopTable() {
        if (!$this->shopTable) {
            $sm = $this->getServiceLocator();
            $this->shopTable = $sm->get('ShopModule\Model\ShopTable');
        }
        return $this->shopTable;
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

    public function getPhotoItemTable() {
        if (!$this->photoItemTable) {
            $sm = $this->getServiceLocator();
            $this->photoItemTable = $sm->get('ItemModule\Model\PhotoItemTable');
        }
        return $this->photoItemTable;
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
