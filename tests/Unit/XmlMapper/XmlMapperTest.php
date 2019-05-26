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
        '<item number="1">Table</item>' .
        '<item number="2">Chair</item>' .
        '<item number="3">Door</item>' .
        '<item number="4">Window</item>' .
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

}