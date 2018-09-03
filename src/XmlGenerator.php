<?php

namespace devtoolboxuk\xmlBuilder;

use UnexpectedValueException;
use XMLWriter;

class XmlGenerator extends XMLWriter
{

    protected $version = '1.0';
    protected $encoding = 'UTF-8';
    protected $rootName = 'root';

    public function setVersion($version)
    {
        $this->version = $version;
    }

    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    public function setRootName($rootName)
    {
        $this->rootName = $rootName;
    }

    public function createDoc(array $tags = [])
    {
        $this->openMemory();
        $this->setIndent(true);

        $this->setIndentString('    ');
        $this->startDocument($this->version, $this->encoding);


        $data = "<" . $this->rootName;

        if (isset($tags['@a'])) {
            if (!is_array($tags['@a'])) {
                throw new UnexpectedValueException('Expected array for `@a` key');
            }

            foreach ($tags['@a'] as $name => $value) {
                $data .= ' ' . $name . '="' . $value . '"';
            }
        } else {
            if (isset($tags['@attributes'])) {
                if (!is_array($tags['@attributes'])) {
                    throw new UnexpectedValueException('Expected array for `@attributes` key');
                }

                foreach ($tags['@attributes'] as $name => $value) {
                    $data .= ' ' . $name . '="' . $value . '"';
                }
            }
        }

        $data .= ">\n";
        $this->writeRaw($data);
        return $this->outputMemory();
    }

    public function endDoc()
    {
        $this->openMemory();
        $this->writeRaw('</' . $this->rootName . '>');
        return $this->outputMemory();
    }

    public function create($tag_name, array $tags)
    {
        $this->openMemory();
        $this->setIndent(true);
        $this->setIndentString('    ');
        $this->startElement($tag_name);

        if (isset($tags['@a'])) {
            if (!is_array($tags['@a'])) {
                throw new UnexpectedValueException('Expected array for `@a` key');
            }

            foreach ($tags['@a'] as $name => $value) {
                $this->writeAttribute($name, $value);
            }
        } else {
            if (isset($tags['@attributes'])) {
                if (!is_array($tags['@attributes'])) {
                    throw new UnexpectedValueException('Expected array for `@attributes` key');
                }

                foreach ($tags['@attributes'] as $name => $value) {
                    $this->writeAttribute($name, $value);
                }
            }
        }

        if (isset($tags['@v'])) {
            $this->writeRaw($tags['@v']);
        } else {
            if (isset($tags['@value'])) {
                $this->writeRaw($tags['@value']);
            } else {
                if (isset($tags['@t'])) {
                    if (!is_array($tags['@t'])) {
                        throw new UnexpectedValueException('Expected array for `@t` key');
                    }

                    $this->addTags($tags['@t']);
                } else {
                    if (isset($tags['@tags'])) {
                        if (!is_array($tags['@tags'])) {
                            throw new UnexpectedValueException('Expected array for `@tags` key');
                        }

                        $this->addTags($tags['@tags']);
                    }
                }
            }
        }

        $this->endElement();

        return $this->outputMemory();
    }

    /**
     * Generate child tag xml markup
     *
     * @param array $tags Child tag data
     * @throws \UnexpectedValueException  Invalid array for reserved tag value
     */
    protected function addTags(array $tags)
    {
        foreach ($tags as $name => $value) {
            if (is_array($value)) {
                // Check if this is a sequential array
                if ($value === array_values($value)) {
                    foreach ($value as $tags) {
                        $this->startElement($name);
                        $this->addTags($tags);
                        $this->endElement();
                    }
                } else {
                    if ($name === '@a' || $name === '@attributes') {
                        if (!is_array($tags[$name])) {
                            throw new UnexpectedValueException('Expected array for `' . $name . '` key');
                        }

                        foreach ($value as $attr_name => $attr_value) {
                            $this->writeAttribute($attr_name, $attr_value);
                        }
                    } else {
                        if ($name === '@v' || $name === '@value') {
                            $this->writeRaw($value);
                        } else {
                            $this->startElement($name);
                            $this->addTags($value);
                            $this->endElement();
                        }
                    }
                }
            } else {
                if ($name === '@v' || $name === '@value') {
                    $this->writeRaw($value);
                } else {
                    $this->addTag($name, $value);
                }
            }
        }
    }

    /**
     * Generate a standard xml tag
     *
     * @param string $tag_name Tag name
     * @param mixed $value Tag value
     */
    protected function addTag($tag_name, $value = null)
    {
        $this->startElement($tag_name);

        if ($value !== null) {
            $this->writeRaw($value);
        }

        $this->endElement();
    }

    /**
     * Wrap value in cdata tag
     *
     * @param mixed $value Tag value
     * @return string
     */
    public function cdata($value)
    {
        return '<![CDATA[' . $value . ']]>';
    }

    /**
     * Format decimal number
     *
     * @param string|int|float $value The decimal value
     * @param int $precision Decimal precision
     * @return string  Formatted decimal number
     */
    public function decimal($value, $precision = 2)
    {
        return number_format((float)$value, $precision, '.', '');
    }

}