<?php
App::uses("ImgLibComponent", "ImgLib.Controller/Component");
App::uses("GrpHelper", "Urg.View/Helper");
App::uses("WidgetUtilComponent", "Urg.Controller/Component");
App::uses("PostModel", "UrgPost.Model");
App::uses("GroupModel", "Urg.Model");
App::uses("FlyLoaderComponent", "Controller/Component");
App::uses("MarkdownHelper", "Markdown.View/Helper");
class GroupsController extends UrgAppController {
    var $IMAGES = "/app/plugins/urg_post/webroot/img";
	var $name = 'Groups';
    var $helpers = array("Html", "Form", "Slug", "Urg.Grp", "Markdown.Markdown");
    var $components = array("ImgLib.ImgLib", "Urg.WidgetUtil", "FlyLoader");

	function index() {
		$this->Group->recursive = 0;
        $groups = $this->Group->find("threaded");
        $this->log("groups: " . Debugger::exportVar($groups, 6), LOG_DEBUG);
        $this->set('groups', $groups);
	}

	function add($parent_id = null) {
		if (!empty($this->data)) {
			$this->Group->create();
			if ($this->Group->save($this->data)) {
				$this->Session->setFlash(__('The group has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.'));
			}
		} else if ($parent_id != null) {
            $this->data["Group"]["parent_id"] = $parent_id;
            $parent_group = $this->Group->findById($parent_id);
            $this->data["ParentGroup"] = $parent_group["Group"];
        }
		$groups = $this->Group->find('list');
		$this->set(compact('groups', 'groups'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid group'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Group->save($this->data)) {
				$this->Session->setFlash(__('The group has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Group->read(null, $id);
            $this->log("editing group: " . Debugger::exportVar($this->data, 4), LOG_DEBUG);
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
        $this->log("Viewing group: " . Debugger::exportVar($group, 3), LOG_DEBUG);

        $widgets = $this->WidgetUtil->load($group["Group"]["id"], 
                                           array('group_id' => $group["Group"]["id"]));
        $widget_list = $this->prepare_widgets($widgets);

        $this->Group->locale = array("en_us");
        $mcac = $this->Group->find("first", 
                array("conditions" => array("Group.name" => "Montreal Chinese Alliance Church")));

        $this->set('group', $group);

        $this->set("title_for_layout", __("Groups") . " &raquo; " . $group["Group"]["name"]);

        $this->set("widgets", $widget_list);
    }

    function prepare_widgets($widgets) {
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

    function translate($slug = null, $original_locale = null) {
        parent::translate($slug, $original_locale);
        $this->data["Group"]["parent_id"] = $this->data["Translation"]["Group"]["parent_id"];
    }
}
?>