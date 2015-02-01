<?php

namespace About\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class About implements InputFilterAwareInterface {

    public $id;
    public $name;
    public $address;
    public $tel;
    public $email;
    public $description;
    public $current_account;
    public $unp;
    public $okpo;
    public $director;
    public $bank_info;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->tel = (!empty($data['tel'])) ? $data['tel'] : null;
        $this->address = (!empty($data['address'])) ? $data['address'] : null;
         $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->description = (!empty($data['description'])) ? $data['description'] : null;
        $this->director = (!empty($data['director'])) ? $data['director'] : null;
        $this->okpo = (!empty($data['okpo'])) ? $data['okpo'] : null;
        $this->unp = (!empty($data['unp'])) ? $data['unp'] : null;
        $this->current_account = (!empty($data['current_account'])) ? $data['current_account'] : null;
        $this->bank_info = (!empty($data['bank_info'])) ? $data['bank_info'] : null;
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
                'name' => 'tel',
                'required' => false,
            ));

            $inputFilter->add(array(
                'name' => 'address',
                'required' => false,
            ));
            
             $inputFilter->add(array(
                'name' => 'email',
                'required' => false,
            ));

            $inputFilter->add(array(
                'name' => 'director',
                'required' => false,
            ));

            $inputFilter->add(array(
                'name' => 'okpo',
                'required' => false,
            ));

            $inputFilter->add(array(
                'name' => 'unp',
                'required' => false,
            ));

            $inputFilter->add(array(
                'name' => 'current_account',
                'required' => false,
            ));
            
             $inputFilter->add(array(
                'name' => 'bank_info',
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
                'required' => false,
            ));


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
