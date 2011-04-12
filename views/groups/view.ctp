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

    <div id="col-1" class="grid_4 right-border view-col">
        <?php 
        foreach ($widgets[0] as $widget) {
            $options = array();
            foreach ($this->{$widget}->widget_options as $option) {
                $options[$option] = ${$option};
            }
            echo $this->{$widget}->build($options);
        }
        ?>
    </div>
    <div id="col-2" class="grid_4 right-border view-col">
        <?php 
        foreach ($widgets[1] as $widget) {
            $options = array();
            foreach ($this->{$widget}->widget_options as $option) {
                $options[$option] = ${$option};
            }
            echo $this->{$widget}->build($options);
        }
        ?>
    </div>
    <div id="col-3" class="grid_4 view-col">
        <?php 
        foreach ($widgets[2] as $widget) {
            $options = array();
            foreach ($this->{$widget}->widget_options as $option) {
                $options[$option] = ${$option};
            }
            echo $this->{$widget}->build($options);
        }
        ?>
    </div>
</div>
<script type="text/javascript">
<?php echo $this->element("js_equal_height"); ?>
$(".view-col").equalHeight();
</script>
<?php $this->Html->css("/urg_post/css/urg_post.css", null, array("inline" => false)); ?>
