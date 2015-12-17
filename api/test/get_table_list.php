<?php 
include_once ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/header.html");
?>
<html>
	<title>get_table_list</title>
<body>
		
<h2>get_table_list</h2>

<?php
include ($_SERVER["DOCUMENT_ROOT"]."/web/api/test/widget/user_dropdown.php");
?>
<hr>
<form action="../casino/get_table_list.php" method="get" target="result">
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
            <td>sort field:</td>
            <td>
                <select name="sort">
                    <option value="0">creation_datetime</option>
                    <option value="1">cutoff_datetime</option>
                    <option value="2">title</option>
                    <option value="3">status</option>
                    <option value="4">amount</option>
                    <option value="5">host_username</option>
                    <option value="6">target</option>
                    <option value="7">player_amount</option>
                </select>
                
            </td>
        <tr>
            <td>order by:</td>
            <td>
                <select name="order">
                    <option value="asc">asc</option>
                    <option value="desc">desc</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>start:</td>
            <td><input name="start" value="0"></td>
        </tr>
        <tr>
            <td>end:</td>
            <td><input name="end" value="200"></td>
        </tr>
        <tr>
            <td>starttime:</td>
            <td><input name="starttime" value="2015-01-05 11:00:00" size="30"></td>
        </tr>
        <tr>
            <td>endtime:</td>
            <td><input name="endtime" value="2015-02-05 11:00:00" size="30"></td>
        </tr>
        <tr>
            <td>status:</td>
            <td><select name="status">
                   <option value="" selected=>All</option>
                    <option value="O" >Open</option>
                    <option value="F">Finished</option>
                    <option value="C">Canceled</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>participant:</td>
            <td><input name="participant" value="" size="10"></td>
        </tr>
        <tr>
            <td>target:</td>
            <td><input name="target" value="" size="10"></td>
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