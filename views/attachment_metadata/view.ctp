<div class="attachmentMetadata view">
<h2><?php  __('Attachment Metadatum');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $attachmentMetadatum['AttachmentMetadatum']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Attachment'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($attachmentMetadatum['Attachment']['filename'], array('controller' => 'attachments', 'action' => 'view', $attachmentMetadatum['Attachment']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Key'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $attachmentMetadatum['AttachmentMetadatum']['key']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Value'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $attachmentMetadatum['AttachmentMetadatum']['value']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $attachmentMetadatum['AttachmentMetadatum']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $attachmentMetadatum['AttachmentMetadatum']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Attachment Metadatum', true), array('action' => 'edit', $attachmentMetadatum['AttachmentMetadatum']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Attachment Metadatum', true), array('action' => 'delete', $attachmentMetadatum['AttachmentMetadatum']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $attachmentMetadatum['AttachmentMetadatum']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Attachment Metadata', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Attachment Metadatum', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Attachments', true), array('controller' => 'attachments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Attachment', true), array('controller' => 'attachments', 'action' => 'add')); ?> </li>
	</ul>
</div>
