<?php
class UsersController extends UrgAppController {
	var $name = "Users";
	var $modelName;

    var $components = array("Urg");

    function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    function dashboard() {
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid user', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('user', $this->User->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            $this->User->create();
            if ($this->User->save($this->data)) {
                $this->Session->setFlash(__('The user has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
            }
        }
        
        $roles = $this->getRolesList();
        
        $this->set(compact('roles'));
    }
    
    function getRolesList() {
        $this->User->Role->bindModel(array(
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
        
        $roles = $this->User->Role->find('list', array("fields" => array("Role.id", "Role.name", "Group.name" ), "joins" => $joins));
        
        $this->User->Role->unbindModel(array("belongsTo" => array("Group")));
        
        return $roles;
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid user', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->User->save($this->data)) {
                $this->Session->setFlash(__('The user has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->User->read(null, $id);
        }
        $roles = $this->getRolesList();
        $this->set(compact('roles'));
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for user', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->User->delete($id)) {
            $this->Session->setFlash(__('User deleted', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->Session->setFlash(__('User was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }
	
    function beforeFilter() {
        $this->modelName = Inflector::singularize($this->name);
        $this->Auth->allow("register", "login", "logout", "locale");
    }

	function login() {
		if (!empty($this->data)) {
            if ($this->Auth->user() != null) {
                $logged_user = $this->Auth->user();
                $this->Session->write("User", $logged_user);
                $this->log("user logging in: " . Debugger::exportVar($this->data, 3), LOG_DEBUG);
                Configure::load("config");
                $languages = Configure::read("Language");
                $this->loadModel("Profile");

                $profile = $this->Profile->findByUserId($logged_user["User"]["id"]);

                if ($profile && isset($profile["Profile"]["locale"])) {
                    $language = $profile["Profile"]["locale"];
                    Configure::write("Config.language", $language);
                    $this->Session->write("Config.language", $language);
                    $this->log("Setting language to: $language", LOG_DEBUG);
                }
                $this->redirect($this->Auth->redirect());
            }
		}
	}
	
	function logout() {
		$this->log("logging out user: " . $this->data[$this->modelName]["username"], LOG_DEBUG);
        $this->Session->destroy();
		$this->redirect($this->Auth->logout());
	}
	
	function register() {
		if (!empty($this->data)) {
			$this->log("Validating user form...", LOG_DEBUG);
			if ($this->data[$this->modelName]["password"] == $this->Auth->password($this->data[$this->modelName]["confirm"])) {
				$this->{$this->modelName}->create();
				
				$this->log("Attempting to register user..." . Debugger::exportVar($this->data, 3), 
                           LOG_DEBUG);
				$user = $this->{$this->modelName}->saveAll($this->data);
				
				if (empty($user)) {
					$this->log("User creation failed for user: " . $this->data[$this->modelName]["username"], LOG_DEBUG);
				} else {
					$this->log("Registered user: " . $this->data[$this->modelName]["username"], LOG_DEBUG);
				}
			} else {
				$this->log("User not registered, did not pass validation: " . $this->data[$this->modelName]["username"], LOG_DEBUG);
				$this->Session->setFlash(__("The confirmation password does not match.", true));
			}
			
			$this->log("Clearing user's password.", LOG_DEBUG);
			$this->data[$this->modelName]["password"] = "";
			$this->data[$this->modelName]["confirm"] = "";
        }

        $locales = Configure::read("Locale");
        $this->set("locales", $locales);
	}

    function locale($locale) {
        $this->Session->write("Config.language", $locale);
        $this->redirect($this->referer());
    }
}