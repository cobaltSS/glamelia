<?php

namespace Category\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class CategoryTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false,$where=array()) {
        if ($paginated) {
            // create a new Select object for the table album
            $select = new Select('category');
            $select->where($where);
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Category());
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
        $resultSet = $this->tableGateway->select($where);
        return $resultSet;
    }

    public function getCategory($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveCategory(Category $category) {
        $data = array(
            'name' => $category->name,
            'description' => $category->description,
            'status' => $category->status,
        );


        $id = (int) $category->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCategory($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Category id does not exist');
            }
        }
    }

    public function deleteCategory($id) {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

    public function getCategoryList() {
        $resultSet = $this->tableGateway->select();
        return $resultSet->toArray();
    }

    public function getCategory2Sub() {

        $select = new Select;
        $select->from('category');
        $select->join('subcategory', "subcategory.id_category = category.id", array('subname' => 'name', 'subcategory_id' => 'id'), 'left');
        $rowset = $this->tableGateway->selectWith($select);
        $result = array();
        foreach ($rowset->toArray() as $elem) {
            $key = $elem['id'];
            if (isset($result[$key])) {
                $result[$key]['subname'] .= ':' . $elem['subname'];
                $result[$key]['subcategory_id'] .= ':' . $elem['subcategory_id'];
            } else {
                $result[$key] = $elem;
            }
        }
        return $result;
    }
    
    public function getCategory2SubForShop($id_shop) {

        $select = new Select;
        $select->from('category');
        $select->quantifier('DISTINCT');
        $select->join('subcategory', "subcategory.id_category = category.id", array('subname' => 'name', 'subcategory_id' => 'id'), 'left');
        $select->join('items2shop', "items2shop.id_item", array(), 'left');
        $select->join('item', "item.id = items2shop.id_item AND item.category_id=category.id", array(), 'inner');
        $select->where(array('items2shop.id_shop='.$id_shop));
        
        $rowset = $this->tableGateway->selectWith($select);
        $result = array();
     //   print_r($rowset->getDataSource());
   // print_r($rowset->toArray());
     //  die();
        foreach ($rowset->toArray() as $elem) {
            $key = $elem['id'];
            if (isset($result[$key])) {
                $result[$key]['subname'] .= ':' . $elem['subname'];
                $result[$key]['subcategory_id'] .= ':' . $elem['subcategory_id'];
            } else {
                $result[$key] = $elem;
            }
        }
        return $result;
    }

}
