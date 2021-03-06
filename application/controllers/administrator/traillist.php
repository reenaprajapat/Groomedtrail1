<?php $this->load->view('administrator/include/left_sidebar'); ?>
<style type="text/css">
    #ajax_favorite_loddder {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background:rgba(255, 255, 255, 0.5);
    z-index: 9999;
}
#ajax_favorite_loddder img {
    top: 42vh;
    left: 0;
    position: absolute;
    right: 0;
    margin: 0 auto;
    max-width: 120px;
}

</style>
<div class="warper container-fluid">
  <div id="responseMsg"></div>            
  <div id="responseMsg1"  style="display: none"></div>  
<div class="page-header"><h3>Trail List</h3></div>
<div class="panel panel-default POI-table">
  
    <div class="panel-body">
   <?php if(isset($segment)){
             if($segment == 'trails'){?>     
         <button  class="AllData" onclick="window.location.href='<?php echo base_url(); ?>administrator/traillist'">All</button>
    <?php } }else{?>
        <button  class="AllData">All</button>
    <?php } ?>

    <button  class="ViewPendingUpdates">View Pending Updates</button>
    <button  class="CloseSelectedTrails" id="CloseALLTrails">Close Selected Trails</button>
    <button  class="OpenSelectedTrails" id="OpenALLTrails">Open Selected Trails</button>
    <button  class="CloseALLTrails" id="seletAllClose">Close ALL Trails</button>
    <button  class="OpenALLTrails" id="seletAllOpen">Open ALL Trails</button>
    <form name="statefrm" id="statefrm" method="get" action="<?php echo base_url()?>administrator/trails">
    <select id="state" name="state">
    <option value="">Select A State</option>
         <?php $query = $this->db->query('SELECT DISTINCT region_name FROM tbl_kml_data_trail');
          $region = $query->result();
          if(isset($region)){
            foreach ($region as $regionName) { ?>
              <option value="<?php echo $regionName->region_name; ?>" <?php if($regionName->region_name ==$_GET['state']){echo 'Selected';}?>><?php echo $regionName->region_name; ?></option>
         <?php } } ?>
    </select>
    <button type="submit" id="stateSubmit" style="display: none">submit</button>
    </form>
    <textarea name="inbox_ids" id="inbox_ids" value="" style="display: none"></textarea>
    <div id="AllTrailData">
        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered mytabletraillist" id="toggleColumn-datatable123">
            <thead>
                <tr><th class="no-sort"><input type="checkbox" class="mail-checkbox mail-group-checkbox" value="1" id="select_all"></th>
                    <th style="display: none;">id</th>
                    <th>State Name</th>
                    <th>Trail Name</th>
                    <!--<th>Trail Points</th>-->
                    <th>Trail Description</th>
                   <!--  <th>Last Updated</th> -->
                    
                    <th>Trail Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="postList">
                <?php 
                $i =1;
                if(isset($kmlList)){
                    foreach ($kmlList as $poi) { 
                        $name ='';

                       ?>
                    <tr id="dele<?php if(isset($poi->klm_trail_name) && !empty($poi->klm_trail_name)) {echo $poi->klm_trail_name;} ?>">
                        <td><input type="checkbox" name="inbox_id[]" class="mail-checkbox mycheckbox" value="<?php if(isset($poi->klm_trail_name) && !empty($poi->klm_trail_name)) {echo $poi->klm_trail_name;} ?>" ></td>
                        <td style="display: none;"><?php if(isset($poi->id)){echo $poi->id;}?></td>
                         <td><?php if(isset($poi->region_name)){echo $poi->region_name;}?></td>
                         <td><a data-toggle="modal" data-target="#trailnameModal<?php if(isset($poi->klm_trail_name)){echo preg_replace('/[^A-Za-z0-9\-]/', '', strip_tags(str_replace(' ', '', str_replace(':', '', $poi->klm_trail_name))));}?>" id="<?php echo $i.'trailname'.$i;?>"><?php if(isset($poi->klm_trail_name)){echo $poi->klm_trail_name;}?></a>
                         <!-- Modal  klm_trail_name-->
                          <div class="modal fade updateTrail" id="trailnameModal<?php if(isset($poi->klm_trail_name)){echo preg_replace('/[^A-Za-z0-9\-]/', '', strip_tags(str_replace(' ', '', str_replace(':', '', $poi->klm_trail_name))));}?>" role="dialog" >
                            <div class="modal-dialog modal-md">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  <h4 class="modal-title">Update Trail Detail</h4>
                                </div>
                                <div class="modal-body">
                                <div id="respMsgtrail"></div>
                                <label>Trail Name</label>
                               
                                <p><input type="text" class="klm_trail_name" name="klm_trail_name" id="trail_name<?php echo $i;?>" value="<?php if(isset($poi->klm_trail_name)){echo strip_tags($poi->klm_trail_name);}?>"></p>
                                <label>Trail Description</label>
                                <p><textarea id="trailDesc<?php echo $i;?>" class="trailDesc" name="trailDesc" cols="35" row="40"> 

                                 <?php /*if(isset($poi->status) && $poi->status == 1){
                            echo $poi->trail_description;
                            }else{*/
                                if(isset($poi->trail_dscrptn)){ echo $poi->trail_dscrptn; }
                            //}?>
                                </textarea></p>
                                
                                </div>
                                <div class="modal-footer">
                                <button type="submit" class="trailDetailBtn" onClick="trailDetailEdit(<?php echo $i ?>, '<?php if(isset($poi->klm_trail_name)){echo $poi->klm_trail_name;}?>');">Update</button>
                                </div>
                               
                              </div>
                            </div>
                          </div>
                           <!-- Modal  klm_trail_name--></td>

                         
                        <td><p class="trailDescriptionCls" id="<?php echo $i.'trailDesc'.$i;?>" >
                            <?php /*if(isset($poi->status) && $poi->status == 1){
                            echo $poi->trail_description;
                            }else{*/
                                if(isset($poi->trail_dscrptn)){ echo $poi->trail_dscrptn; }
                            //}?>
                          
                        </p>
                      </td>
                        <td>
                        <?php 
                        if(isset($poi->previous_trail_status)){
                            if($poi->previous_trail_status == 0){ 
                                echo '<span class="OpenCls open0 OpenStatus'.$poi->klm_trail_name.' commencls" id="commenID'.$poi->klm_trail_name.'">Open</span>';
                            }else if($poi->previous_trail_status == 1){
                               echo '<span class="ClosedCls close1 ClosedStatus'.$poi->klm_trail_name.' commencls" id="commenID'.$poi->klm_trail_name.'">Closed</span>'; 
                            }else if($poi->previous_trail_status == 2){
                                echo '<span class="CautionCls caution2 CautionStatus'.$poi->klm_trail_name.' commencls" id="commenID'.$poi->klm_trail_name.'">Caution</span>';
                            }
                        } ?>
                        </td>
                        <td>
                        <?php if($poi->trail_name == $poi->klm_trail_name){
                            if($poi->status == 0){ ?>
                            <button data-toggle="modal" class="adminReviewCls" data-target="#myModal<?php if(isset($poi->klm_trail_name)){echo strip_tags(str_replace(' ', '', str_replace(':', '', $poi->klm_trail_name)));}?>">Review Change</button>
                           <!-- Modal -->
                          <div class="modal fade" id="myModal<?php if(isset($poi->klm_trail_name)){echo strip_tags(str_replace(' ', '', str_replace(':', '', $poi->klm_trail_name)));}?>" role="dialog">
                            <div class="modal-dialog modal-sm">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  <h4 class="modal-title">Review Change</h4>
                                </div>
                                <div class="modal-body">
                                <div id="respMsg"></div>
                                <?php if(isset($poi->trail_description)){echo $poi->trail_description;}?>
                                <hr/>
                              <p><b>previous status : </b><?php if($poi->previous_trail_status == 0){ 
                                    echo '<span class="OpenCls open0 OpenStatus'.$poi->klm_trail_name.' commencls" id="commenID'.$poi->klm_trail_name.'">Open</span>';
                                }else if($poi->previous_trail_status == 1){
                                   echo '<span class="ClosedCls close1 ClosedStatus'.$poi->klm_trail_name.' commencls" id="commenID'.$poi->klm_trail_name.'">Closed</span>'; 
                                }else if($poi->previous_trail_status == 2){
                                    echo '<span class="CautionCls caution2 CautionStatus'.$poi->klm_trail_name.' commencls" id="commenID'.$poi->klm_trail_name.'">Caution</span>';
                                } ?></p>
                              <p><b>New status : </b><?php if($poi->trail_status == 0){ 
                                        echo '<span class="OpenCls open0 OpenStatus'.$poi->klm_trail_name.' commencls" id="commenID'.$poi->klm_trail_name.'">Open</span>';
                                    }else if($poi->trail_status == 1){
                                       echo '<span class="ClosedCls close1 ClosedStatus'.$poi->klm_trail_name.' commencls" id="commenID'.$poi->klm_trail_name.'">Closed</span>'; 
                                    }else if($poi->trail_status == 2){
                                        echo '<span class="CautionCls caution2 CautionStatus'.$poi->klm_trail_name.' commencls" id="commenID'.$poi->klm_trail_name.'" >Caution</span>';
                                    } ?></p>
                                </div>
                                <div class="modal-footer">
<button class="approveCls"  onclick="changestatus2(1,'tbl_trail_report','status','trail_report_id','<?php if(isset($poi->trail_report_id)){echo $poi->trail_report_id;}?>',0,'tbl_kml_data_trail','flag','klm_trail_name','<?php if(isset($poi->klm_trail_name)){echo $poi->klm_trail_name;}?>','<?php echo $poi->trail_status; ?>','previous_trail_status');">Approve</button>

<button class="rejectCls" onclick="changestatus1(2,'tbl_trail_report','status','trail_report_id','<?php if(isset($poi->trail_report_id)){echo $poi->trail_report_id;}?>',1,'tbl_kml_data_trail','flag','klm_trail_name','<?php if(isset($poi->klm_trail_name)){echo $poi->klm_trail_name;}?>');">Reject</button>

                                </div>
                              </div>
                            </div>
                          </div>
                           <!-- Modal -->

                         <?php }else{ ?>
                        <select id="update_status" name="update_status" onchange="changestatus('tbl_kml_data_trail','previous_trail_status','klm_trail_name','<?php if(isset($poi->klm_trail_name)){ echo $poi->klm_trail_name; }?>',this.value)" >
                            <option value="">Update Status</option>
                            <option value="0">Open</option>
                            <option value="1">Closed</option>
                            <option value="2">Caution</option>
                        </select>
                        <a class="btn btn-default btn-sm deletekml btn-delete" id="<?php if(isset($poi->klm_trail_name)){ echo $poi->klm_trail_name; }?>">Delete</a>
                        <?php  } }else{ ?>
                        <select id="update_status" name="update_status"  onchange="changestatus('tbl_kml_data_trail','previous_trail_status','klm_trail_name','<?php if(isset($poi->klm_trail_name)){ echo $poi->klm_trail_name; }?>',this.value)" >
                            <option value="">Update Status</option>
                            <option value="0">Open</option>
                            <option value="1">Closed</option>
                            <option value="2">Caution</option>
                        </select>
                        <a class="btn btn-default btn-sm deletekml btn-delete" id="<?php if(isset($poi->klm_trail_name)){ echo $poi->klm_trail_name; }?>">Delete</a>
                        <?php } ?>
                        </td>
                    </tr>  

            
               <?php $i++; } }?>
            </tbody>
        </table>
   </div>
        <div id="PendingUpdates" style="display: none;">
        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered " id="toggleColumn-datatable11" >
            <thead >
                <tr><th ></th>
                    <th>Trail Type</th>
                    <th>Trail Name</th>
                    <th>Trail Description</th>
                    <!-- <th>Last Updated</th> -->
                    <th>Trail Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
       <?php  if(isset($ViewPendingUpdates)){
                    foreach ($ViewPendingUpdates as $PendingUpdates) { 
                       ?>
                    <tr class="dele<?php if(isset($PendingUpdates->klm_trail_name)){echo $PendingUpdates->klm_trail_name;}?>"><td ><input type="checkbox" name="checkTrail" id="checkTrail"></td>
                        <td>Trail</td>
                        <td><?php if(isset($PendingUpdates->klm_trail_name)){echo $PendingUpdates->klm_trail_name;}?></td>
                       
                        <td><p class="trailDescriptionCls" id="trailDescriptionId">
                        <?php if(isset($PendingUpdates->status) && $PendingUpdates->status == 1){
                                echo $PendingUpdates->trail_description;
                                }else{
                                if(isset($PendingUpdates->trail_dscrptn)){ echo $PendingUpdates->trail_dscrptn; }
                                }?>
                      
                        <td>
                        <?php if($PendingUpdates->trail_name == $PendingUpdates->klm_trail_name){
                                if($PendingUpdates->status == 0){
                                    echo '<span class="pendingApprovalCls" >Pending Approval</span>';
                                }
                            } ?>
                        
                        </td>
                        <td>
                        <?php if($PendingUpdates->trail_name == $PendingUpdates->klm_trail_name){
                            if($PendingUpdates->status == 0){
                              echo  '<button data-toggle="modal" class="adminReviewCls" data-target="#myModal'.strip_tags(str_replace(' ', '', str_replace(':', '', $PendingUpdates->klm_trail_name))).'">Review Change</button>'; ?>
                              <a class="btn btn-default btn-sm deletekml btn-delete" id="<?php if(isset($PendingUpdates->klm_trail_name)){echo $PendingUpdates->klm_trail_name;}?>">Delete</a>
                           <!-- Modal -->
                          <div class="modal fade" id="myModal<?php if(isset($PendingUpdates->klm_trail_name)){echo strip_tags(str_replace(' ', '', str_replace(':', '', $PendingUpdates->klm_trail_name)));}?>" role="dialog">
                            <div class="modal-dialog modal-sm">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  <h4 class="modal-title">Review Change</h4>
                                </div>
                                <div class="modal-body">
                                <div id="respMsg"></div>
                                <?php if(isset($PendingUpdates->trail_description)){echo $PendingUpdates->trail_description;}?>
                                <hr/>
                              <p><b>previous status : </b><?php if($PendingUpdates->previous_trail_status == 0){ 
                                    echo '<span class="OpenCls OpenStatus'.$PendingUpdates->klm_trail_name.' commencls" >Open</span>';
                                }else if($PendingUpdates->previous_trail_status == 1){
                                   echo '<span class="ClosedCls ClosedStatus'.$PendingUpdates->klm_trail_name.' commencls" >Closed</span>'; 
                                }else if($PendingUpdates->previous_trail_status == 2){
                                    echo '<span class="CautionCls CautionStatus'.$PendingUpdates->klm_trail_name.' commencls" >Caution</span>';
                                } ?></p>
                              <p><b>New status : </b><?php if($PendingUpdates->trail_status == 0){ 
                                        echo '<span class="OpenCls OpenStatus'.$PendingUpdates->klm_trail_name.' commencls" >Open</span>';
                                    }else if($PendingUpdates->trail_status == 1){
                                       echo '<span class="ClosedCls ClosedStatus'.$PendingUpdates->klm_trail_name.' commencls" >Closed</span>'; 
                                    }else if($PendingUpdates->trail_status == 2){
                                        echo '<span class="CautionCls CautionStatus'.$PendingUpdates->klm_trail_name.' commencls" >Caution</span>';
                                    } ?></p>
                                </div>
                                <div class="modal-footer">

<button class="approveCls"  onclick='changestatus2(1,"tbl_trail_report","status","trail_report_id","<?php if(isset($PendingUpdates->trail_report_id)){echo $PendingUpdates->trail_report_id;}?>",0,"tbl_kml_data_trail","flag","klm_trail_name","<?php if(isset($PendingUpdates->klm_trail_name)){echo $PendingUpdates->klm_trail_name;}?>","<?php echo $PendingUpdates->trail_status; ?>","previous_trail_status");'>Approve</button>

<button class="rejectCls" onclick='changestatus1(2,"tbl_trail_report","status","trail_report_id","<?php if(isset($PendingUpdates->trail_report_id)){echo $PendingUpdates->trail_report_id;}?>",1,"tbl_kml_data_trail","flag","klm_trail_name","<?php if(isset($PendingUpdates->klm_trail_name)){echo $PendingUpdates->klm_trail_name;}?>");'>Reject</button>
                                </div>
                              </div>
                            </div>
                          </div>
                           <!-- Modal -->

                         <?php }else{ ?>
                        <select id="update_status" name="update_status" onchange="changestatus('tbl_kml_data_trail','previous_trail_status','klm_trail_name','<?php if(isset($PendingUpdates->klm_trail_name)){ echo $PendingUpdates->klm_trail_name; }?>',this.value)" >
                            <option value="">Update Status</option>
                            <option value="0">Open</option>
                            <option value="1">Closed</option>
                            <option value="2">Caution</option>
                        </select>
                        <?php  } }else{ ?>
                        <select id="update_status" name="update_status"  onchange="changestatus('tbl_kml_data_trail','previous_trail_status','klm_trail_name','<?php if(isset($PendingUpdates->klm_trail_name)){ echo $PendingUpdates->klm_trail_name; }?>', this.value)" >
                            <option value="">Update Status</option>
                            <option value="0">Open</option>
                            <option value="1">Closed</option>
                            <option value="2">Caution</option>
                        </select>
                        <?php } ?>
                        </td>
                    </tr>   
            
               <?php  } }?> </tbody>
        </table>
        <div>
    </div>
