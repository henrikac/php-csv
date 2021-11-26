<?php

declare(strict_types=1);

namespace CSV;

/**
 * CSV is a class that makes it easy to read data to and from csv files
 *
 * Example usage:
 * $csv = new CSV\CSV();
 * $csv->read("my-csv-file");
 * $csv->addRow(array("col1", "col2", "col3"));
 * $csv->write("my-csv-file");
 */
class CSV implements \ArrayAccess, \Countable, \IteratorAggregate {
	const DEFAULT_SEPARATOR = ",";

	/**
	 * The CSV headers
	 *
	 * @access private
	 */
	private array $headers;

	/**
	 * The CSV rows
	 *
	 * @access private
	 */
	private array $rows;

	/**
	 * Initializes a new CSV
	 */
	public function __construct() {
		$this->headers = array();
		$this->rows = array();
	}

	/**
	 * Appends a row to the CSV
	 *
	 * Example usage:
	 * $csv = new CSV\CSV();
	 * $csv[] = array("1", "Alice", "84");
	 *
	 * @param null $offset 
	 * @param array $value
	 * @exception \InvalidArgumentException if $offset is not null
	 */
	public function offsetSet($offset, $value) {
		if (!is_null($offset)) {
			throw new \InvalidArgumentException("invalid offset");
		}

		$this->rows[] = $value;
	}

	/**
	 * Returns whether a row at the given offset exists. This should always return
	 * true, if no exception is thrown
	 *
	 * @param int $offset
	 * @return bool
	 * @exception \InvalidArgumentException if $offset is not an int
	 * @exception \OutOfRangeException
	 */
	public function offsetExists($offset) {
		$this->throwIfInvalidOffset($offset);

		return isset($this->rows[$offset]);
	}

	/**
	 * Removes a row at the given offset
	 *
	 * @param int $offset
	 * @exception \InvalidArgumentException if $offset is not an int
	 * @exception \OutOfRangeException
	 */
	public function offsetUnset($offset) {
		$this->throwIfInvalidOffset($offset);

		unset($this->rows[$offset]);
	}

	/**
	 * Returns the row at the given offset
	 *
	 * @param int $offset
	 * @return array
	 * @exception \InvalidArgumentException if $offset is not an int
	 * @exception \OutOfRangeException
	 */
	public function offsetGet($offset) {
		$this->throwIfInvalidOffset($offset);

		return $this->rows[$offset];
	}

	/**
	 * Returns the number of rows in the CSV
	 *
	 * @return int
	 */
	public function count(): int {
		return count($this->rows);
	}

	/**
	 * Implements \IteratorAggregate
	 */
	public function getIterator(): \Traversable {
		return new \ArrayIterator($this->rows);
	}

	/**
	 * Returns CSV headers
	 *
	 * @return array The CSV headers
	 */
	public function getHeaders(): array {
		return $this->headers;
	}

	/**
	 * Sets the CSV headers
	 *
	 * @param array $headers
	 */
	public function setHeaders(array $headers): void {
		$this->headers = $headers;
	}

	/**
	 * Returns the CSV rows
	 *
	 * @return array The CSV rows
	 */
	public function getRows(): array {
		return $this->rows;
	}

	/**
	 * Adds multiple rows to the CSV
	 *
	 * @param array $rows 2d array
	 */
	public function addRows(array $rows): void {
		foreach ($rows as $row) {
			$this->addRow($row);
		}
	}

	/**
	 * Adds a single row to the CSV
	 *
	 * @param array $row
	 */
	public function addRow(array $row): void {
		array_push($this->rows, $row);
	}

	/**
	 * Reads data from csv file
	 *
	 * @param string $filename
	 * @param string $sep
	 */
	public function read(string $filename, string $sep = self::DEFAULT_SEPARATOR): void {
		if (empty($filename)) {
			throw new \InvalidArgumentException("undefined filename");
		}

		if (!file_exists($filename)) {
			throw new \Exception("$filename does not exist");
		}

		$file = fopen($filename, "r");

		if (!feof($file)) {
			$this->headers = explode($sep, trim(fgets($file)));

			while (($row = fgets($file))) {
				$this->addRow(explode($sep, trim($row)));
			}
		}

		fclose($file);
	}

	/**
	 * Writes the CSV headers and rows into a csv file
	 *
	 * @param string $filename
	 * @param string $sep
	 */
	public function write(string $filename, string $sep = self::DEFAULT_SEPARATOR): void {
		if (empty($filename)) {
			throw new \InvalidArgumentException("undefined filename");
		}

		$file = fopen($filename, "w");

		if (count($this->headers) > 0) {
			fwrite($file, implode($sep, $this->headers).PHP_EOL);
		}

		if (count($this->rows) > 0) {
			foreach ($this->rows as $row) {
				fwrite($file, implode($sep, $row).PHP_EOL);
			}
		}

		fclose($file);
	}

	private function throwIfInvalidOffset(mixed $offset): void {
		if (!is_int($offset)) {
			throw new \InvalidArgumentException("offset must be an int");
		}

		if ($offset < 0 || $offset > count($this->rows) - 1) {
			throw new \OutOfRangeException("index out of range");
		}
	}
}
