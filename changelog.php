<!DOCTYPE HTML>
<marquee>
<div id="version">
------------------------Version Number 0.0.01:------------------<br>
•Created Form for the fields; Assigned to date Assigned, Priority level, excepted completion.<br>
•Created the pages that are required as stated in the meeting on 1/12/2017.<br>
•Pages; Assignment Form, In progress, Completed, and Graphs.<br>

-------------------------Version Number 0.1.00:-----------------<br>
•Functionality to many pages added including<br>
•Tables on the in progress page and the completed page.<br>
•Added more text fields and dynamic content to the webpage.<br>
•Added more new files to handle different user actions.<br>
•User error handling implemented on form page.<br>

-------------------------Version Number 0.5.00:-----------------<br>
•Form mostly in working order new revisions were made in order to accommodate user needs.<br>
•Some fields were removed and other fields added and merged.<br>


-------------------------Version Number 0.6.50:-----------------<br>
Major fixes and edits done to form page.<br>
•In progess page fixes<br>
•Completed page<br>
•All admin related features (request access on index.php - only allowed to request once so spamming can't happen, only allow edit if you're an admin, allow access table in admin.php)<br>
•Minor navbar changes (the timer messed up the navbar on 4:3 resolution screens)<br>
•Minor name changes of form inputs/table header names (CAD assigned to -> Currently Assigned)<br>

-------------------------Version Number 0.7.00:-----------------<br>
Progress
Form page + status: <font color="#6CFF00">Complete</font>.<br>
In_progress page + status: <font color="#EDDF00">Near completion.</font><br>
edit page + status: <font color="red">Work in progress.</font><br>
Graph page + status: <font color="red">Work in progress.</font><br>
Permission page + status: <font color="red">Work in progress.</font><br>

-------------------------Version Number 0.7.10:-----------------<br>
Added new permission Read&write<br>
PROGRESS ON READ&WRITE PERMISSION IS <font color="#6CFF00">Complete</font>.<br>
Changed query for permissions<br>

-------------------------Version Number 0.9.00:-----------------<br>
Current Build
</div>
</marquee>

<script type="text/javascript">
	var col=0;
function changeMarqueeColor() 
{
    if(col==0)
    {
        document.getElementById("version").style.color="red";
        col = 1;
    }
    else if(col == 1)
    {
        document.getElementById("version").style.color="blue";
        col = 2;
    }
    else if(col == 2)
    {
        document.getElementById("version").style.color="green";
        col = 0;
    }
    else if(col == 3)
    {
        document.getElementById("version").style.color="#F97777";
        col = 0;
    }

}
setInterval(changeMarqueeColor,300);

</script>