</div>
</div>
 <div id="ajax_favorite_loddder" style="display:none;">
  <div align="center" style="vertical-align:middle;"> <img src="<?php echo base_url();?>assets/images/white_loader.svg" /> </div>
<!-- Warper Ends Here (working area) -->
<script type="text/javascript">


 
 function changestatus(table,field,wherefield,wherevalue,value){
  // var valueq = $(this).val();
  // alert(value)
   $.ajax({
    url: "<?php echo base_url();?>administrator/admin/changeTrailReportStatus",
    type: "post",
    data:({value:value,table:table,field:field,wherefield:wherefield,wherevalue:wherevalue}),
    success: function (data) {
        $('#responseMsg').html('<div class="alert alert-success"><?php echo 'Status change successfully'; ?> </div>').show().fadeOut(5000);
        location.reload();
        /*var classname = $("#commenID"+wherevalue).attr('class'); 
       if(value == 0){
            $("#commenID"+wherevalue).html('Open');
            $( "#commenID"+wherevalue ).removeClass(classname).addClass("OpenCls open0 commencls");
        }
        if(value == 1){
            $("#commenID"+wherevalue).html('Closed');
            $( "#commenID"+wherevalue).removeClass(classname).addClass("ClosedCls close1 commencls");
        }
        if(value == 2){
            $("#commenID"+wherevalue).html('Caution');
            $( "#commenID"+wherevalue).removeClass(classname).addClass("CautionCls caution2 commencls");
        } */

    }
    });
}

 function changestatus1(value,table,field,wherefield,wherevalue,value2,table2,field2,wherefield2,wherevalue2){


  
 // alert(value);var row = $(this).parent().parent(); 
    $.ajax({
    url: "<?php echo base_url();?>administrator/admin/changeTrailStatus",
    type: "post",
    data:({value:value,table:table,field:field,wherefield:wherefield,wherevalue:wherevalue,value2:value2,table2:table2,field2:field2,wherefield2:wherefield2,wherevalue2:wherevalue2}),
    success: function (data) {
        $('#respMsg').html('<div class="alert alert-success"><?php echo 'Status change successfully'; ?> </div>').show().fadeOut(5000);
        $('#PendingUpdates').show();
       $('#AllTrailData').hide();
        //location.reload();

    }
    });
}




 //function changestatus2(value,table,field,wherefield,wherevalue,value2,table2,field2,wherefield2,wherevalue2,wherefield3,wherevalue3,wherefield4,wherevalue4){

