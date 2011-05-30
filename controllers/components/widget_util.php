<?php
class WidgetUtilComponent extends Object {
    var $controller;
    var $settings = null; 
    var $components = array("FlyLoader");

    function initialize(&$controller, $settings = array()) {
        $this->controller =& $controller;
        $this->settings = $settings;
    }

    function url() {
        $url = "";

        if (isset($this->controller->params["plugin"])) {
            $url = "/" . $this->controller->params["plugin"];
        }

        if (isset($this->controller->params["controller"])) {
            $url .= "/" . $this->controller->params["controller"];
        }

        if (isset($this->controller->params["action"])) {
            $url .= "/" . $this->controller->params["action"];
        }

        return $url;
    }

    function load($group_id, $vars = array()) {
        $url = $this->url();
        $this->log("loading widgets for $url", LOG_DEBUG); 
        $widgets = $this->controller->Group->Widget->find("all", array(
                "conditions" => array("Widget.group_id" => $group_id,
                                      "Widget.action" => "/urg/groups/view"),
                "order" => "Widget.placement"
        ));

        $this->log("widgets: " . Debugger::exportVar($widgets, 3), LOG_DEBUG);

        while (empty($widgets)) {
            $parent = $this->controller->Group->getparentnode($group_id);

            if ($group_id !== false) {
                $widgets = $this->controller->Group->Widget->find("all", array(
                        "conditions" => array("Widget.group_id" => $parent["Group"]["id"],
                                              "Widget.action" => $url),
                        "order" => "Widget.placement"
                ));
            } else {
                break;
            }
        }

        $widget_list = array();
        foreach ($widgets as $widget) {
            foreach ($vars as $key => $value) {
                $widget["Widget"]["options"] = 
                        str_replace('${' . $key . '}', 
                                    is_numeric($value) ? $value : "\"$value\"", 
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
                $this->FlyLoader->load("Behavior", $widget["Widget"]["name"]);
            }

            if ($component !== false) {
                $this->controller->{$component}->build($widget["Widget"]["id"]);
            }
        }

        CakeLog::write("debug", "widget list: " . Debugger::exportVar($widget_list, 4));

        return $widget_list;
    }

    function load_widgets($widgets, $vars = array()) {
    }
}
