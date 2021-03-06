<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class CSVTest extends TestCase {
	public function testCanBeCreated(): void {
		$this->assertInstanceOf(
			CSV\CSV::class,
			new CSV\CSV()
		);
	}

	public function testRowCanBeAccessedByIndex(): void {
		$rows = array(
			array("1", "Alice", "23"),
			array("2", "Bob", "31"),
			array("3", "Eve", "17")
		);
		$csv = new CSV\CSV();
		$csv->addRows($rows);

		$this->assertEquals(
			new CSV\Row(array("2", "Bob", "31")),
			$csv[1]
		);
	}

	public function testOffsetGetThrowsInvalidArgumentExceptionIfNotAnInt(): void {
		$rows = array(
			array("1", "Alice", "23"),
			array("2", "Bob", "31"),
			array("3", "Eve", "17")
		);
		$csv = new CSV\CSV();
		$csv->addRows($rows);
		
		$this->expectException(InvalidArgumentException::class);
		$csv["bob"];
	}

	public function testOffsetGetThrowsOutOfRangeException(): void {
		$csv = new CSV\CSV();
		
		$this->expectException(OutOfRangeException::class);
		$csv[-1];
	}

	public function testCSVIsCountable(): void {
		$rows = array(
			array("1", "Alice", "23"),
			array("2", "Bob", "31"),
			array("3", "Eve", "17")
		);
		$csv = new CSV\CSV();
		$csv->addRows($rows);

		$this->assertEquals(
			count($rows),
			count($csv)
		);
	}

	public function testNewCSVHeadersAreEmpty(): void {
		$csv = new CSV\CSV();

		$this->assertEmpty($csv->getHeaders());
	}

	public function testHeadersAreSetCorrectly(): void {
		$headers = array("id", "name", "age");
		$csv = new CSV\CSV();

		$csv->setHeaders($headers);

		$this->assertEquals(
			$headers,
			$csv->getHeaders()
		);
	}

	public function testNewCSVRowsAreEmpty(): void {
		$csv = new CSV\CSV();

		$this->assertEmpty($csv->getRows());
	}

	public function testRowCanBeAdded(): void {
		$rows = array(new CSV\Row(array("1", "Alice", "23")));
		$csv = new CSV\CSV();

		$csv->addRow(array("1", "Alice", "23"));

		$this->assertEquals(
			$rows,
			$csv->getRows()
		);
	}

	public function testRowsCanBeAdded(): void {
		$rows = array(
			array("1", "Alice", "23"),
			array("2", "Bob", "31"),
			array("3", "Eve", "17")
		);
		$expectedRows = array(
			new CSV\Row(array("1", "Alice", "23")),
			new CSV\Row(array("2", "Bob", "31")),
			new CSV\Row(array("3", "Eve", "17"))
		);
		$csv = new CSV\CSV();

		$csv->addRows($rows);

		$this->assertEquals(
			$expectedRows,
			$csv->getRows()
		);
	}

	public function testCanReadCSVFile(): void {
		$expectedHeaders = array("id", "name", "age");
		$expectedRows = array(
			new CSV\Row(array("1", "Alice", "23")),
			new CSV\Row(array("2", "Bob", "31")),
			new CSV\Row(array("3", "Eve", "17"))
		);
		$csv = new CSV\CSV();

		$csv->read("./tests/data/test.csv");

		$this->assertEquals(
			$expectedHeaders,
			$csv->getHeaders()
		);
		$this->assertEquals(
			$expectedRows,
			$csv->getRows()
		);
	}

	public function testReadNoFilenameThrowsException(): void {
		$this->expectException(InvalidArgumentException::class);

		$csv = new CSV\CSV();
		$csv->read("");
	}

	public function testReadExceptionIsThrownIfFileDoesNotExist(): void {
		$this->expectException(Exception::class);

		$csv = new CSV\CSV();
		$csv->read("does-not-exist.csv");
	}

	public function testWriteCSVFile(): void {
		$headers = array("id", "name", "age");
		$rows = array(
			array("1", "Alice", "23"),
			array("2", "Bob", "31"),
			array("3", "Eve", "17")
		);

		$filename = "./tests/data/write.csv";
		$csv = new CSV\CSV();
		$csv->setHeaders($headers);
		$csv->addRows($rows);

		$csv->write($filename);

		$this->assertFileExists($filename);
		$this->assertFileEquals("./tests/data/test.csv", $filename);

		if (file_exists($filename)) {
			unlink($filename);
		}
	}

	public function testWriteNoFilenameThrowsException(): void {
		$this->expectException(InvalidArgumentException::class);

		$csv = new CSV\CSV();
		$csv->write("");
	}
}
