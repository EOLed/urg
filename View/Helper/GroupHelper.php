<?php
class GroupHelper extends AppHelper {
    var $helpers = array("Html", "Time");

    function post_feed($group, $posts) {
        $feed = "";
        foreach ($posts as $feed_item) {
            $feed_icon = $this->feed_icon($feed_item);
            $time = $this->Html->div("feed-timestamp",
                    $feed_icon . 
                    $this->Time->timeAgoInWords($feed_item["Post"]["publish_timestamp"], 'j/n/y', false, true));
            $title = $this->Html->tag("h3", $this->Html->link($feed_item["Post"]["title"], 
                                    "/urg_post/posts/view/" . $feed_item["Post"]["id"]));
            $feed .= $title . $feed_item["Post"]["content"] . $time;
        }

        return $this->Html->div("", $feed, array("id" => "activity-feed"));
    }

    function feed_icon($feed_item) {
        $icon = null;
        if (isset($feed_item["Post"])) {
           $icon = $this->Html->image("/urg/img/icons/feed/cloud-alt.png",
                                      array("class" => "feed-icon")); 
        }
        return $icon; 
    }

    function upcoming_activity($posts) {
        $upcoming_events = "";
        foreach ($posts as $post) {
            $time = $this->Html->div("upcoming-timestamp",
                    $this->Time->format("F d, Y", $post["Post"]["publish_timestamp"]));
            $upcoming_events .= $this->Html->tag("li", $time . $post["Post"]["title"]);
        }

        return $this->Html->tag("ul", $upcoming_events, array("id" => "upcoming-events"));
    }

    function rows($group) {
        $rows = $this->Html->tableCells(array($group["Group"]["id"], $group["ParentGroup"]["name"], $group["Group"]["name"]));

        if (isset($group["children"])) {
            foreach ($group["children"] as $child) {
                $rows .= $this->list($child);
            }
        }

        return $rows;
    }
}
