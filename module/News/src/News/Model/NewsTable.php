<?php

namespace News\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class NewsTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false,$where=array()) {
        if ($paginated) {
            // create a new Select object for the table album
            $select = new Select('news');
            $select->where($where);
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new News());
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

    public function getNews($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveNews(News $news) {
        $data = array(
            'name' => $news->name,
            'description' => $news->description,
            'status' =>(int) $news->status,
            'date' => date("Y-m-d H:i:s"),
        );


        $id = (int) $news->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getNews($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('News id does not exist');
            }
        }
    }

    public function deleteNews($id) {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

    public function getNewsList() {
        $resultSet = $this->tableGateway->select();
        return $resultSet->toArray();
    }

    public function getNewsRandom($limit) {
        $rand = new \Zend\Db\Sql\Expression('RAND()');

        $select = new Select;
        $select->from('news');
        $select->limit((int)$limit);
        $select->order($rand);
        
        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet->toArray();
    }


}
