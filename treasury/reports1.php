<?php
	session_start();
	require_once('../config.php');
	require('../fpdf.php');
	require_once('../function.php');
 	require_once('../function-cashier.php');
	class REPORTS extends FPDF
	{

		function setReportName($rname)
		{
			$this->reportname = $rname;
		}

		function setDate($daterel)
		{
			$this->datereleased = $daterel;
		}

		function setRelNum($rel)
		{
			$this->relnum = $rel;
		}

		function setFooterText($text)
		{
			$this->foot = $text;
		}

		function Footer()
		{
			$this->SetY(-15);
			$this->SetTextColor(74, 74, 74);
			$this->SetFont("Arial", "", 7);
			$this->SetDrawColor(74, 74, 74);
			$this->SetLineWidth(0.2);
			$this->Line(10, 265, 205, 265);
			$this->Cell(0, 10, "Page ".$this->PageNo()." - {nb}", 0, 0, "C");
			$this->Cell(0, 10, $this->foot, 0, 0, "R");
		}

		function docHeader($data)
		{
			// get user department
			$usertype = str_replace('Corporate', '', $_SESSION['gc_title']);
			$this->SetFont("Helvetica", "B", 11);
			$this->Cell(0, 5, 'ALTURAS GROUP OF COMPANIES', 0, 0, "C");			
			$this->Ln();
			$this->SetFont("times", "B",10);
			$this->Cell(0, 5, 'Head Office - '.ucwords($usertype).' Department', 0, 0, "C");
			$this->Ln();
			$this->SetFont("times", "B",10);
			$this->Cell(0, 5, $this->reportname, 0, 0, "C");	
			$this->Ln(4);
		}

		function subheaderpromo($id,$data)
		{
			$this->Ln();
			$this->SetFont("Arial", "B", 9);
			$this->Cell(28,5,'SGCR #.: ',0,0,'R');
			$this->SetFont("Arial", "", 9);
			$this->Cell(86,5,sprintf("%03d",$data->spexgc_num),0,0,'L');
			$this->SetFont("Arial", "B", 9);
			$this->Cell(40,5,'Date Requested:',0,0,'R');
			$this->SetFont("Arial", "", 9);
			$this->Cell(50,5,_dateFormat($data->spexgc_datereq),0,0,'L');
			$this->Ln();
			$this->SetFont("Arial", "B", 9);
			$this->Cell(28,5,'Customer: ',0,0,'R');
			$this->SetFont("Arial", "", 9);
			$this->Cell(86,5,ucwords($data->spcus_companyname),0,0,'L');
			$this->SetFont("Arial", "B", 9);
			$this->Cell(40,5,'Date Released:',0,0,'R');
			$this->SetFont("Arial", "", 9);
			$this->Cell(50,5,_dateFormat($data->reqap_date),0,0,'L');
			$this->Ln(6);
		}

		function detailsspecialexternalgc($link,$data,$gcs)
		{
			// $this->SetFont("Arial", "B", 10);
			// $this->Cell(8,8,'',0,0,'L');
			// $this->Cell(40,8,'Lastname',1,0,'L');
			// $this->Cell(40,8,'Firstname',1,0,'L');
			// $this->Cell(30,8,'Middlename',1,0,'L');
			// $this->Cell(20,8,'Name Ext.',1,0,'L');
			// $this->Cell(30,8,'Denomination',1,0,'L');
			// $this->Cell(20,8,'Barcode',1,0,'L');
			// $this->Ln();
			// $this->SetFont("Arial", "", 10);
			// foreach ($gcs as $gc) 
			// {
			// 	$this->Cell(8,8,'',0,0,'L');
			// 	$this->Cell(40,8,ucwords($gc->spexgcemp_lname),1,0,'L');
			// 	$this->Cell(40,8,ucwords($gc->spexgcemp_fname),1,0,'L');
			// 	$this->Cell(30,8,ucwords($gc->spexgcemp_mname),1,0,'L');
			// 	$this->Cell(20,8,ucwords($gc->spexgcemp_extname),1,0,'L');
			// 	$this->Cell(30,8,number_format($gc->spexgcemp_denom,2),1,0,'L');
			// 	$this->Cell(20,8,$gc->spexgcemp_barcode,1,0,'L');
			// 	$this->Ln();
			// }
			// $this->Ln(3);

			$this->SetFont("Arial", "B", 9);
			$this->Cell(8,5,'',0,0,'L');
			$this->Cell(40,5,'Lastname',1,0,'L');
			$this->Cell(40,5,'Firstname',1,0,'L');
			$this->Cell(30,5,'Middlename',1,0,'L');
			$this->Cell(20,5,'Name Ext.',1,0,'L');
			$this->Cell(30,5,'Denomination',1,0,'L');
			$this->Cell(20,5,'Barcode',1,0,'L');
			$this->Ln();
			$this->SetFont("Arial", "", 9);
			$cnt = 0;
			foreach ($gcs as $gc) 
			{

				$this->Cell(8,5,'',0,0,'L');
				$this->Cell(40,5,ucwords(utf8_decode(html_entity_decode($gc->spexgcemp_lname))),1,0,'L');
				$this->Cell(40,5,ucwords(utf8_decode(html_entity_decode($gc->spexgcemp_fname))),1,0,'L');
				$this->Cell(30,5,ucwords(utf8_decode(html_entity_decode($gc->spexgcemp_mname))),1,0,'L');
				$this->Cell(20,5,ucwords(utf8_decode(html_entity_decode($gc->spexgcemp_extname))),1,0,'L');
				$this->Cell(30,5,number_format($gc->spexgcemp_denom,2),1,0,'L');
				$this->Cell(20,5,$gc->spexgcemp_barcode,1,0,'L');
				$this->Ln();
			}
			$this->Ln(3);
		}

		function detailsspecialexternalgcNonames($link,$data,$gcs)
		{
			$this->SetFont("Arial", "B", 10);
			$this->Cell(6,8,'',0,0,'L');
			$this->Cell(25,8,'Barcode',1,0,'L');
			$this->Cell(35,8,'Denomination',1,0,'L');
			$this->Cell(25,8,'Barcode',1,0,'L');
			$this->Cell(35,8,'Denomination',1,0,'L');
			$this->Cell(25,8,'Barcode',1,0,'L');
			$this->Cell(35,8,'Denomination',1,0,'L');
			$this->Ln();
			$this->SetFont("Arial", "", 10);
			$this->SetFont("Arial", "", 10);
			$td = 0;
			foreach ($gcs as $gc) 
			{
				if($td == 0)
				{
					$this->Cell(6,8,'',0,0,'L');
				}
				if($td <= 3)
				{
					$this->Cell(25,8,$gc->spexgcemp_barcode,1,0,'L');
					$this->Cell(35,8,$gc->spexgcemp_denom,1,0,'L');
				}
				$td++;
				if($td ==3)
				{
					$this->Ln();
					$td = 0;
				}
			}
			$this->Ln(3);
		}

		function subfooterpromo($data,$totcount)
		{
			$this->SetFont("Arial", "", 9);
			$this->Cell(30,	8,'Total No. of GC:',0,0,'R');
			$this->Cell(60,	8,$totcount[1],0,0,'L');
			$this->Ln(5);
			$this->Cell(30,	8,'Total GC Amount:',0,0,'R');
			$this->Cell(60,	8,number_format($totcount[0],2),0,0,'L');
			$this->SetFont("Arial", "", 9);
			$this->Ln(8);
			$this->Cell(10,8,'',0,0,'L');
			$this->Cell(105,8,'Received by:',0,0,'L');
			$this->Cell(80,8,'Released by:',0,0,'L');
			$this->Ln(8);
			$this->SetFont("Arial", "B", 9);
			$this->Cell(10,8,'',0,0,'L');
			$this->Cell(80,	8,ucwords(utf8_decode(html_entity_decode($data->spexgc_receviedby))),0,0,'C');
			$this->Cell(34,8,'',0,0,'C');
			$this->Cell(60,8,ucwords(utf8_decode(html_entity_decode($data->rby))),0,0,'C');
			$this->Ln(4);
			$this->Cell(10,8,'',0,0,'L');
			$this->SetFont("Arial", "", 9);
			$this->Cell(18,	1,'',0,0,'R');
			$this->Cell(50,	1,'______________________________',0,0,'C');
			$this->Cell(36,	1,'',0,0,'C');
			$this->Cell(80,	1,'______________________________',0,0,'C');
			$this->Ln(5);
			$this->SetFont("Arial", "B", 7);
			$this->Cell(10,8,'',0,0,'L');
			$this->Cell(13,	1,'',0,0,'C');
			$this->Cell(60,	1,'(Signature over Printed name)',0,0,'C');
			$this->Cell(41,	1,'',0,0,'C');
			$this->Cell(60,	1,'(Signature over Printed name)',0,0,'C');
			$this->Ln(8);
			$this->SetFont("Arial", "", 9);
			$this->Cell(115,8,'',0,0,'L');
			$this->Cell(80,8,'Checked by:',0,0,'L');
			$this->Ln(8);
			$this->SetFont("Arial", "B", 9);
			$this->Cell(10,8,'',0,0,'L');
			$this->Cell(80,	8,'',0,0,'C');
			$this->Cell(34,8,'',0,0,'C');
			$this->Cell(60,8,ucwords(utf8_decode(html_entity_decode($data->reqap_checkedby))),0,0,'C');
			$this->Ln(4);
			$this->Cell(10,8,'',0,0,'L');
			$this->SetFont("Arial", "", 9);
			$this->Cell(18,	1,'',0,0,'R');
			$this->Cell(50,	1,'',0,0,'C');
			$this->Cell(36,	1,'',0,0,'C');
			$this->Cell(80,	1,'______________________________',0,0,'C');
			$this->Ln(5);
			$this->Cell(10,8,'',0,0,'L');
			$this->SetFont("Arial", "B", 7);
			$this->Cell(13,	1,'',0,0,'C');
			$this->Cell(60,	1,'',0,0,'C');
			$this->Cell(41,	1,'',0,0,'C');
			$this->Cell(60,	1,'(Signature over Printed name)',0,0,'C');
		}
	
	}

