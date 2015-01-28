<?php

namespace Category\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Category\Model\Category;
use Category\Form\CategoryForm;
use Category\Form\CategoryTable;

class CategoryController extends AbstractActionController {

    protected $categoryTable;

    public function indexAction() {

        // grab the paginator from the categoryTable
        $paginator = $this->getCategoryTable()->fetchAll(true);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage(10);

        return new ViewModel(array(
            'paginator' => $paginator
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
        );
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

    public function getCategoryTable() {
        if (!$this->categoryTable) {
            $sm = $this->getServiceLocator();
            $this->categoryTable = $sm->get('Category\Model\CategoryTable');
        }
        return $this->categoryTable;
    }

}
