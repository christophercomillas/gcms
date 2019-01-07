<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $bud = countAdj($link,'budget_adjustment');
  $production = countAdj($link,'gc_adjustment');
  $allocate = countAdj($link,'allocation_adjustment'); 
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
      	<div class="box-header"><h4><i class="fa fa-inbox"></i> Budget Request</h4></div>
      	<div class="box-content budbox">
        
          <?php 
            $dept = getField($link,'usertype','users','user_id',$_SESSION['gc_id']);
            $table = 'budget_request';
            $select = 'budget_request.br_id';
            $where = 'users.usertype='.$dept.'
                AND
              budget_request.br_request_status=0';
            $join = 'INNER JOIN
                users
              ON
                users.user_id = budget_request.br_requested_by';
            $limit='';
            $budPenReq = getAllData($link,$table,$select,$where,$join,$limit);
          ?>
          <?php echo count($budPenReq)>0 ? '<a href="pending_budget_request.php">':''; ?>
      		<div class="slate-colorbox red red-x bot">      			
      				<i class="fa fa-exclamation-triangle fa-pad"></i>
      				<div class="slate-colorbox-label">Pending Request</div>
              <span class="badge badge-count red-b"><?php echo count($budPenReq); ?></span>      				  		  
    			</div>
          <?php echo count($budPenReq)>0 ? '</a>':''; ?>

          <?php 
            $budAppReq = checkRequest($link,'budget_request','br_request_status','1'); 
          ?>
          <?php echo $budAppReq>0 ?'<a href="approved-budget-request.php">':''; ?>
    			<div class="slate-colorbox blue blue-x bot">
        			<i class="fa fa-check-square-o fa-pad"></i>
        			<div class="slate-colorbox-label">Approved Request</div>
       				<span class="badge badge-count blue-b"><?php echo $budAppReq; ?></span>
    			</div>
          <?php echo $budAppReq>0 ?'</a>':''; ?>

          <?php 
            $budCanReq = checkRequest($link,'budget_request','br_request_status','2'); 
          ?>
          <?php echo $budCanReq>0 ?'<a href="cancelled-budget-request.php">':''; ?>
          <div class="slate-colorbox gray gray-x">
              <i class="fa fa-times fa-pad"></i>
              <div class="slate-colorbox-label">Cancelled Request</div>
              <span class="badge badge-count black-b"><?php echo $budCanReq; ?></span>  
          </div>
          <?php echo $budCanReq>0 ?'</a>':''; ?>                	
				</div>
			</div>
			<div class="box box-bot">
      	<div class="box-header"><h4><i class="fa fa-inbox"></i> Store GC Request</h4></div>
      	<div class="box-content storeqbox">

          <?php 
            $storePenReq = countStoresRequest($link);//checkGCStoreRequest($link,'store_gcrequest','sgc_status',1,0);
            echo $storePenReq > 0 ? '<a href="tran_release_gc.php">' : '';
          ?>
      		<div class="slate-colorbox red red-x bot">
      				<i class="fa fa-exclamation-triangle fa-pad"></i>
      				<div class="slate-colorbox-label">Pending Request</div>
      				 <span class="badge badge-count red-b"><?php echo $storePenReq; ?></span>
    			</div>
          <?php echo $storePenReq > 0 ? '</a>' : ''; ?>

          <?php 
            $storeAppReq = count(GCReleasedAllStore($link));
            echo $storeAppReq > 0 ? '<a href="approved-gc-request.php">':'';
          ?>
    			<div class="slate-colorbox blue blue-x bot">
        			<i class="fa fa-check-square-o fa-pad"></i>
        			<div class="slate-colorbox-label">Released GC</div>
       				<span class="badge badge-count blue-b"><?php echo $storeAppReq; ?></span>
    			</div>
          <?php echo $storeAppReq > 0 ? '</a>':''?>

          <?php 
            $storeCanReq = countAllGCRequestCancelled($link); 
            echo $storeCanReq > 0 ? '<a href="cancelled-gc-request.php">':''; 
          ?>
          <div class="slate-colorbox gray gray-x">
              <i class="fa fa-times fa-pad"></i>
              <div class="slate-colorbox-label">Cancelled Request</div>
              <span class="badge badge-count black-b"><?php echo $storeCanReq; ?></span>
          </div>    
          <?php echo $storeCanReq > 0 ? '</a>':''; ?>         	
				</div>
			</div>
      <div class="box box-bot">
        <div class="box-header"><h4><i class="fa fa-inbox"></i> Promo GC Released</h4></div>
        <div class="box-content storeqbox">
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
        <div class="box-header">
          <h4>
          <i class="fa fa-inbox"></i> Institution GC Sales
          </h4>
        </div>
        <div class="box-content">
          <?php 
            $instr = numRowsNoWhere($link,'institut_transactions');
            echo $instr > 0 ? '<a href="#/institution-gc-sales/">': ''; 
          ?>      
          <div class="slate-colorbox blue blue-x">            
              <i class="fa fa-adjust fa-pad"></i>
              <div class="slate-colorbox-label"><?php echo $instr > 1 ? 'Transactions':'Transaction'; ?></div>
              <span class="badge badge-count blue-b"><?php echo $instr; ?></span>                     
          </div> 
          <?php echo $instr > 0 ? '</a>': ''; ?>                        
        </div>  
      </div>
		</div>
		<div class="col-sm-4">
			<div class="box box-bot">
      	<div class="box-header">
          <h4>
          <i class="fa fa-inbox"></i> GC Production Request
          </h4>
        </div>
      	<div class="box-content gcreqbox">
          <?php 
            $table = 'production_request';
            $select = 'production_request.pe_id';
            $where = 'users.usertype='.$dept.'
              AND
                production_request.pe_status=0';
            $join = 'INNER JOIN
                users
              ON
                users.user_id = production_request.pe_requested_by';
            $limit='';
            $proPenReq = getAllData($link,$table,$select,$where,$join,$limit);
          ?>
          <?php  
            echo count($proPenReq) > 0 ? '<a href="pending_production_request.php">':'';
          ?>
      		<div class="slate-colorbox red red-x bot">
      				<i class="fa fa-exclamation-triangle fa-pad"></i>
      				<div class="slate-colorbox-label">Pending Request</div>
      				<span class="badge badge-count red-b"><?php echo count($proPenReq); ?></span>
    			</div>
          <?php echo count($proPenReq) > 0 ? '</a>':''; ?>

           <?php  
            $proAppReq = checkRequest($link,'production_request','pe_status','1');
            echo $proAppReq > 0 ? '<a href="approved-production-request.php">':'';
          ?>         
    			<div class="slate-colorbox blue blue-x bot">
        			<i class="fa fa-check-square-o fa-pad"></i>
        			<div class="slate-colorbox-label">Approved Request</div>
       				<span class="badge badge-count blue-b"><?php echo $proAppReq; ?></span>
    			</div>
          <?php echo $proAppReq > 0 ? '</a>':''; ?>

           <?php  
            $proCanReq = checkRequest($link,'production_request','pe_status','2');
            echo $proCanReq > 0 ? '<a href="cancelled-production-request.php">':'';
          ?>            
          <div class="slate-colorbox gray gray-x">
              <i class="fa fa-times fa-pad"></i>
              <div class="slate-colorbox-label">Cancelled Request</div>
              <span class="badge badge-count black-b"><?php echo $proCanReq; ?></span>
          </div>
          <?php echo $proCanReq > 0 ? '</a>':''; ?>      					
				</div>  
			</div>
      <div class="box box-bot">
        <div class="box-header">
          <h4>
          <i class="fa fa-inbox"></i> Adjustments
          </h4>
        </div>
        <div class="box-content">
          <?php echo $bud > 0 ? '<a href="view-budget-adj.php">': ''; ?>      
          <div class="slate-colorbox blue blue-x bot">
            <i class="fa fa-adjust fa-pad"></i>
            <div class="slate-colorbox-label">Budget Adjustments</div>
            <span class="badge badge-count blue-b"><?php echo $bud; ?></span>         
          </div>
          <?php echo $bud > 0 ? '</a>': ''; ?>
          <?php echo $allocate > 0 ? '<a href="view-allocation-adj.php">': ''; ?>      
          <div class="slate-colorbox blue blue-x">            
              <i class="fa fa-adjust fa-pad"></i>
              <div class="slate-colorbox-label">Allocation Adjustments</div>
              <span class="badge badge-count blue-b"><?php echo $allocate; ?></span>                     
          </div> 
          <?php echo $allocate > 0 ? '</a>': ''; ?>                        
        </div>

      </div>
        <div class="box">
          <div class="box-header"><h4><i class="fa fa-inbox"></i> Special External GC Request</h4></div>
          <div class="box-content storeqbox">

            <?php 
              $segcpending  = numRows($link,'special_external_gcrequest','spexgc_status','pending');
              echo $segcpending > 0 ? '<a href="#/special-external-request/">' : '';
            ?>
            <div class="slate-colorbox red red-x bot">
                <i class="fa fa-exclamation-triangle fa-pad"></i>
                <div class="slate-colorbox-label">Pending Request</div>
                 <span class="badge badge-count red-b"><?php echo $segcpending; ?></span>
            </div>
            <?php echo $segcpending > 0 ? '</a>' : ''; ?>

            <?php 
              $segcapproved  = numRows($link,'special_external_gcrequest','spexgc_status','approved');
              echo $segcapproved > 0 ? '<a href="#/special-external-request-approved/">':'';
            ?>
            <div class="slate-colorbox blue blue-x bot">
                <i class="fa fa-check-square-o fa-pad"></i>
                <div class="slate-colorbox-label">Approved GC</div>
                <span class="badge badge-count blue-b"><?php echo $segcapproved; ?></span>
            </div>
            <?php echo $segcapproved > 0 ? '</a>':''?>

            <?php 
              $segcreviewed  = numRowsWhereTwo($link,'special_external_gcrequest','spexgc_id','spexgc_reviewed','spexgc_released','reviewed','');
              echo $segcreviewed > 0 ? '<a href="#/special-external-gc-reviewed">':'';
            ?>
            <div class="slate-colorbox blue blue-x bot">
                <i class="fa fa-check-square-o fa-pad"></i>
                <div class="slate-colorbox-label">Reviewed GC For Releasing</div>
                <span class="badge badge-count blue-b"><?php echo $segcreviewed; ?></span>
            </div>
            <?php echo $segcreviewed > 0 ? '</a>':''?>

            <?php 
              $segcapproved  = numRows($link,'special_external_gcrequest','spexgc_released','released');
              echo $segcapproved > 0 ? '<a href="#/released-special-external-request/">' : '';
            ?>
            <div class="slate-colorbox blue blue-x bot">
                <i class="fa fa-check-square-o fa-pad"></i>
                <div class="slate-colorbox-label">Released GC</div>
                <span class="badge badge-count blue-b"><?php echo $segcapproved; ?></span>
            </div>
            <?php echo $segcapproved > 0 ? '</a>':''?>

            <?php 
              $segccancelled  = numRows($link,'special_external_gcrequest','spexgc_status','cancelled');
              echo $segccancelled > 0 ? '<a href="cancelled-gc-request.php">':''; 
            ?>
            <div class="slate-colorbox gray gray-x">
                <i class="fa fa-times fa-pad"></i>
                <div class="slate-colorbox-label">Cancelled Request</div>
                <span class="badge badge-count black-b"><?php echo $segccancelled; ?></span>
            </div>    
            <?php echo $segccancelled > 0 ? '</a>':''; ?>           
          </div>
        </div>
		</div>
		<div class="col-sm-4">
			<div class="box box-bot">
            	<div class="box-header"><h4><i class="fa fa-inbox"></i> Current Budget</h4></div>
            	<div class="box-content">
              	<h3 class="current-budget">&#8369 <?php echo number_format(currentBudget($link),2); ?></h3>
				</div>
			</div>
      <div class="box box-bot">
        <div class="box-header"><h4><i class="fa fa-inbox"></i> EOD</h4></div>
        <div class="box-content storeqbox">
          <?php 
            $eod = numRowsNoWhere($link,'institut_eod');
            echo $eod > 0 ? '<a href="#/eod-list/">':'';
          ?>
          <div class="slate-colorbox blue blue-x bot">
              <i class="fa fa-check-square-o fa-pad"></i>
              <div class="slate-colorbox-label">EOD List</div>
              <span class="badge badge-count blue-b"><?php echo $eod; ?></span>
          </div>
          <?php echo $eod > 0 ? '</a>':''?>         
        </div>
      </div>
     
        <?php if(numRowsForValidationtres($link,'production_request','pe_generate_code',0,'pe_status',1)>0): ?>
            <div class="form-generator">
              <table class="table table-adjust bs-callout-info">
                  <tbody>
                      <?php 

                          $query = $link->query(
                              "SELECT 
                                  `pe_id`,`pe_num` 
                              FROM 
                                  `production_request`
                              WHERE 
                                  `pe_generate_code` = '0'
                              AND
                                  `pe_status`= '1'
                              ");

                          while($row = $query->fetch_assoc()):
                      ?>         
                      
                      <tr>
                          <td>Production Request No. <?php echo $row['pe_num']; ?> has been approved please click button to generate barcode.</td>
                          <td><button peid="<?php echo $row['pe_id']; ?>" onclick="generateBarcode(<?php echo $row['pe_id']; ?>,<?php echo $row['pe_num']; ?>);" class="btn btn-info gencode"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Generate Barcode</button></td>
                      </tr>    

                      <?php endwhile; ?>

                  </tbody>
              </table>
            </div>
        <?php endif; ?>

		</div>
  	</div>

  <div class="modal modal-static fade loadingstyle" id="processing-modal" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog loadingstyle">
        <div class="text-center">
            <img src="../assets/images/ring-alt.svg" class="icon" />
            <h4 class="loading">Generating Barcode..</h4>
        </div>
      </div>
  </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/main.js"></script>
<?php include 'footer.php' ?>