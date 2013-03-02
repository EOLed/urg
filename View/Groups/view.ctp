<?php $this->Html->css("/urg/css/urg.css", null, array("inline" => false)); ?>
<div class="groups view">
    <?php $bannerClass = isset($widgets["side"]) ? "span9" : "span12"; ?>
    <div class="row">
        <div id="banner" class="<?php echo $bannerClass ?>">
        <?php
            if (isset($widgets["banner"])) {
                $banner = $widgets["banner"];
                echo $this->{$banner["Widget"]["helper_name"]}->build(${"options_" .  
                                                                      $banner["Widget"]["id"]});
            }
        ?>
        </div>

        <?php if (isset($widgets["side"])) { ?>
        <div id="side-panel" class="hidden-phone span3">
          <?php 
            $side = $widgets["side"];
            echo $this->{$side["Widget"]["helper_name"]}->build(${"options_" .  
                                                                $side["Widget"]["id"]});
          ?>
        </div>
        <?php } ?>
    </div>

    <div class="row">
        <div class='span12'>
            <div id="group-name" class="page-title"><?php echo __($group["Group"]["name"]) ?></div>
        </div>
    </div>

    <div class="row">
    <?php
    $columns = array();
    if (!isset($widgets["layout"])) {
        $columns["col-0"] = "span4";
        $columns["col-1"] = "span4 center-col";
        $columns["col-2"] = "span4";
    } else {
        $layout_widget = $widgets["layout"];
        $this->{$layout_widget["Widget"]["helper_name"]}->build(${"options_" . 
                                                                $layout_widget["Widget"]["id"]});
        $columns = $this->{$layout_widget["Widget"]["helper_name"]}->get_columns();
    }

    foreach ($columns as $column_id => $column_class) { ?>
    <div id="<?php echo $column_id ?>" class="group-col <?php echo $column_class ?>">
        <?php 
        if (isset($widgets[$column_id])) {
            foreach ($widgets[$column_id] as $widget) {
                $widget = $this->{$widget["Widget"]["helper_name"]}->build(${"options_" . 
                          $widget["Widget"]["id"]});
                echo $this->Html->div("post-widget", $widget);
            }
        }
        ?>
    </div>
    <?php } ?>
    </div>
</div>
<?php /*
<script type="text/javascript">
<?php echo $this->element("js_equal_height"); ?>
$(".group-col").equalHeight();
</script> */
