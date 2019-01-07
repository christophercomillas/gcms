<?php 

	require('reports.php');
	if(isset($_GET['repoutid']))
	{
		$id = trim($_GET['repoutid']);		
	}
	else 
	{
		exit();
	}

	$table = 'transfer_request_served';
	$select = "transfer_request.t_reqstoreby,
		store_reqby.store_name as storereqby,
		transfer_request_served.tr_serverelnum,
		transfer_request.t_reqnum,
		transfer_request.t_reqdatereq,
		transfer_request_served.tr_servedate,
		CONCAT(servedby.firstname,' ',servedby.lastname) as sby";
	$where = "transfer_request_served.tr_servedid = '".$id."'
		AND
			transfer_request.t_reqstoreto = '".$_SESSION['gc_store']."'";
	$join = 'INNER JOIN
			transfer_request
		ON
			transfer_request.tr_reqid = transfer_request_served.tr_reqid
		INNER JOIN
			stores as store_reqby
		ON
			store_reqby.store_id = transfer_request.t_reqstoreby
		INNER JOIN
			users as servedby
		ON
			servedby.user_id = transfer_request_served.tr_serveby';
	$limit = '';

	$reldata = getSelectedData($link,$table,$select,$where,$join,$limit);

	if(count($reldata)==0)
	{
		exit();
	}
	
// SELECT 
// 	transfer_request.t_reqstoreby,
// 	store_reqby.store_name as storereqby,
// 	transfer_request_served.tr_serverelnum,
// 	transfer_request.t_reqnum,
// 	transfer_request.t_reqdatereq,
// 	transfer_request_served.tr_servedate,
// 	CONCAT(servedby.firstname,' ',servedby.lastname) as sby
// FROM 
// 	transfer_request_served 
// INNER JOIN
// 	transfer_request
// ON
// 	transfer_request.tr_reqid = transfer_request_served.tr_reqid
// INNER JOIN
// 	stores as store_reqby
// ON
// 	store_reqby.store_id = transfer_request.t_reqstoreby
// INNER JOIN
// 	users as servedby
// ON
// 	servedby.user_id = transfer_request_served.tr_serveby
// WHERE 
// 	transfer_request_served.tr_servedid = '4'
// AND
// 	transfer_request.t_reqstoreto = '3'	

	$pdf = new REPORTS();
	$pdf->AliasNbPages();
	$pdf->AddPage("P","Letter");
	$pdf->SetFont('Arial','B',16);

	//$pdf->setFooter(true);

	$pdf->setReportName('Gift Check Transfer (Out)');
	$pdf->setRelNum($id);

	$pdf->docHeader($link);
	$pdf->subheaderTransferReleasing($reldata);

	$table = 'transfer_request_served_items';
	$select = 'denomination.denom_id,denomination.denomination, COUNT(denomination.denom_id) as cnt';
	$where = "transfer_request_served_items.trs_served = '".$id."'";
	$join = 'INNER JOIN
			gc
		ON
			gc.barcode_no = transfer_request_served_items.trs_barcode
		INNER JOIN
			denomination
		ON
			denomination.denom_id = gc.denom_id';
	$limit = 'GROUP BY 
			denomination.denom_id
		ORDER BY
			denomination.denom_id
		ASC';
	$denoms = getAllData($link,$table,$select,$where,$join,$limit);

	$pdf->transferItems($denoms,$id,$link);


	$table = 'transfer_request_served_items';
	$select = '	COUNT(denomination.denom_id) as cnt,
		SUM(denomination.denomination) as sum,
		transfer_request_served.tr_serveStatus';
	$where = "transfer_request_served_items.trs_served = '".$id."'";
	$join = 'transfer_request_served_items 
		INNER JOIN
			gc 
		ON
			gc.barcode_no = transfer_request_served_items.trs_barcode
		INNER JOIN
			denomination
		ON
			denomination.denom_id = gc.denom_id
		INNER JOIN
			transfer_request_served
		ON
			transfer_request_served.tr_servedid = transfer_request_served_items.trs_served';
	$limit = '';
	$infos = getSelectedData($link,$table,$select,$where,$join,$limit);

	$pdf->transferInfo($link,$infos);

	$pdf->signaturesTransferReleasing($link);
	//$pdf->signatures($link);

	//$pdf->Output();	

	$pdf->Output('../reports/store-transfer/gctrrel'.$id.'.pdf','F');

?>
<script>
	window.location = "<?php echo 'index.php?transferReleasing='.$id; ?>";
</script>

