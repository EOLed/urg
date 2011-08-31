<div class="attachments view">
<h2><?php  __('Attachment');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $attachment['Attachment']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Attachment Type'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($attachment['AttachmentType']['name'], array('controller' => 'attachment_types', 'action' => 'view', $attachment['AttachmentType']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Post'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($attachment['Post']['title'], array('controller' => 'posts', 'action' => 'view', $attachment['Post']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($attachment['User']['id'], array('controller' => 'users', 'action' => 'view', $attachment['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Filename'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $attachment['Attachment']['filename']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $attachment['Attachment']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $attachment['Attachment']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Attachment', true), array('action' => 'edit', $attachment['Attachment']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Attachment', true), array('action' => 'delete', $attachment['Attachment']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $attachment['Attachment']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Attachments', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Attachment', true), array('action' => 'add')); ?> </li>
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
<div class="related">
	<h3><?php __('Related Attachment Metadata');?></h3>
	<?php if (!empty($attachment['AttachmentMetadatum'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Attachment Id'); ?></th>
		<th><?php __('Key'); ?></th>
		<th><?php __('Value'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($attachment['AttachmentMetadatum'] as $attachmentMetadatum):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $attachmentMetadatum['id'];?></td>
			<td><?php echo $attachmentMetadatum['attachment_id'];?></td>
			<td><?php echo $attachmentMetadatum['key'];?></td>
			<td><?php echo $attachmentMetadatum['value'];?></td>
			<td><?php echo $attachmentMetadatum['created'];?></td>
			<td><?php echo $attachmentMetadatum['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'attachment_metadata', 'action' => 'view', $attachmentMetadatum['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'attachment_metadata', 'action' => 'edit', $attachmentMetadatum['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'attachment_metadata', 'action' => 'delete', $attachmentMetadatum['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $attachmentMetadatum['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Attachment Metadatum', true), array('controller' => 'attachment_metadata', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
