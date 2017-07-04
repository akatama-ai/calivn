<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>Provide Donation</h1>

  </div>
</div>
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Provide Donation</h3>
    </div>
    <div class="panel-body">
        <div class="navbar-form">
            <select name="filter_status" class="form-control" style="width: 200px;">
                     <option value="*">Select Status</option>
                     <option value="1" <?php echo 1 == $filter_status? 'selected="selected"':'';?>>PD WAITING</option>
                      <option value="2" <?php echo 2 == $filter_status? 'selected="selected"':'';?>>PD MARCHED</option>
                     <option value="3" <?php echo 3 == $filter_status? 'selected="selected"':'';?>>PD FINISH</option>
                  
                </select>
            <button id="btn-filter" type="submit" class="btn btn-default">Filter</button>
        </div>
     	<table class="table table-bordered table-hover">
     		<thead>
     			<tr>
     				<th>NO</th>
     				<th>Username</th>
     				<th>Account Holder</th>
     				<th>Amount</th>
                    <th>Status</th>
                    <th>Date_added</th>
     				
                    <th>Time-Remain</th>
     			</tr>
     		</thead>
     		<tbody>
                    <?php $stt = 1; $sum=0;
                foreach ($allGd as $key => $value): ?>
     			<tr>
                    <td><?php echo $stt; ?></td>
     				<td><?php echo $value['username'] ?></td>
     				<td><?php echo $value['account_holder'] ?></td>
     				<td><?php echo number_format(doubleval($value['amount'])) ?> VND</td>
     				<?php if ($value['status'] == 0) {
                       echo '<td><span class="label label-default">Waiting</span></td>';
                    } ?>
                    <?php if ($value['status'] == 1) {
                       echo '<td><span class="label label-info">March</span></td>';
                    } ?>
                    <?php if ($value['status'] == 2) {
                       echo '<td><span class="label label-success">Finish</span></td>';
                    } ?>
                     <td><?php echo date('d/m/Y H:i',strtotime($value['date_added'])) ?></td>
                    
                    <td><span style="color:red; font-size:15px;" class="text-danger countdown" data-countdown="<?php echo $value['date_finish']; ?>">
                     </span> </td>
     			</tr>
                <?php $sum = $sum + $value['amount'];  ?>
                 
                        <?php $stt++; endforeach; ?>
                        <tr>
                    <td colspan="3" style="text-align: right; color:red; font-size:20px">
                        Total:
                    </td>
                    <td colspan="3"> <span style="color:red; font-size:20px"><?php echo number_format($sum); ?> VND </span></td>
                </tr>
               
     		</tbody>
     	</table>
        <?php echo $pagination ?>
    </div>
  </div>
</div>
<script type="text/javascript">
    $('#btn-filter').on('click', function() {
    url = 'index.php?route=pd/pd&token=<?php echo $token; ?>';
    var filter_status = $('select[name=\'filter_status\']').val();
  
  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status); 
  } 
  location = url;
  });
</script>
<?php echo $footer; ?>