<?php
class GroupUtilComponent extends Component {
    private $__controller = null;

    public function initialize(Controller $controller) { 
        $this->__controller = $controller;
    }

    public function get_title($group) {
        if (!isset($this->__controller->Group)) {
            $this->__controller->loadModel("Urg.Group");
        }

        $path = $this->__controller->Group->getPath($group["Group"]["id"]);

        $home_group = $this->get_closest_home_group($group, $path);
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

    public function get_closest_home_group($group, $path = null) {
        $cached_home_group = Cache::read("grouputil-homegroup-" . $group["Group"]["id"]);

        if ($cached_home_group !== false) {
            CakeLog::write(LOG_DEBUG, "using cached home group");
            return $cached_home_group;
        }

        if ($group["Group"]["home"]) {
            Cache::write("grouputil-homegroup-" . $group["Group"]["id"], $group);
            return $group;
        }

        if (!isset($this->__controller->Group))
            $this->__controller->loadModel("Urg.Group");

        if ($path == null)
            $path = $this->__controller->Group->getPath($group["Group"]["id"]);

        for ($i = (sizeof($path) - 1); $i >=0; $i--) {
            $current_group = $path[$i];
            if ($current_group["Group"]["home"]) {
                Cache::write("grouputil-homegroup-" . $group["Group"]["id"], $current_group);
                return $current_group;
            }
        }

        return null;
    }
}
