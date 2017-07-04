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
        <div class="pull-right">
        <div class="form-group">
            <div class="col-sm-3 input-group date">
                 <label class=" control-label" for="input-date_create">Xuất theo ngày</label>
                 <input style="margin-top: 5px;" type="text" id="date_day_export" name="date_create" value="<?php echo date('d-m-Y')?>" placeholder="Ngày đăng ký" data-date-format="DD-MM-YYYY" id="date_export" class="form-control">
                 <span class="input-group-btn">
                 <button style="margin-top:28px" type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                 </span>
              </div>
              <div class="col-sm-3">
                <button id="submit_export" style="margin-top: 28px;" type="button" class="btn btn-success">Xuất Excel</button>
              </div>
            </div>
          
        </div>
       
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>TT</th>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Họ Tên</th>
                    <th>STK</th>
                    <th>Ngân hàng</th>
                    <th>Chi nhánh</th>
                     <th>Gói đầu tư</th>
                     <th>Lợi nhuận <br> 15%/15 ngày</th>
                     <th>Ngày tạo</th>
                      <th>Ngày kết thúc</th>
                </tr>
            </thead>
            <tbody id="result_date"> 
                <?php $stt = 0;
                foreach ($allPD as $value) { $stt ++?>
                  <tr>
                    <td><?php echo $stt; ?></td>
                    <td><?php echo $value['customer_id'] ?></td>
                    <td><?php echo $value['username'] ?></td>
                    <td><?php echo $value['account_holder'] ?></td>
                    <td><?php echo $value['bank_name'] ?></td>
                    <td><?php echo $value['account_number'] ?></td>
                    <td><?php echo $value['branch_bank'] ?></td>
                    <td><?php echo number_format($value['filled']) ?> USD</td>
                    <td><?php echo number_format($value['max_profit']) ?> USD</td>
                    <td><?php echo date('d/m/Y H:i',strtotime($value['date_added'])) ?></td>
                    <td><?php echo date('d/m/Y H:i',strtotime($value['date_finish'])) ?></td>
                </tr>  
              
                <?php } ?>
                
               
            </tbody>
        </table>
      
    </div>
  </div>
</div>
<script type="text/javascript">
    $('#submit_date').click(function(){
        var date_day = $('#date_day').val();
        $.ajax({
            url : "<?php echo $load_pd_finish ?>",
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
    $('#submit_export').click(function(){
        var date_day = $('#date_day_export').val();
        $.ajax({
            url : "index.php?route=pd/pd/export_finish&token=<?php echo $_GET['token'];?>",
            type : "post",
            dataType:"text",
            data : {
                'date' : date_day
            },
            success : function (result){
                window.location.href=result;
            }
        });
    });
    $('.date').datetimepicker({
        pickTime: false
    });

</script>
<?php echo $footer; ?>