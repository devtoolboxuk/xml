<?php

namespace devtoolboxuk\XmlBundle;

class XmlBuilder implements xmlInterface
{

    protected $write;
    protected $read;

    public function __construct()
    {
        $this->write = new XmlGenerator();
        $this->read = new XmlReader();
    }

    public function writeXMLService()
    {
        return $this->write;
    }

    public function readXMLService()
    {
        return $this->read;
    }

    public function formatXML($data)
    {
        return preg_replace('/(\>)\s*(\<)/m', '$1$2', $data);
    }

}