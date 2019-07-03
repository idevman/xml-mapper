<?php

namespace Tests\Unit;

use Idevman\XmlMapper\Support\Facades\XmlMapper;

/**
 * Xml mapper testing data
 */
class XmlMapperTest extends TestCase {

    /**
     * XML data
     * @var string
     */
    private $xml;

    /**
     * Initialize test
     */
    public function setUp(): void {
        parent::setUp();
        $this->xml = '<note from="Tove" to="Jani">' .
        '<content heading="Reminder" body="Don\'t forget me this weekend!"/>' .
        '<raw>Simple text</raw>' .
        '<item number="1" value="50">Table</item>' .
        '<item number="2" value="40">Chair</item>' .
        '<item number="3" value="30">Door</item>' .
        '<item number="4" value="60">Window</item>' .
        '</note>';
    }

    /**
     * Should map attribute
     */
    public function testShouldMapAttribute() {
        $response = XmlMapper::mapTo([
            'from' => '/note@from',
            'to' => '/note@to',
            'heading' => '/note/content@heading',
            'body' => '/note/content@body',
            'raw' => '/note/raw',
            'simpleItem' => '/note/item',
            'simpleItemNumber' => '/note/item@number',
            'allItems' => '/note/item[]',
            'allItemsNumber' => '/note/item[]@number',
        ], $this->xml);
        $this->assertNotNull($response);
        $this->assertArrayHasKey('from', $response);
        $this->assertArrayHasKey('to', $response);
        $this->assertArrayHasKey('heading', $response);
        $this->assertArrayHasKey('body', $response);
        $this->assertArrayHasKey('raw', $response);
        $this->assertArrayHasKey('simpleItem', $response);
        $this->assertArrayHasKey('simpleItemNumber', $response);
        $this->assertArrayHasKey('allItems', $response);
        $this->assertArrayHasKey('allItemsNumber', $response);

        $this->assertEquals('Tove', $response['from']);
        $this->assertEquals('Jani', $response['to']);
        $this->assertEquals('Reminder', $response['heading']);
        $this->assertEquals('Don\'t forget me this weekend!',
                            $response['body']);
        $this->assertEquals('Simple text', $response['raw']);

        $this->assertEquals('Table', $response['simpleItem']);
        $this->assertEquals('1', $response['simpleItemNumber']);

        $this->assertCount(4, $response['allItems']);
        $this->assertEquals('Table', $response['allItems'][0]);
        $this->assertEquals('Chair', $response['allItems'][1]);
        $this->assertEquals('Door', $response['allItems'][2]);
        $this->assertEquals('Window', $response['allItems'][3]);

        $this->assertCount(4, $response['allItemsNumber']);
        $this->assertEquals('1', $response['allItemsNumber'][0]);
        $this->assertEquals('2', $response['allItemsNumber'][1]);
        $this->assertEquals('3', $response['allItemsNumber'][2]);
        $this->assertEquals('4', $response['allItemsNumber'][3]);
    }

    /**
     * Should map node attributes
     */
    public function testShouldMapNodeAttribute() {
        $response = XmlMapper::mapTo([
            'addressing[from,to]' => '/note[@from,@to]',
            'content[header,body]' => '/note/content[@heading,@body]',
            'items[sequence,value]' => '/note/item[][@number,@value]',
        ], $this->xml);
        $this->assertNotNull($response);
        $this->assertCount(3, $response);
        $this->assertArrayHasKey('addressing', $response);
        $this->assertArrayHasKey('content', $response);
        $this->assertArrayHasKey('items', $response);

        $this->assertCount(2, $response['addressing']);
        $this->assertArrayHasKey('from', $response['addressing']);
        $this->assertArrayHasKey('to', $response['addressing']);

        $this->assertEquals('Tove', $response['addressing']['from']);
        $this->assertEquals('Jani', $response['addressing']['to']);

        $this->assertCount(2, $response['content']);
        $this->assertArrayHasKey('header', $response['content']);
        $this->assertArrayHasKey('body', $response['content']);

        $this->assertEquals('Reminder', $response['content']['header']);
        $this->assertEquals('Don\'t forget me this weekend!',
            $response['content']['body']);

        $this->assertCount(4, $response['items']);
        
        $this->assertCount(2, $response['items'][0]);
        $this->assertArrayHasKey('sequence', $response['items'][0]);
        $this->assertArrayHasKey('value', $response['items'][0]);
        $this->assertEquals('1', $response['items'][0]['sequence']);
        $this->assertEquals('50', $response['items'][0]['value']);

        $this->assertCount(2, $response['items'][1]);
        $this->assertArrayHasKey('sequence', $response['items'][1]);
        $this->assertArrayHasKey('value', $response['items'][1]);
        $this->assertEquals('2', $response['items'][1]['sequence']);
        $this->assertEquals('40', $response['items'][1]['value']);

        $this->assertCount(2, $response['items'][2]);
        $this->assertArrayHasKey('sequence', $response['items'][2]);
        $this->assertArrayHasKey('value', $response['items'][2]);
        $this->assertEquals('3', $response['items'][2]['sequence']);
        $this->assertEquals('30', $response['items'][2]['value']);

        $this->assertCount(2, $response['items'][3]);
        $this->assertArrayHasKey('sequence', $response['items'][3]);
        $this->assertArrayHasKey('value', $response['items'][3]);
        $this->assertEquals('4', $response['items'][3]['sequence']);
        $this->assertEquals('60', $response['items'][3]['value']);
    }

    /**
     * Should map attribute
     */
    public function testShouldGetNulls() {
        $response = XmlMapper::mapTo([
            'from' => '/note@from',
            'to' => '/note@to',
            'tom' => '/note@tom',
            'simpleItem2' => '/note/item2',
        ], $this->xml);


        $this->assertNotNull($response);
        $this->assertArrayHasKey('from', $response);
        $this->assertArrayHasKey('to', $response);
        $this->assertArrayHasKey('tom', $response);
        $this->assertArrayHasKey('simpleItem2', $response);

        $this->assertEquals('Tove', $response['from']);
        $this->assertEquals('Jani', $response['to']);
        $this->assertNull($response['tom']);
        $this->assertNull($response['simpleItem2']);
    }

}