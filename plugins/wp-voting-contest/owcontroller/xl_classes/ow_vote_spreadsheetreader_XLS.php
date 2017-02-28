<?php
	/****** Ohio web tech **********/
 	class Ow_Vote_SpreadsheetReader_XLS implements Iterator, Countable
	{
		private $Options = array(
		);
		private $Handle = false;
		private $Index = 0;
		private $Error = false;
		private $Sheets = false;
		private $SheetIndexes = array();
		private $CurrentSheet = 0;
		private $CurrentRow = array();
		private $ColumnCount = 0;
		private $RowCount = 0;
		private $EmptyRow = array();

		public function __construct($Filepath, array $Options = null)
		{
			if (!is_readable($Filepath))
			{
				throw new Exception('SpreadsheetReader_XLS: File not readable ('.$Filepath.')');
			}

			if (!class_exists('Ow_Vote_Spreadsheet_Excel_Reader'))
			{
				throw new Exception('SpreadsheetReader_XLS: Spreadsheet_Excel_Reader class not available');
			}

			$this -> Handle = new Ow_Vote_Spreadsheet_Excel_Reader($Filepath, false, 'UTF-8');

			if (function_exists('mb_convert_encoding'))
			{
				$this -> Handle -> ow_vote_setUTFEncoder('mb');
			}

			if (empty($this -> Handle -> sheets))
			{
				$this -> Error = true;
				return null;
			}

			$this -> ChangeSheet(0);
		}

		public function __destruct()
		{
			unset($this -> Handle);
		}

		/**
		 * Retrieves an array with information about sheets in the current file
		 *
		 * @return array List of sheets (key is sheet index, value is name)
		 */
		public function Sheets()
		{
			if ($this -> Sheets === false)
			{
				$this -> Sheets = array();
				$this -> SheetIndexes = array_keys($this -> Handle -> sheets);

				foreach ($this -> SheetIndexes as $SheetIndex)
				{
					$this -> Sheets[] = $this -> Handle -> boundsheets[$SheetIndex]['name'];
				}
			}
			return $this -> Sheets;
		}

		/**
		 * Changes the current sheet in the file to another
		 *
		 * @param int Sheet index
		 *
		 * @return bool True if sheet was successfully changed, false otherwise.
		 */
		public function ChangeSheet($Index)
		{
			$Index = (int)$Index;
			$Sheets = $this -> Sheets();

			if (isset($this -> Sheets[$Index]))
			{
				$this -> rewind();
				$this -> CurrentSheet = $this -> SheetIndexes[$Index];

				$this -> ColumnCount = $this -> Handle -> sheets[$this -> CurrentSheet]['numCols'];
				$this -> RowCount = $this -> Handle -> sheets[$this -> CurrentSheet]['numRows'];

				// For the case when Spreadsheet_Excel_Reader doesn't have the row count set correctly.
				if (!$this -> RowCount && count($this -> Handle -> sheets[$this -> CurrentSheet]['cells']))
				{
					end($this -> Handle -> sheets[$this -> CurrentSheet]['cells']);
					$this -> RowCount = (int)key($this -> Handle -> sheets[$this -> CurrentSheet]['cells']);
				}

				if ($this -> ColumnCount)
				{
					$this -> EmptyRow = array_fill(1, $this -> ColumnCount, '');
				}
				else
				{
					$this -> EmptyRow = array();
				}
			}

			return false;
		}

		public function __get($Name)
		{
			switch ($Name)
			{
				case 'Error':
					return $this -> Error;
					break;
			}
			return null;
		}

		// !Iterator interface methods
		/** 
		 * Rewind the Iterator to the first element.
		 * Similar to the reset() function for arrays in PHP
		 */ 
		public function rewind()
		{
			$this -> Index = 0;
		}

		/**
		 * Return the current element.
		 * Similar to the current() function for arrays in PHP
		 *
		 * @return mixed current element from the collection
		 */
		public function current()
		{
			if ($this -> Index == 0)
			{
				$this -> next();
			}

			return $this -> CurrentRow;
		}

		/** 
		 * Move forward to next element. 
		 * Similar to the next() function for arrays in PHP 
		 */ 
		public function next()
		{
			// Internal counter is advanced here instead of the if statement
			//	because apparently it's fully possible that an empty row will not be
			//	present at all
			$this -> Index++;

			if ($this -> Error)
			{
				return array();
			}
			elseif (isset($this -> Handle -> sheets[$this -> CurrentSheet]['cells'][$this -> Index]))
			{
				$this -> CurrentRow = $this -> Handle -> sheets[$this -> CurrentSheet]['cells'][$this -> Index];
				if (!$this -> CurrentRow)
				{
					return array();
				}

				$this -> CurrentRow = $this -> CurrentRow + $this -> EmptyRow;
				ksort($this -> CurrentRow);

				$this -> CurrentRow = array_values($this -> CurrentRow);
				return $this -> CurrentRow;
			}
			else
			{
				$this -> CurrentRow = $this -> EmptyRow;
				return $this -> CurrentRow;
			}
		}

		/** 
		 * Return the identifying key of the current element.
		 * Similar to the key() function for arrays in PHP
		 *
		 * @return mixed either an integer or a string
		 */ 
		public function key()
		{
			return $this -> Index;
		}

		/** 
		 * Check if there is a current element after calls to rewind() or next().
		 * Used to check if we've iterated to the end of the collection
		 *
		 * @return boolean FALSE if there's nothing more to iterate over
		 */ 
		public function valid()
		{
			if ($this -> Error)
			{
				return false;
			}
			return ($this -> Index <= $this -> RowCount);
		}

		// !Countable interface method
		/**
		 * Ostensibly should return the count of the contained items but this just returns the number
		 * of rows read so far. It's not really correct but at least coherent.
		 */
		public function count()
		{
			if ($this -> Error)
			{
				return 0;
			}

			return $this -> RowCount;
		}
	}
?>