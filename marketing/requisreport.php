<?php
	session_start();
	require_once('../config.php');
	require('../fpdf.php');
	require_once('../function.php');
	if(isset($_GET['id']))
	{
		$id = $_GET['id'];
		if(trim($id)===''|| !is_numeric($id) || !checkIfExist($link,'repuis_pro_id','requisition_entry','repuis_pro_id',$id))
		{
			exit();
		}
	}
	else 
	{
		exit();
	}

	class REQUISREPORT extends FPDF
	{
		/**
		 * Draws text within a box defined by width = w, height = h, and aligns
		 * the text vertically within the box ($valign = M/B/T for middle, bottom, or top)
		 * Also, aligns the text horizontally ($align = L/C/R/J for left, centered, right or justified)
		 * drawTextBox uses drawRows
		 *
		 * This function is provided by TUFaT.com
		 */
		function drawTextBox($strText, $w, $h, $align='L', $valign='T', $border=true)
		{
		    $xi=$this->GetX();
		    $yi=$this->GetY();
		    
		    $hrow=$this->FontSize;
		    $textrows=$this->drawRows($w, $hrow, $strText, 0, $align, 0, 0, 0);
		    $maxrows=floor($h/$this->FontSize);
		    $rows=min($textrows, $maxrows);

		    $dy=0;
		    if (strtoupper($valign)=='M')
		        $dy=($h-$rows*$this->FontSize)/2;
		    if (strtoupper($valign)=='B')
		        $dy=$h-$rows*$this->FontSize;

		    $this->SetY($yi+$dy);
		    $this->SetX($xi);

		    $this->drawRows($w, $hrow, $strText, 0, $align, false, $rows, 1);

		    if ($border)
		        $this->Rect($xi, $yi, $w, $h);
		}

		function drawRows($w, $h, $txt, $border=0, $align='J', $fill=false, $maxline=0, $prn=0)
		{
		    $cw=&$this->CurrentFont['cw'];
		    if($w==0)
		        $w=$this->w-$this->rMargin-$this->x;
		    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		    $s=str_replace("\r", '', $txt);
		    $nb=strlen($s);
		    if($nb>0 && $s[$nb-1]=="\n")
		        $nb--;
		    $b=0;
		    if($border)
		    {
		        if($border==1)
		        {
		            $border='LTRB';
		            $b='LRT';
		            $b2='LR';
		        }
		        else
		        {
		            $b2='';
		            if(is_int(strpos($border, 'L')))
		                $b2.='L';
		            if(is_int(strpos($border, 'R')))
		                $b2.='R';
		            $b=is_int(strpos($border, 'T')) ? $b2.'T' : $b2;
		        }
		    }
		    $sep=-1;
		    $i=0;
		    $j=0;
		    $l=0;
		    $ns=0;
		    $nl=1;
		    while($i<$nb)
		    {
		        //Get next character
		        $c=$s[$i];
		        if($c=="\n")
		        {
		            //Explicit line break
		            if($this->ws>0)
		            {
		                $this->ws=0;
		                if ($prn==1) $this->_out('0 Tw');
		            }
		            if ($prn==1) {
		                $this->Cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
		            }
		            $i++;
		            $sep=-1;
		            $j=$i;
		            $l=0;
		            $ns=0;
		            $nl++;
		            if($border && $nl==2)
		                $b=$b2;
		            if ( $maxline && $nl > $maxline )
		                return substr($s, $i);
		            continue;
		        }
		        if($c==' ')
		        {
		            $sep=$i;
		            $ls=$l;
		            $ns++;
		        }
		        $l+=$cw[$c];
		        if($l>$wmax)
		        {
		            //Automatic line break
		            if($sep==-1)
		            {
		                if($i==$j)
		                    $i++;
		                if($this->ws>0)
		                {
		                    $this->ws=0;
		                    if ($prn==1) $this->_out('0 Tw');
		                }
		                if ($prn==1) {
		                    $this->Cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
		                }
		            }
		            else
		            {
		                if($align=='J')
		                {
		                    $this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
		                    if ($prn==1) $this->_out(sprintf('%.3F Tw', $this->ws*$this->k));
		                }
		                if ($prn==1){
		                    $this->Cell($w, $h, substr($s, $j, $sep-$j), $b, 2, $align, $fill);
		                }
		                $i=$sep+1;
		            }
		            $sep=-1;
		            $j=$i;
		            $l=0;
		            $ns=0;
		            $nl++;
		            if($border && $nl==2)
		                $b=$b2;
		            if ( $maxline && $nl > $maxline )
		                return substr($s, $i);
		        }
		        else
		            $i++;
		    }
		    //Last chunk
		    if($this->ws>0)
		    {
		        $this->ws=0;
		        if ($prn==1) $this->_out('0 Tw');
		    }
		    if($border && is_int(strpos($border, 'B')))
		        $b.='B';
		    if ($prn==1) {
		        $this->Cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
		    }
		    $this->x=$this->lMargin;
		    return $nl;
		}


		function Header()
		{
			$this->SetFont("Helvetica", "B", 12);
			$this->SetTextColor(28, 28, 28);
			$this->Cell(0, 8, 'Marketing Department', 0, 0, "C");
			$this->Ln(5);
			$this->SetFont("Helvetica", "B", 11);
			$this->SetTextColor(28, 28, 28);
			$this->Cell(0, 8, 'ALTURAS GROUP OF COMPANIES', 0, 0, "C");
			$this->SetFont("times", "B", 11);
			$this->SetTextColor(28, 28, 28);
			$this->Ln();
			$this->Cell(0, 1, 'GC E-Requisition', 0, 0, "C");	
			$this->Ln(6);
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
		}

		function subheader($rdetails)
		{
			$this->Ln();
			$this->SetFont("Arial", "B", 9);
			$this->Cell(28,5,'E-Req. No: ',0,0,'R');
			$this->SetFont("Arial", "", 9);
			$this->Cell(86,5,$rdetails->requis_erno,0,0,'L');
			$this->SetFont("Arial", "B", 9);
			$this->Cell(40,5,'Date Requested: ',0,0,'R');
			$this->SetFont("Arial", '', 9);
			$this->Cell(50,5,_dateFormat($rdetails->requis_req),0,0,'L');
			$this->Ln();
			$this->SetFont("Arial", "B", 9);
			$this->Cell(28,5,'',0,0,'R');
			$this->SetFont("Arial", "", 9);
			$this->Cell(86,5,'',0,0,'L');
			$this->SetFont("Arial", "B", 9);
			$this->Cell(40,5,'Date Needed: ',0,0,'R');
			$this->SetFont("Arial", "", 9);
			$this->Cell(50,5,_dateFormat($rdetails->requis_need),0,0,'L');
			$this->Ln(12);
		}

		function details($data,$id,$link)
		{
			$this->Cell(20,5,'',0,0,'L');
			$this->SetFont("Arial", "B", 9);
			$this->Cell(150,8,' Request for gift cheque printing as per breakdown provided below.',1,0,'L');
			$this->SetFont("Arial", "", 9);	
			$this->Ln();
			$this->Cell(20,5,'',0,0,'');
			$this->Cell(30,8,'Denomination',1,0,'C');
			$this->Cell(25,8,'Qty',1,0,'C');
			$this->Cell(25,8,'Unit',1,0,'C');
			$this->Cell(35,8,'Barcode No. Start',1,0,'C');
			$this->Cell(35,8,'Barcode No. End',1,0,'C');
			foreach ($data as $key) 
			{
				$select = 'barcode_no';
				$where =  'denom_id='.$key->pe_items_denomination.'
                            AND
                          pe_entry_gc='.$id;
				$join = '';
				$limit = 'ORDER BY 
                         	barcode_no
                         ASC 
                         LIMIT 1';
				$start = getSelectedData($link,'gc',$select,$where,$join,$limit);

				$limit = 'ORDER BY 
                         	barcode_no
                         DESC 
                         LIMIT 1';
				$end = getSelectedData($link,'gc',$select,$where,$join,$limit);

				$this->Ln();
				$this->Cell(20,5,'',0,0,'');
				$this->Cell(30,8,number_format($key->denomination,2),1,0,'C');
				$this->Cell(25,8,number_format($key->pe_items_quantity),1,0,'C');
				$this->Cell(25,8,'pcs',1,0,'C');
				$this->Cell(35,8,$start->barcode_no,1,0,'C');
				$this->Cell(35,8,$end->barcode_no,1,0,'C');
			}
			$this->Ln(14);
                        // <th>Denomination</th>
                        // <th>Qty</th>
                        // <th>Unit</th>
                        // <th>Barcode No. Start</th>
                        // <th>Barcode No. End</th>
		}

		function supplierinfo($rdetails)
		{
			$x = $this->GetX();
			$y = $this->GetY();
			$this->SetX(30);
			//$this->MultiCell(20,8,'',1,'L',false);
			$this->SetFont("Arial", "B", 9);
			$this->MultiCell(150,8,'Supplier Information',1,'L',false);
			$this->SetX(30);
			$cheight = $this->calculateColHeight($rdetails->gcs_companyname);
			$torow = $cheight;
			$this->drawTextBox('Company Name', 35, $cheight, 'C', 'M');
			// $this->MultiCell(35,$cheight,'Company Name:',1,'L',false);
			$row = $y+8;
			$this->SetXY(65,$row);
			$this->MultiCell(115,8,ucwords($rdetails->gcs_companyname),1,'L',false);
			$this->SetX(30);
			$cheight = $this->calculateColHeight($rdetails->gcs_contactperson);
			$this->MultiCell(35,$cheight,'Contact Person:',1,'L',false);
			$row = $row+$torow;
			$this->SetXY(65,$row);
			$this->MultiCell(115,8,ucwords($rdetails->gcs_contactperson),1,'L',false);
			$this->SetX(30);
			$cheight = $this->calculateColHeight($rdetails->gcs_contactnumber);
			$this->MultiCell(35,$cheight,'Contact #:',1,'L',false);
			$row = $row+8;
			$this->SetXY(65,$row);
			$this->MultiCell(115,8,$rdetails->gcs_contactnumber,1,'L',false);
			$this->SetX(30);
			$cheight = $this->calculateColHeight($rdetails->gcs_address);
			$this->MultiCell(35,$cheight,'Address:',1,'L',false);
			$row = $row+8;
			$this->SetXY(65,$row);
			$this->MultiCell(115,8,ucwords($rdetails->gcs_address),1,'L',false);

			// $this->Cell(150,8,' Supplier Information',1,0,'L');
			// $this->Cell(20,5,'',0,0,'L');
			// $this->SetFont("Arial", "B", 9);
			// $this->Cell(150,8,' Supplier Information',1,0,'L');
			// $this->Ln();	
			// $this->Cell(20,5,'',0,0,'L');
			// $this->Cell(50,8,' Company Name: ',1,0,'L');
			// $this->SetFont("Arial", "", 9);	
			// //$this->MultiCell(100,8,ucwords($rdetails->gcs_companyname),1,'L',false);
			// $this->Cell(100,8,' Company Name: ',1,0,'L');
			// $this->Ln();	
			// $this->Cell(20,5,'',0,0,'L');
			// $this->SetFont("Arial", "B", 9);
			// $this->Cell(50,8,' Contact Person: ',1,0,'L');
			// $this->SetFont("Arial", "", 9);	
			// $this->Cell(100,8,ucwords($rdetails->gcs_contactperson)	,1,0,'L');	
			// $this->Ln();	
			// $this->Cell(20,5,'',0,0,'L');
			// $this->SetFont("Arial", "B", 9);
			// $this->Cell(50,8,' Contact #: ',1,0,'L');
			// $this->SetFont("Arial", "", 9);	
			// $this->Cell(100,8,ucwords($rdetails->gcs_contactnumber)	,1,0,'L');	
			// $this->Ln();	
			// $this->Cell(20,5,'',0,0,'L');
			// $this->SetFont("Arial", "B", 9);
			// $this->Cell(50,8,' Address : ',1,0,'L');
			// $this->SetFont("Arial", "", 9);	
			// $this->MultiCell(100,8,$rdetails->gcs_address,1,'L',false);
			// $this->Ln();
		}

		function calculateColHeight($string)
		{
			$strlen = strlen($string);
			$c = 8;
			$nblines = ceil($strlen / 70);
			if($nblines > 1)
			{
				$c = $nblines*8;
			}
			return $c;
		}

		function assignatories($rdetails)
		{
			$this->Ln(18);
			$this->Cell(105,8,'Checked by:',0,0,'L');
			$this->Cell(80,8,'Prepared by:',0,0,'L');
			$this->Ln(8);
			$this->SetFont("Arial", "B", 10);
			$this->Cell(12,	8,'',0,0,'C');
			$this->Cell(63,	8,ucwords($rdetails->requis_checked),0,0,'C');
			$this->Cell(40,8,'',0,0,'C');
			$this->Cell(60,8,ucwords($rdetails->firstname.' '.$rdetails->lastname),0,0,'C');
			$this->Ln(4);
			$this->SetFont("Arial", "", 10);
			$this->Cell(18,	1,'',0,0,'R');
			$this->Cell(50,	1,'______________________________',0,0,'C');
			$this->Cell(36,	1,'',0,0,'C');
			$this->Cell(80,	1,'______________________________',0,0,'C');
			$this->Ln(5);
			$this->SetFont("Arial", "B", 7);
			$this->Cell(13,	1,'',0,0,'C');
			$this->Cell(60,	1,'(Signature over Printed name)',0,0,'C');
			$this->Cell(41,	1,'',0,0,'C');
			$this->Cell(60,	1,'(Signature over Printed name)',0,0,'C');
			// $this->Ln(8);
			// $this->SetFont("Arial", "", 10);
			// $this->Cell(105,8,'',0,0,'L');
			// $this->Cell(80,8,'Approved by:',0,0,'L');
			// $this->Ln(8);
			// $this->SetFont("Arial", "B", 10);
			// $this->Cell(80,	8,'',0,0,'C');
			// $this->Cell(34,8,'',0,0,'C');
			// $this->Cell(60,8,ucwords($rdetails->requis_approved),0,0,'C');
			// $this->Ln(4);
			// $this->SetFont("Arial", "", 10);
			// $this->Cell(18,	1,'',0,0,'R');
			// $this->Cell(50,	1,'',0,0,'C');
			// $this->Cell(36,	1,'',0,0,'C');
			// $this->Cell(80,	1,'______________________________',0,0,'C');
			// $this->Ln(5);
			// $this->SetFont("Arial", "B", 7);
			// $this->Cell(13,	1,'',0,0,'C');
			// $this->Cell(60,	1,'',0,0,'C');
			// $this->Cell(41,	1,'',0,0,'C');
			// $this->Cell(60,	1,'(Signature over Printed name)',0,0,'C');
		}
	}

	$pdf = new REQUISREPORT();
	$pdf->AliasNbPages();
	$pdf->AddPage("P","Letter");

	$select = 'requisition_entry.requis_erno,
				requisition_entry.requis_req,
				requisition_entry.requis_need,
				requisition_entry.requis_approved,
				requisition_entry.requis_checked,
				users.firstname,
				users.lastname,
				supplier.gcs_companyname,
				supplier.gcs_contactperson,
				supplier.gcs_contactnumber,
				supplier.gcs_address';
	$where = 'requisition_entry.repuis_pro_id='.$id;
	$join = 'INNER JOIN
				users
			ON
				users.user_id = requisition_entry.requis_req_by 
			INNER JOIN
				supplier
			ON
				supplier.gcs_id = requisition_entry.requis_supplierid';
	$limit = 'LIMIT 1';
	$requisdetails = getSelectedData($link,'requisition_entry',$select,$where,$join,$limit);

	$requisnum = $requisdetails->requis_erno;

	$select = 'denomination.denomination,
				production_request_items.pe_items_quantity,
				production_request_items.pe_items_denomination';
	$where = 'pe_items_request_id='.$id;
	$join = 'INNER JOIN 
				denomination
			ON 
				production_request_items.pe_items_denomination = denomination.denom_id';
	$limit = '';
	$denoms = getAllData($link,'production_request_items',$select,$where,$join,$limit);
	$pdf->subheader($requisdetails);
	$pdf->details($denoms,$id,$link);
	$pdf->supplierinfo($requisdetails);
	$pdf->assignatories($requisdetails);
	//$pdf->Output();
	$pdf->Output('../reports/marketing/requis'.$requisnum.'.pdf','F');
?>

<script>
	window.location = "<?php echo 'index.php?request='.$requisnum; ?>";
</script>


<!--     
	http://fpdf.de/downloads/add-ons/textbox.html
strText: the string to print
    w: width of the box
    h: height of the box
    align: horizontal alignment (L, C, R or J). Default value: L
    valign: T, M or B). Default value: T
    border: whether to draw the border of the box. Default value: true> -->
