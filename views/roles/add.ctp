<div class="roles form">
<?php echo $this->Form->create('Role');?>
	<fieldset>
 		<legend><?php __('Add Role'); ?></legend>
	<?php
		echo $this->Form->input('group_id', array("label"=>__("roles.label.group", true), "empty"=>__("roles.label.group.select", true)));
		echo $this->Form->input('name');
		echo $this->Form->input('description');
	?>
	</fieldset>
	<fieldset>
 		<legend><?php __('role.title.secured.actions.manage'); ?></legend>
        <?php echo $this->Form->input("secured_actions", 
                array("multiple"=>"checkbox", "options"=>$controllers)
        ); ?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Roles', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Groups', true), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group', true), array('controller' => 'groups', 'action' => 'add')); ?> </li>
	</ul>
</div>
