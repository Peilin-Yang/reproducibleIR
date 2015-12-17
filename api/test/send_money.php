<?php 
include_once ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/header.html");
?>
<html>
    <title>send_money</title>
<body>

<h2>send_money</h2>

<?php
include ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/user_dropdown.php");
?>
<hr>
<form action="../casino/send_money.php" method="post" target="result">
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
            <td>username:</td>
            <td><input name="username" id="username" value="yond"></td>
        </tr>
        <tr>
            <td>amount:</td>
            <td><input name="amount" id="amount" value="10"></td>
        </tr>
        <tr>
            <td>comment:</td>
            <td><input name="comment" id="comment" value="Thank you!" size="30"></td>
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