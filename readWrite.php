<?php
  require_once('secure.php');
  $person = $user['usid'];
  include('database.php');
  include('scripts/select_admins.php');

  $sqlReadWrite = "SELECT * FROM OEP_DASHBOARD_ADMINS WHERE PERMISSIONS = 'RW'";
  $sql = "SELECT * FROM OEP_DASHBOARD WHERE STATUS = 'REQUESTED'"; 
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

	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">

<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
          <li><a href="index.php">New Assignment</a></li>
          <li><a href="in_progress.php">In Progress</a></li>
          <li><a href="completed.php">Completed</a></li>
          <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Metrics<span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="metrics.php">Metrics General</a></li>
                    <li><a href="metrics_group.php">Metrics Groups</a></li>
                  </ul>
          </li>

      	 <?if (in_array($person, $readWrite_id)||in_array($person, $admin_id)){?>
          <li class="active"><a href="readWrite.php">Permissions</a></li>
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
  <div class="col-md-3"></div>
  <div class="col-md-6">
    <div class="page-header"> 
      <h3>Permissions</h3>
    </div>
    <table class="table table-bordered" id="info_table2">
      <thead>
        <tr>
          <th>Name</th>
          <th>User ID</th>
          <th>Permissions</th>
          <?if(in_array($person, $admin_id)) { ?> 
          <th></th> <? }?>
         </tr>
      </thead>
    <tbody>
      <?
      while ($row = $db->fetch($sqlReadWrite)){ ?>
            <tr>
            <? echo "<td>".$row['NAME']."</td>" ; ?>
            <? echo "<td>".$row['USER_ID']."</td>" ; ?>
            <? echo "<td>".$row['PERMISSIONS']."</td>" ; ?>
            <?if(in_array($person, $admin_id)) { ?> 
            <? echo "<td class='view' style='width: 10%;'><button id='delete_user_btn' class='btn btn-danger btn-xs' data-userid=".$row['USER_ID']."><span class='glyphicon glyphicon-remove'></span></button></a></td>" ; } ?>

            </tr>
      <? } ?>
          </tbody>  
        </table><br>

        <?if(in_array($person, $admin_id)) { ?> 
        <center><button type="button" class="btn btn-primary btn-lg" data-toggle='modal' data-target='#AddUser'>Add User</button></center>
        <? } ?>
  </div>
  <div class="col-md-3"></div>
</div><br>

<div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
    <div class="page-header"> 
      <h3>Requested Assignments</h3>
    </div>
    <table class="table table-bordered" id="request_table">
      <thead>
        <tr>
          <th>Contact</th>
          <th>Section</th>
          <th>Issue Description</th>
          <th></th>
          <th></th>
         </tr>
      </thead>
    <tbody>
      <?
      while ($row_requested = $db->fetch($sql)){ ?>
            <tr>
            <? echo "<td>".$row_requested['CONTACT']."</td>" ; ?>
            <? echo "<td>".$row_requested['SECTION_NUM']."</td>" ; ?>
            <? echo "<td>".$row_requested['ISSUE_DESC']."</td>" ; ?>
            <? echo "<td style='width: 10%;'><button class='btn btn-success btn-sm approve_assignment_btn' data-userid=".$row_requested['ID'].">Approve</button></a></td>"; ?>
            <? echo "<td style='width: 10%;'><button class='btn btn-danger btn-sm deny_assignment_btn' data-userid=".$row_requested['ID'].">Deny</button></a></td>"; ?>
            </tr>
      <? } ?>
          </tbody>
        </table>
  </div>
  <div class="col-md-3"></div>
</div>


<!--Scripts Need to be moved to a different javascript file-->
  <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>

   <script>
    $(document).ready(function(){
      $(document).on('click', '#add_user_btn', function(){
        var name = $('input[name=name]').val();
        var user_id = $('input[name=user_id]').val();
        var permissions = $("#permission option:selected").text();

        $.ajax({
          type:"POST",
          url:"scripts/add_readWrite.php",
          data: {
            name: name,
            user_id: user_id,
            permissions: permissions
          },
          success:function(data){
            alert('User succesfully added');
            location.reload();
          }
        });
      });

      $(document).on('click', '#delete_user_btn', function(){
        var user_id = this.getAttribute('data-userid');

        $.ajax({
          type:"POST",
          url:"scripts/remove_readWrite.php",
          data: {
            user_id: user_id
          },
          success:function(data){
            alert('User succesfully deleted');
            location.reload();
          }
        });
      });

      $(document).on('click', '.approve_assignment_btn', function(){
        var user_id = this.getAttribute('data-userid');

        $.ajax({
          type:"POST",
          url:"scripts/approve_assignment.php",
          data: {
            user_id: user_id
          },
          success:function(data){
            alert('Requested assignment succesfully approved.');
            location.reload();
          }
        });
      });

       $(document).on('click', '.deny_assignment_btn', function(){
        var user_id = this.getAttribute('data-userid');

        $.ajax({
          type:"POST",
          url:"scripts/deny_assignment.php",
          data: {
            user_id: user_id
          },
          success:function(data){
            alert('Requested assignment has been denied.');
            location.reload();
          }
        });
      });
    });
  </script>

  <script>
      $(document).ready(function(){
          $('#info_table2').DataTable({
            "bLengthChange": false,
            "info": false,
            "scrollY": "200px",
            "scrollCollapse": true,
            "paging": false,
            "aaSorting": []
          });

          $('#info_table2').css('width', '100%');

          $('#request_table').DataTable({
            "bLengthChange": false,
            "info": false,
            "scrollY": "200px",
            "scrollCollapse": true,
            "paging": false,
            "aaSorting": []
          });

          $('#request_table').css('width', '100%');

      });
  </script>
</body>

<div id="AddUser" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" style="margin-bottom: 0px;">Add User</h4>
      </div>
      <div class="modal-body">
        <form method="post" enctype="multipart/form-data" id="user_form">
          <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
          </div>
          <div class="form-group">
            <label for="user_id">ID:</label>
            <input type="text" class="form-control" id="user_id" name="user_id" placeholder="DTE ID">
          </div>
          <div class="form-group">
            <label for="permission">Permissions:</label>
            <select class="form-control" id="permission" name="permission">
              <option>RW</option>
            </select>
          </div>
        </form>
        <button id="add_user_btn" class="btn btn-primary btn-sm">Add User</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


</html>