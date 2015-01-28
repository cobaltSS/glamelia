<?php

namespace ItemModule\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class PhotoItemTable {

    protected $tableGateway;
    protected $table = 'item_photo';
    protected $select;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        
    }

    public function fetchAll($paginated = false) {
        if ($paginated) {
            // create a new Select object for the table item
            $select = new Select('item_photo');

            // create a new result set based on the Item entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Item());
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

    public function getPhoto($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function savePhoto($photo) {
        $data = array(
            'id_item' => $photo['id_item'],
            'patch' => $photo['patch'],
            'status' => $photo['status'],
        );

        $id = (int) $photo['id'];
        if ($id == 0) {
            $this->tableGateway->insert($data);

             return $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getItem($id)) {
                $this->tableGateway->update($data, array('id' => $id));
                return $id;
            } else {
                throw new \Exception('Item id does not exist');
            }
        }
       
    }

    public function deletePhoto($id) {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
    
     public function deletePhotoWhere($data) {
        $this->tableGateway->delete($data);
    }

}
