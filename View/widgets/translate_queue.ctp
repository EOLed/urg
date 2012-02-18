<div class="widgets index">
	<h2><?php echo __('Translate Widgets');?></h2>
	<table>
	<tr>
        <th><?php echo __('Slug');?></th>
        <th class="actions"></th>
	</tr>
	<?php
	$i = 0;
	foreach ($widgets as $widget):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $this->Html->link($widget['Widget']['slug'], array('controller' => 'widgets', 'action' => 'view', $widget['Widget']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Translate'), 
                                         array('action' => 'translate', 
                                               $widget['Widget']['slug'], 
                                               $widget["Widget"]["locale"]), 
                                         array("class" => "button")); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Widget'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Widgets'), array('controller' => 'widgets', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Widget'), array('controller' => 'widgets', 'action' => 'add')); ?> </li>
	</ul>
</div>

<script type="text/javascript">
    $(function() {
        $(".button").button();
    });
</script>
