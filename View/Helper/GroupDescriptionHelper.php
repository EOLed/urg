<?php
App::uses("Helper", "Markdown.Markdown");
App::uses("Lib", "Urg.AbstractWidgetHelper");
class GroupDescriptionHelper extends AbstractWidgetHelper {
    var $helpers = array("Html", "Time", "Markdown"); 
    function build_widget() {
        $this->Html->css("/urg/css/urg.css", null, array("inline"=>false));
        $title = $this->options["group_name"] ? 
                $this->Html->tag("h2", $this->options["group_name"], array("class"=>"group-title")) : "";
        $content = $this->Html->para("group-description", $this->Markdown->html($this->options["group_description"]));

        return $this->Html->div("about", $title . $content);
    }
}
