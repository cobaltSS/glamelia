<?php

namespace ShopModule\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class PhotoShopTable {

    protected $tableGateway;
    protected $table = 'shop_photo';
    protected $select;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        
    }

    public function fetchAll($paginated = false) {
        if ($paginated) {
            // create a new Select object for the table shop
            $select = new Select('shop_photo');

            // create a new result set based on the Shop entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Shop());
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
            'id_shop' => $photo['id_shop'],
            'patch' => $photo['patch'],
            'status' => $photo['status'],
        );

        $id = (int) $photo['id'];
        if ($id == 0) {
            $this->tableGateway->insert($data);
             return $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getShop($id)) {
                $this->tableGateway->update($data, array('id' => $id));
                return $id;
            } else {
                throw new \Exception('Shop id does not exist');
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
