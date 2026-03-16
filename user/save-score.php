<?php
session_start();
include('../connection.php');

if(!isset($_SESSION['auth_token'])) exit();

$token=$_SESSION['auth_token'];
$now=date("Y-m-d H:i:s");

$stmt=$conn->prepare("SELECT id FROM users WHERE auth_token=? AND token_expiry>?");
$stmt->bind_param("ss",$token,$now);
$stmt->execute();
$user=$stmt->get_result()->fetch_assoc();

if(!$user) exit();

$user_id=$user['id'];
$game_id=intval($_POST['game_id']);
$score=intval($_POST['score']);

$insert=$conn->prepare("INSERT INTO game_scores(user_id,game_id,score) VALUES(?,?,?)");
$insert->bind_param("iii",$user_id,$game_id,$score);
$insert->execute();
?>