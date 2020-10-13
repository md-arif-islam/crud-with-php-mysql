<?php
    //---------------------------------------------
    session_start();
    $_userId = $_SESSION['id'] ?? 0;
    if ( $_userId ) {
        header( "location:index.php" );
        die();
    }
    //----------------------------------------------
    include_once "config.php";
    $connection = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
    if ( !$connection ) {
        echo mysqli_error( $connection );
        throw new Exception( "Cannot connect to database" );
    }
    //----------------------------------------------
    $userName = $_REQUEST['username'] ?? '';
    $password = $_REQUEST['password'] ?? '';

    if ( $userName && $password ) {
        $getUsers = "SELECT * FROM users WHERE user_name='{$userName}'";
        $result = mysqli_query( $connection, $getUsers );

        if ( mysqli_num_rows( $result ) > 0 ) {
            $user = mysqli_fetch_assoc( $result );
            $userId = $user['id'];
            $userName = $user['user_name'];
            $userPwd = $user['pwd'];

            if ( $userPwd == $password ) {
                $_SESSION['id'] = $userId;
                $_SESSION['username'] = $userName;
                header( "location:index.php" );
                die();
            } else {
                header( "location:auth.php?incorrect" );
            }
        } else {
            header( "location:auth.php?notFound" );
        }
    }
    //----------------------------------------------

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Form Example</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.0/milligram.css">
    <style>
        body {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="column column-60 column-offset-20">
                <h2>Login</h2>
            </div>
        </div>

        <div class="row" style="margin-top:100px;">
            <div class="column column-60 column-offset-20">
                <?php if ( isset( $_REQUEST['incorrect'] ) ) {
                        echo "<blockquote>Username and Password didn't match</blockquote>";
                    } elseif ( isset( $_REQUEST['notFound'] ) ) {
                        echo "<blockquote>User not Found !</blockquote>";
                }?>
                <form method="POST">
                    <label for=username>Username</label>
                    <input type="text" name='username' id="username" required>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                    <button type="submit" class="button-primary" name="login">Log In</button>
                </form>
            </div>
        </div>
    </div>
