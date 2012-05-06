<?php
class GroupTitleComponent extends Component {
    private $__controller = null;

    public function initialize(Controller $controller) { 
        $this->__controller = $controller;
    }

    public function get_title($group) {
        if (!isset($this->__controller->Group)) {
            $this->__controller->loadModel("Urg.Group");
        }

        $path = $this->__controller->Group->getPath($group["Group"]["id"]);

        $home_group = $this->__get_closest_home_group($group, $path);
        $root = null;
        foreach ($path as $current_group) {
            if ($current_group["Group"]["home"]) {
                $root = $current_group;
                break;
            }
        }

        $title = $home_group["Group"]["name"]; 
        if ($home_group["Group"]["name"] != $root["Group"]["name"]) {
            $title .= " &laquo; " . $root["Group"]["name"];
        }

        return $title;
    }

    private function __get_closest_home_group($group, $path) {
        if ($group["Group"]["home"])
            return $group;

        for ($i = (sizeof($path) - 1); $i >=0; $i--) {
            $current_group = $path[$i];
            if ($current_group["Group"]["home"])
                return $current_group;
        }

        return null;
    }
}
