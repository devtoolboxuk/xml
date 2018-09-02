# XML
Basic write XML Service from an Array, and a read XML service to an Array

## Table of Contents

- [Background](#Background)
- [Usage](#Usage)
- [Maintainers](#Maintainers)
- [License](#License)

## Background

Used to create and read XML files

## Usage

Usage of the XML service

```sh
$ composer require devtoolboxuk/xml
```

Then include Composer's generated vendor/autoload.php to enable autoloading:

```sh
require 'vendor/autoload.php';
```

```sh
use devtoolboxuk/xml;

$this->xmlService = new xmlService();
```

### Write XML Service
##### Create the Writing XML service
```sh
$this->writeXMLService = $this->xmlService->writeXMLService();
```

##### Setting the Writing XML root name
By default the root name is `root`
```sh
$this->writeXMLService->setRootName('products');
```

##### Example of Writing an XML
Plain XML
```sh
echo $this->writeXMLService->createDoc();
echo $this->writeXMLService->endDoc();
```

```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
</root>
```

XML with attributes
```sh
echo $this->writeXMLService->createDoc();
echo $this->writeXMLService->endDoc([
    '@a' => [
        'xml_version' => '1.0',
    ]
]);
```

```xml
<?xml version="1.0" encoding="UTF-8"?>
<root xml_version="1.0">
</root>
```


XML with data
```sh
echo $this->writeXMLService->createDoc();

echo $this->writeXMLService->create('product',[
    '@t'=>[
        'sku'=>12345,
        'text'=>'I am text',
    ]
]);

echo $this->writeXMLService->endDoc([
    '@a' => [
        'xml_version' => '1.0',
    ]
]);
```

```xml
<?xml version="1.0" encoding="UTF-8"?>
<root xml_version="1.0">
<product>
    <sku>12345</sku>
    <text>I am text</text>
</product>
</root>
```


## Maintainers

[@DevToolboxUk](https://github.com/DevToolBoxUk).


## License

[MIT](LICENSE) © DevToolboxUK