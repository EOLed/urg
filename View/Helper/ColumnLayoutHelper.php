<?php
App::uses("Lib", "Urg.AbstractWidgetHelper");
class ColumnLayoutHelper extends AbstractWidgetHelper {
    var $helpers = array("Html", "Time");

    function build_widget() {
        return null;
    }

    function get_columns() {
        return $this->options["columns"];
    }
}

