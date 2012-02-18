<?php
App::uses("AbstractWidgetHelper", "Urg.Lib");
class ColumnLayoutHelper extends AbstractWidgetHelper {
    var $helpers = array("Html", "Time");

    function build_widget() {
        return null;
    }

    function get_columns() {
        return $this->options["columns"];
    }
}

