<?php

namespace ItemModule\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class Items2ShopTable {

    protected $tableGateway;
    protected $table = 'items2shop';
    protected $select;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false) {
        if ($paginated) {
            // create a new Select object for the table item
            $select = new Select($table);

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

    public function getShop2Item($id_item) {
        $id_item = (int) $id_item;
        $rowset = $this->tableGateway->select(array('id_item' => $id_item));
        $rows = $rowset->array();
        if (!$row) {
            throw new \Exception("Could not find row $id_item");
        }
        return $rows;
    }

    public function saveItem2Shop($id_item, $data) {
        $this->tableGateway->delete(array('id_item'=>$id_item));
        foreach ($data as $shop) {
            $data2item = array(
                'id_item' => $id_item,
                'id_shop' => $shop,
            );
            $this->tableGateway->insert($data2item);
          /*  $id = (int) $data['id'];
            if ($id == 0) {
                $this->tableGateway->insert($data2item);
                return $this->tableGateway->getLastInsertValue();
            } else {
                if ($this->getItem($id)) {
                    $this->tableGateway->update($data2item, array('id' => $id));
                    return $id;
                } else {
                    throw new \Exception('Item id does not exist');
                }
            }*/
        }
    }

    public function deleteShop2Items($id) {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

    public function deleteShop2ItemsWhere($data) {
        $this->tableGateway->delete($data);
    }

}
