<div class="attachments index">
	<h2><?php __('Attachments');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('attachment_type_id');?></th>
			<th><?php echo $this->Paginator->sort('post_id');?></th>
			<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th><?php echo $this->Paginator->sort('filename');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($attachments as $attachment):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $attachment['Attachment']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($attachment['AttachmentType']['name'], array('controller' => 'attachment_types', 'action' => 'view', $attachment['AttachmentType']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($attachment['Post']['title'], array('controller' => 'posts', 'action' => 'view', $attachment['Post']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($attachment['User']['id'], array('controller' => 'users', 'action' => 'view', $attachment['User']['id'])); ?>
		</td>
		<td><?php echo $attachment['Attachment']['filename']; ?>&nbsp;</td>
		<td><?php echo $attachment['Attachment']['created']; ?>&nbsp;</td>
		<td><?php echo $attachment['Attachment']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $attachment['Attachment']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $attachment['Attachment']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $attachment['Attachment']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $attachment['Attachment']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Attachment', true), array('action' => 'add')); ?></li>
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