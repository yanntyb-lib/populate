<?php

namespace Yanntyb\Populate\Model\Classes;

use Exception;
use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;

class Populate{
    private static array $instances = [];
    private static string $table_name;
    private static bool $setupFlag = false;
    private static string $dbname = "";
    private static string $user = "";
    private static string $password = "";
    private int $instance_id;
    private static string $lorem = "";

    protected function __construct(string $dbname, string $user, string $password){
        $this->instance_id = count(self::$instances);
        $this->setup($dbname,$user,$password);
        static::$lorem = static::makeRandomString(10000);
    }

    public static function setup(string $dbname, string $user, string $password){
        if(!self::$setupFlag){
            if(!R::testConnection()){
                R::setup("mysql:host=localhost;dbname=$dbname","$user","$password");
            }
            self::$dbname = $dbname;
            self::$user = $user;
            self::$password = $password;
            self::$setupFlag = true;
        }
    }

    /**
     * @throws PopulateNotSetup
     */
    public static function maker(string $tableName): Populate
    {
        if(self::$setupFlag){
            $instance = new Populate(self::$dbname, self::$user, self::$password);
            self::$instances[count(self::$instances)] = $instance;
            return self::$instances[count(self::$instances) - 1]->setTableName($tableName);
        }
        else{
            throw new PopulateNotSetup();
        }
    }


    /**
     * @return string
     */
    public function getTableName(): string
    {
        return self::$table_name;
    }

    /**
     * @param string $table_name
     * @return self
     */
    public function setTableName(string $table_name): Populate
    {
        self::$table_name = $table_name;
        return self::$instances[$this->instance_id];
    }

    /**
     * @throws SQL
     * @throws Exception
     */
    public function populate(int $rowNumber, array $columns){
        $beans = R::dispense($this->getTableName(), $rowNumber);

        foreach($beans as $key => $bean){
            foreach($columns as $col){
                $name = $col["name"];
                switch($col["type"]){
                    case "string":
                        $bean->$name = trim(ucfirst(substr(static::$lorem,rand(0,strlen(static::$lorem)),$col["len"])));
                        break;
                    case "number":
                        $bean->$name = $this->randomNumber($col["min"], $col["max"]);
                        break;
                    case "fk":
                        $bean->$name = $this->randomFk($col["table"]);
                        break;
                }
            }
            R::store($bean);
        }

    }

    public static function makeRandomString(int $len): string
    {
        return substr(simplexml_load_file('http://www.lipsum.com/feed/xml?amount=1&what=paras&start=0')->lipsum->__toString(),0,$len);
    }

    /**
     * @throws Exception
     */
    private function randomNumber(int $min, int $max): int{
        return random_int($min, $max);
    }

    private function randomFk(string $table){
        $col = R::findall($table);
        $key = array_rand($col);
        return $col[$key]->id;
    }
}