function changestatus2(value,table,field,wherefield,wherevalue,value2,table2,field2,wherefield2,wherevalue2,wherefield4,wherevalue4){

/*value = 1,
table = "tbl_trail_report",
field = "status",
wherefield = "trail_report_id",
wherevalue = "<?php if(isset($PendingUpdates->trail_report_id)){echo $PendingUpdates->trail_report_id;}?>",
value2 = 0,
table2 = "tbl_kml_data_trail",
field2 = "flag",
wherefield2 = "klm_trail_name",
wherevalue2  = "<?php if(isset($PendingUpdates->klm_trail_name)){echo $PendingUpdates->klm_trail_name;}?>",
wherefield3 = "<?php if(isset($PendingUpdates->trail_description)){echo $PendingUpdates->trail_description;}?>",
wherevalue3 = "trail_dscrptn",
wherefield4 = "<?php echo $PendingUpdates->trail_status; ?>",
wherevalue4 ="previous_trail_status"*/


  
 // alert(value);var row = $(this).parent().parent(); 
    $.ajax({
    url: "<?php echo base_url();?>administrator/admin/changeTrailStatusChange",
    type: "post",
    data:({value:value,table:table,field:field,wherefield:wherefield,wherevalue:wherevalue,value2:value2,table2:table2,field2:field2,wherefield2:wherefield2,wherevalue2:wherevalue2,wherevalue4:wherevalue4,wherefield4:wherefield4}),
    success: function (data) {
        $('#respMsg').html('<div class="alert alert-success"><?php echo 'Status change successfully'; ?> </div>').show().fadeOut(5000);
        $('#PendingUpdates').show();
       $('#AllTrailData').hide();
        location.reload();

    }
    });
}

