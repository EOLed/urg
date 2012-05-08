<?php
App::uses("AuthComponent", "Controller/Component");
App::uses("UrgAppModel", "Urg.Model");
class User extends UrgAppModel {
	var $name = "User";
    var $displayField = "username";	
	
    var $validate = array(
        "password" => array(
                "notEmpty" => array(
	                "rule" => "notEmpty",
		            "message" => "errors.users.password.required"
		        )
		),
		"confirm" => array(
		        "passwordLength" => array(
                    "rule" => "passwordLength",
                    "message" => "errors.users.error.password.length"
		        )
        ),
        "username" => array(
                "isUnique" => array(
                        "rule" => "isUnique",
                        "message" => "errors.users.username.unique"
                ),
                "notEmpty" => array(
                        "rule" => "notEmpty",
                        "message" => "errors.users.username.required"
                )
        )
	);
	
    var $hasOne = array(
        'Profile' => array(
            'className' => 'Profile',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    var $hasAndBelongsToMany = array(
        'Role' => array(
            'className' => 'Urg.Role',
            'joinTable' => 'roles_users',
            'foreignKey' => 'user_id',
            'associationForeignKey' => 'role_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        )
    );
	
	
    function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        
        Configure::load("config");
    }
	
	function passwordLength($check) {
		$min = intval(Configure::read("User.passwordMinLength"));
		$max = intval(Configure::read("User.passwordMaxLength"));
		
		$size = array_values($check);
		$size = strlen($size[0]);
		
		$valid = $size >= $min && $size <= $max;
		
		return $valid;
	} 

    public function beforeSave($options = array()) { 
        $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']); 
        return true; 
    } 
}
