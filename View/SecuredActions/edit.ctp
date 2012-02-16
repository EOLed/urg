<div class="securedActions form">
<?php echo $this->Form->create('SecuredAction');?>
	<fieldset>
 		<legend><?php __('Edit Secured Action'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('role_id', array("empty" => "Public"));
		echo $this->Form->input('controller');
		echo $this->Form->input('action');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('SecuredAction.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('SecuredAction.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Secured Actions', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Roles', true), array('controller' => 'roles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Role', true), array('controller' => 'roles', 'action' => 'add')); ?> </li>
	</ul>
</div>
