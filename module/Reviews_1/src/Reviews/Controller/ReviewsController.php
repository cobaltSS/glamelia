<?php

namespace Reviews\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Reviews\Model\Reviews;
use Reviews\Form\ReviewsForm;

class ReviewsController extends AbstractActionController {

    protected $reviewsTable;

    public function indexAction() {
        // grab the paginator from the reviewsTable
        $paginator = $this->getReviewsTable()->fetchAll(true);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage(10);
        return new ViewModel(array(
            'paginator' => $paginator
        ));
    }

    public function addAction() {
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

                // Redirect to list of reviews
                return $this->redirect()->toRoute('reviews');
            }
        }
        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('reviews', array(
                        'action' => 'add'
            ));
        }

// Get the Reviews with the specified id. An exception is thrown
// if it cannot be found, in which case go to the index page.
        try {
            $reviews = $this->getReviewsTable()->getReviews($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('reviews', array(
                        'action' => 'index'
            ));
        }

        $form = new ReviewsForm();
        $form->bind($reviews);
        $form->get('submit')->setAttribute('value', 'Редактировать');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($reviews->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getReviewsTable()->saveReviews($reviews);

// Redirect to list of $reviewss
                return $this->redirect()->toRoute('reviews');
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
            return $this->redirect()->toRoute('reviews');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Нет');

            if ($del == 'Да') {
                $id = (int) $request->getPost('id');
                $this->getReviewsTable()->deleteReviews($id);
            }

// Redirect to list of albums
            return $this->redirect()->toRoute('reviews');
        }
        return array(
            'id' => $id,
            'reviews' => $this->getReviewsTable()->getReviews($id)
        );
    }

    public function getReviewsTable() {
        if (!$this->reviewsTable) {
            $sm = $this->getServiceLocator();
            $this->reviewsTable = $sm->get('Reviews\Model\ReviewsTable');
        }
        return $this->reviewsTable;
    }

}
