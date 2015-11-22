<?php
namespace Libraries;
use Resources;

class PhpExcel {

    public function __construct(){
        Resources\Import::composer();
        $this->db = new Resources\Database;
    }

	public function ReadXlsSeveral($inputFileName,$args=array()){
		$data = array();
		$default = array(
			"inputFileType" => \PHPExcel_IOFactory::identify($inputFileName),
			"sheetName" => array("Sheet1"),
			"format" => array()
		);
		$args = array_merge($default, $args);

		$objReader = \PHPExcel_IOFactory::createReader($args['inputFileType']);
		$objReader->setLoadSheetsOnly($args['sheetName']);
		$objPHPExcel = $objReader->load($inputFileName);

		$namedDataArray = array();
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$sheetName		  	= $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;

			$headingsArray = $worksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
			$headingsArray = $headingsArray[1];

			$r = -1;
			for ($row = 2; $row <= $highestRow; ++$row) {
				$dataRow = $worksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, false, true);
				if ((isset($dataRow[$row]['A'])) && ($dataRow[$row]['A'] > '')) {
					++$r;
					foreach($headingsArray as $columnKey => $columnHeading) {
						$namedDataArray[$r][$columnHeading] = $dataRow[$row][$columnKey];
						if ((!empty($args['format'])) && (in_array($columnHeading,$args['format'])) )
							$namedDataArray[$r][$columnHeading] = $this->FormatDate($dataRow[$row][$columnKey]);

						if ((!empty($args['substr'])) && (in_array($columnHeading,$args['substr'])) )
							$namedDataArray[$r][$columnHeading] = $this->explode($dataRow[$row][$columnKey], "-");

						if ((!empty($args['separator'])) && (in_array($columnHeading,$args['separator'])) )
							$namedDataArray[$r][$columnHeading] = $this->explode($dataRow[$row][$columnKey], " | ");
					}
				}
			}
		}
		if ($data = $namedDataArray)
			return $data;
		return false;
	}

	public function ReadNilai($inputFileName,$args=array()){
		$data = array();
		$default = array(
			"inputFileType" => \PHPExcel_IOFactory::identify($inputFileName),
			"sheetName" => array("Sheet1"),
			"format" => array()
		);
		$args = array_merge($default, $args);

		$objReader = \PHPExcel_IOFactory::createReader($args['inputFileType']);
		$objReader->setLoadSheetsOnly($args['sheetName']);
		$objPHPExcel = $objReader->load($inputFileName);

		$namedDataArray = array();
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$sheetName		  	= $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;

			$headingsArray = $worksheet->rangeToArray('A4:'.$highestColumn.'4',null, true, true, true);
			$headingsArray = $headingsArray[4];

			$r = -1;
			for ($row = 5; $row <= $highestRow; ++$row) {
				$dataRow = $worksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, false, true);
				if ((isset($dataRow[$row]['A'])) && ($dataRow[$row]['A'] > '')) {
					++$r;
					foreach($headingsArray as $columnKey => $columnHeading) {
						$namedDataArray[$r][$columnHeading] = $dataRow[$row][$columnKey];
					}
				}
			}
		}
		if ($data = $namedDataArray)
			return $data;

		return false;
	}

	private function FormatDate($data){
		$PHPDateTimeObject = \PHPExcel_Shared_Date::ExcelToPHPObject($data);
		return $PHPDateTimeObject->format('Y-m-d');
	}

	private function explode($data, $separator, $index=0){
		$str = explode($separator, $data);
		return $str[$index];
	}

    private function doComparison($a, $operator, $b){
	    switch ($operator) {
	        case '<':  return ($a <  $b); break;
	        case '<=': return ($a <= $b); break;
	        case '=':  return ($a == $b); break; // SQL way
	        case '==': return ($a == $b); break;
	        case '!=': return ($a != $b); break;
	        case '>=': return ($a >= $b); break;
	        case '>':  return ($a >  $b); break;
	    }
	    Resources\Tools::setStatusHeader(400);
	    throw new Exception("The {$operator} operator does not exists", 1);
	}
}
