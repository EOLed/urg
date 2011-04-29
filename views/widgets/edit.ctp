<div class="widgets form">
<?php echo $this->Form->create('Widget');?>
	<fieldset>
 		<legend><?php __('Edit Widget'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('group_id');
		echo $this->Form->input('name');
		echo $this->Form->input('action');
		echo $this->Form->input('placement');
		echo $this->Form->input('options');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Widget.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Widget.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Widgets', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Groups', true), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group', true), array('controller' => 'groups', 'action' => 'add')); ?> </li>
	</ul>
</div>