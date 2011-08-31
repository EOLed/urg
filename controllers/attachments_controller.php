<?php
class AttachmentsController extends UrgAppController {
    var $BANNER_FOLDER = "/app/plugins/urg/webroot/img/banners";
	var $name = 'Attachments';
    var $components = array("Cuploadify.cuploadify");

	function index() {
		$this->Attachment->recursive = 0;
		$this->set('attachments', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid attachment', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('attachment', $this->Attachment->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Attachment->create();
			if ($this->Attachment->save($this->data)) {
				$this->Session->setFlash(__('The attachment has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The attachment could not be saved. Please, try again.', true));
			}
		}
		$attachmentTypes = $this->Attachment->AttachmentType->find('list');
		$posts = $this->Attachment->Post->find('list');
		$users = $this->Attachment->User->find('list');
		$this->set(compact('attachmentTypes', 'posts', 'users'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid attachment', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Attachment->save($this->data)) {
				$this->Session->setFlash(__('The attachment has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The attachment could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Attachment->read(null, $id);
		}
		$attachmentTypes = $this->Attachment->AttachmentType->find('list');
		$posts = $this->Attachment->Post->find('list');
		$users = $this->Attachment->User->find('list');
		$this->set(compact('attachmentTypes', 'posts', 'users'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for attachment', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Attachment->delete($id)) {
			$this->Session->setFlash(__('Attachment deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Attachment was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

    function banner($group_slug = null) {
        $this->loadModel("Group");
        $banner_type = $this->Attachment->AttachmentType->findByName("Banner");

        if ($this->data == null)
        {
            $group = $this->Group->findBySlug($group_slug);
            $this->set("banner_type", $banner_type["AttachmentType"]["id"]);
            $this->set("group", $group);
            $this->set("group_slug", $group_slug);
        } else {
            $group = $this->Group->findBySlug($group_slug);
            $uploaded_banner = $this->data["Attachment"]["banner"];
            $this->data["Attachment"]["filename"] = $uploaded_banner["name"];
            $user = $this->Auth->user();
            $this->data["Attachment"]["user_id"] = $user["User"]["id"];
            $this->data["Attachment"]["attachment_type_id"] = $banner_type["AttachmentType"]["id"];
            if ($this->Attachment->saveAll($this->data)) {
                $target_path = $this->get_doc_root($this->BANNER_FOLDER) . "/" . $this->Attachment->id;
                if (!file_exists($target_path)) {
                    CakeLog::write("debug", "Creating directory: $target_path");
                    $old = umask(0);
                    mkdir($target_path, 0777, true); 
                    umask($old);
                }

                move_uploaded_file($uploaded_banner["tmp_name"], 
                                   $this->get_doc_root($this->BANNER_FOLDER) . "/" . $this->Attachment->id . "/" . 
                                   $uploaded_banner["name"]);

                $this->redirect(array("plugin" => "urg", 
                                      "controller" => "groups", 
                                      "action" => "view", 
                                      $this->data["Attachment"]["group_slug"]));
            }
        }
    }

    function get_doc_root($root = null) {
        $doc_root = $this->remove_trailing_slash(env('DOCUMENT_ROOT'));

        if ($root != null) {
            $root = $this->remove_trailing_slash($root);
            $doc_root .=  $root;
        }

        return $doc_root;
    }

    /**
     * Removes the trailing slash from the string specified.
     * @param $string the string to remove the trailing slash from.
     */
    function remove_trailing_slash($string) {
        $string_length = strlen($string);
        if (strrpos($string, "/") === $string_length - 1) {
            $string = substr($string, 0, $string_length - 1);
        }

        return $string;
    }
}
?>
