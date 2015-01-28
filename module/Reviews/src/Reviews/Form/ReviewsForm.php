<?php

namespace Reviews\Form;

use Zend\Form\Form;

class ReviewsForm extends Form {

    public function __construct($name = null) {
// we want to ignore the name passed
        parent::__construct('reviews');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Имя',
            ),
        ));
        
        $this->add(array(
            'name' => 'phone',
            'type' => 'Text',
            'options' => array(
                'label' => 'Телефон',
            ),
        ));
        
        $this->add(array(
            'name' => 'email',
            'type' => 'Text',
            'options' => array(
                'label' => 'Email',
            ),
        ));
        
        $this->add(array(
            'name' => 'description',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'Отзыв',
            ),
        ));
        $this->add(array(
            'name' => 'status',
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => 'Статус',
                'checked_value' => '1',
                'unchecked_value' => '0',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Сохранить',
                'id' => 'submitbutton',
            ),
        ));
    }

}
