<?php

/**
 * @see put the folder on bin
 */
use PHPUnit\Framework\TestCase;
use bin\models\mysql\Mysql;
define("ROOTDIR", __DIR__."/");
require_once __DIR__.'/../config.php';


final class MysqlTest extends TestCase {
    public function testInsertEveryValue()
    : void
    {
        $mysql = Mysql::getInstance();
        //*/
        $iteration = 200000;
        /*/
        $iteration = 0;
        //*/
        $mysql->setUser(true);
        for ($i=0; $i < $iteration; $i++) {

            $values = [
                "hello",
                "héllo",
                "h§llo",
                "hèllöôà&"
            ];
            //$mongo = Mongo::getInstance()->setNewBulk();
            foreach($values as $value) {
                $this->assertFalse(true, $mysql->setDBDatas(
                    'test',
                    '(value, date) VALUES (?,NOW())',
                    [$value]
                ));

            }
            //$mongo->execute('test');
            //$mongo = $mongo->setNewBulk();
            foreach($values as $value) {
                $this->assertFalse(true, $mysql->setDBDatas(
                    'test',
                    '(value, date) VALUES (?,NOW())',
                    [$value]
                ));
            }
            //$mongo->execute('test');
        }
    }


    public function testDelete()
    : void
    {

    }

}
