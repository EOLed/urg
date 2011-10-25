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
           ), "Session"
    );

    var $controller;
    var $settings = array("disabled" => false); 

    function initialize(&$controller, $settings = array()) {
        $this->controller =& $controller;
        $this->settings = array_merge($this->settings, $settings);
    }

	function has_access($action = null) {
        $plugin_name = $controller_name = $controller_action = null;
        if (is_array($action)) {
            $plugin_name = isset($action["plugin"]) ? $action["plugin"] : null;
            $controller_name = $this->controller->params["controller"];
            $controller_action = $this->controller->params["action"];
        } else {
            $plugin_name = $this->controller->params["plugin"];
            $controller_name = $this->controller->params["controller"];
            $controller_action = $this->controller->params["action"];
        }

        $logged_user = $this->Auth->user();

        $logged_user_id = isset($logged_user["User"]["id"]) ? $logged_user["User"]["id"] : "";

        CakeLog::write("debug", 
                "verifying access for " . ($plugin_name != "" ? "/$plugin_name" : "") . 
                "/$controller_name/$controller_action...");

        if (isset($logged_user["User"]["username"])) {
            CakeLog::write("debug", "Logged user: " . $logged_user["User"]["username"] . " id: " . 
                    $logged_user["User"]["id"]);

            if (!$this->Session->check("Locale")) {
                $this->controller->loadModel("Profile");
                $logged_profile = $this->controller->Profile->findByUserId($logged_user["User"]["id"]);
                $this->Session->write("Locale", $logged_profile["Profile"]["locale"]);
                $this->Session->write("Config.language", "chi");
            }

            CakeLog::write("debug", "User locale: " . $this->Session->read("Config.language"));
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
                "access for /$controller_name/$controller_action... " . ($access ? "true" : "false"));

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
                $locales[$catalog["locale"]] = __($catalog["language"], true);
            }
        }

        return $locales;
    }
}
