<?php
    echo $this->Slug->include_script(array("inline"=>"true"));
?>
<div class="groups form span6 suffix_6">
<?php echo $this->Form->create('Group');?>
	<fieldset>
 		<legend><?php echo __('Add Group'); ?></legend>
	<?php
		echo $this->Form->hidden('parent_id');
        $slug_options = array("auto_update" => true,
                              "base_url" => "http://montreal-cac.org/urg_group/groups/");
        if (isset($this->request->data["ParentGroup"])) {
            $slug_options["slug_prefix"] = $this->request->data["ParentGroup"]["slug"] . "-";
        }
        echo $this->Slug->slug("name", "slug", $slug_options); 
		echo $this->Form->input('description', array("type" => "textarea"));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions span6 suffix_6">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
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
