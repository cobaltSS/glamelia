<?php

namespace ItemModule\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Items2Shop implements InputFilterAwareInterface {

    public $id;
    public $id_item;
    public $id_shop;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->id_item = (!empty($data['id_item'])) ? $data['id_item'] : null;
        $this->patch = (!empty($data['id_shop'])) ? $data['id_shop'] : null;
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
                'name' => 'id_item',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ));
            
            
            $inputFilter->add(array(
                'name' => 'id_shop',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ));
           
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
