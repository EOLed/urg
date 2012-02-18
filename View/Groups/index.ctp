<div class="container">
    <div class="row">
        <div class="span8 groups index">
            <h2><?php echo __('Groups');?></h2>
            <ul id="group-list">
            <?php
            $i = 0;
            foreach ($groups as $group):
                $class = null;
                if ($i++ % 2 == 0) {
                    $class = ' class="altrow"';
                } 

                echo $this->Grp->li($group);
                
            endforeach; ?>
            </ul>
        </div>
        <div class="span4 actions">
            <h2><?php echo __('Actions'); ?></h2>
            <ul>
                <li><?php echo $this->Html->link(__('New Group'), array('action' => 'add')); ?></li>
                <li><?php echo $this->Html->link(__('List Roles'), array('controller' => 'roles', 'action' => 'index')); ?> </li>
                <li><?php echo $this->Html->link(__('New Role'), array('controller' => 'roles', 'action' => 'add')); ?> </li>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("#group-list li").hover(function() { $(this).children("span").show(); }, function() { $(this).children("span").hide(); });
</script>
<?php echo $this->Html->css("/urg/css/urg.css", null, array("inline"=>false)); ?>
