<?php
App::uses("AbstractWidgetComponent", "Urg.Lib");
class GroupTitleComponent extends AbstractWidgetComponent {
    function build_widget() {
        $this->controller->loadModel("Urg.Group");
        $this->group = $this->controller->Group->findById($this->widget_settings["group_id"]);
        $this->set("group", $this->group);
        $this->set("title", isset($this->widget_settings["title"]) ? 
                            $this->widget_settings["title"] : $this->group["Group"]["name"]);
    }
}



