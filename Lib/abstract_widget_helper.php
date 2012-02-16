<?php
/**
 * An Abstract Widget Helper class to facilitate widget creation.
 */

require_once("i_widget_helper.php");
abstract class AbstractWidgetHelper extends Object implements IWidgetHelper {
    var $options = null;

    abstract function build_widget();

    function build($options = array()) {
        $this->options = $options;

        return $this->build_widget();
    }
}

