<?php
App::uses("AbstractWidgetHelper", "Urg.Lib");
class PageLayoutHelper extends AbstractWidgetHelper {
    var $helpers = array("Html", "Time");

    function build_widget() {
        return null;
    }

    function get_layout() {
        return $this->options["layout"];
    }
}


