<?php 

	require('reports.php');
	if(isset($_GET['id']))
	{
		$id = trim($_GET['id']);
		if(trim($id)===''|| !is_numeric($id)||!checkIfExist($link,'prrelto_relnumber','promo_gc_release_to_details','prrelto_relnumber',$id))
		{
			exit();
		}
	}
	else 
	{
		exit();
	}

	$pdf = new REPORTS();
	$pdf->AliasNbPages();
	$pdf->AddPage("P","Letter");
	$pdf->SetFont('Arial','B',16);

	$pdf->setFooter(true);

	$pdf->setReportName('Promo GC Releasing Report');
	$pdf->setRelNum($id);
	$deptid = getField($link,'usertype','users','user_id',$_SESSION['gc_id']);
	$usertype = getField($link,'title','access_page','access_no',$deptid);
	$pdf->docHeader($usertype);
	$table = 'promo_gc_release_to_details';
	$select = "promo_gc_release_to_details.prrelto_date,
			promo_gc_release_to_details.prrelto_status,
			promo_gc_release_to_details.prrelto_id,
			promo_gc_release_to_details.prrelto_checkedby,
			promo_gc_release_to_details.prrelto_approvedby,
			CONCAT(users.firstname,' ',users.lastname) as user";
	$where = 'promo_gc_release_to_details.prrelto_relnumber='.$id;
	$join = 'INNER JOIN
				users
			ON
				users.user_id=promo_gc_release_to_details.prrelto_relby';
	$limit = '';
	$data = getSelectedData($link,$table,$select,$where,$join,$limit);
	$pdf->subheaderpromo($id,$data);
	$denomgroup = getAllReleasedGCByIDPromo($data->prrelto_id,$link);

	$pdf->detailspromo($link,$denomgroup,$data->prrelto_id);
	$totcount = $link->query(
		"SELECT 
			IFNULL(SUM(denomination.denomination),0.00) as total,
			IFNULL(COUNT(promo_gc_release_to_items.prreltoi_id),0) as cnt
		FROM 
			promo_gc_release_to_items 
		INNER JOIN
			gc
		ON
			gc.barcode_no = promo_gc_release_to_items.prreltoi_barcode
		INNER JOIN
			denomination
		ON
			denomination.denom_id = gc.denom_id
		WHERE 
			promo_gc_release_to_items.prreltoi_relid='$data->prrelto_id'
	");

	if($totcount)
	{
		$totcount = $totcount->fetch_object();
	}

	$pdf->setDate($data->prrelto_date);
	$text = 'Rel #'.sprintf("%03d",$id).' Date:'._dateFormat($data->prrelto_date).' Time:'._timeFormat($data->prrelto_date);
	$pdf->setFooterText($text);
	//var_dump($totcount);
	$pdf->subfooterpromo($data,$totcount);

	//$pdf->Output();

	$pdf->Output('../reports/treasury_releasingpromo/gcrprom'.$id.'.pdf','F');

?>
<script>
	window.location = "<?php echo 'index.php?gcreleaseidpromo='.$id; ?>";
</script>

