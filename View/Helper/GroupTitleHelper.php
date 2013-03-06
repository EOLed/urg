<?php
App::uses("AbstractWidgetHelper", "Urg.Lib");
class GroupTitleHelper extends AbstractWidgetHelper {
    var $helpers = array("Html", "Time");

    function build_widget() {
        return $this->title_widget($this->options["title"], $this->options["group"]);
    }

    function title_widget() {
        $group = $this->options["group"];
        $title = $this->Html->div("", $group["Group"]["name"]);

        return $title;
    }
}



