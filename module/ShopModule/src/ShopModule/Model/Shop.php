<?php

namespace ShopModule\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Shop implements InputFilterAwareInterface {

    public $id;
    public $city_id;
    public $work_time;
    public $address;
    public $status;
    public $citys;
    public $id_photo;
     public $patch;
    protected $inputFilter;
    
    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->city_id = (!empty($data['city_id'])) ? $data['city_id'] : null;
        $this->work_time = (!empty($data['work_time'])) ? $data['work_time'] : null;
        $this->address = (!empty($data['address'])) ? $data['address'] : null;
        $this->status = (!empty($data['status'])) ? $data['status'] : null;
        $this->id_photo = (!empty($data['id_photo'])) ? $data['id_photo'] : null;
        $this->patch = (!empty($data['patch'])) ? $data['patch'] : null;
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
                'name' => 'city_id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ));

            
            $inputFilter->add(array(
                'name' => 'work_time',
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


            $inputFilter->add(array(
                'name' => 'address',
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
