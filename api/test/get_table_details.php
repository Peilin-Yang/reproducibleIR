<?php 
include_once ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/header.html");
?>
<html>
	<title>get_table_details</title>
<body>
		
<h2>get_table_details</h2>

<?php
include ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/user_dropdown.php");
include ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/table_dropdown.php");
?>
<hr>
<form action="../casino/get_table_details.php" method="get" target="result">
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
            <td>table id</td>
            <td><input name="tid" id="tid" value=""></td>
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