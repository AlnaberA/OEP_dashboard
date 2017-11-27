<?php
  require_once('secure.php');
  $person = $user['usid'];
  include('database.php');
  include('scripts/select_admins.php');

  $sql = "SELECT * FROM OEP_DASHBOARD 
          WHERE COMPLETED = '1'";
  $today = date("Y/m/d", strtotime("now"));

?>

<!DOCTYPE HTML>

<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=9">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <meta name="description" content="">
  <meta name="author" content="">

    <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.13/b-1.2.4/b-colvis-1.2.4/b-flash-1.2.4/b-html5-1.2.4/datatables.min.css"/>
  <link rel="stylesheet" href="css/style.css">

</head>

<body class="index_Body">

<!--Navigation bar-->
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <!--Navbar header-->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">OEP Dashboard</a>
      </div>

      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav">
                      <?
        if(in_array($person, $readWrite_id)||in_array($person, $admin_id)){
          ?>
          <li><a href="index.php">New Assignment</a></li>
        <?}else {?>
          <li><a href="index.php">Request Assignment</a></li>
        <?}?>
          <li><a href="in_progress.php">In Progress</a></li>
          <li class="active"><a href="completed.php">Completed</a></li>
          <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Metrics<span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="metrics.php">Metrics General</a></li>
                    <li><a href="metrics_group.php">Metrics Groups</a></li>
                  </ul>
          </li>
         <?if (in_array($person, $readWrite_id)||in_array($person, $admin_id)){?>
          <li><a href="readWrite.php">Permissions</a></li>
          <?}
              if (in_array($person, $admin_id)) { ?>
                  <li><a href="admin.php">Admin</a></li>
             <? } ?>


        </ul>
         <ul class="nav navbar-nav navbar-right">
             <ul class="nav navbar-nav navbar-brand">
                <li><div id="clockDisplay" class="clockStyle" style="font-size:100%;color:white;"></div></li>
             </ul>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
              <? echo $name ?><span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li style="padding-bottom: 5px"><center><?mcl_Header::logout_btn();?></center></li>

              </ul>
            </li>
          </ul>
      </div>
    </div>
  </nav>

<div class="row">
  <div class="col-md-1"></div>
  <div id="table" class="col-md-10">
  <center><h1>Standards Completed</h1><hr></center>
  <?
  $table = '
  <table class="table table-bordered table-striped" id="completed_table" align="center">
    <thead>
      <tr>
        <th>Assigned To</th>
        <th>Completed Date</th>
        <th>Expected Comp Date</th>
        <th>Section</th>
        <th>Issue Description</th>
        <th>Issue Description Search</th>
        <th>Priority</th>
        <th>Contact</th>
        <th>Resolution</th>
        <th>Resolution Search</th>
        <th>Date Recorded</th>
        <th>Currently Assigned</th>
        <th>CU Update</th>
        <th>DTE Group</th>
        <th>Status</th>
        <th></th>';

    if (in_array($person, $admin_id)) {
      $table .= '
        <th></th>';
    }

      $table .= '
        <th></th>';

      $table .= '
      </tr>
    </thead>
    <tbody>';

      while($row = $db->fetch($sql)){
        $table .= "<tr>";
          $table .= "<td>".$row['ASSIGNED_TO']."</td>";
          $table .= "<td>".$row['COMPLETED_DATE']."</td>";
          $table .= "<td>".$row['EXPECTED_COMPLETION']."</td>";
          $table .= "<td>".$row['SECTION_NUM']."</td>";

          //Double issue description columns for search indexing and displaying the popover (one of the columns is hidden in the datatable)
          $table .= "<td data-toggle='popover' data-container='body' title='Issue Description' data-content='".$row['ISSUE_DESC']."'>".substr($row['ISSUE_DESC'], 0, 50);
          if ($row['ISSUE_DESC'] == ''){
            $table .= "</td>";
          }

          else if ($row['ISSUE_DESC'] != '' && strlen($row['ISSUE_DESC']) >= 50){
            $table .= "...</td>";
          }

          else{
            $table .= "</td>";
          }
          $table .= "<td>".$row['ISSUE_DESC']."</td>";

          $table .= "<td>".$row['PRIORITY']."</td>";
          $table .= "<td>".$row['CONTACT']."</td>";

          //Double assignment columns for search indexing and displaying the popover (one of the columns is hidden in the datatable)
          $table .= "<td data-toggle='popover' data-container='body' title='Resolution' data-content='".$row['ASSIGNMENT']."'>".substr($row['ASSIGNMENT'], 0, 50);
          if ($row['ASSIGNMENT'] == ''){
            $table .= "</td>";
          }

          else if ($row['ASSIGNMENT'] != '' && strlen($row['ASSIGNMENT']) >= 50){
            $table .= "...</td>";
          }

          else{
            $table .= "</td>";
          }

          $table .= "<td>".$row['ASSIGNMENT']."</td>";

          $table .= "<td>".$row['DATE_RECORDED']."</td>";
          $table .= "<td>".$row['CAD_ASSIGNED_TO']."</td>";
          $table .= "<td>".$row['CU_UPDATE']."</td>";
          $table .= "<td>".$row['DTE_GROUP']."</td>";

          //if today is past the expected completion date and the expected completion date is not null
          if (( date("Y/m/d", strtotime($row['COMPLETED_DATE'])) > date("Y/m/d", strtotime($row['EXPECTED_COMPLETION'])) ) && $row['EXPECTED_COMPLETION'] != '' ){
            $table .= "<td style='background-color: #ffdddf'>LATE</td>";
          }

          //if expected completion date is null, make status null too
          else if ($row['EXPECTED_COMPLETION'] == ''){
            $table .= "<td></td>";
          }

          //otherwise, show on time
          else {
            $table .= "<td style='background-color: #e5ffdd'>ON TIME</td>";
          }

          $table .= "<td><button class='btn btn-info btn-sm changelog_btn' data-id='".$row['ID']."' data-changelog='".$row['CHANGELOG']."'><span class='glyphicon glyphicon-list-alt'></span></button></td>";
          
          if (in_array($person, $admin_id)) {
            $table .= "<td><a href='edit.php?id=".$row['ID']."' class='btn btn-warning btn-sm'><span class='glyphicon glyphicon-pencil'></span></a></td>";
          }
          if ($row['UPLOAD'] != null)
          {
            $table .="<td><a href='download.php?file=".$row['UPLOAD']."' class='btn btn-default btn-sm file_btn' id='file_btn'><span class='glyphicon glyphicon-download-alt'></span></a></td>";
          }
          else
          {
            $table .="<td><button class='btn btn-danger btn-sm disabled file_btn' id='file_btn'><span class='glyphicon glyphicon-ban-circle'></span></button></td>";
          }
        $table .= "</tr>";
      }

  $table .= '
    </tbody>
  </table>';

  echo $table;
  ?>
  </div>
  <div class="col-md-1"></div>
