<?php

namespace Yanntyb\Populate\Model\Classes;

use Exception;

class PopulateNotSetup extends Exception
{
    public function __construct(){
        echo "Need Populate::setup()";
    }

}