</script>



<script type="text/javascript">
$(document).ready(function(){

    $(".ViewPendingUpdates").click(function(){
        $("#PendingUpdates").show();
        $("#AllTrailData").hide();
    }); 
    $(".AllData").click(function(){
        $("#PendingUpdates").hide();
        $("#AllTrailData").show();
    });
    var table = $('#toggleColumn-datatable').DataTable({
          "order": [[ 1, "desc" ]]
         
    });
    
      $('.sorting_asc').removeClass('sorting_asc');
   /*$('#select_all').on('click', function(){
     
      var rows = table.rows({ 'search': 'applied' }).nodes();
      $('input[type="checkbox"]',rows).prop('checked', this.checked);
     
    });*/
    

$("#select_all").change(function(){ 
    $(".mycheckbox").prop('checked', $(this).prop("checked"));  
    /*$(".select_checkbox").text("("+$('.mycheckbox:checked').length+")");  
    if ($('.mycheckbox:checked').length == $('.mycheckbox').length){      
      $(".selectall").text("Deselect all");
    } else{
      $(".selectall").text("Select all");
  }*/ 
});



    $('.mycheckbox').on('click',function(){

        if($('.mycheckbox:checked').length > 0){
            $('#select_all').prop('checked',true);
        }
        else{
            $('#select_all').prop('checked',false);
        }
    }); 
    $("#CloseALLTrails").click(function() {
     
      var rows = table.rows({ 'search': 'applied' }).nodes();
       var region_name ='<?php echo $_GET['state'];?>';
      var selectedItems = new Array();
      $("input:checkbox[name='inbox_id[]']:checked",rows).each(function() {
        selectedItems.push('"'+$(this).val()+'"');
      });
      var data = selectedItems.join(',');

    /*if(data !=''){
        $("#ajax_favorite_loddder").show();
    }else{
     $('#responseMsg1').html('<div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert">&times;</a>You must check at least one checkbox</div>').show().fadeOut(5000);
    }*/
      //alert(data);
      var inbox_ids = data;
      var inbox_action = 'CloseALLTrails';
      var buttonVal = 1
      $.ajax({
            type: "POST",
            url: '<?php echo  base_url().'administrator/admin/trail_acitities'; ?>',
            data: {inbox_ids:inbox_ids, inbox_action:inbox_action,region_name:region_name}, // serializes the form's elements.
            dataType: "JSON",
            success: function(data)
            {
               $("#ajax_favorite_loddder").hide();
               //$(".open0").html('Closed');
               //$( ".open0" ).removeClass( "OpenCls open0" ).addClass( "ClosedCls close1");
               location.reload();
            }
      });                

    });
      $("#OpenALLTrails").click(function() {
      var rows = table.rows({ 'search': 'applied' }).nodes();
      var region_name ='<?php echo $_GET['state'];?>';
      var selectedItems = new Array();
      $("input:checkbox[name='inbox_id[]']:checked",rows).each(function() {
        selectedItems.push('"'+$(this).val()+'"');
      });
      var data1 = selectedItems.join(',');

      var inbox_ids = data1;
      var inbox_action = 'OpenALLTrails';
     
     // $("#ajax_favorite_loddder").show();

      /*  if(data1 !=''){
           $("#ajax_favorite_loddder").show();
        }else{
           $('#responseMsg1').html('<div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert">&times;</a>You must check at least one checkbox</div>').show().fadeOut(5000);
        }*/
      $.ajax({
            type: "POST",
            url: '<?php echo  base_url().'administrator/admin/trail_acitities'; ?>',
            data: {inbox_ids:inbox_ids, inbox_action:inbox_action,region_name:region_name}, // serializes the form's elements.
            dataType: "JSON",
            success: function(data)
            {
                $("#ajax_favorite_loddder").hide();
                //$(".close1").html('Open');
               // $( ".close1" ).removeClass( "ClosedCls close1" ).addClass( "OpenCls open0");
               location.reload();
            }
      });                

    });

});

