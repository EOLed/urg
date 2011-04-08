<?php
App::import("Component", "ImgLib.ImgLib");
App::import("Helper", "Urg.Group");
class GroupsController extends UrgAppController {

    var $IMAGES = "/app/plugins/urg_post/webroot/img";
	var $name = 'Groups';
    var $helpers = array("Html", "Form", "Slug");
    var $components = array("ImgLib", "FlyLoader");

	function index() {
		$this->Group->recursive = 0;
		$this->set('groups', $this->paginate());
	}

	function add() {
		if (!empty($this->data)) {
			$this->Group->create();
			if ($this->Group->save($this->data)) {
				$this->Session->setFlash(__('The group has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.', true));
			}
		}
		$groups = $this->Group->find('list');
		$this->set(compact('groups', 'groups'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid group', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Group->save($this->data)) {
				$this->Session->setFlash(__('The group has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Group->read(null, $id);
		}
		$groups = $this->Group->find('list');
		$this->set(compact('groups', 'groups'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for group', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Group->delete($id)) {
			$this->Session->setFlash(__('Group deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Group was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

    function view($slug) {
        if (!$slug) {
            $this->Session->setFlash(__('Must specify group.', true));
            $this->redirect(array('action' => 'index'));
        }

        $this->FlyLoader->load("Helper", "Urg.Group");

        $group = $this->Group->findBySlug($slug);

        $this->log("Viewing group: " . Debugger::exportVar($group, 3), LOG_DEBUG);
        $about_group = $this->get_about($group["Group"]["name"]);
        $about = $this->get_about("Montreal Chinese Alliance Church");
        $this->set('group', $group);
        $this->set("about_group", $about_group);
        $this->set("activity", $this->get_recent_activity($group));
        $this->set("upcoming_events", $this->get_upcoming_activity($group));
        $this->set("about", $about);

        $banners = $this->get_banners($about_group);
        if (empty($banners)) {
            $banners = $this->get_banners($about);
        }

        $this->set("title_for_layout", __("Groups", true) . " &raquo; " . $group["Group"]["name"]);

        $this->set("banners", $banners);
    }

    function get_banners($post) {
        $this->loadModel("Attachment");
        $this->Attachment->bindModel(array("belongsTo" => array("AttachmentType")));

        $banner_type = $this->Attachment->AttachmentType->findByName("Banner");

        $banners = array();

        if (isset($post["Attachment"])) {
            Configure::load("config");
            foreach ($post["Attachment"] as $attachment) {
                if ($attachment["attachment_type_id"] == $banner_type["AttachmentType"]["id"]) {
                    $this->log("getting banner for " . $attachment["filename"], LOG_DEBUG);
                    array_push($banners, $this->get_image_path($attachment["filename"],
                                                               $post,
                                                               Configure::read("Banner.defaultWidth")));
                }
            }
        }

        return $banners;
    }

    function get_about($name) {
        $this->loadModel("Post");
        $this->Post->bindModel(array("belongsTo" => array("Group")));
        $this->Post->bindModel(array("hasMany" => array("Attachment")));

        $about_group = $this->Group->findByName("About");

        $about = $this->Post->find("first", 
                array("conditions" => 
                        array("OR" => array(
                                "Group.name" => "About", 
                                "Group.group_id" => $about_group["Group"]["id"]),
                              "AND" => array("Post.title" => $name)
                        ),
                      "order" => "Post.publish_timestamp DESC"
                )
        );

        $this->log("about for group: $name" .  Debugger::exportVar($about, 3), LOG_DEBUG);

        return $about;
    }

    function get_recent_activity($group) {
        $posts = $this->Post->find('all', 
                array("conditions" => array("Post.group_id" => $group["Group"]["id"],
                                            "Post.publish_timestamp < NOW()"),
                      "limit" => 10,
                      "order" => "Post.publish_timestamp DESC"));
        $activity = array();
        foreach ($posts as $post) {
            array_push($activity, $post);
        }
        
        $this->log("group activity: " . Debugger::exportVar($activity, 3), LOG_DEBUG);

        return $activity;
    }

    function get_upcoming_activity($group) {
        $posts = $this->Post->find('all', 
                array("conditions" => array("Post.group_id" => $group["Group"]["id"],
                                            "Post.publish_timestamp > NOW()"),
                      "limit" => 10,
                      "order" => "Post.publish_timestamp"));
        
        $this->log("upcoming posts: " . Debugger::exportVar($posts, 3), LOG_DEBUG);

        return $posts;
    }

    function get_image_path($filename, $post, $width, $height = 0) {
        $full_image_path = $this->get_doc_root($this->IMAGES) . "/" .  $post["Post"]["id"];
        $image = $this->ImgLib->get_image("$full_image_path/$filename", $width, $height, 'landscape'); 
        return "/urg_post/img/" . $post["Post"]["id"] . "/" . $image["filename"];
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

    function get_webroot_folder($filename) {
        $webroot_folder = null;

        if ($this->is_filetype($filename, array(".jpg", ".jpeg", ".png", ".gif", ".bmp"))) {
            $webroot_folder = $this->IMAGES_WEBROOT;
        } else if ($this->is_filetype($filename, array(".mp3"))) {
            $webroot_folder = $this->AUDIO_WEBROOT;
        } else if ($this->is_filetype($filename, array(".ppt", ".pptx", ".doc", ".docx"))) {
            $webroot_folder = $this->FILES_WEBROOT;
        }

        return $webroot_folder;
    }

    function is_filetype($filename, $filetypes) {
        $filename = strtolower($filename);
        $is = false;
        if (is_array($filetypes)) {
            foreach ($filetypes as $filetype) {
                if ($this->ends_with($filename, $filetype)) {
                    $is = true;
                    break;
                }
            }
        } else {
            $is = $this->ends_with($filename, $filetypes);
        }

        $this->log("is $filename part of " . implode(",",$filetypes) . "? " . ($is ? "true" : "false"), 
                LOG_DEBUG);
        return $is;
    }

    function ends_with($haystack, $needle) {
        return strrpos($haystack, $needle) === strlen($haystack)-strlen($needle);
    }
}
?>
