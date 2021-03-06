<?php

namespace ItemModule\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class ItemForm extends Form {

    public function __construct($name = null) {
// we want to ignore the name passed
        parent::__construct('item');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));



        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Наименование товара*',
            ),
            'attributes'=>array(
              'class'=>'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'category_id',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'category_id',
                'OnChange' => 'ShowSubcat(this)',
                'class'=>'form-control',
            ),
            'options' => array(
                'label' => 'Категория*',
                'empty_option' => 'Пожалуйста выберите категорию*',
            ),
        ));
        
         $this->add(array(
            'name' => 'description',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'Описание',
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'subcategory_id',
             'required' => false,
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'subcategory_id',
                'class'=>'form-control',
            ),
            'options' => array(
                'label' => 'Подкатегория',
                 'disable_inarray_validator' => true,
                'empty_option' => 'Пожалуйста выберите подкатегорию',
            ),
        ));

        $this->add(array(
            'name' => 'shop_id',
            'type' => 'Zend\Form\Element\MultiCheckbox',
            'attributes' => array(
                'id' => 'shop_id',
            ),
            'options' => array(
                'label' => 'Магазин*',
            ),
        ));


        $this->add(array(
            'name' => 'status',
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => 'Отображать на сайте:',
                'checked_value' => '1',
                'unchecked_value' => '0',
            ),
        ));

        $this->add(array(
            'name' => 'action',
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => 'Товар на акции:',
                'checked_value' => '1',
                'unchecked_value' => '0',
            ),
        ));

        $this->add(array(
            'name' => 'percentage',
            'type' => 'Text',
            'options' => array(
                'label' => 'Процент скидки',
            ),
            'attributes' => array(
                'class'=>'form-control',
            ),
        ));



        $this->add(array(
            'name' => 'id_photo',
            'type' => 'Zend\Form\Element\File',
            'options' => array(
                'label' => 'Загрузить фотографию',
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
                'value' => 'Добавить',
                'id' => 'submitbutton',
                'class'=>'btn btn-success',
            ),
        ));
    }

}
