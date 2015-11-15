<?php

namespace User\Form;

use Zend\Form\Form;

class AuthForm extends Form {
    public function __construct($name = null) {
        parent::__construct('user');
        
        $this->add(array(
            'name' => 'username',
            'type' => 'Text',
            'options' => array(
                'label' => 'Username',
            ),
        ) );
        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'options' => array(
                'label' => 'Password',
            ),
        ) );
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Login',
                'id' => 'submitbutton',
            ),
        ) );
        
//        $this->add('text', 'username', array(
//            'label' => 'Username:',
//            'required' => true,
//            'filters'    => array('StringTrim'),
//        ) );
//        
//        $this->add('password', 'password', array(
//            'label' => 'Password:',
//            'required' => true,
//        ) );
//        
//        $this->add('submit', 'submit', array(
//            'ignore'   => true,
//            'label'    => 'Login',
//        ) );
    }
}
