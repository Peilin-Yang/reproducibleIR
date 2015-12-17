<?php 
include_once ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/header.html");
?>
<html>
    <title>delete_table</title>
<body>
		
<h2>delete_table</h2>

<?php
include ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/user_dropdown.php");
include ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/table_dropdown.php");
?>

<form action="../casino/delete_table.php" method="post" target="result">
    <table>
        <tr>
            <td>uid:</td>
            <td><input name="uid" id="uid" value=""></td>
        </tr>
        <tr>
            <td>apikey:</td>
            <td><input name="apikey" id="apikey" value="" size="50"></td>
        </tr>
        <tr>
            <td>table id:</td>
            <td><input name="tid" id="tid" value=""></td>
        </tr>
    </table>
    
    <div>
	<input type="submit" name="save" value="submit">&nbsp;&nbsp;		
    </div>
    			
</form>
<?php
    include ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/footer.html");    
?>
</body>
</html>