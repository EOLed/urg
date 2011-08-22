<div class="groups index">
	<h2><?php __('Translate Groups');?></h2>
	<table>
	<tr>
        <th><?php echo __('Slug');?></th>
        <th class="actions"></th>
	</tr>
	<?php
	$i = 0;
	foreach ($groups as $group):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $this->Html->link($group['Group']['slug'], array('controller' => 'groups', 'action' => 'view', $group['Group']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Translate', true), 
                                         array('action' => 'translate', 
                                               $group['Group']['slug'], 
                                               $group["Group"]["locale"]), 
                                         array("class" => "button")); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Group', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Groups', true), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group', true), array('controller' => 'groups', 'action' => 'add')); ?> </li>
	</ul>
</div>

<script type="text/javascript">
    $(function() {
        $(".button").button();
    });
</script>
