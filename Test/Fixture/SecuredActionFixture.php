<?php
class SecuredActionFixture extends CakeTestFixture {
    public $useDbConfig = "test";

    public $fields = array("id" => array("type"=>"integer", "key" => "primary"),
                           "role_id" => array("type" => "integer", "null" => false),
                           "controller" => array("type" => "string", "null" => false),
                           "action" => array("type" => "string", "null" => false));

    public $records = array();
}
