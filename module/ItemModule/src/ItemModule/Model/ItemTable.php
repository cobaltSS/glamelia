<?php

namespace ItemModule\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class ItemTable {

    protected $tableGateway;
    protected $table = 'item';
    protected $select;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false, $where = array()) {
        $select = new Select('item');
        $select->join('category', "category.id = item.category_id", array('name_cat' => 'name'), 'left');
        $select->where($where);
        if ($paginated) {

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
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function getItem($id) {
        $id = (int) $id;
        $select = new Select;
        $select->from('item');
        $select->quantifier('DISTINCT');
        $select->join('item_photo', "item_photo.id_item = item.id", array('patch' => new Expression("GROUP_CONCAT(DISTINCT `item_photo`.`patch` SEPARATOR ', ')")), 'left');
        $select->join('items2shop', "items2shop.id_item = item.id", array('id_shop' => new Expression("GROUP_CONCAT(DISTINCT `items2shop`.`id_shop` SEPARATOR ', ')")), 'left');
        $select->group("item.id");
        $select->having("item.id = " . $id);
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveItem(Item $item) {
        $data = array(
            'name' => $item->name,
            'category_id' => $item->category_id,
            'subcategory_id' => $item->subcategory_id,
            'status' => (int)$item->status,
            'cost' => $item->cost,
            'id_photo' => $item->id_photo,
            'action' => $item->action,
            'percentage' => $item->percentage,
            'description' => $item->description,
        );
        $id = (int) $item->id;
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

    public function deleteItem($id) {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

    public function getItems2Category($id,$where=array()) {
        $id = (int) $id;
        $select = new Select;
        $select->from('item');
        $select->quantifier('DISTINCT');
        $select->join('item_photo', "item_photo.id_item = item.id", array('patch'), 'left');
        $select->join('items2shop', "items2shop.id_item = item.id", array('id_shop' => new Expression("GROUP_CONCAT(DISTINCT `items2shop`.`id_shop` SEPARATOR ', ')")), 'left');
        $select-where($where);
        $select->group("item.id");
        $select->having("item.category_id = " . $id);
        $rowset = $this->tableGateway->selectWith($select);
        return $rowset->toArray();
    }

    public function getItems2SubCategory($id,$where=array()) {
        $id = (int) $id;
        $select = new Select;
        $select->from('item');
        $select->quantifier('DISTINCT');
        $select->join('item_photo', "item_photo.id_item = item.id", array('patch'), 'left');
        $select->join('items2shop', "items2shop.id_item = item.id", array('id_shop' => new Expression("GROUP_CONCAT(DISTINCT `items2shop`.`id_shop` SEPARATOR ', ')")), 'left');
        $select->where($where);
        $select->group("item.id");
        $select->having("item.subcategory_id = " . $id);
        $rowset = $this->tableGateway->selectWith($select);

        return $rowset->toArray();
    }

    public function getActionItemsRandom($where, $limit) {
        $rand = new \Zend\Db\Sql\Expression('RAND()');
        $select = new Select;
        $select->from($this->table);
        $select->where($where);
        $select->limit((int) $limit);
        $select->join('item_photo', "item_photo.id_item = item.id", array('patch'), 'left');
        $select->join('category', "category.id = item.category_id", array(), 'left');
        $select->order($rand);

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet->toArray();
    }

    public function getItems($limit=false, $where = array()) {
        $rand = new \Zend\Db\Sql\Expression('RAND()');
        $select = new Select;
        $select->from('item');
        $select->where($where);
        $select->quantifier('DISTINCT');
        if($limit)
        $select->limit((int) $limit);
        $select->join('item_photo', "item_photo.id_item = item.id", array('patch'), 'left');
        $select->join('category', "category.id = item.category_id", array(), 'left');
        $select->order($rand);
        $rowset = $this->tableGateway->selectWith($select);

        return $rowset->toArray();
    }

}
