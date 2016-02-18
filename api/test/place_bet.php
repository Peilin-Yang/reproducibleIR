<?php 
include_once ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/header.html");
?>
<html>
	<title>place_bet</title>
<body>
	 		
<h2>place_bet</h2>

<?php
include ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/user_dropdown.php");
include ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/table_dropdown.php");
?>
<hr>
<form action="../casino/place_bet.php" method="post" target="result">
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
    <table border="1">
        <tr><th>选项</th><th>下注</th></tr>
        <tr>
            <td>1</td>
            <td><input name="bets[]" value="" size="5" style="text-align: right"/></td>
        </tr>
        <tr>
            <td>2</td>
            <td><input name="bets[]" value="" size="5" style="text-align: right"/></td>
        </tr>
        <tr>
            <td>3</td>
            <td><input name="bets[]" value="" size="5" style="text-align: right"/></td>
        </tr>
        <tr>
            <td>4</td>
            <td><input name="bets[]" value="" size="5" style="text-align: right"/></td>
        </tr>
        <tr>
            <td>5</td>
            <td><input name="bets[]" value="" size="5" style="text-align: right"/></td>
        </tr>
        <tr>
            <td>6</td>
            <td><input name="bets[]" value="" size="5" style="text-align: right"/></td>
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