<?php

declare(strict_types=1);

namespace CSV;

/**
 * TODO: write documentation
 */
class Row implements \ArrayAccess, \Countable, \Stringable {
	/**
	 * Items in the row
	 *
	 * @access private
	 */
	private array $items;

	/**
	 * Initializes a new Row
	 *
	 * @param array $items
	 * @exception \InvalidArgumentException if $items is empty
	 */
	public function __construct(array $items) {
		if (empty($items)) {
			throw new \InvalidArgumentException("row must contain at least one item");
		}

		$this->items = $items;
	}

	/**
	 * Implements \Stringable
	 */
	public function __toString(): string {
		return implode(", ", $this->items).PHP_EOL;
	}

	/**
	 * Appends a new item to the row if the given offset is null or
	 * updates an item in the row if the given offset is an int
	 *
	 * @param mixed $offset
	 * @param mixed $value
	 * @exception \InvalidArgumentException if the given offset is not null or an int
	 * @exception \OutOfRangeException
	 */
	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->items[] = $value;
		} elseif (is_int($offset)) {
			if ($offset < 0 || $offset > count($this->items) - 1) {
				throw new \OutOfRangeException("index out of range");
			}
			$this->items[$offset] = $value;
		}

		throw new \InvalidArgumentException("index must be null or an int");
	}

	/**
	 * Returns whether the item at the given offset exists. This should always return
	 * true, if no exception if thrown
	 *
	 * @param int $offset
	 * @exception \InvalidArgumentException if the given offset is not an int
	 * @exception \OutOfRangeException
	 */
	public function offsetExists($offset) {
		$this->throwIfInvalidOffset($offset);

		return isset($this->items[$offset]);
	}

	/**
	 * Removes the item at the given offset
	 *
	 * @param int $offset
	 * @exception \InvalidArgumentException if the given offset is not an int
	 * @exception \OutOfRangeException
	 */
	public function offsetUnset($offset) {
		$this->throwIfInvalidOffset($offset);

		unset($this->items[$offset]);
	}

	/**
	 * Returns the item at the given offset
	 *
	 * @param int $offset
	 * @exception \InvalidArgumentException if the given offset is not an int
	 * @exception \OutOfRangeException
	 */
	public function offsetGet($offset) {
		$this->throwIfInvalidOffset($offset);

		return $this->items[$offset];
	}

	/**
	 * Returns the number of items in the row
	 *
	 * @return int
	 */
	public function count(): int {
		return count($this->items);
	}

	/**
	 * Returns the items in the row
	 *
	 * @return array
	 */
	public function getItems(): array {
		return $this->items;
	}

	private function throwIfInvalidOffset($offset) {
		if (!is_int($offset)) {
			throw new \InvalidArgumentException("index must be an int");
		}
		if ($offset < 0 || $offset > count($this->items) - 1) {
			throw new \OutOfRangeException("index out of range");
		}
	}
}
