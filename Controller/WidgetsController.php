<?php
class WidgetsController extends UrgAppController {
	var $name = 'Widgets';

    public $helpers = array("Html", "Form", "TwitterBootstrap.TwitterBootstrap");

	function index() {
		$this->Widget->recursive = 0;
		$this->set('widgets', $this->paginate());
	}

    function migrate() {
        $widgets = $this->Widget->find("all");
        foreach ($widgets as $widget) {
            $options = $widget["Widget"]["options"];
            if (strlen(trim($options)) <= 0)
                continue;

            $options = str_replace('${group_id}', '"__group_id__"', $options);
            $options = str_replace('${post_id}', '"__post_id__"', $options);
            $options = json_decode($options, true);
            if (isset($options["Component"])) {
                $options = json_encode($options["Component"]);
                $options = str_replace('"__group_id__"', '${group_id}',  $options);
                $options = str_replace('"__post_id__"', '${post_id}', $options);
                $widget["Widget"]["options"] = $options;
                $this->Widget->save($widget);
            }
        }

        $this->redirect(array("action" => "index"));
    }


    function generate() {
		if (!empty($this->request->data)) {
			$this->Widget->create();
            $full_widget_name = $this->request->data["Widget"]["name"];
            $widget_name = $this->__get_widget_name($full_widget_name);
            $this->{$widget_name} = $this->Components->load($full_widget_name);
            $this->request->data["Widget"]["placement"] = $this->{$widget_name}->build_placement($this->request->data);
            $this->request->data["Widget"]["options"] = json_encode($this->{$widget_name}->build_options($this->request->data));
			if ($this->Widget->save($this->request->data)) {
				$this->Session->setFlash(__('The widget has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The widget could not be saved. Please, try again.'));
			}
		}

		$groups = $this->Widget->Group->find('all', array("order" => "Group.name"));
        $group_options = array();
        foreach ($groups as $group) {
            $group_options[$group["Group"]["id"]] = $group["Group"]["name"] . " (" . $group["Group"]["slug"] . ")";
        }

        // get all used widgets for auto-complete purposes
        $used_widgets = $this->Widget->find("all", array("fields" => array("DISTINCT Widget.name")));

        $widgets = $this->__build_widgets_map($used_widgets);

		$this->set("groups", $group_options);
		$this->set("widgets", $widgets);
    }

    function ui($full_widget_name) {
        $widget_name = $this->__get_widget_name($full_widget_name);
        $this->{$widget_name} = $this->Components->load($full_widget_name);
        $ui_options = $this->{$widget_name}->build_ui_options($this);
        $this->helpers[] = $full_widget_name;
        $this->set("helper_name", $widget_name);
        $this->set("ui_options", $ui_options);
        $this->layout = null;
    }

    function __build_widgets_map($widgets) {
        $list = array();
        foreach ($widgets as $widget) {
            $widget_name = $widget["Widget"]["name"];
            $pos = strpos($widget_name, ".");

            // no "." means no plugin
            $key = $pos === false ? "app" : substr($widget_name, 0, $pos);
            if (!isset($list[$key])) {
                $list[$key] = array();
            }

            $list[$key][$widget_name] = $this->__get_widget_name($widget_name);
        }

        return $list;
    }

    /**
     * extract the widget name from the full widget name.
     * ie. Plugin.widget returns "widget"
     */
    function __get_widget_name($widget_name) {
        $pos = strpos($widget_name, ".");
        return $pos === false ? $widget_name : substr($widget_name, $pos + 1, strlen($widget_name)); 
    }

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid widget'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('widget', $this->Widget->read(null, $id));
	}

    /** given a list of groups, return an array formatted to be displayed in dropdown */
    function __build_groups_dropdown_list($groups) {
        $dropdown_groups = array();
        foreach ($groups as $group) {
            $dropdown_groups[$group["Group"]["id"]] = $group["Group"]["name"] . " (" . $group["Group"]["slug"] . ")";
        }

        return $dropdown_groups;
    }

	function add($group_id = null) {
		if (!empty($this->request->data)) {
			$this->Widget->create();
			if ($this->Widget->save($this->request->data)) {
				$this->Session->setFlash(__('The widget has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The widget could not be saved. Please, try again.'));
			}
		}

        if ($group_id != null) {
            $this->request->data["Widget"]["group_id"] = $group_id;
        }

		$groups = $this->__build_groups_dropdown_list($this->Widget->Group->find('all'));
		$this->set(compact('groups'));
	}

	function edit($id = null) {
		if (!$id && empty($this->request->data)) {
			$this->Session->setFlash(__('Invalid widget'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->request->data)) {
            $success = $this->Widget->save($this->request->data);
            if (!$success) {
                $this->Session->setFlash(__('The widget could not be saved. Please, try again.'));
                break;
            }

            if ($success) {
                $this->Session->setFlash(__('The widget has been saved'));
                $this->redirect(array('action' => 'index'));
            }
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->Widget->find("first", 
                                              array("conditions" => array("Widget.id" => $id)));
		}
		$groups = $this->__build_groups_dropdown_list($this->Widget->Group->find('all'));
		$this->set(compact('groups'));
	}

	function duplicate($id = null) {
		if (!$id && empty($this->request->data)) {
			$this->Session->setFlash(__('Invalid widget'));
			$this->redirect(array('action' => 'index'));
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->Widget->find("first", 
                                              array("conditions" => array("Widget.id" => $id)));
            $this->request->data["Widget"]["group_id"] = null;
		} else {
            $this->Widget->create();

            $success = $this->Widget->save($this->request->data);

            if ($this->Widget->save($this->request->data)) {
                $this->Session->setFlash(__('The widget has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The widget could not be saved. Please, try again.'));
            }
		}
		$groups = $this->__build_groups_dropdown_list($this->Widget->Group->find('all'));
		$this->set(compact('groups'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for widget'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Widget->delete($id)) {
			$this->Session->setFlash(__('Widget deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Widget was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
?>
