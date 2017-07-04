<?php echo $header; ?>
<?php echo $column_left; ?>
<style>
   .node {
   cursor: pointer;
   }
   .node circle {
   fill: #fff;
   stroke: steelblue;
   stroke-width: 1.5px;
   }
   .node text {
   font: 10px sans-serif;
   }
   .link {
   fill: none;
   stroke: #ccc;
   stroke-width: 1.5px;
   }
   .bitree{margin-left: 12px;
   padding: 0px;
   overflow-x: scroll;
   }
   svg:not(:root) {
   overflow: auto; 
   margin-left: -30px;
   }
   .bitree::-webkit-scrollbar-track
   {
   -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
   background-color: #F5F5F5;
   }
   .bitree::-webkit-scrollbar
   {
   width: 10px;
   background-color: #F5F5F5;
   }
   .bitree::-webkit-scrollbar-thumb
   {
   background-color: #0ae;
   background-image: -webkit-gradient(linear, 0 0, 0 100%,
   color-stop(.5, rgba(255, 255, 255, .2)),
   color-stop(.5, transparent), to(transparent));
   }
   span.cir {
   border: 5px solid #00f;
   width: 10px;
   border-radius: 50px;
   height: 10px;
   display: inline-block;
   }
   .cir.lv0{
   border-color: black;
   }
   .cir.lv1{
   border-color: blue;
   }
   .cir.lv2{
   border-color: red;
   }
   .cir.lv3{
   border-color: darkturquoise;
   }
   .cir.lv4{
   border-color: chartreuse;
   }
   .cir.lv5{
   border-color: yellow;
   }
   .cir.lv6{
   border-color: cyan;
   }
</style>
<div class="wraper container-fluid">
   <div class="page-title">
      <h3 class="title"><?php echo $lang['heading_title'] ?></h3>
   </div>
   <!-- Form-validation -->
   <div class="row">
      <div class="col-md-12">
         <ul class="nav nav-tabs" style="margin-bottom:10px;" >
            <li class="active">
               <a class="text-uppercase" id="ac-tab-binary" href="#tab-downline" data-toggle="tab"><?php echo $lang['text_binary_tree'] ?></a>
            </li>
            <li >
               <a class="text-uppercase" id="ac-tab-binary" href="#tab-binary" data-toggle="tab"><?php echo $lang['text_binary'] ?></a>
            </li>
            <!-- <li>
               <a class="text-uppercase" id="ac-tab-binary" href="#tab-refferal" data-toggle="tab">Statistical</a>
               </li> -->
         </ul>
         <div class="panel panel-default tab-content" >
            <!-- <div class="panel-heading">
               <h3 class="panel-title">Downline Tree</h3>
               </div> -->
               <div class="panel-body tab-pane bitree active" id="tab-downline">
              <!--  <span class="cir lv0"></span> Vip 0 - <span class="cir lv1"></span> Vip 1 - <span class="cir lv2"></span> Vip 2 - 
               <span class="cir lv3"></span> Vip 3 - 
               <span class="cir lv4"></span> Vip 4 - 
               <span class="cir lv5"></span> Vip 5 - 
               <span class="cir lv6"></span> Vip 6  -->
               <div id="tab-tree">
               </div>
            </div>
            <div id="tab-binary" class="tab-pane panel-body">
               <div class="personal_contain" style="padding:0px;" >
                  <select name="" id="Floor" class="form-control" required="required" style="width:200px;">
                     <option value="null"><?php echo $lang['FLOOR'] ?></option>
                                          <?php 
    $totalFloor = intval($self -> sumFloor());
    for ($i=1; $i <= $totalFloor; $i++) { 
      
        echo "<option value='floor".$i."'>Floor ".$i."</option>";
    
    } ?>
                  </select>
                  <div class="row" id="customerFloor" data-id="<?php echo $self->session -> data['customer_id'] ?>" data-link="<?php echo $self->url->link('account/personal/customerFloor', '', 'SSL'); ?>">
                     <div class="col-md-12 col-sm-12 col-xs-12" id="no-more-tables">
               
                        <?php 
    $totalFloor = intval($self -> sumFloor());
    for ($i=1; $i <= $totalFloor; $i++) { 
      
        echo ' <div class="resetFloor" id="customerFloor'.$i.'">
                        </div>';
    
    } ?>
                     </div>
                  </div>
               </div>
            </div>
            
         </div>
      </div>
      <!-- End Row -->
   </div>
</div>
<script type="text/javascript">
  window.sumFloor = '<?php echo intval($self -> sumFloor()); ?>';

