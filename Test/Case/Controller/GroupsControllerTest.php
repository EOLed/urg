<?php
class GroupsControllerTest extends ControllerTestCase {
    public $fixtures = array("plugin.urg.group", 
                             "plugin.urg.secured_action",
                             "plugin.urg.role"
                            );
    public $Group;

    function testIndex() {
        $result = $this->testAction("/urg/groups/index");
        debug($result);
    }
}
