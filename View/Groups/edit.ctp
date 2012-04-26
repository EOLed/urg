<?php
    echo $this->Slug->include_script(array("inline"=>"true"));
?>
<div class="groups form span6 suffix_6">
<?php echo $this->Form->create('Group');?>
	<fieldset>
 		<legend><?php echo __('Edit Group'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('parent_id', array("label"=>__("Parent Group"), "empty"=>__("No Parent")));
        echo $this->Slug->slug("name", "slug", 
                array("base_url" => "http://montreal-cac.org/urg_group/groups/", 
                      "auto_update" => false));
		echo $this->Form->input('description', array("type" => "textrea"));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions span6 suffix_6">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete'), array('action' => 'delete', $this->Form->value('Group.id')), null, sprintf(__('Are you sure you want to delete # %s?'), $this->Form->value('Group.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Groups'), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('List Roles'), array('controller' => 'roles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Role'), array('controller' => 'roles', 'action' => 'add')); ?> </li>
	</ul>
</div>
<script type="text/javascript">
    $("#GroupName").focus();
</script>
<?php 
$this->Html->css("/urg/css/urg", null, array("inline"=>false));
