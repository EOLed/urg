<div class="securedActions index">
	<h2><?php echo __('Secured Actions');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('role_id');?></th>
			<th><?php echo $this->Paginator->sort('controller');?></th>
			<th><?php echo $this->Paginator->sort('action');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($securedActions as $securedAction):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $securedAction['SecuredAction']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($securedAction['Role']['name'], array('controller' => 'roles', 'action' => 'view', $securedAction['Role']['id'])); ?>
		</td>
		<td><?php echo $securedAction['SecuredAction']['controller']; ?>&nbsp;</td>
		<td><?php echo $securedAction['SecuredAction']['action']; ?>&nbsp;</td>
		<td><?php echo $securedAction['SecuredAction']['created']; ?>&nbsp;</td>
		<td><?php echo $securedAction['SecuredAction']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $securedAction['SecuredAction']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $securedAction['SecuredAction']['id'])); ?>
			<?php echo $this->Html->link(__('Delete'), array('action' => 'delete', $securedAction['SecuredAction']['id']), null, sprintf(__('Are you sure you want to delete # %s?'), $securedAction['SecuredAction']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Secured Action'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Roles'), array('controller' => 'roles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Role'), array('controller' => 'roles', 'action' => 'add')); ?> </li>
	</ul>
</div>