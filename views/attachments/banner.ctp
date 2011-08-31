<div class="attachments form">
    <div class="grid_6 right-border">
        <?php echo $this->Form->create('Attachment', array("type" => "file")); ?>
        <fieldset>
            <legend> <div> <h2><?php __('Add Banner'); ?></h2> </div> </legend>
            <?php
            echo $this->Form->hidden("Attachment.group_slug", array("value" => $group_slug));
            echo $this->Form->hidden("AttachmentMetadatum.0.key", array("value" => "group_id"));
            echo $this->Form->hidden("AttachmentMetadatum.0.value", array("value" => $group["Group"]["id"]));
            echo $this->Form->file("Attachment.banner");
            ?>
        </fieldset>
        <?php echo $this->Form->end("Upload"); ?>
    </div>
</div>
