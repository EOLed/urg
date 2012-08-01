<?php
App::uses("ColumnLayoutComponent", "Urg.Controller/Component");

class I18nColumnLayoutComponent extends ColumnLayoutComponent {
    function build_widget() {
        $this->widget_settings = $this->widget_settings[$this->controller->Session->read("Config.language")];
        parent::build_widget();
    }
}

