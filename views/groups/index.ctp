<div class="grid_8 groups index">
	<h2><?php __('Groups');?></h2>
	<ul id="group-list">
    <?php
	$i = 0;
	foreach ($groups as $group):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		} 

        echo $this->Grp->li($group);
        
        /*
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $group['Group']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($group['ParentGroup']['name'], array('controller' => 'groups', 'action' => 'view', $group['ParentGroup']['id'])); ?>
		</td>
		<td><?php echo $group['Group']['name']; ?>&nbsp;</td>
		<td><?php echo $group['Group']['description']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $group['Group']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $group['Group']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $group['Group']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $group['Group']['id'])); ?>
		</td>
	</tr> */
    endforeach; ?>
	</ul>
</div>
<div class="grid_4 actions">
	<h2><?php __('Actions'); ?></h2>
	<ul>
		<li><?php echo $this->Html->link(__('New Group', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Roles', true), array('controller' => 'roles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Role', true), array('controller' => 'roles', 'action' => 'add')); ?> </li>
	</ul>
</div>

<?php echo $this->Html->css("/urg/css/urg.css", null, array("inline"=>false)); ?>
