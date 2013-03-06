<?php $this->Html->css("/urg/css/urg.css", null, array("inline" => false)); ?>
<div class="groups view">
  <?php
    $layout_widget = $widgets["layout"];
    $this->{$layout_widget["Widget"]["helper_name"]}
         ->build(${"options_" . $layout_widget["Widget"]["id"]});
    $layout = $this->{$layout_widget["Widget"]["helper_name"]}->get_layout();

    foreach($layout as $rowId => $row) { 
  ?>
  <div class="row" id="<?php echo $rowId; ?>">
    <?php foreach ($row as $columnId => $columnClass) { ?>
      <div id="<?php echo $columnId; ?>" class="<?php echo $columnClass ?>">
      <?php
        if (isset($widgets[$columnId])) {
            $columnWidget = $widgets[$columnId];
            if (!isset($columnWidget[0]))
              $columnWidget = array($columnWidget);

            foreach($columnWidget as $widget) {
              echo $this->{$widget["Widget"]["helper_name"]}
                        ->build(${"options_" . $widget["Widget"]["id"]});
            }
        }
      ?>
      </div>
    <?php } ?>
  </div>
  <?php } ?>
</div>
<?php
