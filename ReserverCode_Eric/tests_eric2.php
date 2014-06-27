<?php
if(isset($_POST['test'])){
	//var_dump($_SERVER);
	var_dump($_REQUEST);
}
?>
<form method="get" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
<input type="text" name="test[]" value="on" />
<input type="text" name="test[]" value="off" />
<select name="test[]" ><option>high</option/></select>
<input type="text" name="test[]" value="low" />
<button>go</button>
</form>