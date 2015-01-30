<?php

namespace Category\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class SubcategoryTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false) {
        if ($paginated) {
            // create a new Select object for the table album
            $select = new Select('subcategory');
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Subcategory());
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

    public function getSubcategory($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function getSubcategories($id) {
        $id_category = (int) $id;
        $rowset = $this->tableGateway->select(array('id_category' => $id_category));
        $rows = $rowset->toArray();
        return $rows;
    }

    public function saveSubcategory($subcategory) {
        $data = array(
            'name' => $subcategory['name'],
            'id_category' => $subcategory['id_category'],
            'status' => $subcategory['status'],
        );


        $id = (int) $subcategory['id'];
        if ($id == 0) {
            $this->tableGateway->insert($data);
            return $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getSubcategory($id)) {
                $this->tableGateway->update($data, array('id' => $id));
                return $id;
            } else {
                throw new \Exception('Subcategory id does not exist');
            }
        }
    }

    public function deleteSubcategory($id) {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
    
    public function getSubcategoryList() {
         $resultSet = $this->tableGateway->select();
         return $resultSet->toArray();
    }

}