$("#seletAllClose").click(function() {
    // $("#ajax_favorite_loddder").show();
      var inbox_action = 'seletAllClose';
      $.ajax({
            type: "POST",
            url: '<?php echo  base_url().'administrator/admin/trail_acitities'; ?>',
            data: {inbox_action:inbox_action}, // serializes the form's elements.
            dataType: "JSON",
            success: function(data)
            {
               location.reload();
            }
      });                

    });
$("#seletAllOpen").click(function() {
     //$("#ajax_favorite_loddder").show();
      var inbox_action = 'seletAllOpen';
      $.ajax({
            type: "POST",
            url: '<?php echo  base_url().'administrator/admin/trail_acitities'; ?>',
            data: {inbox_action:inbox_action}, // serializes the form's elements.
            dataType: "JSON",
            success: function(data)
            {
                location.reload();
            }
      });                

    });
function trailDetailEdit(trail_name, oldtrail){
    var trailName = $('#trail_name'+trail_name+'').val();
    //alert(trailName);
    var trailDesc = $('#trailDesc'+trail_name+'').val();
     $.ajax({
            type: "POST",
            url: '<?php echo  base_url().'administrator/admin/trailDetailEdit'; ?>',
            data: {trailName:trailName, trailDesc:trailDesc,oldtrail:oldtrail}, // serializes the form's elements.
            dataType: "JSON",
            success: function(data)
            {
                $('#'+trail_name+'trailDesc'+trail_name).html(trailDesc);
                $('#'+trail_name+'trailname'+trail_name).html(trailName);
                $('#respMsgtrail').html('<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a>Trail detail Update successfully </div>').show().fadeOut(5000);
                var trailnamejoin = oldtrail.replace(/\s/g, '');
                //alert(trailnamejoin);
               // setTimeout($('#trailnameModal'+trailnamejoin).modal('hide'), 40000);
               location.reload();
       
            }
   }); 

}

$(document).on('click','.deletekml',function(){
 var del_id= $(this).attr('id');
 var tablename= 'tbl_kml_data_trail';
 var row = $(this).parent().parent();       
if (confirm('Do you want to remove this?'))
{

$.ajax({
        type:'POST',
        url:'<?php echo base_url();?>administrator/admin/deletefun',
        data:{ del_id:del_id, tablename:tablename },
        success: function(data){
          if(data == 1){
             //location.reload();
             row.remove();
          }
           }
        });
           return false;
        }else{
           return true;
        }

});

$(document).ready(function(){
   // code to get all records from table via select box
    $("#state").change(function() {
       $('#stateSubmit').trigger('click');
    });


    $('#toggleColumn-datatable123').dataTable( {
      "search": {
      "smart": false
      },
      "ordering": true,
      columnDefs: [{  orderable: false, targets:  "no-sort"}]
    });
});
</script> 