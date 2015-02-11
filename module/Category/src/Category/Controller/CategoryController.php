<?php

namespace Category\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Category\Model\Category;
use Category\Form\CategoryForm;
use Zend\View\Model\JsonModel;
use Zend\Mvc\Controller\Plugin\Url;

class CategoryController extends AbstractActionController {

    protected $categoryTable;
    protected $subcategoryTable;

    public function indexAction() {

        $request = $this->getRequest();
        $where = array();
        if ($request->isPost()) {
            $search = $request->getPost()->get('data');
            foreach ($search as $key => $query) {
                if ($key == 'status')
                {
                    if($query>='0')
                        $where[$key] = (int) $query;
                }
                else
                    $where[$key . ' LIKE ?'] = '%' . $query . '%';
            }
        }

        // grab the paginator from the categoryTable
        $paginator = $this->getCategoryTable()->fetchAll(true, $where);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage(10);

        return new ViewModel(array(
            'paginator' => $paginator,
            'search' => $search
        ));
    }

    public function addAction() {
        $form = new CategoryForm();
        $form->get('submit')->setValue('Добавить');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $category = new Category();
            $form->setInputFilter($category->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $category->exchangeArray($form->getData());
                $this->getCategoryTable()->saveCategory($category);

                // Redirect to list of category
                return $this->redirect()->toRoute('category');
            }
        }
        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('category', array(
                        'action' => 'add'
            ));
        }

// Get the Category with the specified id. An exception is thrown
// if it cannot be found, in which case go to the index page.
        try {
            $category = $this->getCategoryTable()->getCategory($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('category', array(
                        'action' => 'index'
            ));
        }

        $form = new CategoryForm();
        $form->bind($category);
        $form->get('submit')->setAttribute('value', 'Редактировать');
        $subcategories = $this->getSubcategoryTable()->getSubcategories($id);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($category->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getCategoryTable()->saveCategory($category);

// Redirect to list of $categorys
                return $this->redirect()->toRoute('category');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
            'subcategories' => $subcategories,
        );
    }

    public function getsubcatAction() {
        $request = $this->getRequest();
        $id_category = $request->getPost('id_category');
        $subcat = $this->getSubcategoryTable()->getSubcategories($id_category);
        $result = new JsonModel(array(
            'subcat' => $subcat
        ));
        return $result;
    }

    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('category');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Нет');

            if ($del == 'Да') {
                $id = (int) $request->getPost('id');
                $this->getCategoryTable()->deleteCategory($id);
            }

// Redirect to list of albums
            return $this->redirect()->toRoute('category');
        }
        return array(
            'id' => $id,
            'category' => $this->getCategoryTable()->getCategory($id)
        );
    }

    public function deletesubcatAction() {
        $request = $this->getRequest();
        $id = $request->getPost('qString');
        $this->getSubcategoryTable()->deleteSubcategory($id);
        $text = "successfully processed";
        $result = new JsonModel(array(
            'text' => $text
        ));
        return $result;
    }

    public function addsubcatAction() {

        $request = $this->getRequest();

        $id_category = $request->getPost('id_cat');
        $id = $request->getPost('id');
        $name = $request->getPost('name');

        $data = array(
            'id_category' => $id_category,
            'name' => $name,
            'id' => $id
        );

        $id = $this->getsubcategoryTable()->saveSubcategory($data);

        #do something with the data
        $text = $text . "successfully processed";
        $result = new JsonModel(array(
            'id_sub' => (int) $id,
            'urldel' => '/admin/category/deletesubcat',
            'urledit' => '/admin/category/addsubcat'
        ));
        return $result;
    }

    public function getsubcategoryTable() {

        if (!$this->subcategoryTable) {
            $sm = $this->getServiceLocator();
            $this->subcategoryTable = $sm->get('Category\Model\SubcategoryTable');
        }
        return $this->subcategoryTable;
    }

    public function getCategoryTable() {
        if (!$this->categoryTable) {
            $sm = $this->getServiceLocator();
            $this->categoryTable = $sm->get('Category\Model\CategoryTable');
        }
        return $this->categoryTable;
    }

}
