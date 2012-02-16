<div class="grid_6 roles form">
<?php echo $this->Form->create('Role');?>
	<fieldset>
 		<legend><?php __('Edit Role'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('group_id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
	?>
	</fieldset>
	<fieldset id="secured-actions">
 		<legend><?php __('Manage Secured Actions'); ?></legend>
        <?php echo $this->Form->input("secured_actions", 
                array("multiple"=>"checkbox", "options"=>$controllers)
        ); ?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="grid_6 actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Role.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Role.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Roles', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Groups', true), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group', true), array('controller' => 'groups', 'action' => 'add')); ?> </li>
	</ul>
</div>

<?php $this->Html->css("/urg/css/urg.css", null, array("inline"=>false)); ?>

