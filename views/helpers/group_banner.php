<?php
class GroupBannerHelper extends AppHelper {
    var $helpers = array("Html", "Time");

    function build($options = array()) {
        $this->Html->css("/urg_post/css/urg_post.css", null, array("inline"=>false));
        $banners = $options["banners"];

        $widget = "";

        foreach ($banners as $banner) {
            foreach ($banner["AttachmentMetadatum"] as $meta) {
                if (strcmp($meta["key"], "group_id") == 0) {
                    $widget .= $this->Html->div("banner", $this->Html->image("/urg/img/banners/$meta[value]/" . 
                                                                             $banner["Attachment"]["filename"]));
                    break;
                }
            }
        }

        return $widget;
    }
}
