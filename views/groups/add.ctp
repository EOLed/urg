<?php
    echo $this->Slug->include_script(array("inline"=>"true"));
?>
<div class="groups form grid_6 suffix_6">
<?php echo $this->Form->create('Group');?>
	<fieldset>
 		<legend><?php __('Add Group'); ?></legend>
	<?php
		echo $this->Form->input('group_id', array("label"=>__("Parent Group", true), "empty"=>__("No Parent", true)));
        echo $this->Slug->slug("name", "slug", array("slug_prefix" => "http://montreal-cac.org/groups/"));
		echo $this->Form->input('description');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions grid_6 suffix_6">
	<h3><?php __('Actions'); ?></h3>
	<ul>
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
