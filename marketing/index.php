<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $bud = countAdj($link,'budget_adjustment');
  $query_store = $link->query("SELECT store_id,store_name FROM stores WHERE store_status='active'");
  $query_gc_type = $link->query(
    "SELECT 
      gc_type_id,
      gctype,
      gc_status 
    FROM 
      gc_type 
    WHERE 
      gc_status='1'
    AND
      gc_forallocation='1'
  "); 
  $denoms = getAllDenomination($link);
  

?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
    
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i></a></li>
			<li><a href="#">Dashboard</a></li>  
      <span class="fa fa-refresh pull-right _refreshpage" title="Reload Page"></span>            
		</ol>
	</div>      

	<div class="row">
		<div class="col-sm-4">
			<div class="box box-bot">
      	<div class="box-header"><h4><i class="fa fa-inbox"></i> Promo GC Request</h4></div>
      	<div class="box-content budbox">
          <?php 
            $group = getField($link,'usergroup','users','user_id',$_SESSION['gc_id']);
            $table = 'promo_gc_request';
            $select = "promo_gc_request.pgcreq_reqnum,
                promo_gc_request.pgcreq_datereq,
                promo_gc_request.pgcreq_id,
                promo_gc_request.pgcreq_dateneeded,
                promo_gc_request.pgcreq_total,
                CONCAT(users.firstname,' ',users.lastname) as user";
            $where = "promo_gc_request.pgcreq_group!=''
                AND 
                  promo_gc_request.pgcreq_tagged='1'
                AND
                    (promo_gc_request.pgcreq_group_status=''
                AND
                    promo_gc_request.pgcreq_status='pending')
                OR 
                    (promo_gc_request.pgcreq_group_status='approved'
                AND
                    promo_gc_request.pgcreq_status='pending')
                ";
            $join = 'INNER JOIN
                    users
                ON
                    users.user_id = promo_gc_request.pgcreq_reqby';
            $limit = 'ORDER BY pgcreq_id ASC';
            $budAppReq = count(getAllData($link,$table,$select,$where,$join,$limit));
            echo $budAppReq>0 ?'<a href="#/promo-request/" class="ajaxpages">':''; 
          ?>
          <div class="slate-colorbox red red-x bot">            
              <i class="fa fa-exclamation-triangle fa-pad"></i>
              <div class="slate-colorbox-label">Pending Request</div>
              <span class="badge badge-count red-b"><?php echo $budAppReq; ?></span>                     
          </div>
          <?php echo $budAppReq>0 ? '</a>':''; ?>

          <?php 
            $budAppReq = promoRequestCount($link,'approved'); 
            echo $budAppReq>0 ?'<a href="#/promo-request-approved/">':''; 
          ?>
          <div class="slate-colorbox blue blue-x bot">
              <i class="fa fa-check-square-o fa-pad"></i>
              <div class="slate-colorbox-label">Approved Request</div>
              <span class="badge badge-count blue-b"><?php echo $budAppReq; ?></span>
          </div>
          <?php echo $budAppReq>0 ?'</a>':''; ?>

          <?php 
            $budAppReq = checkRequest($link,'promo_gc_request','pgcreq_status','cancel'); 
          ?>
          <?php echo $budAppReq>0 ?'<a href="promo-gc-request-list-cancelled.php">':''; ?>
          <div class="slate-colorbox gray gray-x">
              <i class="fa fa-times fa-pad"></i>
              <div class="slate-colorbox-label">Cancelled Request</div>
              <span class="badge badge-count black-b"><?php echo $budAppReq; ?></span>  
          </div>
          <?php echo $budAppReq>0 ?'</a>':''; ?> 
				</div>
			</div>
      <div class="box box-bot">
        <div class="box-header"><h4><i class="fa fa-inbox"></i> GC Production Request</h4></div>
        <div class="box-content gcreqbox">
          <?php  
            $proPenReq = checkRequest($link,'production_request','pe_status','0');
            echo $proPenReq > 0 ? '<a href="#/pending-production-request-list/">':'';
          ?>
          <div class="slate-colorbox red red-x bot">
              <i class="fa fa-exclamation-triangle fa-pad"></i>
              <div class="slate-colorbox-label">Pending Request</div>
              <span class="badge badge-count red-b"><?php echo $proPenReq; ?></span>
          </div>
          <?php echo $proPenReq > 0 ? '</a>':''; ?>

          <?php  
            $proAppReq = checkRequest($link,'production_request','pe_status','1');
            echo $proAppReq > 0 ? '<a href="#/approved-production-request-list/">':'';
          ?>         
          <div class="slate-colorbox blue blue-x bot">
              <i class="fa fa-check-square-o fa-pad"></i>
              <div class="slate-colorbox-label">Approved Request</div>
              <span class="badge badge-count blue-b"><?php echo $proAppReq; ?></span>
          </div>
          <?php echo $proAppReq > 0 ? '</a>':''; ?>

          <?php  
            $proCanReq = checkRequest($link,'production_request','pe_status','2');
            echo $proCanReq > 0 ? '<a href="#/cancelled-production-request-list/">':'';
          ?>            
          <div class="slate-colorbox gray gray-x">
              <i class="fa fa-times fa-pad"></i>
              <div class="slate-colorbox-label">Cancelled Request</div>
              <span class="badge badge-count black-b"><?php echo $proCanReq; ?></span>
          </div>
          <?php echo $proCanReq > 0 ? '</a>':''; ?>   
        </div> 
      </div>
		</div>
    <div class="col-sm-4">
      <div class="box box-bot">
        <div class="box-header"><h4><i class="fa fa-inbox"></i> Promo GC Received</h4></div>
        <div class="box-content budbox">
          <?php 
            $promoGCREl = numRowsNoWhere($link,'promo_gc_release_to_details');
            echo $promoGCREl > 0 ? '<a href="#/promo-gc-released-list/">':'';
          ?>
          <div class="slate-colorbox blue blue-x bot">
              <i class="fa fa-check-square-o fa-pad"></i>
              <div class="slate-colorbox-label">Released GC</div>
              <span class="badge badge-count blue-b"><?php echo $promoGCREl; ?></span>
          </div>
          <?php echo $promoGCREl > 0 ? '</a>':''?>
        </div>
      </div>
      <div class="box box-bot">
        <div class="box-header"><h4><i class="fa fa-inbox"></i> Available GC for Allocation / Sales</h4></div>
        <div class="box-content budbox">
          <ul class="list-group bld">
              <?php foreach ($denoms as $denom): ?>
                <input type="hidden" id="nx<?php echo $denom->denom_id; ?>"  value="<?php echo countGCNotYetAllocatedandNotPromo($link,$denom->denom_id); ?>"/>                         
                <li class="list-group-item"><span class="badge" id="n<?php echo $denom->denom_id; ?>"><?php echo countGCNotYetAllocatedandNotPromo($link,$denom->denom_id); ?></span> &#8369 <?php echo number_format($denom->denomination,2); ?></li>          
              <?php endforeach ?>   
          </ul> 
          <button type="button" class="btn btn-info pull-right" id="view-allocated-gc" onclick="showGCforAllocation()">View GC</button>
        </div>
      </div>
    </div>

		<div class="col-sm-4">
			<div class="box">
      	<div class="box-header"><h4><i class="fa fa-inbox"></i> Current Budget</h4></div>
      	<div class="box-content">
        	<h3 class="current-budget mbot">&#8369 <?php echo number_format(currentBudget($link),2); ?></h3>
				</div>
			</div>
      <?php 
        if($_SESSION['gc_uroles']==0): ?>
        <?php if(checkIfHasRows($link,'pe_requisition','production_request','pe_requisition','0','pe_generate_code','1')): ?>                    
            <table class="table table-adjust bs-callout-info">
                <tbody>
                    <?php 

                        $query = $link->query(
                            "SELECT 
                                `pe_id`,`pe_num` 
                            FROM 
                                `production_request`
                            WHERE 
                                `pe_generate_code` = '1'
                            AND
                                `pe_requisition`= '0'
                            AND 
                                `pe_status`='1'
                            ");

                        while($row = $query->fetch_assoc()):
                    ?>         
                    
                    <tr>
                        <td>Please fill up Requisition Form for Production Request No. <?php echo $row['pe_num']; ?> P.O.</td>
                        <td>                          
                            <a href="requisition.php?request=<?php echo $row['pe_id']; ?>" class="btn btn-info requi-box"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Click Here</a>
                        </td>
                    </tr>    

                    <?php endwhile; ?>

                </tbody>
            </table>
        <?php endif; ?> 
      <?php endif; ?>
  	</div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/marketing.js"></script>
<script type="text/javascript" src="../assets/js/main.js"></script>
<script>

</script>
<?php include 'footer.php' ?>