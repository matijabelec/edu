<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Form\AuthForm;
use User\Model\User;
use Zend\Session\Config\StandardConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

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
        $container = new Container('user');
        
        if(isset($container->username) )
            $username = $container->username;
        else
            $username = null;
        
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
                    
                    if($result) {
                        $config = new StandardConfig();
                        $config->setOptions(array(
                            'remember_me_seconds' => 1800,
                            'name'                => 'user_session',
                        ) );
                        $manager = new SessionManager($config);

                        $container = new Container('user');
                        $container->username = $user->username;
                    }
                } catch (\Exception $e) {
                    
                    //$form->setMessages();
                    return $this->redirect()->toRoute('auth', array('action' => 'login') );
                }
                
                return $this->redirect()->toRoute('home');
            }
        }
        
        return array(
            'form' => $form,
            'username' => $username,
        );
    }
    
    public function logoutAction() {
        
        $container = new Container('user');
        $container->getManager()->getStorage()->clear('user');
        
        return $this->redirect()->toRoute('auth', array('action' => 'login') );
    }
}