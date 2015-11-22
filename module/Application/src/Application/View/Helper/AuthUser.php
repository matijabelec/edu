<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Session\Config\StandardConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class AuthUser extends AbstractHelper {
    protected $user;
    
    public function __invoke() {
        $container = new Container('user');
        
        if(isset($container->username) )
            $this->user = $container->username;
        else
            $this->user = null;
        
        //$output = sprintf("%d", $this->user);
        //return htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
        return $this->user;
    }
}
