<?php
class WidgetsController extends UrgAppController {
	var $name = 'Widgets';

	function index() {
		$this->Widget->recursive = 0;
		$this->set('widgets', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid widget'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('widget', $this->Widget->read(null, $id));
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

		$groups = $this->Widget->Group->find('list');
		$this->set(compact('groups'));
	}

	function edit($id = null) {
		if (!$id && empty($this->request->data)) {
			$this->Session->setFlash(__('Invalid widget'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->request->data)) {
            $this->request->data["Widget"]["options"] = $this->data["Widget"]["options"];
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
		$groups = $this->Widget->Group->find('list');
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
		$groups = $this->Widget->Group->find('list', array("order" => "Group.name"));
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
