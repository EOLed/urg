<?php $this->Html->css("/urg/css/urg.css", null, array("inline" => false)); ?>
<div class="groups view">
    <?php foreach ($banners as $banner) { ?>
    <div id="banner" class="grid_9 right-border">
        <?php echo $this->Html->image($banner, array("class"=>"shadow")); ?>
    </div>
    <?php } ?>
    <div id="about-panel" class="grid_3">
        <h3><?php echo strtoupper(__("About us", true)); ?></h3>
        <?php echo $about["Post"]["content"] ?>
    </div>

    <div id='group-name' class='grid_12 page-title'>
        <div><?php echo $group["Group"]["name"]?></div>
    </div>

    <?php
    $columns = array();
    if (!isset($widgets["layout"])) {
        $columns["col-0"] = "grid_4 right-border";
        $columns["col-1"] = "grid_4 right-border";
        $columns["col-2"] = "grid_4";
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
<script type="text/javascript">
<?php echo $this->element("js_equal_height"); ?>
$(".group-col").equalHeight();
</script>
