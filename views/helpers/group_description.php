<?php
class GroupDescriptionHelper extends AppHelper {
    var $helpers = array("Html", "Time");

    function build($options = array()) {
        $this->Html->css("/urg/css/urg.css", null, array("inline"=>false));
        $title = $this->Html->tag("h2", $options["group_name"], array("class"=>"group-title"));
        $content = $this->Html->para("group-description", $options["group_description"]);

        return $this->Html->div("about", $title . $content);
    }
}
