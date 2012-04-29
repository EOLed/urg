<?php
App::uses("UrgComponent", "Urg.Controller/Component");
class UrgAppController extends AppController {
    var $components = array("Urg.Urg",
                            "Auth" => array("loginAction" => array("plugin" => "urg",
                                                                   "controller" => "users",
                                                                   "action" => "login"),
                                            "authenticate" => array("Form"),
                                            "logoutRedirect" => "/"));

    var $helpers = array("Js", "Html", "Form");

    function beforeFilter() {
        if (!$this->Urg->has_access()) {
            $this->log("Redirecting to " . $this->Auth->loginAction, LOG_DEBUG);
            $this->redirect($this->Auth->loginAction);
        } else {
            $this->Auth->allow("*");
        }
        parent::beforeFilter();
    }



    function log($msg, $type = LOG_ERROR) {
    	$trace = debug_backtrace();
        parent::log("[" . $this->toString() . "::" . $trace[1]["function"] . "()] $msg", $type);
    }

    function logVar($msg, $var, $type = LOG_ERROR) {
        $this->log($msg . "\n" . Debugger::exportVar($var, 3), $type);
    }

}
