<?php
session_start();require_once '../includes/config.php';header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD']!=='POST'){echo json_encode(['success'=>false]);exit;}
try{
    $db=Database::getInstance();
    $title=sanitize($_POST['title']??'');$content=sanitize($_POST['content']??'');
    $target=sanitize($_POST['target']??'all');$priority=sanitize($_POST['priority']??'normal');
    $expires=sanitize($_POST['expires_at']??'')?:null;
    if(empty($title)||empty($content)){echo json_encode(['success'=>false,'message'=>'Title and content required']);exit;}
    $db->insert("INSERT INTO announcements(title,content,target,priority,posted_by,expires_at) VALUES(?,?,?,?,?,?)",[$title,$content,$target,$priority,$_SESSION['staff_id']??'STAFF',$expires]);
    echo json_encode(['success'=>true]);
}catch(Exception $e){echo json_encode(['success'=>false,'message'=>$e->getMessage()]);}
