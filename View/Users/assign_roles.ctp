<div class="row">
    <div class="span6">
        <?php echo $this->Form->create("Users", array("action" => "assign_roles", "class" => "form-horizontal")); ?>
        <fieldset>
            <legend><?php echo __("Assign Roles"); ?></legend>
            <?php 
            echo $this->Form->hidden("User.id"); 
            echo $this->TwitterBootstrap->input("User.username", array("readonly" => "readonly"));
            echo $this->TwitterBootstrap->input("Role", array("type" => "select", "multiple" => "checkbox"));
            ?>
        </fieldset>
        <div class="row form-actions">
            <div class="span12">
                <?php
                    echo $this->Form->button(__("Save", true), array("class" => "btn btn-primary")) . " ";
                    echo $this->Form->button(__("Reset", true), array("type" => "reset", "class" => "btn"));
                ?>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
