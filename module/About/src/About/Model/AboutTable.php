<?php

namespace About\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class AboutTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false) {
        if ($paginated) {
            // create a new Select object for the table album
            $select = new Select('about');
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new About());
            // create a new pagination adapter object
            $paginatorAdapter = new DbSelect(
                    // our configured select object
                    $select,
                    // the adapter to run it against
                    $this->tableGateway->getAdapter(),
                    // the result set to hydrate
                    $resultSetPrototype
            );
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getAbout() {
        $rowset = $this->tableGateway->select();
        $row = $rowset->current();
        return $row;
    }

    public function saveAbout(About $about) {
        $data = array(
            'name' => $about->name,
            'tel' => $about->tel,
            'email' => $about->email,
            'address' => $about->address,
            'description' => $about->description,
            'director' => $about->director,
            'okpo' => $about->okpo,
            'unp' => $about->unp,
            'current_account' => $about->current_account,
            'bank_info' => $about->bank_info,

        );
        $id = (int) $about->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getAbout($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('About id does not exist');
            }
        }
    }

    public function deleteAbout($id) {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

    public function getAboutList() {
        $resultSet = $this->tableGateway->select();
        return $resultSet->toArray();
    }

   }
