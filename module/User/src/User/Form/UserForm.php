<?php

namespace User\Form;

use Zend\Form\Form;

class UserForm extends Form {
    public function __construct($name = null) {
        parent::__construct('user');
        
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ) );
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
            'name' => 'usergroup',
            'type' => 'Text',
            'options' => array(
                'label' => 'Usergroup',
            ),
        ) );
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ) );
    }
}
