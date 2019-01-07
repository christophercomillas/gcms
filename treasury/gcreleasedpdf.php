<?php
	session_start();
	require_once('../config.php');
	require('../fpdf.php');
	require_once('../function.php');
	if(isset($_GET['id']))
	{
		$id = trim($_GET['id']);
		if(trim($id)===''|| !is_numeric($id)||!checkIfExist($link,'rel_num','gc_release','rel_num',$id))
		{
			exit();
		}
	}
	else 
	{
		exit();
	}

	class GCReleasedReport extends FPDF
	{

		function setDate($daterel)
		{
			$this->datereleased = $daterel;
		}

		function setRelNum($rel)
		{
			$this->relnum = $rel;
		}

		function docHeader()
		{
			$this->SetFont("Helvetica", "B", 12);
			$this->SetTextColor(28, 28, 28);
			$this->Cell(0, 8, 'ALTURAS GROUP OF COMPANIES', 0, 0, "C");
			$this->Ln(6);
			$this->Cell(0, 8, 'Head Office - Treasury Department', 0, 0, "C");
			$this->Ln(1);
			$this->SetFont("times", "B",11);
			$this->SetTextColor(28, 28, 28);
			$this->Ln();
			$this->Cell(0, 1, 'GC Releasing Report', 0, 0, "C");	
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
			$this->Cell(0, 10, 'Rel # '.sprintf("%03d",$this->relnum).' '._dateFormat($this->datereleased->agcr_approved_at).' '._timeFormat($this->datereleased->agcr_approved_at), 0, 0, "R");
		}

		function subheader($header_data,$location,$id)
		{
			$this->Ln();
			$this->SetFont("Arial", "B", 10);
			$this->Cell(28,5,'Location: ',0,0,'R');
			$this->SetFont("Arial", "", 10);
			$this->Cell(86,5,$location,0,0,'L');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(40,5,'Store: ',0,0,'R');
			$this->SetFont("Arial", "", 10);
			$this->Cell(50,5,ucwords($header_data->store_name),0,0,'L');
			$this->Ln();
			$this->SetFont("Arial", "B", 10);
			$this->Cell(28,5,'GC Rel. No.: ',0,0,'R');
			$this->SetFont("Arial", "", 10);
			$this->Cell(86,5,sprintf("%03d",$id),0,0,'L');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(40,5,'Date Released:',0,0,'R');
			$this->SetFont("Arial", "", 10);
			$this->Cell(50,5,_dateFormat($header_data->agcr_approved_at),0,0,'L');
			$this->Ln(12);
		}

		function details($link,$group,$id)
		{
			$this->SetFont("Arial", "", 10);
					
			foreach ($group as $d) 
			{
				$pcs = 0;
				$x=1;	
				$this->Cell(190,8,'Denomination: '.number_format($d->denomination,2),1,0,'L');
				$this->Ln(8);
				$gcs = getDenomReleasingReportById($link,$id,$d->denom_id);	
					
				foreach ($gcs as $g) 
				{
					$pcs++;
					if($x<=5)
					{
						$this->Cell(40,	8,$g->re_barcode_no,0,0,'L');
						$x++;
					}
					else 
					{	
						$x=1;										
						$this->Ln(5);
						$this->Cell(40,	8,$g->re_barcode_no,0,0,'L');	
						$x++;											
					}
				}
				$this->Ln(8);
				$this->Cell(140,6,'No of GC:','TB',0,true);
				$this->Cell(50,6,$pcs.' pcs	','TB',0);
				$this->Ln(9);
			}
		}

		function subfooter($details,$d,$id,$link)
		{
			$pc = '';
			$stat = array('none','partial','whole','final');
			$this->SetFont("Arial", "", 10);
			$this->Cell(30,	8,'Releasing Type:',0,0,'R');
			$this->Cell(60,	8,ucwords($stat[$details->agcr_stat]),0,0,'L');
			$this->Ln(5);
			if($d->cnt > 1)
			{
				$pc = 'pcs';
			}
			else 
			{
				$pc = 'pc';
			}
			$this->Cell(30,	8,'Total No. of GC:',0,0,'R');
			$this->Cell(60,	8,number_format($d->cnt,2).' '.$pc,0,0,'L');
			$this->Ln(5);
			$this->Cell(30,	8,'Total GC Amount:',0,0,'R');
			$this->Cell(60,	8,number_format($d->total,2),0,0,'L');
			$this->SetFont("Arial", "", 10);

			if($details->agcr_paymenttype!='')
			{
				$this->Ln(5);				
				$this->Cell(30,	8,'Payment Type:',0,0,'R');
				$this->Cell(60,	8,strtoupper($details->agcr_paymenttype),0,0,'L');
				$this->SetFont("Arial", "", 10);

				$paymentd = getStorePaymentDetails($link,$id);

				if($details->agcr_paymenttype=='cash')
				{
					$this->Ln(5);				
					$this->Cell(30,	8,'Amount Received:',0,0,'R');
					$this->Cell(60,	8,number_format($paymentd->institut_amountrec,2),0,0,'L');
					$this->SetFont("Arial", "", 10);
				}	

				if($details->agcr_paymenttype=='check')
				{

					$this->Ln(5);				
					$this->Cell(30,	8,'Bank Name:',0,0,'R');
					$this->Cell(60,	8,ucwords($paymentd->institut_bankname),0,0,'L');
					$this->SetFont("Arial", "", 10);					

					$this->Ln(5);				
					$this->Cell(30,	8,'Bank Account #:',0,0,'R');
					$this->Cell(60,	8,ucwords($paymentd->institut_bankaccountnum),0,0,'L');
					$this->SetFont("Arial", "", 10);	

					$this->Ln(5);				
					$this->Cell(30,	8,'Check #:',0,0,'R');
					$this->Cell(60,	8,ucwords($paymentd->institut_checknumber),0,0,'L');
					$this->SetFont("Arial", "", 10);

					$this->Ln(5);				
					$this->Cell(30,	8,'Check Amount:',0,0,'R');
					$this->Cell(60,	8,number_format($paymentd->institut_amountrec,2),0,0,'L');
					$this->SetFont("Arial", "", 10);
				}	

				if($details->agcr_paymenttype=='jv')
				{
					$this->Ln(5);				
					$this->Cell(30,	8,'Customer:',0,0,'R');
					$this->Cell(60,	8,strtoupper($paymentd->institut_jvcustomer),0,0,'L');
					$this->SetFont("Arial", "", 10);
				}
			}	

			$this->Ln(8);
			$this->Cell(65,8,'Received by:',0,0,'L');
			$this->Cell(65,8,'Released by:',0,0,'L');
			$this->Cell(65,8,'Checked by:',0,0,'L');
			$this->Ln(8);
			$this->SetFont("Arial", "B", 10);
			$this->Cell(65,	8,$details->agcr_recby,0,0,'C');
			$this->Cell(65,8,ucwords($details->fnameprepared.' '.$details->lnameprepared),0,0,'C');
			$this->Cell(60,8,$details->agcr_checkedby,0,0,'C');
			$this->Ln(4);
			$this->SetFont("Arial", "", 10);
			$this->Cell(65,	1,'__________________________',0,0,'C');
			$this->Cell(65,	1,'__________________________',0,0,'C');
			$this->Cell(65,	1,'__________________________',0,0,'C');
			$this->Ln(5);
			$this->SetFont("Arial", "B", 7);
			$this->Cell(65,	1,'(Signature over Printed name)',0,0,'C');
			$this->Cell(65,	1,'(Signature over Printed name)',0,0,'C');
			$this->Cell(65,	1,'(Signature over Printed name)',0,0,'C');
			$this->Ln(8);
		}
	}

	$pdf = new GCReleasedReport();
	$pdf->AliasNbPages();
	$pdf->AddPage("P","Letter");
	$d = getReleasedDetailsTotalAndCount($link,$id);	
	$header_data = reportHeaderTreasuryReleasedGC($link,$id);
	$gcgroup = getAllReleasedGCByID($link,$id);
	$pdf->setDate($header_data);
	$pdf->setRelNum($id);
	$pdf->docHeader();
	$pdf->subheader($header_data,'AGC Head Office',$id);
	$pdf->details($link,$gcgroup,$id);
	$pdf->subfooter($header_data,$d,$id,$link);
	//$pdf->Output();
	$pdf->Output('../reports/treasury_releasing/gcr'.$id.'.pdf','F');
?>

<script>
	window.location = "<?php echo 'index.php?gcreleaseid='.$id; ?>";
</script>

?>
