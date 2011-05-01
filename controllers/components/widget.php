<?php
class WidgetComponent extends Object {
    var $controller;
    var $settings = null; 
    var $components = array("FlyLoader");

    function initialize(&$controller, $settings = array()) {
        $this->controller =& $controller;
        $this->settings = $settings;
    }

    function load($widgets, $vars = array()) {
        $widget_list = array();
        foreach ($widgets as $widget) {
            $placement = explode("|", $widget["Widget"]["placement"]);

            foreach ($vars as $key => $value) {
                $widget["Widget"]["options"] = str_replace('"${' . $key . '}"', 
                                                           $value, 
                                                           $widget["Widget"]["options"]);
            }
            $widget_settings = json_decode($widget["Widget"]["options"], true);
            $component = $this->FlyLoader->load("Component", array(
                    $widget["Widget"]["name"]=>$widget_settings["Component"]));
            $widget["Widget"]["helper_name"] = $component;
            $widget_list[$placement[0]][$placement[1]] = $widget;
            $this->FlyLoader->load("Helper", $widget["Widget"]["name"]);

            $this->controller->{$component}->build();
        }

        CakeLog::write("debug", "widget list: " . Debugger::exportVar($widget_list, 4));

        return $widget_list;
    }
}
