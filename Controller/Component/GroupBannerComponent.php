<?php
App::uses("ImgLibComponent", "ImgLib.Controller/Component");
App::uses("AbstractWidgetComponent", "Urg.Lib");
App::uses("FlyLoaderComponent", "Controller/Component");
/**
 * The GroupBanner widget can be used to add a banner of the specified group to views.
 *
 * Parameters: group_id        The id of the group whose banner you wish to display.
 */
class GroupBannerComponent extends AbstractWidgetComponent {
    var $BANNERS = "/app/plugins/urg/webroot/img/banners";
    var $components = array("FlyLoader");

    function build_widget() {
        $this->bindModels();
        $group_id = $this->widget_settings["group_id"];
        $banners = $this->get_banners($group_id);

        $this->set_banners($banners);
    }

    function get_banners($group_id) {
        $banners = false;
        $this->controller->loadModel("Urg.AttachmentMetadatum");
        $meta = $this->controller->AttachmentMetadatum->find("first", array("conditions" => array(
                "AttachmentMetadatum.key" => "group_id",
                "AttachmentMetadatum.value" => $group_id), "order" => "created DESC"));

        if (!empty($meta)) {
            $banners = $this->controller->Attachment->findAllById($meta["AttachmentMetadatum"]["attachment_id"]);
        }

        while ($banners === false) {
            $parent = $this->controller->Group->getParentNode($group_id);

            if ($parent !== false) {
                $group_id = $parent["Group"]["id"];
                $banners = $this->get_banners($group_id);
            } else {
                $banners = array();
                break;
            }
        }

        return $banners;
    }
    function bindModels() {
        $this->controller->loadModel("Urg.Attachment");
        $this->controller->loadModel("UrgPost.Post");
    }

    function set_banners($attachments) {
        Configure::load("config");
        foreach ($attachments as &$attachment) {
            $this->log("getting banner for " . $attachment["Attachment"]["filename"], LOG_DEBUG);
            $attachment["Attachment"]["filename"] = $this->get_image_path($attachment, Configure::read("Banner.defaultWidth"));
            CakeLog::write("debug", "Attachment filename: " . $attachment["Attachment"]["filename"]);
        }

        $this->set("banners", $attachments);

        CakeLog::write("debug", "attachments for Group Banner widget: " . Debugger::exportVar($attachments, 3));
    }

    function get_image_path($attachment, $width, $height = 0) {
        $this->controller->FlyLoader->load("Component", "ImgLib.ImgLib");
        //TODO fix FlyLoader... should refer to it within component.
        CakeLog::write("debug", "getting image path for attachment: " . Debugger::exportVar($attachment,3));
        $full_image_path = null;
        foreach ($attachment["AttachmentMetadatum"] as $meta) {
            if (strcmp($meta["key"], "group_id") == 0) {
                $full_image_path = $this->controller->ImgLib->get_doc_root($this->BANNERS) .  "/" .  
                        $meta["value"];
                break;
            }
        }
        $image = $this->controller->ImgLib->get_image("$full_image_path/" . $attachment["Attachment"]["filename"], 
                                          $width, 
                                          $height, 
                                          'landscape'); 
        return $image["filename"];
    }
}
