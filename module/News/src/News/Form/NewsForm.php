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
                'label' => 'Название статьи',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'name',
                'placeholder' => 'Название статьи',
            ),
        ));
        
      
      
        $this->add(array(
            'name' => 'description',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'Текст',
            ),
            'attributes' => array(
                'id'=>'description',
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
