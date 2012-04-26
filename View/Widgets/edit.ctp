<div class="span6 widgets form">
<?php echo $this->Form->create('Widget');?>
	<fieldset>
 		<legend><?php echo __('Edit Widget'); ?></legend>
	<?php
		echo $this->Form->hidden('id');
		echo $this->Form->input('group_id', array("empty"=>__("All groups")));
		echo $this->Form->input('name');
		echo $this->Form->input('action');
		echo $this->Form->input('placement');
		echo $this->Form->input('Widget.options', array("type"=>"textarea",
                                                        "label"=>__("Options")));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="span6 actions">
	<h2><?php echo __('Actions'); ?></h2>
	<ul>

		<li><?php echo $this->Html->link(__('List Widgets'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Groups'), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group'), array('controller' => 'groups', 'action' => 'add')); ?> </li>
	</ul>
</div>
