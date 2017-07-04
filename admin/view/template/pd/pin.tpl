<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>Pin</h1>

  </div>
</div>
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Pin</h3>
    </div>
    <div class="panel-body">
        <div class="pull-left">
            <div class="form-group">
            <div class="col-sm-3 input-group date">
                 <label class=" control-label" for="input-date_create">Lọc theo ngày</label>
                 <input style="margin-top: 5px;" type="text" id="date_day" name="date_create" value="<?php echo date('d-m-Y')?>" placeholder="Ngày đăng ký" data-date-format="DD-MM-YYYY" id="date_create" class="form-control">
                 <span class="input-group-btn">
                 <button style="margin-top:28px" type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                 </span>
              </div>
              <div class="col-sm-3">
                <button id="submit_date" style="margin-top: 28px;" type="button" class="btn btn-success">Lọc</button>
              </div>
            </div>
        </div>
        <span class="url_xuatpin" href="" style="margin-bottom:10px; float:right">
            <div class="btn btn-success pull-right">Xuất Excel</div>
        </span>
     	<table class="table table-bordered table-hover">
     		<thead>
     			<tr>
     				<th>TT</th>
     				<th>Username</th>
     				<th>Địa chỉ ví</th>
     				<th>Số pin</th>
                    <th>Trạng thái</th>
                    <th>Thời gian</th>
     			</tr>
     		</thead>
     		<tbody id="result_date"> 
               
                <?php $stt = 0;
                foreach ($pin as $value) { $stt ++?>
                <?php if ($value['confirmations'] == 0) { ?>
                  <tr>
                    <td><?php echo $stt; ?></td>
                    <td><?php echo $value['username'] ?></td>
                    <td><?php echo $value['input_address'] ?></td>
                    <td><?php echo $value['pin'] ?></td>
                    <td><?php echo ($value['confirmations'] == 0) ? "<span class='label label-warning'>Đang chờ</span>" : "<span class='label label-success'>Đã Chuyển</span>" ?></td>
                   
                    <td><?php echo date('d/m/Y H:i',strtotime($value['date_created'])) ?></td>
                    
                </tr>  
               <?php  } else{ ?>
     			<tr style="
    background: #c1e893; */
">
                    <td><?php echo $stt; ?></td>
     				<td><?php echo $value['username'] ?></td>
     				<td><?php echo $value['input_address'] ?></td>
                    <td><?php echo $value['pin'] ?></td>
                    <td><?php echo ($value['confirmations'] == 0) ? "<span class='label label-warning'>Đang chờ</span>" : "<span class='label label-success'>Đã Chuyển</span>" ?></td>
                   
     				<td><?php echo date('d/m/Y H:i',strtotime($value['date_created'])) ?></td>
     				
     			</tr>
                <?php } ?>
                <?php } ?>
                
               
     		</tbody>
     	</table>
        <?php echo $pagination ?>
    </div>
  </div>
</div>
<script type="text/javascript">
    $('#submit_date').click(function(){
        var date_day = $('#date_day').val();
        $.ajax({
            url : "<?php echo $load_pin_date ?>",
            type : "post",
            dataType:"text",
            data : {
                'date' : date_day
            },
            success : function (result){
                jQuery('#result_date').html(result);
            }
        });
    });
    $('.date').datetimepicker({
    pickTime: false
});
    jQuery('.url_xuatpin').click(function(){
        var date_day = $('#date_day').val();
        //alert(date_day);
        //return false;
        window.location.replace("index.php?route=report/exportCustomer/xuatpin&date="+date_day+"&token=<?php echo $_GET['token'];?>");
    })
</script>
<?php echo $footer; ?>