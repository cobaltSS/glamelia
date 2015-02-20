<?php

namespace Category\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Category implements InputFilterAwareInterface {

    public $id;
    public $name;
    public $description;
    public $status;
    public $subname;
    public $subcategory_id;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->description = (!empty($data['description'])) ? $data['description'] : null;
        $this->status = (!empty($data['status'])) ? $data['status'] : null;
        $this->subname = (!empty($data['subname'])) ? $data['subname'] : null;
        $this->subcategory_id = (!empty($data['subcategory_id'])) ? $data['subcategory_id'] : null;
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ));
            
             $inputFilter->add(array(
                'name' => 'status',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ));
             
              $inputFilter->add(array(
                'name' => 'description',
                'required' => false,
            ));


            $inputFilter->add(array(
                'name' => 'name',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 100,
                        ),
                    ),
                ),
            ));


          /*  $inputFilter->add(array(
                'name' => 'description',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 0,
                            'max' => 10000,
                        ),
                    ),
                ),
            ));*/

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