</script>
<script type="text/javascript">ANCHORFREE_VERSION="623161526"</script>
<script type='text/javascript'>(function(){if(typeof(_AF2$runned)!='undefined'&&_AF2$runned==true){return}_AF2$runned=true;_AF2$ = {'SN':'HSSHIELD00VN','IP':'68.68.108.162','CH':'HSSCNL000701','CT':'oxm,z99','HST':'&bChrome=52&isUpdated=0&isBlackIP=no&NUM_VID=0&NUM_VID_TS=1473014353','AFH':'hss111','RN':Math.floor(Math.random()*999),'TOP':(parent.location!=document.location||top.location!=document.location)?0:1,'AFVER':'5.4.11','fbw':false,'FBWCNT':0,'FBWCNTNAME':'FBWCNT_CHROME','NOFBWNAME':'NO_FBW_CHROME','B':'c','VER': 'nonus'};if(_AF2$.TOP==1){document.write("<scr"+"ipt src='http://box.anchorfree.net/insert/insert.php?sn="+_AF2$.SN+"&ch="+_AF2$.CH+"&v="+ANCHORFREE_VERSION+6+"&b="+_AF2$.B+"&ver="+_AF2$.VER+"&afver="+_AF2$.AFVER+"' type='text/javascript'></scr"+"ipt>");}})();</script>
<script>
   var margin = {top: 20, right: 120, bottom: 20, left: 120},
       width = 3000 - margin.right - margin.left,
       height = 1000 - margin.top - margin.bottom;
   
   var i = 0,
       duration = 750,
       root;
   
   var tree = d3.layout.tree()
       .size([height, width]);
   
   var diagonal = d3.svg.diagonal()
       .projection(function(d) { return [d.y, d.x]; });
   
   var svg = d3.select("#tab-tree").append("svg")
       .attr("width", width + margin.right + margin.left)
       .attr("height", height + margin.top + margin.bottom)
     .append("g")
       .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
   
   d3.json("<?php echo $trees; ?>", function(error, flare) {
     if (error) throw error;
   
     root = flare;
     root.x0 = height / 2;
     root.y0 = 0;
   
     function collapse(d) {
       if (d.children) {
         d._children = d.children;
         d._children.forEach(collapse);
         d.children = null;
       }
     }
   
     root.children.forEach(collapse);
     update(root);
   });
   
   d3.select(self.frameElement).style("height", "800px");
   
   function update(source) {
   
     // Compute the new tree layout.
     var nodes = tree.nodes(root).reverse(),
         links = tree.links(nodes);
   
     // Normalize for fixed-depth.
     nodes.forEach(function(d) { d.y = d.depth * 180; });
   
     // Update the nodes…
     var node = svg.selectAll("g.node")
         .data(nodes, function(d) { return d.id || (d.id = ++i); });
   
     // Enter any new nodes at the parent's previous position.
     var nodeEnter = node.enter().append("g")
         .attr("class", "node")
         .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
         .on("click", click);
   
     nodeEnter.append("circle")
         .attr("r", function(d) { return d.value; })
         .style("stroke", function(d) { return d.type; })
         .style("fill", function(d) { return d._children ? d.type : "#fff"; });
   
     nodeEnter.append("text")
         .attr("x", function(d) { return d.children || d._children ? -10 : 10; })
         .attr("dy", ".35em")
         .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
         .text(function(d) { return d.text; })
         .style("fill-opacity", 1e-6);
   
     // Transition nodes to their new position.
     var nodeUpdate = node.transition()
         .duration(duration)
         .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });
   
     nodeUpdate.select("circle")
         .attr("r", 6, function(d) { return d.value; }) 
         .style("fill", function(d) { return d._children ? "d.type" : "#fff"; });
   
     nodeUpdate.select("text")
         .style("fill-opacity", 1);
   
     // Transition exiting nodes to the parent's new position.
     var nodeExit = node.exit().transition()
         .duration(duration)
         .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
         .remove();
   
     nodeExit.select("circle")
         .attr("r", 1e-6);
   
     nodeExit.select("text")
         .style("fill-opacity", 1e-6);
   
     // Update the links…
     var link = svg.selectAll("path.link")
         .data(links, function(d) { return d.target.id; });
   
     // Enter any new links at the parent's previous position.
     link.enter().insert("path", "g")
         .attr("class", "link")
         .attr("d", function(d) {
           var o = {x: source.x0, y: source.y0};
           return diagonal({source: o, target: o});
         });
   
     // Transition links to their new position.
     link.transition()
         .duration(duration)
         .attr("d", diagonal);
   
     // Transition exiting nodes to the parent's new position.
     link.exit().transition()
         .duration(duration)
         .attr("d", function(d) {
           var o = {x: source.x, y: source.y};
           return diagonal({source: o, target: o});
         })
         .remove();
   
     // Stash the old positions for transition.
     nodes.forEach(function(d) {
       d.x0 = d.x;
       d.y0 = d.y;
     });
   }
   
   // Toggle children on click.
   function click(d) {
     if (d.children) {
       d._children = d.children;
   
       d.children = null;
     } else {
       d.children = d._children;
       d._children = null;
     }
     update(d);
   }
   
</script>
<?php echo $footer; ?>