<?php

namespace ItemModule\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Item implements InputFilterAwareInterface {

    public $id;
    public $name;
    public $category_id;
    public $subcategory_id;
    public $cost;
    public $status;
    public $id_photo;
    public $patch;
    protected $inputFilter;
    
    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->category_id = (!empty($data['category_id'])) ? $data['category_id'] : null;
        $this->subcategory_id = (!empty($data['subcategory_id'])) ? $data['subcategory_id'] : null;
        $this->cost = (!empty($data['cost'])) ? $data['cost'] : null;
        $this->status = (!empty($data['status'])) ? $data['status'] : null;
        $this->id_photo = (!empty($data['id_photo'])) ? $data['id_photo'] : null;
        $this->patch = (!empty($data['patch'])) ? $data['patch'] : null;
        $this->id_shop = (!empty($data['id_shop'])) ? $data['id_shop'] : null;
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
                'name' => 'category_id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ));
           
           $inputFilter->add(array(
                'name' => 'subcategory_id',
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


            

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
