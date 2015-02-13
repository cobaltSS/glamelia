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
    protected $subcategoryTable;

    public function indexAction() {

        $request = $this->getRequest();
        $where = array();
        if ($request->isPost()) {
            $search = $request->getPost()->get('data');
            foreach ($search as $key => $query) {
                if ($query) {
                    if ($key == 'status') {
                        if ($query >= '0')
                            $where['item.'.$key] = (int) $query;
                    } else
                        $where['item.'.$key . ' LIKE ?'] = '%' . $query . '%';
                }
            }
        }

// grab the paginator from the ItemTable
        $paginator = $this->getItemTable()->fetchAll(true, $where);
        
// set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
  // set the number of items per page to 10
        $paginator->setItemCountPerPage(10);
        $category = $this->getCategoryTable()->fetchAll();
        return new ViewModel(array(
            'paginator' => $paginator,
            'categorys' => $category,
            'search' => $search
        ));
    }

    public function getIndexLocation() {
// выборка конфигурации из конфигурационных данных модуля
        $config = $this->getServiceLocator()->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }
        if (!empty($config['module_config']['search_index'])) {
            return $config['module_config']['search_index'];
        } else {
            return FALSE;
        }
    }

    public function addAction() {
        $form = new ItemForm();
        $form->get('submit')->setValue('Добавить');
        $options = $this->GetListCategory();
        $form->get('category_id')->setAttribute('options', $options);

        $options2shop = $this->GetListShop();
        $form->get('shop_id')->setValueOptions($options2shop);


        $options_sub = $this->GetListSubcategory($item->subcategory_id, $item->category_id);
        if ($options_sub)
            $form->get('subcategory_id')->setAttribute('options', $options_sub);

        $adapter = new \Zend\File\Transfer\Adapter\Http();

        $request = $this->getRequest();

        if ($request->isPost()) {
            $item = new Item();
            $form->setInputFilter($item->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {

                $item->exchangeArray($form->getData());
                $uploadFiles = $this->params()->fromFiles('id_photo');
                if ($uploadFiles) {
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
                        $item->id = $this->getItemTable()->saveItem($item);
                        foreach ($uploadFiles as $file) {
                            if ($adapter->receive($file['name'])) {
                                $ext = split("[/\\.]", $file['name']);
                                $new_name = md5(microtime()) . '.' . $ext[count($ext) - 1];
                                $this->resizePhoto($file['name'], $new_name);

                                $data = array(
                                    'id_item' => $item->id,
                                    'patch' => $new_name,
                                    'status' => $item->status,
                                );
                                $item->id_photo = $this->getPhotoItemTable()->savePhoto($data);
                            }
                        }
                    }
                }
                $item->id = $this->getItemTable()->saveItem($item);
                $shop = $form->get('shop_id')->getValue();
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

        $options_sub = $this->GetListSubcategory($item->subcategory_id, $item->category_id);
        if ($options_sub)
            $form->get('subcategory_id')->setAttribute('options', $options_sub);

        $options2shop = $this->GetListShop($item);
        $form->get('shop_id')->setValueOptions($options2shop);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($item->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $uploadFiles = $this->params()->fromFiles('id_photo');
                $uploadPath = $this->getFileUploadLocation();

                if ($uploadFiles) {
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
                        foreach ($uploadFiles as $file) {
                            if ($adapter->receive($file['name'])) {
                                $ext = split("[/\\.]", $file['name']);
                                $new_name = md5(microtime()) . '.' . $ext[count($ext) - 1];
                                $this->resizePhoto($file['name'], $new_name);
                                $data = array(
                                    'id_item' => $id,
                                    'patch' => $new_name,
                                    'status' => $item->status,
                                );
                                $item->id_photo = $this->getPhotoItemTable()->savePhoto($data);
                            }
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

    public function GetListSubcategory($subcategory_id, $cat_id) {
        $subcategories = $this->getSubcategoryTable()->getSubcategories($cat_id);
        for ($i = 0; $i < sizeof($subcategories); $i++) {
            if ($subcategories[$i]['id'] == $subcategory_id) {
                $selected = true;
            } else {
                $selected = false;
            }
            $options[] = (
                    array(
                        'value' => $subcategories[$i]['id'],
                        'label' => $subcategories[$i]['name'],
                        'selected' => $selected,
            ));
        }
        return $options;
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

    public function getFileUploadLocation() {
// Получение конфигурации из конфигурационных данных модуля
        $config = $this->getServiceLocator()->get('config');
        return $config['module_config']['upload_location'];
    }

    public function resizePhoto($name, $new_name) {
        $uploadPath = $this->getFileUploadLocation();
        $filename = $uploadPath . '/' . $name;
        $small_filename = $uploadPath . '/small_' . $new_name;
        $big_filename = $uploadPath . '/big_' . $new_name;
        $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
        $thumb_small = $thumbnailer->create($filename, $options = array());
        $thumb_big = $thumbnailer->create($filename, $options = array());
        $thumb_big->adaptiveResize(700, 280);
        $thumb_big->save($big_filename);

        $thumb_big->adaptiveResize(140, 140);
        $thumb_small->save($small_filename);

        unlink($uploadPath . '/' . $name);

        /* $cmd = "/usr/bin/convert -resize 200 -gravity center  -crop 140x140+0+0 +repage    {$filename} {$small_filename}";
          exec($cmd . " 2>&1", $out, $retVal);

          $cmd = "/usr/bin/convert -resize 700 -gravity center  -crop 700x280+0+0 +repage    {$filename} {$big_filename}";
          exec($cmd . " 2>&1", $out, $retVal);

          $cmd = "/usr/bin/convert -resize 500 {$filename} {$filename}";
          exec($cmd . " 2>&1", $out, $retVal); */
    }

    function additionsearchAction() {

        $request = $this->getRequest();
        $key = $request->getQuery('key');
        $query = $request->getQuery('term');
        $where = array();
        $where[$key . ' LIKE ?'] = '%' . $query . '%';
        $info = $this->getItemTable()->fetchAll(false, $where);
        foreach ($info as $val) {
            $results[] = array('label' => $val->$key);
        }
        echo json_encode($results);
        exit();
        /* $result = new JsonModel(array(
          'results' => $results
          ));
          return $result;)
         * 
         */
    }

    /* $info_val = mysql_escape_string($_GET['term']);
      $key = $_GET['key'];
      $where = array(
      $key => 'LIKE "%' . $info_val . '%"',
      );
      $info = $dbOrder->getAdditionSearch($key, $where);
      foreach ($info as $val)
      $results[] = array('label' => $val[$key]);
      echo json_encode($results);
      exit();
      } */
}
