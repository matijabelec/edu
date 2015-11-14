<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Model\User;
use User\Form\UserForm;

class UserController extends AbstractActionController {
    
    protected $userTable;
    
    public function getUserTable() {
        if(!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('User\Model\UserTable');
        }
        return $this->userTable;
    }
    
    public function indexAction() {
        return new ViewModel(array(
            'users' => $this->getUserTable()->fetchAll(),
        ) );
    }
    
    public function addAction() {
        $form = new UserForm();
        $form->get('submit')->setValue('Add');
        
        $request = $this->getRequest();
        if($request->isPost() ) {
            $user = new User();
            $form->setInputFilter($user->getInputFilter() );
            $form->setData($request->getPost() );
            
            if($form->isValid() ) {
                $data = $form->getData();
                $data['password'] = md5($data['password']);
                $user->exchangeArray($data);
                $this->getUserTable()->saveUser($user);
                
                return $this->redirect()->toRoute('user');
            }
        }
        
        return array('form' => $form);
    }
    
    public function editAction() {
        $id = (int)$this->params()->fromRoute('id', 0);
        if(!$id) {
            return $this->redirect()->toRoute('user', array(
                'action' => 'add',
            ) );
        }
        
        try {
            $user = $this->getUserTable()->getUser($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('user', array(
                'action' => 'index',
            ) );
        }
        
        $form = new UserForm();
        $form->bind($user);
        $form->get('submit')->setAttribute('value', 'Edit');
        
        $request = $this->getRequest();
        if($request->isPost() ) {
            $form->setInputFilter($user->getInputFilter() );
            $form->setData($request->getPost() );
            
            if($form->isValid() ) {
                $user->password = md5($user->password);
                $this->getUserTable()->saveUser($user);
                
                return $this->redirect()->toRoute('user');
            }
        }
        
        return array(
            'id' => $id,
            'form' => $form,
        );
    }
    
    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if(!$id) {
            return $this->redirect()->toRoute('user');
        }
        
        $request = $this->getRequest();
        if($request->isPost() ) {
            $del = $request->getPost('del', 'No');
            
            if($del == 'Yes') {
                $id = (int) $request->getPost('id');
                
                $this->getUserTable()->deleteUser($id);
            }
            
            return $this->redirect()->toRoute('user');
        }
        
        return array(
            'id' => $id,
            'user' => $this->getUserTable()->getUser($id),
        );
    }
}
