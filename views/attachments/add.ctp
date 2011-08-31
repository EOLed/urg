<div class="attachments form">
<?php echo $this->Form->create('Attachment');?>
	<fieldset>
 		<legend><?php __('Add Attachment'); ?></legend>
	<?php
		echo $this->Form->input('attachment_type_id');
		echo $this->Form->input('post_id');
		echo $this->Form->input('user_id');
		echo $this->Form->input('filename');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Attachments', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Attachment Types', true), array('controller' => 'attachment_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Attachment Type', true), array('controller' => 'attachment_types', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Posts', true), array('controller' => 'posts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Post', true), array('controller' => 'posts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Attachment Metadata', true), array('controller' => 'attachment_metadata', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Attachment Metadatum', true), array('controller' => 'attachment_metadata', 'action' => 'add')); ?> </li>
	</ul>
</div>