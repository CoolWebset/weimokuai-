<?php
	$people = pdo_fetch("SELECT * FROM " . tablename('moneygo_member') . " WHERE uniacid= '{$_W['uniacid']}' AND from_user= '{$_W['fans']['from_user']}'");
	include $this->template('prodata');
?>