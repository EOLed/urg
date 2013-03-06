<?php
App::uses("PageLayoutComponent", "Urg.Controller/Component");

class I18nPageLayoutComponent extends PageLayoutComponent {
    function build_widget() {
        $this->widget_settings = $this->widget_settings[$this->controller->Session->read("Config.language")];
        parent::build_widget();
    }
}

