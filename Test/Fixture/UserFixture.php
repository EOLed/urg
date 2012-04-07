<?php
App::uses("AuthComponent", "Controller/Component");
class UserFixture extends CakeTestFixture {
    public $useDbConfig = "test";

    public $fields array("id" => array("type" => "bigint", "key" => "primary"),
                         "username" => array("type" => "string", "length" => 30, "null" => false),
                         "password" => array("type" => "string", "length" => 255, "null" => false));

    public $records = array(
            array("id" => 1, "admin", AuthComponent::password("admin")),
            array("id" => 2, "chineseadmin", AuthComponent::password("chineseadmin")));
}
