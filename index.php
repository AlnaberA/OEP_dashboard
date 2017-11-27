<?php
  require_once('secure.php');
  $person = $user['usid'];
  include('database.php');
  include('scripts/select_admins.php');

  $sql_requested_email = "SELECT EMAIL_ID FROM EMPLOYEE@MAXIMO WHERE USER_ID = '{$person}'";

  $email = $prod->fetch($sql_requested_email);

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
  <link rel="stylesheet" href="css/style.css">

<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<style>
/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    -webkit-animation-name: fadeIn; /* Fade in the background */
    -webkit-animation-duration: 0.4s;
    animation-name: fadeIn;
    animation-duration: 0.4s
}

/* Modal Content */
.modal-content {
    position: fixed;
    bottom: 0;
    background-color: #fefefe;
    width: 100%;
    -webkit-animation-name: slideIn;
    -webkit-animation-duration: 0.4s;
    animation-name: slideIn;
    animation-duration: 0.4s
}

/* The Close Button */
.close {
    color: white;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.modal-header {
    padding: 2px 16px;
    background-color: #000000;
    color: white;
}

.modal-body {padding: 2px 16px;}

.modal-footer {
    padding: 2px 16px;
    background-color: #000000;
    color: white;
}

/* Add Animation */
@-webkit-keyframes slideIn {
    from {bottom: -300px; opacity: 0} 
    to {bottom: 0; opacity: 1}
}

@keyframes slideIn {
    from {bottom: -300px; opacity: 0}
    to {bottom: 0; opacity: 1}
}

@-webkit-keyframes fadeIn {
    from {opacity: 0} 
    to {opacity: 1}
}

@keyframes fadeIn {
    from {opacity: 0} 
    to {opacity: 1}
}
</style>


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
          <li class="active"><a href="index.php">New Assignment</a></li>
        <?}else {?>
          <li class="active"><a href="index.php">Request Assignment</a></li>
        <?}?>
        
          <li><a href="in_progress.php">In Progress</a></li>
          <li><a href="completed.php">Completed</a></li>


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
      </div></div>
    </div>
  </nav>


