<?php

namespace ShopModule\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class ShopTable {

    protected $tableGateway;
    protected $table = 'shop';
    protected $select;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false) {
        if ($paginated) {
            // create a new Select object for the table shop
            $select = new Select('shop');

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

    public function getShop($id) {
        $id = (int) $id;
        $select = new Select;
        $select->from('shop');
        $select->quantifier('DISTINCT');
        $select->join('shop_photo', "shop_photo.id_shop = shop.id", array('patch' => new Expression("GROUP_CONCAT(DISTINCT `shop_photo`.`patch` SEPARATOR ', ')")), 'left');
        $select->group("shop.id");
        $select->having("shop.id = " . $id);

        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveShop(Shop $shop) {
        $data = array(
            'work_time' => $shop->work_time,
            'address' => $shop->address,
            'status' => $shop->status,
            'city_id' => $shop->city_id,
        );


        $id = (int) $shop->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
            return $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getShop($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Shop id does not exist');
            }
        }
    }

    public function deleteShop($id) {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

    public function getShopList() {
        $resultSet = $this->tableGateway->select();
        return $resultSet->toArray();
    }

    public function getShops() {
        $rand = new \Zend\Db\Sql\Expression('RAND()');
        $select = new Select;
        $select->from('shop');
        $select->quantifier('DISTINCT');
        $select->join('shop_photo', "shop_photo.id_shop = shop.id", array('patch' => new Expression("GROUP_CONCAT(DISTINCT `shop_photo`.`patch` SEPARATOR ', ')")), 'left');
        $select->group("shop.id");
        $select->order($rand);

        $rowset = $this->tableGateway->selectWith($select);
        return $rowset->toArray();
    }

}
