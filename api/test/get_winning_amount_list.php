<?php 
include_once ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/header.html");
?>
<html>
	<title>get_winning_amount_list</title>
<body>
		
<h2>get_winning_amount_list</h2>

<?php
include ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/user_dropdown.php");
?>
<hr>
<form action="../casino/get_winning_amount_list.php" method="get" target="result">
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
            <td>limit</td>
            <td><input name="limit" id="limit" value="3" size="10"></td>
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