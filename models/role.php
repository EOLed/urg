<?php
class Role extends UrgAppModel {
	var $name = 'Role';
	var $validate = array(
		'group_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
            "isUnique" => array(
                "rule" => array("isUnique"),
                "message" => "errors.role.name.unique",
            )                
		)
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id'
		)
	);

	var $hasAndBelongsToMany = array("User");
	//	'User' => array(
	//		'className' => 'User',
	//		'joinTable' => 'roles_users',
	//		'foreignKey' => 'role_id',
	//		'associationForeignKey' => 'user_id',
	//		'unique' => true
	//	)
	//);

    var $hasMany = array("SecuredAction");

//    function beforeDelete() {
//        $count = $this->SecuredAction->find("count", array(
//                "conditions" => array("SecuredAction.role_id"=>$this->id)));
//        return $count == 0;
//    }
}
?>
