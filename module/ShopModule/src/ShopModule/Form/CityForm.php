<?php

namespace ShopModule\Form;

use Zend\Form\Form;

class CityForm extends Form {

    public function __construct($name = null) {
// we want to ignore the name passed
        parent::__construct('city');


        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        
        
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Title',
            ),
        ));
       
       
    }

}
