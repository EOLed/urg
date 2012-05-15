<?php
App::uses("UrgAppController", "Urg.Controller");
class RolesController extends UrgAppController {
	var $name = 'Roles';

    function beforeFilter() {
        parent::beforeFilter();
        $this->loadModel("Urg.Group");
        $this->Role->bindModel(array("belongsTo" => array("Group" => array("className" => "Urg.Group"))));
    }
	function index() {
		$this->Role->recursive = 0;
		$this->set('roles', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid role'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('role', $this->Role->read(null, $id));
	}

    /** given a list of groups, return an array formatted to be displayed in dropdown */
    function __build_groups_dropdown_list($groups) {
        $dropdown_groups = array();
        foreach ($groups as $group) {
            $dropdown_groups[$group["Group"]["id"]] = $group["Group"]["name"] . " (" . $group["Group"]["slug"] . ")";
        }

        return $dropdown_groups;
    }

	function add($group_slug = null) {
		if (!empty($this->request->data)) {
			$this->Role->create();
            $this->request->data["SecuredAction"] = $this->convert_to_secured_actions();
            $now = date("Y-m-d H:i:s");
			if ($this->Role->saveAll($this->request->data)) {
				$this->Session->setFlash(__('The role has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The role could not be saved. Please, try again.'));
			}
		}
		if (empty($this->request->data)) {
            if (isset($this->request->data["Role"]["secured_actions"])) {
                $this->request->data["Role"]["secured_actions"] = $this->convert_to_form($secured_actions);
            }
		}

        $group = null;
        if ($group_slug != null) {
            $group = $this->Group->findBySlug($group_slug);
            $this->log("group id: " . $group["Group"]["id"], LOG_DEBUG);
            $this->request->data["Role"]["group_id"] = $group["Group"]["id"];
        }

        if ($group == null) {
            $all_groups = $this->Role->Group->find("all");
            $groups = $this->__build_groups_dropdown_list($all_groups);
        } else {
            $children = $this->Role->Group->children($group["Group"]["id"], false);
            $groups = $this->__build_groups_dropdown_list($children);
            $groups[$group["Group"]["id"]] = $group["Group"]["name"] . " (" . $group["Group"]["slug"] . ")";
        }
		$this->set(compact('groups'));
        $this->set("controllers", $this->get_all_controllers());
	}

	function edit($id = null) {
		if (!$id && empty($this->request->data)) {
			$this->Session->setFlash(__('Invalid role'));
			$this->redirect(array('action' => 'index'));
		}

        $secured_actions = $this->requestAction("/urg/secured_actions/find_by_role_id/$id");

		if (!empty($this->request->data)) {
            $this->request->data["SecuredAction"] = $this->convert_to_secured_actions();
            $now = date("Y-m-d H:i:s");
            $ds = $this->Role->getDataSource();
            $ds->begin($this);
			if ($this->Role->saveAll($this->request->data)) {
                $this->Role->SecuredAction->deleteAll(array(
                        "SecuredAction.created <"=>$now,
                        "SecuredAction.role_id"=>$this->request->data["Role"]["id"]));
                $ds->commit($this);
				$this->Session->setFlash(__('The role has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
                $ds->rollback($this);
				$this->Session->setFlash(__('The role could not be saved. Please, try again.'));
			}
		}

		if (empty($this->request->data)) {
			$this->request->data = $this->Role->read(null, $id);
            $this->request->data["Role"]["secured_actions"] = $this->convert_to_form($secured_actions);
		}
		$groups = $this->Group->find('list');
		$this->set(compact('groups'));
        $this->set("controllers", $this->get_all_controllers());
	}
    
    function convert_to_secured_actions() {
        $actions = array();

        if (isset($this->request->data["Role"]["secured_actions"]) && 
                is_array($this->request->data["Role"]["secured_actions"])) {
            foreach ($this->request->data["Role"]["secured_actions"] as $action) {
                $action = preg_split("/\//", $action);
                array_push($actions, array(#"role_id"=>$this->request->data["Role"]["id"], 
                        "controller"=>$action[0], "action"=>$action[1]));
            }
        }

        return $actions;
    }

    function convert_to_form($secured_actions) {
        $actions = array();
        foreach ($secured_actions as $action) {
            array_push($actions, $action["controller"] . "/" . $action["action"]);
        }

        return $actions;
    }

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for role'));
			$this->redirect(array('action'=>'index'));
		}
        
        $ds = $this->Role->getDataSource();

        $ds->begin($this);

        $this->Role->SecuredAction->deleteAll(array("SecuredAction.role_id" => $id));
		if ($this->Role->delete($id)) {
            $ds->commit($this);
			$this->Session->setFlash(__('Role deleted'));
			$this->redirect(array('action'=>'index'));
		} else {
            $ds->rollback($this);
            $this->Session->setFlash(__('Role was not deleted'));
            $this->redirect(array('action' => 'index'));
        }
	}

    public function get_controller_info($controller, $plugin="") {
        $controller_import = ($plugin != "" ? "$plugin." : "") . "Controller";

        $this->log("Importing controller: App::uses($controller, $controller_import)", LOG_DEBUG);
        App::uses($controller, $controller_import);
        $actions = get_class_methods($controller);
        if ($actions == null) $actions = array();

        foreach($actions as $k => $v) {
            if ($v{0} == '_') {
                unset($actions[$k]);
            }
        }
        
        $parentActions = get_class_methods('AppController');
        return array_diff($actions, $parentActions);
    }

    function get_all_controllers() {
        foreach ($this->get_controllers() as $key => $value) {
            $actions = array();
            foreach ($value as $action) {
                $actions["$key/$action"] = $action;
            }
            $controllers[$key] = $actions;
        }

        foreach ($this->get_plugin_controllers() as $key => $value) {
            $actions = array();
            foreach ($value as $action) {
                $actions["$key/$action"] = $action;
            }
            $controllers[$key] = $actions;
        }

        $this->log("retrieving all controllers: " . 
                Debugger::exportVar($controllers, 5), LOG_DEBUG);

        return $controllers;
    }

    public function get_controllers($plugin="") {
        $controllers = array();
        $prefix = $plugin == "" ? "" : "$plugin.";
        $controller_path = $plugin != null ? 
                APP . "Plugin" . DS . $plugin . DS . "Controller" : "";
        $controllerClasses = App::objects('Controller',  $controller_path, false); 
        $this->log("searching for controllers in path: $controller_path\n" . 
                Debugger::exportVar($controllerClasses, 5), LOG_DEBUG);

        foreach($controllerClasses as $controller) { 
            if ($controller != $plugin.'AppController') { 
                $this->log("getting details of controller: $controller", LOG_DEBUG); 
                $controllers[$prefix . $controller] = $this->get_controller_info($controller, $plugin);
            }
        }

        return $controllers;
    }
    
    public function get_plugin_controllers() {
        $plugins = App::objects("plugin"); 
        $controllers = array(); 
        $this->log("Plugins found: " . Debugger::exportVar($plugins), LOG_DEBUG);
        
        foreach ($plugins as $plugin) {
            $this->log("retrieving list of controllers for $plugin", LOG_DEBUG);
            $plugin_controllers = $this->get_controllers($plugin);
            $this->log("plugin controllers: " . 
                    Debugger::exportVar($plugin_controllers, 2), LOG_DEBUG);
            $controllers = array_merge($controllers, $plugin_controllers);
        }

        return $controllers;
    }
}
?>
