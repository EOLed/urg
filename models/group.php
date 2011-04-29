<?php
class Group extends UrgAppModel {
	var $name = 'Group';
	var $displayField = 'name';
    var $actsAs = array("Tree");
	var $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'ParentGroup' => array(
			'className' => 'Group',
			'foreignKey' => 'parent_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
//		'Meeting' => array(
//			'className' => 'Meeting',
//			'foreignKey' => 'group_id',
//			'dependent' => false,
//			'conditions' => '',
//			'fields' => '',
//			'order' => '',
//			'limit' => '',
//			'offset' => '',
//			'exclusive' => '',
//			'finderQuery' => '',
//			'counterQuery' => ''
//		),
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'group_id',
			'dependent' => false,
		),
		'Widget' => array(
			'className' => 'Widget',
			'foreignKey' => 'group_id',
			'dependent' => false,
		)
	);


	var $hasAndBelongsToMany = array(
		'RelatedGroup' => array(
			'className' => 'Group',
			'joinTable' => 'groups_groups',
			'foreignKey' => 'group_id',
			'associationForeignKey' => 'related_group_id',
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
}
?>
