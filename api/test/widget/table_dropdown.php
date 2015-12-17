<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/web/api/include/dao.php");
$table_list = $dao->get_casino_table_list(1, "kf9qds02b1atzpj4yu8cm5x7henigv6wlro3", "0", "desc");
?>
<b>select table:</b>
<script>

function setTable() {
    var obj = document.getElementById("tableSelect")
    var index = obj.selectedIndex;
    var tid = obj.options[index].value;
    
    document.getElementById("tid").value=tid;
}
</script>
<select id="tableSelect" onchange="setTable();">
    <?php
        foreach ($table_list as $table) {
            echo "<option value='".$table["casino_table_id"]."'>".$table["title"]."-".$table["status"]."-".$table["amount"]."-".$table["begin_datetime"]."/".$table["cutoff_datetime"]."</option>";
        }
    ?>
</select>
<br>
