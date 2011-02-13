<div class="securedActions form">
<?php echo $this->Form->create('SecuredAction');?>
	<fieldset>
 		<legend><?php __('Add Secured Action'); ?></legend>
	<?php
		echo $this->Form->input('role_id', array("empty"=>__("Public", true)));
		echo $this->Form->input('controller');
		echo $this->Form->input('action');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Secured Actions', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Roles', true), array('controller' => 'roles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Role', true), array('controller' => 'roles', 'action' => 'add')); ?> </li>
	</ul>
</div>
