<?php
/**
 * Widget Interface to create widgets for Urg views.
 */
interface IWidgetComponent {
    /**
     * Initialize the widget component.
     *
     * @param array $controller The controller associated to this widget.
     * @param array $settings   The widget settings.
     */
    public function initialize(&$controller, $settings);

    /**
     * Builds the widget.
     *
     * @param array $widget_id
     */
    public function build($widget_id);
}
