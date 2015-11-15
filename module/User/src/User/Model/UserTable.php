<?php

namespace User\Model;

use Zend\Db\TableGateway\TableGateway;

class UserTable {
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    public function getUser($id) {
        $id = (int)$id;
        
        $rowset = $this->tableGateway->select(array('id' => $id) );
        $row = $rowset->current();
        
        if(!$row) {
            throw new \Exception("Could not find row $id");
        }
        
        return $row;
    }
    
    public function saveUser(User $user) {
        $data = array(
            'username' => $user->username,
            'password' => $user->password,
            'usergroup' => $user->usergroup,
        );
        
        $id = (int)$user->id;
        if($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if($this->getUser($id) ) {
                $this->tableGateway->update($data, array('id' => $id) );
            } else {
                throw new \Exception('User id does not exist');
            }
        }
    }
    
    public function deleteUser($id) {
        $this->tableGateway->delete(array('id' => (int)$id) );
    }
    
    public function authenticateUser(User $user) {
        $username = $user->username;
        $password = $user->password;
        
        $rowset = $this->tableGateway->select(array('username' => $username) );
        $row = $rowset->current();
        
        if(!$row) {
            throw new \Exception('Wrong username or password!');
        }
        
        if($row->password != $password) {
            throw new \Exception('Wrong username or password!');
        }
    }
}
