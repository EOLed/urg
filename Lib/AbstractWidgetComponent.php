<?php
/**
 * An Abstract Widget Component class to facilitate widget creation.
 */

require_once("i_widget_component.php");
abstract class AbstractWidgetComponent extends Component implements IWidgetComponent {
    var $controller = null;
    var $widget_id = null;
    var $widget_settings = null;
    var $helper_options = array();

    abstract function build_widget();

    public function initialize(Controller $controller) {
        CakeLog::write("debug", "calling AbstractWidgetComponent->initialize()");
        $this->controller = $controller;

        CakeLog::write("debug", "initializing " . $this->toString());  
    }

    function startup(Controller $controller) {
        CakeLog::write("debug", "calling AbstractWidgetComponent->startup()");
        $this->controller = $controller;

        CakeLog::write("debug", "starting " . $this->toString());  
    }

    function build($widget_id) {
        CakeLog::write("debug", "calling AbstractWidgetComponent->build()");
        $this->widget_id = $widget_id;
        $this->widget_settings = $this->settings[$widget_id];

        $widget = $this->build_widget();

        $this->controller->set("options_{$this->widget_id}", $this->helper_options);

        return $widget;
    }

    /**
     * convenience method to queue variables to be set into the controller's view.
     *
     * @param string $var_name
     * @param mixed $var
     */
    function set($var_name, $var) {
        $this->helper_options[$var_name] = $var;
    }
}
