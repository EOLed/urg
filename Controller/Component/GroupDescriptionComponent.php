<?php
App::uses("AbstractWidgetComponent", "Urg.Lib");
/**
 * The Group Description Widget can be used to add the description
 * of a specified group within a view.
 *
 * Parameters: group_id The group id of the group to retrieve description of.
 *             title    The title of the description. If empty, the group
 *                      name will be used.
 */
class GroupDescriptionComponent extends AbstractWidgetComponent {
    function build_widget() {
        $options = array();

        $original_group = $group = $this->controller->Group->findById($this->widget_settings["group_id"]);

        while ($group["Group"]["description"] == "") {
            $group = $this->controller->Group->getparentnode($group["Group"]["id"]);

            if ($group === false) {
                break;
            }
        }

        if (!isset($this->widget_settings["title"])) {
            $this->widget_settings["title"] = $group["Group"]["name"];
        }

        CakeLog::write("debug", "Group Description is using group: " . Debugger::exportVar($group, 3));

        $this->set("group_name", $this->widget_settings["title"]);
        $this->set("group_description", $group["Group"]["description"]);
    }
}