<!--Form div-->
<? if (in_array($person, $readWrite_id)||in_array($person, $admin_id)) { ?>
<div class="container" style="padding-bottom: 10%">
  <hr/><h1 align="center"><b>Create an Assignment</b></h1><hr/>

  <!--Create and assignment form-->
  <form id="assignment_form" method="post" action="scripts/submit_assignment.php" enctype="multipart/form-data">

        <div class="form-group">
          <label for="assigned_to">Assigned To:</label> <br>
          <input type="text" id="assigned_to" name="assigned_to" class="form-control" placeholder="Enter a name"><br>
        </div>

        <div class="form-group">
          <label for="date_assigned">Date Assigned:</label> <br>
          <input type="text" id="date_assigned" name="date_assigned" class="form-control" placeholder="Select a date to be assigned"><br>
        </div>


        <div id="priorty-select" class="form-group">
          <label for="pLevel">Priority Level:</label>
          
          <!-- Trigger/Open The Modal -->
          <button id="pInfo_Modal_Btn" class="btn btn-info" type="button">Level Information</button><br>

            <input type="radio" name="pLevel" class="pLevel" id="pLevel-1" value="2 weeks"> <label for="pLevel-1">Level 1</label><br>
            <input type="radio" name="pLevel" class="pLevel" id="pLevel-2" value="3 weeks"> <label for="pLevel-2">Level 2</label><br>
            <input type="radio" name="pLevel" class="pLevel" id="pLevel-3" value="4 weeks"> <label for="pLevel-3">Level 3</label><br>
            <input type="radio" name="pLevel" class="pLevel" id="pLevel-4" value="45 days"> <label for="pLevel-4">Level 4</label><br>
            <input type="radio" name="pLevel" class="pLevel" id="pLevel-custom" value="Custom Priority"> <label for="pLevel-custom">Custom</label>
        </div>


         <div class="form-group">
          <label for="date_expected">Expected Completion:</label> <br>
          <input type="text" id="date_expected" name="date_expected" class="form-control" value = "" placeholder="Expected date of Completion." readonly><br>
        </div>

        <div class="form-group">
          <label for="contact">Contact:</label><br>
          <input type="text" id="contact" name="contact" class="form-control" placeholder="Name"><br>
        </div>

       <div class="form-group">
          <label for="sect_num">Section Number:</label> <br>
          <input type="text" id="sect_num" name="sect_num" class="form-control" placeholder="Enter a section number"><br>
        </div>

       <div class="form-group">
          <label for="issue_descript">Issue Description:</label><br>
          <textarea id="issue_descript" name="issue_descript" class="form-control" style="height: 100px; width: 100%;" maxlength="4000"></textarea> 
          <div class="character-counter-wrapper"><span class="character-counter counter">0</span> characters typed</div>
          <div class="characters-remaining-wrapper"><span class="characters-remaining counter">4000</span> characters left</div>
      </div>
      
      <div class="form-group">
        <label>CU Update Required?</label>
        <input type="radio" name="cu_radio" id="cu_radio_yes" value="Yes"/> YES
        <input type="radio" name="cu_radio" id="cu_radio_no" value="No"/> NO<br>
      </div>
      
        <div class="form-group">
          <label for="assignment">Resolution:</label> <br>
          <textarea id="assignment" name = "assignment" class="form-control" style="height: 100px; width: 100%;"></textarea>
            <div class="character-counter-wrapper"><span class="character-counter counter">0</span> characters typed</div>
            <div class="characters-remaining-wrapper"><span class="characters-remaining counter">4000</span> characters left</div>
        </div>

       <div class="form-group">
          <label for="date_recorded">Date Recorded:</label> <br>
          <input type="text" id="date_recorded" name="date_recorded" class="form-control" placeholder="MM/DD/YYYY"><br>
        </div>

      <div class="form-group">
          <label for="cad_assigned_to">Currently Assigned:</label> <br>
          <input type="text" id="cad_assigned_to" name="cad_assigned_to" class="form-control" placeholder="Name">
      </div>

       <div class="form-group">
          <label for="group">Group:</label> <br>
        <select style="width: 50%; " class="form-control" id="group" name="group">
          <option value="">Please Select a Group</option>
          <option value="DCOE">Service Planners Design Center of Execellence</option>
    			<option value="SO">Service Operations</option>
    			<option value="P&D">Planning and Design</option>
    			<option value="OEP">OEP</option>
          <option value="Other">Other</option>
		</select>
      </div>

      <!-- File Input -->
      <input type="file" name="file" id="file" style="visibility: hidden;">
      <label>Choose a File: </label>
      <div class="input-append form-inline form-group input-group" style="width: 30%;">
        <input type="text" id="subfile" name="subfile" class="form-control" aria-describedby="browse_btn" readonly>
        <span class="input-group-btn" id="browse_btn"><a class="btn btn-primary" onclick="$('#file').click();">Browse</a></span>
      </div><br><br>
      

      <input type="submit" value="Submit Form" class="btn btn-success">
      
  </form>
</div>

<!-- The modal for priorty level information-->
<div id="pInfo_Modal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close">&times;</span>
      <h2>Prespecifications to Prioritize Equipment/Specification Issues</h2>
    </div>
    <div class="modal-body">
         <p>The Gives information about the different priorty levels:</p>            
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Priority 1 (2 Weeks)</th>
        <th>Priority 2 (3 Weeks)</th>
        <th>Priority 3 (4 Weeks)</th>
        <th>Priority 4 (45 days)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Direct safety issue (Field need resolution to complete work). Example: Crew needs clarification on how to install mastic cap in the field.</td>
        <td>EI Sketches from P&D or the OPE group.</td>
        <td>Spec Updates requests that come from SO/P&D.</td>
        <td>Equipment/hardware that has experienced multiple defects. This will require the Engineer to start an investigation to collect data from the Service Centers and contact the manufacturer.</td>
      <tr>
        <td>Near Miss (Immediate answer field concerns). Example: Crew went to operate fuse cutout where it failed during operation.</td>
        <td>Problems that occur with defective equipment that will require the Engineer to investigate problem and contact the Service Center personnel and the manufacturer.</td>
        <td>SAP Updates - creation of item master numbers or modification of existing item master description.</td>
        <td>CU Update - Detail is created & completed by the OEP group requires the EBS group to create or update the CU before the spec. is published online and communicated to the SO and P&D.</td>
      </tr>
      <tr>
        <td>Construction needing immediate support. (Example: Material not available to complete a job in the field.)</td>
        <td>Problems with specification details that prevents the planner or field personnel from completing their task.</td>
        <td>NESC Compliance - Review of  Section/Detail in the OH/UG Spec book that requires Engineer to review the 2012 NESC book.</td>
        <td>Equipment evaluation with no impact on safety.</td>
      </tr>
    </tbody>
  </table>
    </div>
    <div class="modal-footer" style="text-align:left">
      <h3>"The boss wants it now."</h3>
    </div>
  </div>

</div>
<!--Modal end-->

<!--People requesting an assignment -else-->
<? } //EndIf
else{ 
  ?>
      <div class="col-md-4 col-sm-2"></div>
      <div class="col-md-4 col-sm-8">
        <h2 style="text-align: center;"><b>Request Assignment</b></h2><hr>
        <h4>This form will request an assignment for approval or denial.</h4><br>
        <form id="request_form" method="post" action="scripts/request_assignment.php">
          <div class="form-group">
            <label for="contact">Contact:</label> <br>
            <input type="text" id="contact" name="contact" value="<?echo $name?>" class="form-control" readonly><br>
          </div>

          <div class="form-group">
            <label for="email">Email:</label> <br>
            <input type="text" id="email" name="email" value="<?echo $email[EMAIL_ID]?>" class="form-control" readonly><br>
          </div>

          <div class="form-group">
            <label for="section">Section:</label> <br>
            <input type="text" id="section" name="section" class="form-control" placeholder="Section Number"><br>
          </div>

          <div class="form-group">
            <label for="issue">Issue Description:</label> <br>
            <textarea id="issue" name="issue" class="form-control" placeholder="Enter an issue description" required></textarea> <div class="character-counter-wrapper"><span class="character-counter counter">0</span> characters typed</div>
            <div class="characters-remaining-wrapper"><span class="characters-remaining counter">4000</span> characters left</div><br>
          </div>

          <center><button type="submit" id="submit" class="btn btn-primary btn-lg" style="font-size: 12pt;">Request Assignment</button><br></center>
        </form>
      </div>
      <div class="col-md-4 col-sm-2"></div>
  <? } ?>

