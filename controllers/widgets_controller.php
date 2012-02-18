<?php
App::import("Lib", "Urg.TranslatableController");
class WidgetsController extends UrgAppController {
	var $name = 'Widgets';

	function index() {
		$this->Widget->recursive = 0;
		$this->set('widgets', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid widget', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('widget', $this->Widget->read(null, $id));
	}

	function add($group_id = null) {
		if (!empty($this->data)) {
			$this->Widget->create();
			if ($this->Widget->save($this->data)) {
				$this->Session->setFlash(__('The widget has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The widget could not be saved. Please, try again.', true));
			}
		}

        if ($group_id != null) {
            $this->data["Widget"]["group_id"] = $group_id;
        }

		$groups = $this->Widget->Group->find('list');
		$this->set(compact('groups'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid widget', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
            $this->data["Widget"]["options"] = $this->data["Widget"]["options"];
            $success = $this->Widget->save($this->data);
            if (!$success) {
                $this->Session->setFlash(__('The widget could not be saved. Please, try again.', true));
                break;
            }

            if ($success) {
                $this->Session->setFlash(__('The widget has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
		}
		if (empty($this->data)) {
			$this->data = $this->Widget->find("first", 
                                              array("conditions" => array("Widget.id" => $id)));
		}
		$groups = $this->Widget->Group->find('list');
		$this->set(compact('groups'));
	}

	function duplicate($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid widget', true));
			$this->redirect(array('action' => 'index'));
		}
		if (empty($this->data)) {
			$this->data = $this->Widget->find("first", 
                                              array("conditions" => array("Widget.id" => $id)));
            $this->data["Widget"]["group_id"] = null;
		} else {
            $this->Widget->create();

            $success = $this->Widget->save($this->data);

            if ($this->Widget->save($this->data)) {
                $this->Session->setFlash(__('The widget has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The widget could not be saved. Please, try again.', true));
            }
		}
		$groups = $this->Widget->Group->find('list', array("order" => "Group.name"));
		$this->set(compact('groups'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for widget', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Widget->delete($id)) {
			$this->Session->setFlash(__('Widget deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Widget was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>