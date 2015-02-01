<?php

namespace About\Form;

use Zend\Form\Form;

class AboutForm extends Form {

    public function __construct($name = null) {
// we want to ignore the name passed
        parent::__construct('about');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Наименование предприятия',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'name',
                'placeholder' => 'Наименование предприятия',
            ),
        ));
        
        $this->add(array(
            'name' => 'tel',
            'type' => 'Text',
            'options' => array(
                'label' => 'Телефон',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Контактный номер телефона',
            ),
        ));
        
        $this->add(array(
            'name' => 'address',
            'type' => 'Text',
            'options' => array(
                'label' => 'Адрес',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Адрес',
            ),
        ));
        
         $this->add(array(
            'name' => 'current_account',
            'type' => 'Text',
            'options' => array(
                'label' => 'РАСЧЕТНЫЙ СЧЕТ',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'РАСЧЕТНЫЙ СЧЕТ',
            ),
        ));
         
          $this->add(array(
            'name' => 'unp',
            'type' => 'Text',
            'options' => array(
                'label' => 'УНП',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'УНП',
            ),
        ));
           $this->add(array(
            'name' => 'bank_info',
            'type' => 'Text',
            'options' => array(
                'label' => 'Информация о банке',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Информация о банке',
            ),
        ));
          
           $this->add(array(
            'name' => 'okpo',
            'type' => 'Text',
            'options' => array(
                'label' => 'ОКПО',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'ОКПО',
            ),
        ));
           
            $this->add(array(
            'name' => 'director',
            'type' => 'Text',
            'options' => array(
                'label' => 'Директор',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'ФИО',
            ),
        ));
            
        $this->add(array(
            'name' => 'email',
            'type' => 'Email',
            'options' => array(
                'label' => 'Email',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Электронный адрес',
            ),
        ));
        
        $this->add(array(
            'name' => 'description',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'О предприятии',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'О предприятии',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Сохранить',
                'id' => 'submitbutton',
            ),
            'attributes' => array(
                'class' => 'btn btn-danger',
            ),
        ));
    }

}
