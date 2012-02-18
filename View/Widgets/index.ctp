<div class="widgets index">
	<h2><?php echo __('Widgets');?></h2>
	<table>
	<tr>
			<th><?php echo $this->Paginator->sort('group_id');?></th>
			<th><?php echo $this->Paginator->sort('action');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('placement');?></th>
			<th><?php echo $this->Paginator->sort('options');?></th>
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
			<?php echo $this->Html->link($widget['Group']['slug'], array('controller' => 'groups', 'action' => 'view', $widget['Group']['id'])); ?>
		</td>
		<td><?php echo $widget['Widget']['action']; ?>&nbsp;</td>
		<td><?php echo $widget['Widget']['name']; ?>&nbsp;</td>
		<td><?php echo $widget['Widget']['placement']; ?>&nbsp;</td>
		<td><?php echo $widget['Widget']['options']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $widget['Widget']['id']), array("class" => "button")); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $widget['Widget']['id']), array("class" => "button")); ?>
			<?php echo $this->Html->link(__('Duplicate'), array('action' => 'duplicate', $widget['Widget']['id']), array("class" => "button")); ?>
			<?php echo $this->Html->link(__('Delete'), array('action' => 'delete', $widget['Widget']['id']), array("class" => "button"), sprintf(__('Are you sure you want to delete # %s?'), $widget['Widget']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%')
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous'), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next') . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Widget'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Groups'), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group'), array('controller' => 'groups', 'action' => 'add')); ?> </li>
	</ul>
</div>

<script type="text/javascript">
    $(function() {
        $(".button").button();
    });
</script>
