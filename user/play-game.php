<?php
session_start();
include('../connection.php');

if(!isset($_SESSION['auth_token'])){
    header("Location: login.php");
    exit();
}

$game_id = intval($_GET['game_id'] ?? 0);

switch($game_id){
    case 1:
        include('games/game1-city-defender.php');
        break;
    case 2:
        include('games/game2-query-architect.php');
        break;
    case 3:
        include('games/game3-battle-arena.php');
        break;
    default:
        echo "Invalid Game";
}
?>