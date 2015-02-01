<?php

namespace About\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use About\Model\About;
use About\Form\AboutForm;

class AboutController extends AbstractActionController {

    protected $aboutTable;

    public function indexAction() {
        try {
            $about = $this->getAboutTable()->getAbout();
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('about', array(
                        'action' => 'index'
            ));
        }

        $form = new AboutForm();
        $form->bind($about);
        $form->get('submit')->setAttribute('value', 'Редактировать');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($about->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getAboutTable()->saveAbout($about);
                return $this->redirect()->toRoute('abouts');
            }
        }

        return array(
            'form' => $form,
        );
    }

    public function getAboutTable() {
        if (!$this->aboutTable) {
            $sm = $this->getServiceLocator();
            $this->aboutTable = $sm->get('About\Model\AboutTable');
        }
        return $this->aboutTable;
    }

}
