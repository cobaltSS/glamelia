<?php

namespace News\Form;

use Zend\Form\Form;

class NewsForm extends Form {

    public function __construct($name = null) {
// we want to ignore the name passed
        parent::__construct('news');

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
                'label' => 'Отзыв',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Оставьте здесь ваш отзыв',
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
            'attributes' => array(
                'class' => 'btn btn-danger',
            ),
        ));
    }

}