</body>


  <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="js/jquery-ui-datepicker.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>  <!--Jquery UI-->
  <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
  <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
  <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script> 

<!--Script controls the radio buttons and plugins-->
<script>
  $(function(){
    $("#pLevel-custom,#pLevel-1,#pLevel-2,#pLevel-3,#pLevel-4").change(function(){
        if($("#pLevel-custom").is(":checked")){
            $("#date_expected").removeAttr("readonly");
            $( "#date_expected" ).datepicker();
            $("#date_expected").focus();
        }
        else
        {
          $("#date_expected").attr("readonly",true);
        }
    });
  });

  $( function() {
    $( "#date_recorded" ).datepicker();
    $( "#date_assigned" ).datepicker();
  });
  

</script>


<!--pInfo Modal Scripts-->
<script>
// Get the modal
var modal = document.getElementById('pInfo_Modal');

// Get the button that opens the modal
var btn = document.getElementById("pInfo_Modal_Btn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<script type="text/javascript" language="javascript">


$(document).ready(function(){

    $('#file').change(function(){
      $('#subfile').val($(this).val());
    });

    $('#showHidden').click(function(){
      $('#file').css('visibility', 'visible');
    });

    //Radio button input function
    $('[name=pLevel]').click(function()  {
        $('[name=date_expected]').val($(this).val());
    });


    if($('.character-counter').length > 0){
    var maximumCharacters = 4000;
    
    $('textarea').keyup(function(){
      var $this = $(this);
      var $parentElement = $this.parent();
      var $characterCounter = $parentElement.find('.character-counter');
      var $charactersRemaining = $parentElement.find('.characters-remaining');
      
      var typedText = $this.val();
      var textLength = typedText.length;
      var charactersRemaining = maximumCharacters - textLength; 
      
      // chop the text to the desired length
      if(charactersRemaining <= 0){
        $this.val(typedText.substr(0, maximumCharacters));
        charactersRemaining = 0;
        textLength = maximumCharacters;
      }      
      if($characterCounter.length){
        $characterCounter.text(textLength);
      }
      if($charactersRemaining.length){
        $charactersRemaining.text(charactersRemaining);
      }
    });
  }
  
//$('#group').val()

    $('#assignment_form').validate({
      ignore: [],
      rules: {
          group: {
              required: true
          }
      },

      highlight: function (element) {
          $(element).closest('.form-group').removeClass('has-success').addClass('has-error').addClass('remove-margin');
      }
  });

    //request assignment issue description required
    $('#request_form').validate({
      ignore: [],
      rules: {
          issue: {
              required: true
          }
      },
      highlight: function (element) {
          $(element).closest('.form-group').removeClass('has-success').addClass('has-error').addClass('remove-margin');
      }
  });

});
</script>
</html>