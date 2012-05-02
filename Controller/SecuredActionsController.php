<?php
App::uses("UrgAppController", "Urg.Controller");
App::uses("UrgComponent", "Urg.Controller/Component");
class SecuredActionsController extends UrgAppController {

	var $name = 'SecuredActions';

    var $components = array("Urg.Urg" => array("disabled" => true));

    function migrate($version) {
        if ($version == "v1") {
            $data = $this->SecuredAction->find("all");
            foreach ($data as $secured_action) {
                if ($secured_action["SecuredAction"]["controller"] == "*")
                    continue;

                $secured_action["SecuredAction"]["controller"] = $secured_action["SecuredAction"]["controller"] . "Controller";
                $this->SecuredAction->save($secured_action);
            }
        }
        $this->redirect("index");
    }

    function beforeFilter() {
        $this->Auth->allow("getSecuredActionsByUser");
    }

	function index() {
		$this->SecuredAction->recursive = 0;
		$this->set('securedActions', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid secured action'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('securedAction', $this->SecuredAction->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->SecuredAction->create();
			if ($this->SecuredAction->save($this->data)) {
				$this->Session->setFlash(__('The secured action has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
                        __('The secured action could not be saved. Please, try again.'));
			}
		}
		$roles = $this->getRolesList();
		$this->set(compact('roles'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid secured action'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->SecuredAction->save($this->data)) {
				$this->Session->setFlash(__('The secured action has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
                        __('The secured action could not be saved. Please, try again.'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->SecuredAction->read(null, $id);
		}
		$roles = $this->getRolesList();
		$this->set(compact('roles'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for secured action'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->SecuredAction->delete($id)) {
			$this->Session->setFlash(__('Secured action deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Secured action was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	
    function getRolesList() {
        $this->SecuredAction->Role->bindModel(array(
                "belongsTo" => array(
                        "Group" => array(
                            "className" => "Group",
                            "foreignKey" => "group_id"
                        )
                )
        ));
        
        $joins = array(
                array("table" => "groups",
                        "type" => "LEFT",
                        "alias" => "Group",
                        "conditions" => array(
                                "Group.id = Role.group_id"
                        )
                )
        );
        
        $roles = $this->SecuredAction->Role->find('list', 
                array("fields" => array("Role.id", "Role.name", "Group.name" ), "joins" => $joins));
        
        $this->SecuredAction->Role->unbindModel(array("belongsTo" => array("Group")));
        
        return $roles;
    }

    function find_by_role_id($role_id) {
        $role_actions = $this->SecuredAction->findAllByRoleId($role_id);
        $default_actions = $this->SecuredAction->find("all", 
                array("conditions" => array("SecuredAction.role_id" => null)));

        $actions = array();

        foreach ($role_actions as $action) {
            array_push($actions, $action["SecuredAction"]);
        }

        foreach ($default_actions as $action) {
            array_push($actions, $action["SecuredAction"]);
        }

        return $actions;
    }

    function getSecuredActionsByUser($username=null) {
        $role_ids = array();

        if ($username != null) {
            $this->log("retrieving secured actions for $username", LOG_DEBUG);
            $this->loadModel("Urg.User");
            $this->User->bindModel(array(
                    "hasAndBelongsToMany" => array(
                            'Role' => array(
                                'className' => 'Role',
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
                    )
            )); 
            
            $user = $this->User->findByUsername($username);

            $this->User->unbindModel(array("hasAndBelongsToMany" => array("Role")));

            CakeLog::write(LOG_DEBUG, "roles from user: " . Debugger::exportVar($user["Role"]));
            foreach ($user["Role"] as $role) {
                array_push($role_ids, $role["id"]);
            }
        }
        
        $this->log("finding secured actions for roles: " . implode(",", $role_ids), LOG_DEBUG);
        $role_actions = $this->SecuredAction->find("all", array("conditions"=>array("role_id"=>$role_ids)));
        $public_actions = $this->SecuredAction->find("all", array("conditions"=>array("role_id"=>null)));

        $actions = array_merge($role_actions, $public_actions);

        return $actions;
    }
}
?>
