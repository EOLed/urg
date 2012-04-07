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
            $controller_name = $action["controller"];
            $controller_action = $action["action"];
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

        $access = false;
        
        if (isset($this->settings["disabled"]) && $this->settings["disabled"]) {
            CakeLog::write("debug", "urg is disabled.");
            $access = true;
        } else {
            $request_action = "/urg/secured_actions/getSecuredActionsByUser/" . 
                    $logged_user["User"]["username"];

            CakeLog::write("debug", "Requesting action: $request_action"); 

            $secured_actions = $this->controller->requestAction($request_action);

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
}
