<div class="row">
    <div class="span12">
        <?php echo $this->Form->create("Widget", array("class" => "form-horizontal")); ?>
        <fieldset>
            <legend><?php echo __('Add Widget'); ?></legend>
            <?php
            echo $this->Form->input("placement", array("type" => "hidden"));
            echo $this->Form->input("options", array("type" => "hidden"));
            echo $this->TwitterBootstrap->input("group_id", array("empty" => __("All groups")));
            echo $this->TwitterBootstrap->input("action");
            echo $this->TwitterBootstrap->input("name", 
                                                array("type" => "select", 
                                                      "options" => $widgets,
                                                      "empty" => "[" . __("Select a widget", true) . "]"));
            ?>
            <div id="widget-ui"></div>
        </fieldset>
        <div class="form-actions">
            <?php echo $this->TwitterBootstrap->button("Save", array("style" => "primary")); ?>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<script type="text/javascript">
    $("#WidgetName").change(function() {
        $("#widget-ui").load("<?php echo $this->Html->url(array("plugin" => "urg",
                                                                "controller" => "widgets",
                                                                "action" => "ui",
                                                                )) ?>" + "/" + $(this).val());
    });
</script>


