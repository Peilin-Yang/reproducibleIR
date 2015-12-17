<?php 
include_once ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/header.html");
?>
<html>
	<title>create_table</title>
<body>
		
<h2>create_table</h2>

<?php
include ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/user_dropdown.php");
?>
<hr>
<form action="../casino/create_table.php" method="post" target="result">
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
            <td class="label">赌局名称:</td>
            <td>
                <input name="title" value=""></td>
	</tr>
	<tr>
            <td class="label">描述:</td>
            <td>
		<textarea name="description" rows="3" cols="50" 
                          style="align:left" ></textarea>		
            </td>
	</tr>
	<tr>
            <td class="label">马:</td>
            <td><input name="target" value="yond" ></td>
	</tr>
	<tr>
            <td class="label">最小赌注:</td>
            <td><input name="minimumbet" value="20"></td>
	</tr>
	<tr>
            <td class="label">开始:</td>
            <td><input name="begintime" value="2015-01-15 08:00:00" size="30"></td>
	</tr>
	<tr>
            <td class="label">截止:</td>
            <td><input name="endtime" value="2015-01-25 09:00:00" size="30"></td>
	</tr>
	<tr>
            <td class="label">活动日期:</td>
            <td><input name="eventtime" value="2015-01-30 09:00:00"></td>
	</tr>		
	<tr>
            <td class="label">活动地点:</td>
            <td><input name="eventlocation" value="New York, NY" size="20"></td>
		</tr>		
	</table>
	<table>
            <tr><th colspan="2">选择</th></tr>
		<tr>
                    <td>1</td>
                    <td><input name="options[]" value="choice 1" size="50"></td>
		</tr>
		<tr>
                    <td>2</td>
                    <td><input name="options[]" value="choice 2" size="50"></td>
		</tr>
		<tr>
                    <td>3</td>
                    <td><input name="options[]" value="choice 3" size="50"></td>
		</tr>
		<tr>
                    <td>4</td>
                    <td><input name="options[]" value="choice 4" size="50"></td>
		</tr>
		<tr>
                    <td>5</td>
                    <td><input name="options[]" value="choice 5" size="50"></td>
		</tr>
		<tr>
                    <td>6</td>
                    <td><input name="options[]" value="choice 6" size="50"></td>
		</tr>
	</table>

<div>
    <input type="submit" name="save" value="save">				
</div>
			
</form>
<?php
    include ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/footer.html");    
?>		
</body>
</html>