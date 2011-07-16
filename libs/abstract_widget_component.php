<?php
/**
 * An Abstract Widget class to facilitate widget creation.
 */

require_once("i_widget_component.php");
abstract class AbstractWidgetComponent extends Object implements IWidgetComponent {
    var $controller = null;
    var $widget_id = null;
    var $widget_settings = null;

    abstract function build_widget();

    function initialize(&$controller, $settings = array()) {
        $this->controller =& $controller;
        $this->settings = $settings;

        CakeLog::write("debug", $this->toString() . " settings: " . Debugger::exportVar($settings, 3));
    }

    function build($widget_id) {
        $this->widget_id = $widget_id;
        $this->widget_settings = $this->settings[$widget_id];

        $this->build_widget();
    }

    function set($var_name, $var) {
        $this->controller->set($var_name . "_" . $this->widget_id, $var);
    }
}
