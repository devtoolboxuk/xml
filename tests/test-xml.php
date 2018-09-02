<?php

namespace devtoolboxuk\xml;

use PHPUnit\Framework\TestCase;

class Xml extends TestCase
{

    protected $writeXMLService;
    protected $readXMLService;
    protected $xmlService;

    function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->xmlService = new xmlService();
    }

    public function testWriteXML()
    {
        $this->writeXMLService = $this->xmlService->writeXMLService();
        $this->writeXMLService->setRootName('products');

        $xml_data = $this->writeXMLService->createDoc([
            '@a' => [
                'product_code' => 'chest',
                'moo' => '1',
            ]
        ]);

        $xml_data .= $this->writeXMLService->create('product', [
            '@t' => [
                'sku' => 12345,
                'text' => 'I am text',
            ]
        ]);
        $xml_data .= $this->writeXMLService->endDoc();


        $this->readXMLService = $this->xmlService->readXMLService();
        $data = $this->readXMLService->convertXML($xml_data);

        foreach ($data as $data) {

            if ($data['product']['sku'] == '12345') {
                $this->assertSame('I am text', $data['product']['text']);
            }
        }

    }

    public function testReadXML()
    {
        $xml_string = '<?xml version="1.0" encoding="UTF-8"?>
                <products>
                    <product product_id="12345">
                        <sku>12345</sku>
                        <text>I am text</text>
                    </product>
                    <product>
                        <sku>12346</sku>
                        <text>I am another</text>
                    </product>                
                </products>';

        $this->readXMLService = $this->xmlService->readXMLService();
        $data = $this->readXMLService->convertXML($xml_string);

        foreach ($data as $data) {

            if ($data['product']['sku'] == '12345') {
                $this->assertSame('I am text', $data['product']['text']);
            }

            if (isset($data['product']['@attributes']['product_id'])) {
                $this->assertSame($data['product']['sku'], $data['product']['@attributes']['product_id']);
            }

            if ($data['product']['sku'] == '12346') {
                $this->assertSame('I am another', $data['product']['text']);
            }
        }
    }

}
