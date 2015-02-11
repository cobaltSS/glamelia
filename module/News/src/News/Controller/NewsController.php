<?php

namespace News\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use News\Model\News;
use News\Form\NewsForm;

class NewsController extends AbstractActionController {

    protected $newsTable;

    public function indexAction() {

        $request = $this->getRequest();
        $where = array();
        if ($request->isPost()) {
            $search = $request->getPost()->get('data');
            foreach ($search as $key => $query) {
                if ($key == 'status' && ($query))
                    $where[$key] = (int) $query;
                else
                    $where[$key . ' LIKE ?'] = '%' . $query . '%';
            }
        }
        // grab the paginator from the newsTable
        $paginator = $this->getNewsTable()->fetchAll(true, $where);
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
        $form = new NewsForm();
        $form->get('submit')->setValue('Добавить');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $news = new News();
            $form->setInputFilter($news->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $news->exchangeArray($form->getData());
                $this->getNewsTable()->saveNews($news);

                // Redirect to list of news
                return $this->redirect()->toRoute('listnews');
            }
        }
        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('listnews', array(
                        'action' => 'add'
            ));
        }

// Get the News with the specified id. An exception is thrown
// if it cannot be found, in which case go to the index page.
        try {
            $news = $this->getNewsTable()->getNews($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('listnews', array(
                        'action' => 'index'
            ));
        }

        $form = new NewsForm();
        $form->bind($news);
        $form->get('submit')->setAttribute('value', 'Редактировать');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($news->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getNewsTable()->saveNews($news);

// Redirect to list of $newss
                return $this->redirect()->toRoute('listnews');
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
            return $this->redirect()->toRoute('listnews');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Нет');

            if ($del == 'Да') {
                $id = (int) $request->getPost('id');
                $this->getNewsTable()->deleteNews($id);
            }

// Redirect to list of albums
            return $this->redirect()->toRoute('listnews');
        }
        return array(
            'id' => $id,
            'news' => $this->getNewsTable()->getNews($id)
        );
    }

    public function getNewsTable() {
        if (!$this->newsTable) {
            $sm = $this->getServiceLocator();
            $this->newsTable = $sm->get('News\Model\NewsTable');
        }
        return $this->newsTable;
    }

}
