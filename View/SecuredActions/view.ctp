<div class="securedActions view">
<h2><?php  __('Secured Action');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $securedAction['SecuredAction']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Role'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($securedAction['Role']['name'], array('controller' => 'roles', 'action' => 'view', $securedAction['Role']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Controller'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $securedAction['SecuredAction']['controller']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Action'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $securedAction['SecuredAction']['action']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $securedAction['SecuredAction']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $securedAction['SecuredAction']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Secured Action', true), array('action' => 'edit', $securedAction['SecuredAction']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Secured Action', true), array('action' => 'delete', $securedAction['SecuredAction']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $securedAction['SecuredAction']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Secured Actions', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Secured Action', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Roles', true), array('controller' => 'roles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Role', true), array('controller' => 'roles', 'action' => 'add')); ?> </li>
	</ul>
</div>
