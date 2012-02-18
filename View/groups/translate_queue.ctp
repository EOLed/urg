<div class="groups index">
	<h2><?php echo __('Translate Groups');?></h2>
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
			<?php echo $this->Html->link(__('Translate'), 
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
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Group'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Groups'), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group'), array('controller' => 'groups', 'action' => 'add')); ?> </li>
	</ul>
</div>

<script type="text/javascript">
    $(function() {
        $(".button").button();
    });
</script>
