# php-csv

Package that makes it easy to read and write data to csv files.

## Usage

#### Basic usage

```php
<?php

$data = array(
	array("1", "Alice", "23"),
	array("2", "Bob", "42"),
	array("3", "Eve", "8")
);

$csv = new CSV\CSV();

$csv->setHeaders(array("id", "name", "age"));
$csv->addRows($data);

foreach ($csv as $row) {
	print_r($row);
}

$csv[1]; // => array("2", "Bob", "42")
count($csv); // => 3

$csv[] = array("4", "John", "89");
count($csv); // => 4

$csv->write("my-csv-file");
```

## Contributing

1. Fork it (<https://github.com/henrikac/php-csv/fork>)
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create a new Pull Request

## Contributors

- [Henrik Christensen](https://github.com/henrikac) - creator and maintainer
