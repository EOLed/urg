<?php
/**
 * Widget Helper Interface to create widgets for Urg views.
 */
interface IWidgetHelper {
    /**
     * Builds the widget.
     *
     * @param array $widget_id
     */
    public function build($widget_id);
}

