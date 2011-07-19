<?php
App::import("Component", "Urg.Urg");
class UrgAppController extends AppController {
    var $components = array(
           "Auth" => array(
                   "loginAction" => array(
                           "plugin" => "urg",
                           "controller" => "users",
                           "action" => "login",
                           "admin" => false
                   )
           ), "Urg"
    );

    function beforeFilter() {
        parent::beforeFilter();
        
        if (!$this->Urg->has_access()) {
            $this->log("Redirecting to " . $this->Auth->loginAction, LOG_DEBUG);
            $this->redirect($this->Auth->loginAction);
        }

        Configure::load("config");
        $languages = Configure::read("Language");
        if (!isset($this->params["lang"])) {
            $logged_user = $this->Auth->user();
            $this->loadModel("Profile");
            $profile = $this->Profile->findByUserId($logged_user["User"]["id"]);

            if (!isset($profile["Profile"]["locale"])) {
                $this->params["lang"] = Configure::read("Language.default");
            } else {
                $this->params["lang"] = $profile["Profile"]["locale"];
            }

        }

        $language = $languages[$this->params["lang"]];

        Configure::write("Config.language", $language);
        $this->log("Setting language to: $language", LOG_DEBUG);
        $this->Session->write("Config.language", $language);
        $this->Session->write("Config.lang", $this->params["lang"]);
        $this->Session->write("Config.locales", $this->Urg->get_locales());
    }

    function set_locales() {
        $this->set("locales", $this->Session->read("Config.locales"));
    }


    function log($msg, $type = LOG_ERROR) {
    	$trace = debug_backtrace();
        parent::log("[" . $this->toString() . "::" . $trace[1]["function"] . "()] $msg", $type);
    }

}
