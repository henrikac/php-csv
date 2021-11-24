<?php

declare(strict_types=1);

namespace CSV;

class CSV {
	private array $headers;
	private array $rows;

	public function __construct() {
		$this->headers = array();
		$this->rows = array();
	}

	public function getHeaders(): array {
		return $this->headers;
	}

	public function setHeaders(array $headers): void {
		$this->headers = $headers;
	}

	public function getRows(): array {
		return $this->rows;
	}

	public function setRows(array $rows): void {
		$this->rows = $rows;
	}

	public function read(string $filename, string $sep = ","): void {
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
				$row = explode($sep, trim($row));

				array_push($this->rows, $row);
			}
		}

		fclose($file);
	}

	public function write(string $filename, string $sep = ","): void {
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
}
