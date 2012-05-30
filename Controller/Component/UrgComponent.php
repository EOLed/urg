<?php
class UrgComponent extends Component {
    var $components = array(
           "Auth" => array("loginAction" => array("plugin" => "urg",
                                                  "controller" => "users",
                                                  "action" => "login"),
                           "authenticate" => array("Form")),
           "Session"
    );

    var $controller;
    var $settings = array("disabled" => false); 

    function initialize(&$controller, $settings = array()) {
        $this->controller =& $controller;
        $this->settings = array_merge($this->settings, $settings);
    }

	function has_access($action = null, $group_id = null) {
        $plugin_name = $controller_name = $controller_action = null;
        if (is_array($action)) {
            $plugin_name = isset($action["plugin"]) ? $action["plugin"] : null;
            $controller_name = $action["controller"] . "Controller";
            $controller_action = $action["action"];
        } else {
            $plugin_name = $this->controller->params["plugin"];
            $controller_name = $this->controller->params["controller"] . "Controller";
            $controller_action = $this->controller->params["action"];
        }

        $logged_user = $this->Session->read("User");

        $logged_user_id = isset($logged_user["User"]["id"]) ? $logged_user["User"]["id"] : "";

        CakeLog::write("debug", 
                "verifying access for " . ($plugin_name != "" ? "/$plugin_name" : "") . 
                "/$controller_name/$controller_action...");

        $access = false;
        
        if (isset($this->settings["disabled"]) && $this->settings["disabled"]) {
            CakeLog::write("debug", "urg is disabled.");
            $access = true;
        } else {
            $this->controller->loadModel("Urg.SecuredAction");
                    $logged_user["User"]["username"];

            $secured_actions = $this->get_secured_actions($logged_user["User"]["username"]);

            $secured_controller = ($plugin_name != "" ? Inflector::camelize($plugin_name) . "."  : "") . 
                    Inflector::camelize($controller_name);

            CakeLog::write("debug", "searching for access of $secured_controller/$controller_action for group id: $group_id");

            foreach ($secured_actions as $action) {
                if (($action["SecuredAction"]["controller"] === $secured_controller || 
                        $action["SecuredAction"]["controller"] === "*") &&
                        ($action["SecuredAction"]["action"] === $controller_action ||
                        $action["SecuredAction"]["action"] === "*")) {
                    CakeLog::write(LOG_DEBUG, "secured action: " . Debugger::exportVar($action, 3));
                    if ($group_id != null && $action["Role"]["group_id"] != null) {
                        if ($group_id == $action["Role"]["group_id"]) {
                            $access = true;
                        } else {
                            CakeLog::write(LOG_DEBUG, "role group id: " . $action["Role"]["group_id"]);

                            if (!isset($this->controller->Group))
                                $this->controller->loadModel("Urg.Group");

                            $children = $this->controller->Group->children($action["Role"]["group_id"]);
                            foreach ($children as $child) {
                                if ($child["Group"]["id"] == $group_id) {
                                    $access = true;
                                    break;
                                }
                            }
                        }
                    } else {
                        $access = true;
                    }

                    if ($access)
                        break;
                }
            }
        }

        CakeLog::write("debug", 
                "access for /$controller_name/$controller_action for group $group_id... " . ($access ? "true" : "false"));

		return $access;
	}

    function get_locales() {
        $locales = $this->Session->read("Config.locales");
        if ($locales == null) {
            $languages = Configure::read("Language");
            unset($languages["default"]);

            $l10n = new L10n();

            foreach ($languages as $lang_key=>$lang) {
                $catalog = $l10n->catalog($lang);
                $locales[$catalog["locale"]] = __($catalog["language"]);
            }
        }

        return $locales;
    }

    function get_secured_actions($username=null) {
        $role_ids = array();

        if ($username != null) {
            CakeLog::write(LOG_DEBUG, "retrieving secured actions for $username");
            $this->controller->loadModel("Urg.User");
            $this->controller->User->bindModel(array(
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
            
            $user = $this->controller->User->findByUsername($username);

            if ($user !== false) {

                CakeLog::write(LOG_DEBUG, "roles from user: " . Debugger::exportVar($user["Role"]));
                foreach ($user["Role"] as $role) {
                    array_push($role_ids, $role["id"]);
                }
            }
            $this->controller->User->unbindModel(array("hasAndBelongsToMany" => array("Role")));
        }
        
        CakeLog::write(LOG_DEBUG, "finding secured actions for roles: " . implode(",", $role_ids));
        $role_actions = $this->controller->SecuredAction->find("all", array("conditions"=>array("role_id"=>$role_ids)));
        $public_actions = $this->controller->SecuredAction->find("all", array("conditions"=>array("role_id"=>null)));

        $actions = array_merge($role_actions, $public_actions);

        return $actions;
    }
}
