<?php
class ColumnLayoutHelper extends AppHelper {
    var $helpers = array("Html", "Time");
    var $widget_options = array("columns");
    var $options = null;

    function build($options = array()) {
        $this->options = $options;
    }

    function get_columns() {
        return $this->options["columns"];
    }
}

