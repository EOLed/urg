<?php
App::uses("WidgetModel", "Urg.Model");
App::uses("GroupModel", "Urg.Model");
class WidgetUtilComponent extends Component {
    var $controller;
    var $settings = null; 
    var $components = array("Session","FlyLoader");

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
        $this->controller->loadModel("Urg.Group");

        //attempt to load widgets associated to group
        $placement_widgets = array();

        $group_widgets = $this->controller->Group->Widget->find("all", array(
                "conditions" => array("Widget.group_id" => $group_id,
                                      "Widget.action" => $url),
                "order" => "Widget.placement"
        ));

        foreach ($group_widgets as $widget) {
            if (!isset($placement_widgets[$widget["Widget"]["placement"]])) {
                $placement_widgets[$widget["Widget"]["placement"]] = $widget;
            }
        }

        CakeLog::write("debug", 
                       "widgets associated to group $group_id: " . Debugger::exportVar($group_widgets, 3));

        //if there are none, get widgets associated to parent
        CakeLog::write(LOG_DEBUG, "group model: " . Debugger::exportVar($this->controller->Group, 3));
        $parent = $this->controller->Group->getParentNode($group_id);
        $parent_widgets = array();
        
        while ($parent) {
            CakeLog::write("debug", "parent of group ($group_id): " . Debugger::exportVar($parent, 3));

            $group_id = $parent["Group"]["id"];
            $parent_widgets = $this->controller->Group->Widget->find("all", array(
                    "conditions" => array("Widget.group_id" => $group_id,
                                          "Widget.action" => $url),
                    "order" => "Widget.placement"
            ));

            foreach ($parent_widgets as $widget) {
                if (!isset($placement_widgets[$widget["Widget"]["placement"]])) {
                    $placement_widgets[$widget["Widget"]["placement"]] = $widget;
                }
            }

            $parent = $this->controller->Group->getParentNode($group_id);
        }

        $widgets = array();
        foreach ($placement_widgets as $placement=>$widget) {
            array_push($widgets, $widget);
        }

        ksort(&$widgets);

        $widget_list = array();
        foreach ($widgets as $widget) {
            $this->prepare_widget($widget, $vars);
            array_push($widget_list, $widget);
        }

        CakeLog::write("debug", "widget list: " . Debugger::exportVar($widget_list, 4));

        return $widget_list;
    }

    function get_settings($widget, $vars) {
        foreach ($vars as $key => $value) {
            $widget["Widget"]["options"] = 
                    str_replace('${' . $key . '}', 
                                is_numeric($value) ? $value : "\"$value\"", 
                                $widget["Widget"]["options"]);
        }

        return json_decode($widget["Widget"]["options"], true);
    }

    function prepare_widget(&$widget, $vars) {
        CakeLog::write("debug", 
                       "loading current widget: " . Debugger::exportVar($widget, 3));

        $widget_settings = $this->get_settings($widget, $vars);

        if ($widget_settings == null) {
            CakeLog::write("error", "widget " . $widget["Widget"]["id"] . " is invalid.");
        }

        $component = $this->FlyLoader->get_name($widget["Widget"]["name"]);
        if (property_exists($this->controller, $component) &&
                $this->controller->{$component} != null) {
            CakeLog::write("debug", "loading ocmponent (existing): $component");
            $widget["Widget"]["helper_name"] = $component;
            $this->controller->{$component}->settings[$widget["Widget"]["id"]] = 
                    $widget_settings;
        } else {
            CakeLog::write("debug", "loading component $component");
            $component = $this->FlyLoader->load("Component", 
                    array($widget["Widget"]["name"] => 
                           array($widget["Widget"]["id"] => $widget_settings)));
            $widget["Widget"]["helper_name"] = $component;
            $this->FlyLoader->load("Helper", $widget["Widget"]["name"]);
            $this->FlyLoader->load("Behavior", $widget["Widget"]["name"]);
        }

        if ($component !== false) {
            $this->controller->{$component}->build($widget["Widget"]["id"]);
        }
    }

    function load_widgets($widgets, $vars = array()) {
    }
}
