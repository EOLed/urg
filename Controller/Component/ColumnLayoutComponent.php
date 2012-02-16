<?php
App::uses("Lib", "Urg.AbstractWidgetComponent");

/**
 * The ColumnLayoutComponent widget facilitates the creation of a
 * column layout in your view.
 *
 * Parameters: group_id The group id associated to the widget
 *             columns  The number of columns in this layout
 *             col-n    The class name associated to col-n
 *                      where n is a zero-based index up until
 *                      $columns-1.
 */
class ColumnLayoutComponent extends AbstractWidgetComponent {
    function build_widget() {
        $options = array();
        $columns = array();

        for ($i = 0; $i < $this->widget_settings["columns"]; $i++) {
            $columns["col-$i"] = $this->widget_settings["col-$i"];
        }

        $this->set("columns", $columns);
    }
}
