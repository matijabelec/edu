<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Form\AuthForm;
use User\Model\User;

class AuthController extends AbstractActionController {
    
    protected $userTable;
    
    public function getUserTable() {
        if(!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('User\Model\UserTable');
        }
        return $this->userTable;
    }
    
    public function loginAction() {
        /*$db = $this->_getParam('db');
        
        $loginForm = new AuthForm();
        
        if($loginForm->isValid($_POST)) {
            $adapter = new Zend_Auth_Adapter_DbTable(
                $db,
                'users',
                'username',
                'password',
                'MD5(CONCAT(?, password_salt) )'
            );
            
            $adapter->setIdentity($loginForm->getValue('username') );
            $adapter->setCredential($loginForm->getValue('password') );
            
            $auth   = Zend_Auth::getInstance();
            $result = $auth->authenticate($adapter);
            
            if($result->isValid() ) {
                $this->_helper->FlashMessenger('Successful Login');
                $this->_redirect('/');
                return;
            }
        }
        
        //$this->view->loginForm = $loginForm;*/
        
        $form = new AuthForm();
        
        $request = $this->getRequest();
        if($request->isPost() ) {
            $user = new User();
            $form->setInputFilter($user->getAuthInputFilter() );
            $form->setData($request->getPost() );
            
            if($form->isValid() ) {
                $data = $form->getData();
                $data['password'] = md5($data['password']);
                $user->exchangeArray($data);
                try {
                    $result = $this->getUserTable()->authenticateUser($user);
                } catch (\Exception $e) {
                    //$form->setMessages();
                    return $this->redirect()->toRoute('auth', array('action' => 'login') );
                }
                
                return $this->redirect()->toRoute('home');
            }
        }
        
        return array(
            'form' => $form,
        );
    }
}