<?php
App::uses("ImgLibComponent", "ImgLib.Controller/Component");
App::uses("GrpHelper", "Urg.View/Helper");
App::uses("WidgetUtilComponent", "Urg.Controller/Component");
App::uses("PostModel", "UrgPost.Model");
App::uses("GroupModel", "Urg.Model");
App::uses("FlyLoaderComponent", "Controller/Component");
App::uses("MarkdownHelper", "Markdown.View/Helper");
class GroupsController extends UrgAppController {
    var $IMAGES = "/app/Plugin/UrgPost/webroot/img";
	var $name = 'Groups';
    var $helpers = array("Html", "Form", "Slug", "Urg.Grp", "Markdown.Markdown");
    var $components = array("Urg.GroupUtil", "ImgLib.ImgLib", "Urg.WidgetUtil", "FlyLoader");

	function index() {
		$this->Group->recursive = 0;
        $groups = $this->Group->find("threaded");
        $this->log("groups: " . Debugger::exportVar($groups, 6), LOG_DEBUG);
        $this->set('groups', $groups);
	}

	function add($parent_id = null) {
		if (!empty($this->request->data)) {
			$this->Group->create();
			if ($this->Group->save($this->request->data)) {
				$this->Session->setFlash(__('The group has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.'));
			}
		} else if ($parent_id != null) {
            $this->request->data["Group"]["parent_id"] = $parent_id;
            $parent_group = $this->Group->findById($parent_id);
            $this->request->data["ParentGroup"] = $parent_group["Group"];
        }
		$groups = $this->Group->find('list');
		$this->set(compact('groups', 'groups'));
	}

	function edit($id = null) {
		if (!$id && empty($this->request->data)) {
			$this->Session->setFlash(__('Invalid group'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->request->data)) {
			if ($this->Group->save($this->request->data)) {
				$this->Session->setFlash(__('The group has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.'));
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->Group->read(null, $id);
            $this->log("editing group: " . Debugger::exportVar($this->request->data, 4), LOG_DEBUG);
		}

		$parents = $this->Group->ParentGroup->find('list');

		$this->set("parents", $parents);
	}


	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for group'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Group->delete($id)) {
			$this->Session->setFlash(__('Group deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Group was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

    function view($slug = null) {
        if ($slug == null) {
            $slug = $this->params["group_slug"];
        }

        $group = $this->Group->findBySlug($slug);

        $home_group = $this->GroupUtil->get_closest_home_group($group);

        if ($home_group["Group"]["id"] != $group["Group"]["id"]) {
            $this->redirect(array("plugin" => "urg", 
                                  "controller" => "groups", 
                                  "action" => "view", 
                                  $home_group["Group"]["slug"]));
        }

        $this->log("Viewing group: " . Debugger::exportVar($group, 3), LOG_DEBUG);

        $widgets = $this->WidgetUtil->load($group["Group"]["id"], 
                                           array('group_id' => $group["Group"]["id"]));
        $widget_list = $this->__prepare_widgets($widgets);

        $this->Group->locale = array("en_us");

        $this->set('group', $group);

        $this->set("title_for_layout", $this->GroupUtil->get_title($group));

        $this->set("widgets", $widget_list);
    }

    function __prepare_widgets($widgets) {
        $widget_list = array();
        foreach ($widgets as $widget) {
            if (strpos($widget["Widget"]["placement"], "|") === false) {
                $widget_list[$widget["Widget"]["placement"]] = $widget;
            } else {
                $placement = explode("|", $widget["Widget"]["placement"]);
                $widget_list[$placement[0]][$placement[1]] = $widget;
            }
        }

        $this->log("prepared widgets: " . Debugger::exportVar($widget_list, 3), LOG_DEBUG);

        return $widget_list;
    }
}
?>
