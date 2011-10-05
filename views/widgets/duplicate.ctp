<div class="grid_6 widgets form">
<?php echo $this->Form->create('Widget');?>
	<fieldset>
 		<legend><?php __('Add Widget'); ?></legend>
	<?php
		echo $this->Form->input('group_id', array("empty"=>__("All groups", true)));
		echo $this->Form->input('name');
		echo $this->Form->input('action');
		echo $this->Form->input('placement');
		echo $this->Form->input('Widget.options', array("type"=>"textarea",
                                                        "label"=>__("Options", true)));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="grid_6 actions">
	<h2><?php __('Actions'); ?></h2>
	<ul>

		<li><?php echo $this->Html->link(__('List Widgets', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Groups', true), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group', true), array('controller' => 'groups', 'action' => 'add')); ?> </li>
	</ul>
</div>
