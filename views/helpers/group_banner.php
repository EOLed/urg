<?php
class GroupBannerHelper extends AppHelper {
    var $helpers = array("Html", "Time");

    function build($options = array()) {
        $this->Html->css("/urg_post/css/urg_post.css", null, array("inline"=>false));
        $banners = $options["banners"];

        $widget = "";

        foreach ($banners as $banner) {
            $widget .= $this->Html->div("banner", $this->Html->image("/urg/img/banners/" . 
                                                                     $banner["Attachment"]["id"] . "/" . 
                                                                     $banner["Attachment"]["filename"]));
        }

        return $widget;
    }
}
