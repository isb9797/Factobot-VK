<?php
$host="localhost";
$user="isb9797_factbot";
$password="sergeisokol3";
$db="isb9797_factbot";
mysql_connect($host, $user, $password) or die("MySQL сервер недоступен!".mysql_error());
mysql_select_db($db) or die("Нет соединения с БД".mysql_error());
?>