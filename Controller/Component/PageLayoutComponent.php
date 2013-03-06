<?php
App::uses("AbstractWidgetComponent", "Urg.Lib");

/**
 * The PageLayout widget facilitates the creation of a
 * page layout in your view.
 *
 * Parameters: group_id The group id associated to the widget
 *             layout[rowId]
 *              - row[columnId] = columnClass
 */
class PageLayoutComponent extends AbstractWidgetComponent {
    function build_widget() {
        $layout = array();
        
        foreach ($this->widget_settings["layout"] as $rowId => $row) {
          foreach($row as $columnId => $columnClass) {
            if (!isset($layout[$rowId]))
              $layout[$rowId] = array();

            if (!isset($layout[$rowId][$columnId]))
              $layout[$rowId][$columnId] = array();

            $layout[$rowId][$columnId] = $columnClass;
          }
        }

        $this->set("layout", $layout);
    }
}

