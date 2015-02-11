<?php

namespace ShopModule\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class ShopForm extends Form {

    public function __construct($name = null) {
// we want to ignore the name passed
        parent::__construct('shop');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));



        $this->add(array(
            'name' => 'work_time',
            'type' => 'Text',
            'options' => array(
                'label' => 'Время работы',
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'city_id',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'city_id',
                 'class' => 'form-control',
            ),
            'options' => array(
                    'label' => 'Город',
                    'empty_option' => 'Пожалуйста выберите город',
                ),
        ));
        
        $this->add(array(
            'name' => 'address',
            'type' => 'Text',
            'options' => array(
                'label' => 'Адрес',
            ),
            'attributes' => array(
                'id' => 'end',
                 'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'start',
            'type' => 'Text',
            'options' => array(
                'label' => 'Ваш адрес',
            ),
            'attributes' => array(
                'id' => 'start',
                 'class' => 'form-control',
            ),
        ));
        
        
        
         $this->add(array(
            'name' => 'status',
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => 'Показывать на сайте',
                'checked_value' => '1',
                'unchecked_value' => '0',
            ),
        ));
        
        $this->add(array(
            'name' => 'id_photo',
            'type' => 'Zend\Form\Element\File',
            'options' => array(
                'label' => 'Загрузить фото',
            ),
            'attributes' => array(
                'id' => 'id_photo',
                'multiple'=>true,
            ),
        ));
        
        $this->add(array(
            'name' => 'photo',
            'type' => 'Zend\Form\Element\Image',
            'attributes' => array(
                'id' => 'photo',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
                'class'=>'btn btn-success',
            ),
        ));
    }

}
