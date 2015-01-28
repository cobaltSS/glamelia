<?php

namespace ShopModule\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class CityTable {

    protected $tableGateway;
    protected $_name = 'city';

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false) {
        if ($paginated) {
            // create a new Select object for the table album
            $select = new Select('city');
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new City());
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

    public function getCityList() {
         $resultSet = $this->tableGateway->select();
         return $resultSet->toArray();
    }
    
    public function getCity2Shop()
    {
     
        $select = new Select;
        $select->from('city');
        $select->join('shop', "shop.city_id = city.id", array('address'));
        $select->group("city.id");
        $rowset = $this->tableGateway->selectWith($select);

        return  $rowset->toArray();
    }

    public function getCity($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveCity(City $city) {
        $data = array(
            'name' => $city->name,
        );

        $id = (int) $city->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCity($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('City id does not exist');
            }
        }
    }

    public function deleteCity($id) {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

}
