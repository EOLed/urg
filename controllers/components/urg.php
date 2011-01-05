<?php
class UrgComponent extends Object {
    var $components = array(
           "Auth" => array(
                   "loginAction" => array(
                           "plugin" => "urg",
                           "controller" => "users",
                           "action" => "login",
                           "admin" => false
                   )
           )
    );

    var $controller;
    var $settings = array("disabled" => false); 

    function initialize(&$controller, $settings = array()) {
        $this->controller =& $controller;
        $this->settings = array_merge($this->settings, $settings);
    }

	function has_access() {
        $plugin_name = $this->controller->params["plugin"];
        $controller_name = $this->controller->params["controller"];
        $controller_action = $this->controller->params["action"];
        $logged_user = $this->Auth->user();
        CakeLog::write("debug", 
                "verifying access for " . ($plugin_name != "" ? "/$plugin_name" : "") . 
                "/$controller_name/$controller_action...");

        if (isset($logged_user["User"]["username"])) {
            CakeLog::write("debug", "Logged user: " . $logged_user["User"]["username"] . " id: " . 
                    $logged_user["User"]["id"]);
        }

        $access = false;
        
        if ($this->settings["disabled"]) {
            CakeLog::write("debug", "urg is disabled.");
            $access = true;
        } else {
            $request_action = "/urg/secured_actions/getSecuredActionsByUser/" . 
                    $logged_user["User"]["id"];

            CakeLog::write("debug", "Requesting action: $request_action"); 

            $secured_actions = $this->controller->requestAction($request_action);

            $secured_controller = ($plugin_name != "" ? Inflector::camelize($plugin_name) . "."  : "") . 
                    Inflector::camelize($controller_name);

            CakeLog::write("debug", "searching for access of $secured_controller/$controller_action");

            foreach ($secured_actions as $action) {
                if (($action["SecuredAction"]["controller"] === $secured_controller || 
                        $action["SecuredAction"]["controller"] === "*") &&
                        ($action["SecuredAction"]["action"] === $controller_action ||
                        $action["SecuredAction"]["action"] === "*")) {
                    $access = true;
                    break;
                }
            }
        }

        CakeLog::write("debug", 
                "access for /$controller_name/$controller_action... $access");


		return $access;
	}
}
