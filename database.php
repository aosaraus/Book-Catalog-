<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    $dsn = 'mysql:host=localhost;dbname=bookcatalog';
    $username = 'php';
    $password = 'php';
            
    try
    {
        $db = new PDO($dsn, $username, $password);
    } catch (PDOException $e) 
    {
        $error_message = $e->getMessage();
        include('database_error.php');
        exit();
    }
?>