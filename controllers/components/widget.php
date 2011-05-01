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
            foreach ($vars as $key => $value) {
                $widget["Widget"]["options"] = str_replace('"${' . $key . '}"', 
                                                           $value, 
                                                           $widget["Widget"]["options"]);
            }
            $widget_settings = json_decode($widget["Widget"]["options"], true);

            $component = $this->FlyLoader->get_name($widget["Widget"]["name"]);
            if (property_exists($this->controller, $component) &&
                    $this->controller->{$component} != null) {
                CakeLog::write("debug", "using existing component $component");
                $widget["Widget"]["helper_name"] = $component;
                $this->controller->{$component}->settings[$widget["Widget"]["id"]] = 
                        $widget_settings["Component"];
                array_push($widget_list, $widget);
            } else {
                CakeLog::write("debug", "loading component $component");
                $component = $this->FlyLoader->load("Component", 
                        array($widget["Widget"]["name"] => 
                               array($widget["Widget"]["id"] => $widget_settings["Component"])));
                $widget["Widget"]["helper_name"] = $component;
                array_push($widget_list, $widget);
                $this->FlyLoader->load("Helper", $widget["Widget"]["name"]);
            }

            $this->controller->{$component}->build($widget["Widget"]["id"]);
        }

        CakeLog::write("debug", "widget list: " . Debugger::exportVar($widget_list, 4));

        return $widget_list;
    }
}
