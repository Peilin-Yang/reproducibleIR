<?php 
include_once ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/header.html");
?>
<html>
	<title>get_user_log</title>
<body>
		
<h2>get_user_log</h2>

<?php
include ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/user_dropdown.php");
?>
<hr>
<form action="../casino/get_user_log.php" method="get" target="result">
    <table>
        <tr>
            <td>uid</td>
            <td><input name="uid" id = "uid" value=""></td>
        </tr>
        <tr>
            <td>apikey</td>
            <td><input name="apikey" id="apikey" value="" size="50"></td>
        </tr>
        <tr>
            <td>username:</td>
            <td><input name="username" value="hqgrow"></td>
        </tr>
        <tr>
            <td>start:</td>
            <td><input name="start" value="0"></td>
        </tr>
        <tr>
            <td>end:</td>
            <td><input name="end" value="5"></td>
        </tr>
        <tr>
            <td>starttime:</td>
            <td><input name="starttime" value="2015-01-15 11:00:00" size="30"></td>
        </tr>
        <tr>
            <td>endtime:</td>
            <td><input name="endtime" value="2015-02-05 11:00:00" size="30"></td>
        </tr>
        
    </table>
<div>
	<input type="submit" name="get" value="get">
</div>
			
</form>
<?php
    include ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/footer.html");    
?>		
</body>
</html>