</div>

</body>

<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.13/b-1.2.4/b-colvis-1.2.4/b-flash-1.2.4/b-html5-1.2.4/datatables.min.js"></script>
<script type="text/javascript" language="javascript">
  $(document).ready(function(){
      $('#completed_table').DataTable({
        dom:
            "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        "bLengthChange":true,
        'aaSorting': [1, 'desc'],
        scrollY: 700,//controls height of scroll bar
        "paging": true,
        "pageLength": 50,
        buttons: [
                { extend: 'excel', 
                  className: 'excelButton btn btn-success', 
                  title: 'Standards Completed',
                  exportOptions: {
                        orthogonal: 'sort',
                        columns: [0,1,2,3,5,6,7,9,10,11,12,13]
                  } 
                }
            ],
        "columnDefs": [
            {
                "targets": [ 5 ],
                "visible": false
            },
            {
                "targets": [ 9 ],
                "visible": false
            }
        ],
        drawCallback: function(){
          $('[data-toggle="popover"]').popover({trigger: "hover focus", placement: "top", template: '<div class="popover" role="tooltip" style="max-width: 600px;"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"><div class="data-content"></div></div></div>'}); 
        }
      });

      $('#in_progress_table').css('width', '100%');

      //Initialize all the popovers to show on mouse hover and style the popover
      $('[data-toggle="popover"]').popover({trigger: "hover focus", placement: "top", template: '<div class="popover" role="tooltip" style="max-width: 600px;"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"><div class="data-content"></div></div></div>'}); 

      $(document).on('click', '.changelog_btn', function(){
          var changelog = this.getAttribute('data-changelog');

          $("#changelog").val(changelog);

          $("#changelogModal").modal('show');

        });

      $(document).on('click', '#complete_btn', function(e){
        var id = this.getAttribute('data-id');

        $.ajax({
          type: "POST",
          url: "scripts/complete_assignment.php",
          data:{
            id: id
          },
          success:function(data){
            alert('Succesfully completed contract');
            location.reload();
          }
        });
      });
  });
</script>

<div id="changelogModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Changelog</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <textarea id="changelog" name="changelog" class="form-control" rows="10" readonly></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
