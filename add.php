<?php
session_start();
function isAdmin() {
    return ( 1 == $_SESSION['id'] );
}

function isEditor() {
    return ( 2 == $_SESSION['id'] );
}

function hasPrivilege() {
    return ( isAdmin() || isEditor() );
}

include_once "config.php";

$connection = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
if ( !$connection ) {
    echo mysqli_error( $connection );
    throw new Exception( "Cannot connect to database" );
}

if ( isAdmin() || isEditor() ) {
    if ( isset( $_REQUEST['submit'] ) ) {
        $fname = $_REQUEST['fname'] ?? '';
        $lname = $_REQUEST['lname'] ?? '';
        $roll = $_REQUEST['roll'] ?? 0;

        $query = "INSERT INTO students (fname,lname,roll) VALUES ('{$fname}','{$lname}','{$roll}') ";
        mysqli_query( $connection, $query );
        header( "location:index.php?successAdd" );

    }

    if ( isset( $_REQUEST['update'] ) ) {
        $id = $_REQUEST['id'] ?? 0;
        $fname = $_REQUEST['fname'] ?? '';
        $lname = $_REQUEST['lname'] ?? '';
        $roll = $_REQUEST['roll'] ?? 0;

        $query = "UPDATE students SET fname='{$fname}',lname='{$lname}',roll='{$roll}' WHERE id={$id} ";
        mysqli_query( $connection, $query );
        header( "location:index.php?successUpdate" );

    }
}
