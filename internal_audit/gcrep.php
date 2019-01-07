<?php
	session_start();
	require_once('../config.php');
	require('../fpdf.php');
	require_once('../function.php');
	if(isset($_GET['id']))
	{
		$id = $_GET['id'];
		if(trim($id)===''|| !is_numeric($id)||!checkIfReceived($link,$id))
		{
			exit();
		}
	}
	else 
	{
		exit();
	}

	class GCREP extends FPDF
	{

		function setDate($date)
		{
			$this->date = $date;
		}

		function setRecNo($rec)
		{
			$this->rec = $rec;
		}

		function docheader()
		{
			$this->SetFont("Helvetica", "B", 12);
			$this->SetTextColor(28, 28, 28);
			$this->Cell(0, 8, 'ALTURAS GROUP OF COMPANIES', 0, 0, "C");			
			$this->Ln(6);
			$this->SetFont("times", "B",11);
			$this->SetTextColor(28, 28, 28);
			$this->Cell(0, 8, 'Head Office - Internal Audit Department', 0, 0, "C");
			$this->Ln(1);
			$this->SetFont("times", "B", 11);
			$this->SetTextColor(28, 28, 28);
			$this->Ln();
			$this->Cell(0, 1, 'GC Audit Report', 0, 0, "C");	
			$this->Ln(10);
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
			$this->Cell(0, 10, 'Rec # '.sprintf("%03d",$this->rec).' '._dateFormat($this->date->csrr_datetime).' '._timeFormat($this->date->csrr_datetime), 0, 0, "R");
		}

		function subheader($header_data,$id)
		{
			$this->Ln();
			$this->SetFont("Arial", "B", 10);
			//$this->Cell(28,6,'Location: ',0,0,'R');
			$this->Cell(28,6,'GC Rec. No.: ',0,0,'R');
			$this->SetFont("Arial", "", 10);
			$this->Cell(86,6,sprintf("%03d",$id),0,0,'L');
			//$this->Cell(86,6,$header_data->requis_loc,0,0,'L');
			$this->SetFont("Arial", "B", 10);
			//$this->Cell(30,6,'Supplier: ',0,0,'R');
			$this->Cell(30,6,'Date Received:',0,0,'R');
			$this->SetFont("Arial", "", 10);
			$this->Cell(50,6,_dateFormat($header_data->csrr_datetime),0,0,'L');
			//$this->Cell(50,6,ucwords($header_data->gcs_companyname),0,0,'L');
			$this->Ln();
			$this->SetFont("Arial", "B", 10);
			//$this->Cell(28,6,'GC Rec. No.: ',0,0,'R');
			$this->Cell(28,6,'Supplier: ',0,0,'R');
			//$this->Cell(28,6,'Location: ',0,0,'R');
			$this->SetFont("Arial", "", 10);
			//$this->Cell(86,6,sprintf("%03d",$id),0,0,'L');
			//$this->Cell(86,6,$header_data->requis_loc,1,0,'L');
			//$this->Cell(86,6,ucwords($header_data->gcs_companyname),0,0,'L');
			$x = $this->GetX();
			$y = $this->GetY();
			$this->MultiCell(86,6,ucwords($header_data->gcs_companyname),0,'L',false);
			$this->SetFont("Arial", "B", 10);
			//$this->Cell(30,6,'Date Received:',0,0,'R');
			$this->setXY($x,$y);
			$this->Cell(117,6,'Location: ',0,0,'R');
			$this->SetFont("Arial", "", 10);
			//$this->Cell(50,6,_dateFormat($header_data->csrr_datetime),0,0,'L');
			$this->Cell(86,6,$header_data->requis_loc,0,0,'L');
			$this->Ln(12);
		}

		function details($group,$id,$link)
		{
			$this->SetFont("Arial", "", 10);
					
			foreach ($group as $d) 
			{
				$pcs = 0;
				$x=1;	
				$this->Cell(190,8,'Denomination: '.number_format($d->denomination,2),1,0,'L');
				$this->Ln(8);
				$gcs = getDenomCustodianReportById($link,$id,$d->denom_id);	
					
				foreach ($gcs as $g) 
				{
					$pcs++;
					if($x<=5)
					{
						$this->Cell(40,	8,$g->cssitem_barcode,0,0,'L');
						$x++;
					}
					else 
					{	
						$x=1;										
						$this->Ln(5);
						$this->Cell(40,	8,$g->cssitem_barcode,0,0,'L');	
						$x++;											
					}
				}
				$this->Ln(8);
				$this->Cell(140,6,'No of GC:','TB',0,true);
				$this->Cell(50,6,$pcs.' pcs	','TB',0);
				$this->Ln(9);
			}
		}

		function receivingDetails($group,$id,$link)
		{
			$this->SetFont("Arial", "B", 10);
			$header = array('Denomination','Barcode Start','Barcode End','Pcs');
			$this->Cell(17,	8,'',0,0,'C');
			foreach ($header as $h) 
			{
				$this->Cell(40,	8,$h,1,0,'C');
			}

			foreach ($group as $g) 
			{
				$start = getBarcodeStartGCReceiving($link,$g->denom_id,$id,'ASC');
				$end = getBarcodeEndGCReceiving($link,$g->denom_id,$id,'DESC');
				$this->Ln();
				$this->Cell(17,	8,'',0,0,'C');
				$this->Cell(40,	8,number_format($g->denomination,2),1,0,'C');
				$this->Cell(40,	8,$start->cssitem_barcode,1,0,'C');
				$this->Cell(40,	8,$end->cssitem_barcode,1,0,'C');
				$this->Cell(40,	8,$start->cnt,1,0,'C');
			}
			
			$this->Ln(12);
		}

		function subfooter($details,$d)
		{
			$this->SetFont("Arial", "", 10);
			$this->Cell(51,	8,'FAD Recieving Type:',0,0,'R');
			$this->Cell(60,	8,ucwords($details->csrr_receivetype),0,0,'L');
			$this->Ln(5);
			$this->Cell(51,	8,'Recieving Type:',0,0,'R');
			$this->Cell(60,	8,ucwords($details->csrr_receivedas),0,0,'L');
			$this->Ln(5);
			$this->Cell(51,	8,'Total No. of GC:',0,0,'R');
			if($d->cnt > 1)
			{
				$p = 'pcs';
			}
			else 
			{
				$p = 'pc';
			}
			$this->Cell(65,	8,number_format($d->cnt).' '.$p,0,0,'L');
			$this->Ln(5);
			$this->Cell(51,	8,'Total GC Amount:',0,0,'R');
			$this->Cell(60,	8,number_format($d->total,2),0,0,'L');
			$this->Ln(16);
			$this->Cell(10,	8,'',0,0,'L');
			$this->Cell(90,	8,'Prepared by:',0,0,'L');
			$this->Cell(80,	8,'Checked by:',0,0,'L');
			$this->Ln(8);
			$this->SetFont("Arial", "B", 10);
			$this->Cell(90,	8,ucwords($details->firstname.' '.$details->lastname),0,0,'C');
			$this->Ln(5);
			$this->SetFont("Arial", "", 10);
			$this->Cell(90,	1,'______________________________',0,0,'C');
			$this->Cell(90,	1,'______________________________',0,0,'C');
			$this->Ln(5);
			$this->SetFont("Arial", "B", 8);
			$this->Cell(90,	1,'(Signature over Printed name)',0,0,'C');
			$this->Cell(90,	1,'(Signature over Printed name)',0,0,'C');
		}
	}

	$pdf = new GCREP();
	$pdf->AliasNbPages();
	$pdf->AddPage("P","Letter");

	$d = getReceivedDetailsTotalAndCount($link,$id);
	$header_data = reportHeaderCustodianSRR($link,$id);	
	$group = groupDenomCustodianReport($link,$id);
	$pdf->setDate($header_data);
	$pdf->setRecNo($id);
	$pdf->docheader();
	$pdf->subheader($header_data,$id);
	//$pdf->details($group,$id,$link);
	$pdf->receivingDetails($group,$id,$link);
	$pdf->subfooter($header_data,$d);
	//$pdf->Output();
	$pdf->Output('../reports/custodian_receiving/csr'.$id.'.pdf','F');
?>	

<script>
	window.location = "<?php echo 'index.php?reqreport='.$id; ?>";
</script>
