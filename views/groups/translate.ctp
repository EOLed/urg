<?php
    echo $this->Slug->include_script(array("inline"=>"true"));
?>
<div class="groups form grid_6 suffix_6">
<?php echo $this->Form->create('Group');?>
	<fieldset>
 		<legend><?php __('Edit Group'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('parent_id', array("label"=>__("Parent Group", true), "empty"=>__("No Parent", true)));
        echo $this->Form->input('Group.locale', array("type" => "select", 
                                                      "label" => __("Language", true),
                                                      "options" => $locales));
		echo $this->Form->input('name');
		echo $this->Form->input('description', array("type"=>"textarea"));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions grid_6 suffix_6">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Group.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Group.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Groups', true), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('List Roles', true), array('controller' => 'roles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Role', true), array('controller' => 'roles', 'action' => 'add')); ?> </li>
	</ul>
</div>
<script type="text/javascript">
    $("#GroupName").focus();
</script>
<?php 
$this->Html->css("/urg/css/urg", null, array("inline"=>false));
