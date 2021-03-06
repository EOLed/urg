<?php
App::uses("UrgAppController", "Urg.Controller");
App::uses("UrgComponent", "Urg.Controller/Component");
class UsersController extends UrgAppController {
	var $name = "Users";
	var $modelName;

    var $components = array("Urg.Urg");
    var $helpers = array("TwitterBootstrap.TwitterBootstrap", "Form", "Session", "Html");

    function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    function dashboard() {
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid user'));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('user', $this->User->read(null, $id));
    }

    function add() {
        if (!empty($this->request->data)) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        }
        
        $roles = $this->__get_roles_list();
        
        $this->set(compact('roles'));
    }

    function __populate_admin() {
        $data["User"]["id"] = 1;
        $data["User"]["username"] = "admin";
        $data["User"]["password"] = $this->Auth->password("admin");
        $this->User->saveAll($data);
    }
    
    function __get_roles_list() {
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

    function assign_roles($username = null) {
        $habtms = array("Role" => array("className" => "Urg.Role"));
        $this->User->bindModel(array("hasAndBelongsToMany" => $habtms));
		if ($this->request->is('post') || $this->request->is('put')) {
            $user = $this->User->findById($this->request->data["User"]["id"]);
            $user["User"]["password"] = null;
            //var_dump($this->request->data);
            if ($this->User->saveAll($this->request->data)) {
                $this->Session->setFlash(__("Roles successfully assigned"));
                $this->redirect(array("action" => "index"));
            } else {
                $this->Session->setFlash(__("Couldn't assign roles"));
            }
        }
        if ($username != null)  {
            $user = $this->User->findByUsername($username);
            $this->request->data = $user;
        }

        $roles = $this->__get_roles_list();
        $this->set("user", $user);
        $this->set("roles", $this->User->Role->find("list"));
    }

    function edit($id = null) {
        if (!$id && empty($this->request->data)) {
            $this->Session->setFlash(__('Invalid user'));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->request->data)) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        }
        if (empty($this->request->data)) {
            $this->request->data = $this->User->read(null, $id);
        }
        $roles = $this->__get_roles_list();
        $this->set(compact('roles'));
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for user'));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->User->delete($id)) {
            $this->Session->setFlash(__('User deleted'));
            $this->redirect(array('action'=>'index'));
        }
        $this->Session->setFlash(__('User was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
	
	function login() {
        CakeLog::write(LOG_DEBUG, "auth data: " . Debugger::exportVar($this->request->data, 3));

		if (!empty($this->request->data)) {
            if ($this->Auth->login() && $this->Auth->user() != null) {
                $logged_user = $this->Auth->user();
                CakeLog::write(LOG_DEBUG, "auth user: " . Debugger::exportVar($logged_user, 3));
                $logged_user = $this->User->findByUsername($logged_user["username"]);

                if ($logged_user !== false) {
                    $this->Session->write("User", $logged_user);
                    $this->log("user logging in: " . Debugger::exportVar($logged_user, 3), LOG_DEBUG);
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
                }

                CakeLog::write(LOG_DEBUG, "auth redirect: " . Debugger::exportVar($this->Auth->redirect(), 5));
                $this->redirect($this->referer());
            }
        }
	}
	
	function logout() {
		$this->log("logging out user: " . Debugger::exportVar($this->Session->read("User"), 3), LOG_DEBUG);
        $this->Session->destroy();
		$this->redirect($this->Auth->logout());
	}

    function locale($locale) {
        $this->Session->write("Config.language", $locale);
        $this->redirect($this->referer());
    }
}
