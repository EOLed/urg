<?php
class RolesController extends UrgAppController {
	var $name = 'Roles';

	function index() {
		$this->Role->recursive = 0;
		$this->set('roles', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid role', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('role', $this->Role->read(null, $id));
	}

	function add($group_slug = null) {
		if (!empty($this->data)) {
			$this->Role->create();
            $this->data["SecuredAction"] = $this->convert_to_secured_actions();
            $now = date("Y-m-d H:i:s");
			if ($this->Role->saveAll($this->data)) {
				$this->Session->setFlash(__('The role has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The role could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
            if (isset($this->data["Role"]["secured_actions"])) {
                $this->data["Role"]["secured_actions"] = $this->convert_to_form($secured_actions);
            }
		}

        $group = null;
        if ($group_slug != null) {
            $group = $this->Role->Group->findBySlug($group_slug);
            $this->log("group id: " . $group["Group"]["id"], LOG_DEBUG);
            $this->data["Role"]["group_id"] = $group["Group"]["id"];
        }

        if ($group == null) {
            $groups = $this->Role->Group->find("list");
        } else {
            $children = $this->Role->Group->children($group["Group"]["id"], false);
            CakeLog::write(LOG_DEBUG, 'group list' . Debugger::exportVar($children, 3));

            foreach ($children as $child) {
                $groups[$child["Group"]["id"]] = $child["Group"]["name"];
            }
        }
		$this->set(compact('groups'));
        $this->set("controllers", $this->get_all_controllers());
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid role', true));
			$this->redirect(array('action' => 'index'));
		}

        $secured_actions = $this->requestAction("/urg/secured_actions/find_by_role_id/$id");

		if (!empty($this->data)) {
            $this->data["SecuredAction"] = $this->convert_to_secured_actions();
            $now = date("Y-m-d H:i:s");
            $ds = $this->Role->getDataSource();
            $ds->begin($this);
			if ($this->Role->saveAll($this->data)) {
                $this->Role->SecuredAction->deleteAll(array(
                        "SecuredAction.created <"=>$now,
                        "SecuredAction.role_id"=>$this->data["Role"]["id"]));
                $ds->commit($this);
				$this->Session->setFlash(__('The role has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
                $ds->rollback($this);
				$this->Session->setFlash(__('The role could not be saved. Please, try again.', true));
			}
		}

		if (empty($this->data)) {
			$this->data = $this->Role->read(null, $id);
            $this->data["Role"]["secured_actions"] = $this->convert_to_form($secured_actions);
		}
		$groups = $this->Role->Group->find('list');
		$this->set(compact('groups'));
        $this->set("controllers", $this->get_all_controllers());
	}
    
    function convert_to_secured_actions() {
        $actions = array();

        if (isset($this->data["Role"]["secured_actions"]) && 
                is_array($this->data["Role"]["secured_actions"])) {
            foreach ($this->data["Role"]["secured_actions"] as $action) {
                $action = preg_split("/\//", $action);
                array_push($actions, array(#"role_id"=>$this->data["Role"]["id"], 
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
			$this->Session->setFlash(__('Invalid id for role', true));
			$this->redirect(array('action'=>'index'));
		}
        
        $ds = $this->Role->getDataSource();

        $ds->begin($this);

        $this->Role->SecuredAction->deleteAll(array("SecuredAction.role_id" => $id));
		if ($this->Role->delete($id)) {
            $ds->commit($this);
			$this->Session->setFlash(__('Role deleted', true));
			$this->redirect(array('action'=>'index'));
		} else {
            $ds->rollback($this);
            $this->Session->setFlash(__('Role was not deleted', true));
            $this->redirect(array('action' => 'index'));
        }
	}

    public function get_controller_info($controller, $plugin="") {
        $controller_import = ($plugin != "" ? "$plugin." : "") . $controller;

        $this->log("Importing controller: $controller_import", LOG_DEBUG);

        App::import('Controller', $controller_import);
        $className = $controller . 'Controller';
        $actions = get_class_methods($className);
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
                Debugger::exportVar($controllers, true), LOG_DEBUG);

        return $controllers;
    }

    public function get_controllers($plugin="") {
        $controllers = array();
        $prefix = $plugin == "" ? "" : "$plugin.";
        $controller_path = $plugin != null ? 
                APP . "plugins" . DS . strtolower(Inflector::underscore($plugin)) . DS . 
                "controllers" : "";
        $controllerClasses = App::objects('controller',  $controller_path, false); 
        $this->log("searching for controllers in path: $controller_path\n" . 
                Debugger::exportVar($controllerClasses), LOG_DEBUG);

        foreach($controllerClasses as $controller) { 
            $this->log("getting details of controller: $controller", LOG_DEBUG); 
            if ($controller != $plugin.'App') { 
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
