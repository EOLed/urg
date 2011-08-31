<div class="attachmentMetadata form">
<?php echo $this->Form->create('AttachmentMetadatum');?>
	<fieldset>
 		<legend><?php __('Add Attachment Metadatum'); ?></legend>
	<?php
		echo $this->Form->input('attachment_id');
		echo $this->Form->input('key');
		echo $this->Form->input('value');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Attachment Metadata', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Attachments', true), array('controller' => 'attachments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Attachment', true), array('controller' => 'attachments', 'action' => 'add')); ?> </li>
	</ul>
</div>