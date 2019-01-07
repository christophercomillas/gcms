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
			$this->footertext = $text;
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
			$this->Cell(0, 10, $this->footertext, 0, 0, "R");
		}

		function docHeader($data)
		{
			// get user department

			$usertype = str_replace('Corporate', '', $data->title);
			$this->SetFont("Helvetica", "B", 10);
			//$this->SetTextColor(28, 28, 28);
			$this->Cell(0, 4, 'ALTURAS GROUP OF COMPANIES', 0, 0, "C");			
			$this->Ln();
			$this->SetFont("times", "B",10);
			//$this->SetTextColor(28, 28, 28);
			$this->Cell(0, 5, 'Head Office - '.ucwords($usertype).' Department', 0, 0, "C");
			$this->Ln();
			$this->SetFont("times", "B",10);
			//$this->SetTextColor(28, 28, 28);
			$this->Cell(0, 4, $this->reportname, 0, 0, "C");	
			$this->Ln(10);
        }
        
        function docheaderSPGC($period)
        {
			// get user department

			$usertype = str_replace('Corporate', '', 'Finance');
			$this->SetFont("Helvetica", "B", 10);
			//$this->SetTextColor(28, 28, 28);
			$this->Cell(0, 4, 'ALTURAS GROUP OF COMPANIES', 0, 0, "C");			
			$this->Ln();
			$this->SetFont("times", "B",10);
			//$this->SetTextColor(28, 28, 28);
			$this->Cell(0, 5, 'Head Office - '.ucwords($usertype).' Department', 0, 0, "C");
			$this->Ln();
			$this->SetFont("times", "B",10);
			//$this->SetTextColor(28, 28, 28);
            $this->Cell(0, 5, $this->reportname, 0, 0, "C");	
            $this->Ln();
            $this->Cell(0, 5, $period, 0, 0, "C");
			$this->Ln(5);
        }

        function subheaderSPGC($todays_date)
        {
			$this->Ln();
			$this->SetFont("Arial", "B", 9);
			$this->Cell(28,5,'Date Printed: ',0,0,'R');
			$this->SetFont("Arial", "", 9);
			$this->Cell(86,5,_dateFormat($todays_date),0,0,'L');
			$this->Ln(7);
        }

		function subheaderpromo($id,$data)
		{
			$this->Ln();
			$this->SetFont("Arial", "B", 9);
			$this->Cell(28,5,'Request #.: ',0,0,'R');
			$this->SetFont("Arial", "", 9);
			$this->Cell(86,5,sprintf("%03d",$data->spexgc_num),0,0,'L');
			$this->SetFont("Arial", "B", 9);
			$this->Cell(40,5,'Date Approved:',0,0,'R');
			$this->SetFont("Arial", "", 9);
			$this->Cell(50,5,_dateFormat($data->spexgc_datereq),0,0,'L');
			$this->Ln();
			$this->SetFont("Arial", "B", 9);
			$this->Cell(28,5,'Customer: ',0,0,'R');
			$this->SetFont("Arial", "", 9);
			$this->Cell(86,5,ucwords($data->spcus_companyname),0,0,'L');
			$this->SetFont("Arial", "B", 9);
			$this->Cell(40,5,'Date Needed:',0,0,'R');
			$this->SetFont("Arial", "", 9);
			$this->Cell(50,5,_dateFormat($data->spexgc_dateneed),0,0,'L');
			$this->Ln(12);
        }
        
        function dataSPGC($datacus,$databar)
        {
            $this->SetFont("Arial", "B", 9);
			$this->Cell(8,5,'',0,0,'L');
            $this->Cell(180,6,'PER CUSTOMER',1,0,'L');
			$this->Ln();
			$this->Cell(8,6,'',0,0,'L');
			$this->Cell(40,6,'Date',1,0,'L');
			$this->Cell(70,6,'Company',1,0,'L');
			$this->Cell(25,6,'Releasing #',1,0,'L');
			$this->Cell(45,6,'Total Amount',1,0,'L');
            $this->Ln();
            foreach($datacus as $d)
            {               
                $this->Cell(8,6,'',0,0,'L');
                $this->Cell(40,6,date("F d, Y",strtotime($d->datereq)),1,0,'L');
                $this->Cell(70,6,strtoupper($d->spcus_companyname),1,0,'L');
                $this->Cell(25,6,$d->spexgc_num,1,0,'R');
                $this->Cell(45,6,number_format($d->totdenom,2),1,0,'R');
                $this->Ln();
            }
			$this->Ln(10);
            $this->SetFont("Arial", "B", 9);
			$this->Cell(8,5,'',0,0,'L');
            $this->Cell(180,6,'PER BARCODE',1,0,'L');
            $this->Ln();
			$this->Cell(8,6,'',0,0,'L');
			$this->Cell(32,6,'Date',1,0,'L');
			$this->Cell(18,6,'Barcode',1,0,'L');
			$this->Cell(18,6,'Denom',1,0,'L');
            $this->Cell(60,6,'Customer',1,0,'L');
            $this->Cell(20,6,'Released #',1,0,'L');
            $this->Cell(32,6,'Date Released',1,0,'L');
            $this->Ln();
            $customer = "";
            foreach($databar as $d)
            {               
                $customer = $d->spexgcemp_lname.', '.$d->spexgcemp_fname;
                if(trim($d->spexgcemp_mname)!='')
                {
                    $customer.= $d->spexgcemp_mname;
                }
                $customer = utf8_decode(html_entity_decode($customer,ENT_QUOTES,'UTF-8'));
                $this->Cell(8,6,'',0,0,'L');
                $this->Cell(32,6,date("F d, Y",strtotime($d->datereq)),1,0,'L');
                $this->Cell(18,6,$d->spexgcemp_barcode,1,0,'R');
                $this->Cell(18,6,number_format($d->spexgcemp_denom,2),1,0,'R');
                $this->Cell(60,6,strtoupper($customer),1,0,'L');
                $this->Cell(20,6,$d->spexgc_num,1,0,'R');
                $this->Cell(32,6,date("F d, Y",strtotime($d->daterel)),1,0,'L');
                $this->Ln();
            }
        }
        
		function detailsspecialexternalgc($link,$data,$gcs)
		{
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
				if($cnt > 10)
				{
					break;
				}
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

			$count = count($gcs);
			$kuwang = 0;
			if($count % 3 != 0)
			{
				$kuwang = 3 - ($count - (3 * (floor($count / 3))));
		
			}

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

			if($kuwang > 0)
			{
				for ($i=0; $i < $kuwang; $i++) 
				{ 
					$this->Cell(25,8,'',1,0,'L');
					$this->Cell(35,8,'',1,0,'L');	

				}
				$this->Ln();	
			}

			$this->Ln(6);
		}

		function footerSpecialExternalGC($data,$totcount)
		{
			$this->SetFont("Arial", "", 9);
			$this->Cell(40,	8,'AR #:',0,0,'R');
			$this->Cell(60,	8,$data->spexgc_payment_arnum,0,0,'L');
			$this->Ln(5);
			$this->SetFont("Arial", "", 9);
			$this->Cell(40,	8,'Total No. of GC:',0,0,'R');
			$this->Cell(60,	8,$totcount[1],0,0,'L');
			$this->Ln(5);
			$this->Cell(40,	8,'Total GC Amount:',0,0,'R');
			$this->Cell(60,	8,number_format($totcount[0],2),0,0,'L');
			$this->SetFont("Arial", "", 9);
			$this->Ln(8);
			$this->Cell(105,8,'Prepared by:',0,0,'L');
			$this->Cell(80,8,'Checked by:',0,0,'L');
			$this->Ln(8);
			$this->SetFont("Arial", "B", 9);
			$this->Cell(80,	8,ucwords(utf8_decode(html_entity_decode($data->approved))),0,0,'C');
			$this->Cell(34,8,'',0,0,'C');
			$this->Cell(60,8,utf8_decode(html_entity_decode($data->reqap_checkedby)),0,0,'C');
			$this->Ln(4);
			$this->SetFont("Arial", "", 9);
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
			$this->Ln(8);
			$this->SetFont("Arial", "", 9);
			$this->Cell(105,8,'',0,0,'L');
			$this->Cell(80,8,'Approved by:',0,0,'L');
			$this->Ln(8);
			$this->SetFont("Arial", "B", 9);
			$this->Cell(80,	8,'',0,0,'C');
			$this->Cell(34,8,'',0,0,'C');
			$this->Cell(60,8,utf8_decode(html_entity_decode($data->reqap_approvedby)),0,0,'C');
			$this->Ln(4);
			$this->SetFont("Arial", "", 9);
			$this->Cell(18,	1,'',0,0,'R');
			$this->Cell(50,	1,'',0,0,'C');
			$this->Cell(36,	1,'',0,0,'C');
			$this->Cell(80,	1,'______________________________',0,0,'C');
			$this->Ln(5);
			$this->SetFont("Arial", "B", 7);
			$this->Cell(13,	1,'',0,0,'C');
			$this->Cell(60,	1,'',0,0,'C');
			$this->Cell(41,	1,'',0,0,'C');
			$this->Cell(60,	1,'(Signature over Printed name)',0,0,'C');
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
			$this->Cell(105,8,'Prepared by:',0,0,'L');
			$this->Cell(80,8,'Checked by:',0,0,'L');
			$this->Ln(8);
			$this->SetFont("Arial", "B", 9);
			$this->Cell(80,	8,ucwords(utf8_decode(html_entity_decode($data->approved))),0,0,'C');
			$this->Cell(34,8,'',0,0,'C');
			$this->Cell(60,8,utf8_decode(html_entity_decode($data->reqap_checkedby)),0,0,'C');
			$this->Ln(4);
			$this->SetFont("Arial", "", 9);
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
			$this->Ln(8);
			$this->SetFont("Arial", "", 9);
			$this->Cell(105,8,'',0,0,'L');
			$this->Cell(80,8,'Approved by:',0,0,'L');
			$this->Ln(8);
			$this->SetFont("Arial", "B", 9);
			$this->Cell(80,	8,'',0,0,'C');
			$this->Cell(34,8,'',0,0,'C');
			$this->Cell(60,8,utf8_decode(html_entity_decode($data->reqap_approvedby)),0,0,'C');
			$this->Ln(4);
			$this->SetFont("Arial", "", 9);
			$this->Cell(18,	1,'',0,0,'R');
			$this->Cell(50,	1,'',0,0,'C');
			$this->Cell(36,	1,'',0,0,'C');
			$this->Cell(80,	1,'______________________________',0,0,'C');
			$this->Ln(5);
			$this->SetFont("Arial", "B", 7);
			$this->Cell(13,	1,'',0,0,'C');
			$this->Cell(60,	1,'',0,0,'C');
			$this->Cell(41,	1,'',0,0,'C');
			$this->Cell(60,	1,'(Signature over Printed name)',0,0,'C');
		}
	
	}

