<?php
    echo $this->Slug->include_script(array("inline"=>"true"));
?>
<div class="container">
    <div class="row">
        <div class="groups form span6">
        <?php echo $this->Form->create('Group');?>
            <fieldset>
                <legend><?php __('Add Group'); ?></legend>
            <?php
                echo $this->Form->hidden('parent_id');
                $slug_options = array("auto_update" => true,
                                      "base_url" => "http://montreal-cac.org/urg_group/groups/");
                if (isset($this->data["ParentGroup"])) {
                    $slug_options["slug_prefix"] = $this->data["ParentGroup"]["slug"] . "-";
                }
                echo $this->Slug->slug("name", "slug", $slug_options); 
                echo $this->Form->input('description', array("type" => "textarea"));
            ?>
            </fieldset>
        <?php echo $this->Form->end(__('Submit', true));?>
        </div>
        <div class="actions span6">
            <h3><?php __('Actions'); ?></h3>
            <ul>
                <li><?php echo $this->Html->link(__('List Groups', true), array('controller' => 'groups', 'action' => 'index')); ?> </li>
                <li><?php echo $this->Html->link(__('List Roles', true), array('controller' => 'roles', 'action' => 'index')); ?> </li>
                <li><?php echo $this->Html->link(__('New Role', true), array('controller' => 'roles', 'action' => 'add')); ?> </li>
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#GroupName").focus();
</script>
<?php 
$this->Html->css("/urg/css/urg", null, array("inline"=>false));
