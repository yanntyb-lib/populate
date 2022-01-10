<?php

require "../vendor/autoload.php";

use Yanntyb\Populate\Model\Classes\Populate;

Populate::help();

Populate::setup("admin","root","");

$maker = Populate::maker("article");

//$maker->populate(10,
//    [
//        [
//            "type" => "string",
//            "name" => "content",
//            "len" => 500
//        ],
//        [
//            "type" => "fk",
//            "name" => "user_fk",
//            "table" => "user"
//        ]
//    ]
//);