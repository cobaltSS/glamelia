<?php

namespace Reviews\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class ReviewsTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false) {
        if ($paginated) {
            // create a new Select object for the table album
            $select = new Select('reviews');
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Reviews());
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

    public function getReviews($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveReviews(Reviews $reviews) {
        $data = array(
            'name' => $reviews->name,
            'phone' => $reviews->phone,
            'email' => $reviews->email,
            'description' => $reviews->description,
            'status' => $reviews->status,
            'date' => date("Y-m-d H:i:s"),
        );


        $id = (int) $reviews->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getReviews($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Reviews id does not exist');
            }
        }
    }

    public function deleteReviews($id) {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

    public function getReviewsList() {
        $resultSet = $this->tableGateway->select();
        return $resultSet->toArray();
    }

    public function getReviewsRandom($limit) {
        $rand = new \Zend\Db\Sql\Expression('RAND()');

        $select = new Select;
        $select->from('reviews');
        $select->limit((int)$limit);
        $select->order($rand);
        
        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet->toArray();
    }

}
