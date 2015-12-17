<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/web/api/include/dao.php");
$user_list = $dao->get_user_list(1, "kf9qds02b1atzpj4yu8cm5x7henigv6wlro3");
?>
<b>select "test as" user:</b>
<script>
var apiKeyLookup = {};

function setUser() {
    var obj = document.getElementById("userSelect")
    var index = obj.selectedIndex;
    var uid = obj.options[index].value;
    var apikey = apiKeyLookup[uid];
    
    document.getElementById("uid").value=uid;
    document.getElementById("apikey").value=apikey;
}
</script>
<select id="userSelect" onchange="setUser();">
    <?php
        foreach ($user_list as $user) {
            echo "<option value='".$user["uid"]."'>".$user["username"]."/".$user["admin"]."/".$user["casino_table_creator"]."/".$user["coin"]."</option>";
            echo "<script>apiKeyLookup['".$user["uid"]."'] = '".$user["apikey"]."'</script>";
        }
    ?>
    
    
</select>
<br>
