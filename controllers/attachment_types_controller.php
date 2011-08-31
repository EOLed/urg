<?php
class AttachmentTypesController extends UrgPostAppController {

	var $name = 'AttachmentTypes';

	function index() {
		$this->AttachmentType->recursive = 0;
		$this->set('attachmentTypes', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid attachment type', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('attachmentType', $this->AttachmentType->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->AttachmentType->create();
			if ($this->AttachmentType->save($this->data)) {
				$this->Session->setFlash(__('The attachment type has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The attachment type could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid attachment type', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->AttachmentType->save($this->data)) {
				$this->Session->setFlash(__('The attachment type has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The attachment type could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->AttachmentType->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for attachment type', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->AttachmentType->delete($id)) {
			$this->Session->setFlash(__('Attachment type deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Attachment type was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

    function find_by_name($name) {
        return $this->AttachmentType->find("first", array("conditions"=>array("AttachmentType.name" => $name)));
    }
}
?>
