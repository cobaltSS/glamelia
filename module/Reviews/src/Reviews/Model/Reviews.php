<?php

namespace Reviews\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Reviews implements InputFilterAwareInterface {

    public $id;
    public $name;
    public $phone;
    public $email;
    public $description;
    public $status;
    public $date;
    
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->phone = (!empty($data['phone'])) ? $data['phone'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->description = (!empty($data['description'])) ? $data['description'] : null;
        $this->status = (!empty($data['status'])) ? $data['status'] : null;
        $this->date = (!empty($data['date'])) ? $data['date'] : null;
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
                'name' => 'date',
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

            $inputFilter->add(array(
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
                            'min' => 1,
                            'max' => 100000,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'status',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ));
            
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
