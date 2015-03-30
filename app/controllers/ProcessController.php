<?php

class ProcessController extends BaseController {

	public function loadXls() {
		if (Input::hasFile('excelsheet')) {
			$this->processXls(Input::file('excelsheet'));
		}
	}

	public function processXls($getInput) {
		if ($this->validateInput($getInput)) {
			$this->outputPdf($getInput->getRealPath());
		} else {
			exit("Please go back and upload an Excel spreadsheet!<br/>
				Non valid file inputted. Please try again.");
		}
	}

	public function validateInput($xlsInput) {
		$excelMimes = array(
			"application/vnd.ms-excel",
			"application/msexcel",
			"application/x-msexcel",
			"application/x-ms-excel",
			"application/x-excel",
			"application/x-dos_ms_excel",
			"application/xls",
			"application/x-xls",
			"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
			);
		//Check here if it is the correct file and mimetype.
		//If not, get out immediately and show error.
		if (($xlsInput->getClientOriginalExtension() != 'xls') || 
			(!in_array($xlsInput->getMimeType(), $excelMimes))) {
			return false;
		} else {
			return true;
		}
	}

	public function outputPdf($path) {
		$excelData = Excel::load($path, function($reader) { })->all()->toArray();
		$information = '<table class="table"><thead><tr><th>No</th>';
		//Manufacture the PDF.
	    $fpdf = new Fpdf();
	    $fpdf->AddPage('P','A4');
	    //Load the scanned form as a BACKGROUND IMAGE.
	    $fpdf->Image(url().'/KWSP6ContribFormA.jpg', 0, 0, -300);
	    $fpdf->SetFont('Arial', '', 10);
	    $fpdf->setXY(15, 95);
	    $employerContrib = 0;
	    $employeeContrib = 0;
	    //Layer all the data on top, positioning them into the fields..
		foreach ($excelData[0] as $excelRow) {
			$fpdf->setX(15);
			$fpdf->Cell(7, 5.3, $excelRow['bil'], 0, 0, 'L');
			$fpdf->Cell(29, 5.3, $excelRow['no_ahli'], 0, 0, 'L');
			$fpdf->Cell(31, 5.3, $excelRow['kad_pengenalan'], 0, 0, 'L');
			$fpdf->Cell(43, 5.3, $excelRow['nama_pekerja'], 0, 0, 'L');
			$fpdf->Cell(38, 5.3, number_format($excelRow['upah']), 0, 0, 'R');
			$fpdf->Cell(18, 5.3, number_format($excelRow['caruman_majikan']), 0, 0, 'R');
			$fpdf->Cell(18, 5.3, number_format($excelRow['caruman_pekerja']), 0, 1, 'R');
			if ($excelRow['bil'] == 6) {
				$fpdf->setY(128); //Account for the form layout error.
			}
			if ($excelRow['bil'] == 10) {
				$fpdf->setY(150); //Account for the form layout error.
			}
			$employerContrib += $excelRow['caruman_majikan'];
			$employeeContrib += $excelRow['caruman_pekerja'];
		}
	    $fpdf->setXY(167, 211);
	    //Render the grand totals into the "total" fields.
		$fpdf->Cell(18, 5, number_format($employerContrib), 0, 0, 'R');
		$fpdf->Cell(18, 5, number_format($employeeContrib), 0, 1, 'R');
	    $fpdf->Output('KWSP Form 6 Filled.pdf','D'); //Generate PDF and prompt download.
	    return Route::redirect('upload');//Go back to upload screen
	}

}