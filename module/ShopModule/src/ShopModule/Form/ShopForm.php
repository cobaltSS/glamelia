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
                'label' => 'Work Time',
            ),
        ));

        $this->add(array(
            'name' => 'city_id',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'city_id',
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
            ),
        ));
        
        
        
         $this->add(array(
            'name' => 'status',
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => 'Active',
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
            ),
        ));
    }

}
