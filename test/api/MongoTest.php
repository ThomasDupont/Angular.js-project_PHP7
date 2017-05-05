<?php

/**
 * @see put the folder on bin
 */
use PHPUnit\Framework\TestCase;
use bin\models\mongo\Mongo;
define("ROOTDIR", __DIR__."/");
require_once __DIR__.'/../config.php';


final class MongoTest extends TestCase {
    public function testInsertEveryValue()
    : void
    {
        $mongo = Mongo::getInstance()->setNewBulk();
        //*/
        $iteration = 200000;
        /*/
        $iteration = 0;
        //*/
        for ($i=0; $i < $iteration; $i++) {

            $values = [
                "hello",
                "héllo",
                "h§llo",
                "hèllöôà&"
            ];
            //$mongo = Mongo::getInstance()->setNewBulk();
            foreach($values as $value) {
                $mongo->addToBulk([[
                    'action' => 'insert',
                    'body' => [
                        'test' => utf8_encode($value),

                    ]
                ]]);
            }
            //$mongo->execute('test');
            //$mongo = $mongo->setNewBulk();
            foreach($values as $value) {
                $mongo->addToBulk([[
                    'action' => 'insert',
                    'body' => [
                        'test' => utf8_encode($value),

                    ]
                ]]);
            }
            //$mongo->execute('test');
        }
        $ex = $mongo->execute('test');
        $this->assertFalse(true, $ex['success']);
    }


    public function testDelete()
    : void
    {
        /*Mongo::getInstance()->addToBulk([[
                'action' => 'delete',
                'body' => []
            ]])->execute('test');
        */
    }

    public function testInsertHudgeValue()
    : void
    {

        $text = file_get_contents(__DIR__."/test.txt");
        $mongo = Mongo::getInstance();
        $mongo->addToBulk([[
            'action' => 'insert',
            'body' => [
                'test' => utf8_encode($text),

            ]
        ]]);
        $ex = $mongo->execute('test');
        $this->assertFalse(true, $ex['success']);
    }
}
