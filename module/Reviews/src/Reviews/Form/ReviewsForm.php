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
                'label' => 'Имя*',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'name',
                'placeholder' => 'Ваше имя',
            ),
        ));
        
        $this->add(array(
            'name' => 'phone',
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
            'name' => 'email',
            'type' => 'Email',
            'options' => array(
                'label' => 'Электронный адрес*',
            ),
            'attributes' => array(
                'class' => 'form-control',
                 'id' => 'email',
                'placeholder' => 'Электронный адрес',
            ),
        ));
        
        $this->add(array(
            'name' => 'description',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'Отзыв*',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'rew',
                'placeholder' => 'Оставьте здесь ваш отзыв',
            ),
        ));
        $this->add(array(
            'name' => 'status',
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => 'Опубликовать',
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
            'attributes' => array(
                'class' => 'btn btn-danger',
            ),
        ));
    }

}
