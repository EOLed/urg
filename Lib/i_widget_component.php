<?php
/**
 * Widget Component Interface to create widgets for Urg views.
 */
interface IWidgetComponent {
    /**
     * Builds the widget.
     *
     * @param array $widget_id
     */
    public function build($widget_id);
}
