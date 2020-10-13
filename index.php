<?php
    //--------------------------------------
    session_start();
    $_userId = $_SESSION['id'] ?? 0;

    function isAdmin() {
        return ( 1 == $_SESSION['id'] );
    }

    function isEditor() {
        return ( 2 == $_SESSION['id'] );
    }

    function hasPrivilege() {
        return ( isAdmin() || isEditor() );
    }

    error_reporting( 0 );
    //--------------------------------------
    include_once "config.php";
    $connection = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
    if ( !$connection ) {
        echo mysqli_error( $connection );
        throw new Exception( "Cannot connect to database" );
    }
    $task = $_REQUEST['task'] ?? 'report';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.0/milligram.css">
    <title>CURD</title>
    <style>
        body {
            margin-top: 50px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="column column-60 column-offset-20">
                <h2>CRUD</h2>
                <h5>Created By MD Arif Islam</h5>
                <!-- ------------------- Nav --------------------- -->
                <p>
                    <div class="float-left">
                        <a href="index.php?task=report">All Students</a>
                        <?php if ( hasPrivilege() ) {?>
                        <a href="index.php?task=add">Add New Students</a>
                        <?php }?>
                    </div>
                    <div class="float-right">
                        <?php if ( !$_userId ) {?>
                            <a href="auth.php">Log In</a>
                        <?php } else {?>
                            <a href="logout.php">Log Out(<?php echo ucwords( $_SESSION['username'] ); ?>)</a>
                        <?php }?>
                    </div>
                </p>
                <!-- ------------------- Nav --------------------- -->
            </div>
        </div>
            <?php if ( isset( $_REQUEST['successAdd'] ) ) {?>
                <div class="row">
                    <div class="column column-60 column-offset-20">
                        <p style="color: green;font-weight: bold;">Successfully Add a Student</p>
                    </div>
                </div>
            <?php }?>

            <?php if ( isset( $_REQUEST['successUpdate'] ) ) {?>
                <div class="row">
                    <div class="column column-60 column-offset-20">
                        <p style="color: green;font-weight: bold;">Successfully Update a Student</p>
                    </div>
                </div>
            <?php }?>

        <?php
            if ( 'report' == $task ) {
                $getStudentQuery = "SELECT * FROM students";
                $result = mysqli_query( $connection, $getStudentQuery );
            ?>
            <div class="row">
                <div class="column column-60 column-offset-20">
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Roll</th>
                        <?php if ( isAdmin() || isEditor() ) {?>
                        <th width="25%">Action</th>
                        <?php }?>
                    </tr>
                    <?php while ( $student = mysqli_fetch_assoc( $result ) ) {?>
                    <tr>
                        <td><?php printf( "%s %s", $student['fname'], $student['lname'] );?></td>
                        <td><?php printf( "%s", $student['roll'] );?></td>
                        <?php if ( isAdmin() ) {?>
                        <td><?php printf( "<a href='index.php?task=edit&id=%s'>Edit</a> | <a class='delete' href='index.php?task=delete&id=%s'>Delete</a>", $student["id"], $student["id"] );?></td>
                        <?php } elseif ( isEditor() ) {?>
                            <td><?php printf( "<a href='index.php?task=edit&id=%s'>Edit</a>", $student["id"] );?></td>
                        <?php }?>
                    </tr>
                    <?php }?>

                </table>
                </div>
            </div>
        <?php }?>

        <?php
            if ( isAdmin() || isEditor() ) {
                if ( 'add' == $task ) {
                ?>
                <div class="row">
                    <div class="column column-60 column-offset-20">
                        <form action="add.php" method="POST">
                            <label for="fname">First Name</label>
                            <input type="text" name="fname" id="fname" value="">
                            <label for="lname">Last Name</label>
                            <input type="text" name="lname" id="lname" value="">
                            <label for="roll">Roll</label>
                            <input type="number" name="roll" id="roll" value="">
                            <button type="submit" class="button-primary" name="submit">Save</button>
                        </form>
                    </div>
                </div>
        <?php }}?>

        <?php
            if ( isAdmin() || isEditor() ) {
                if ( 'edit' == $task ) {
                    $selectStudent = "SELECT * FROM students WHERE id={$_REQUEST['id']}";
                    $result = mysqli_query( $connection, $selectStudent );
                    $student = mysqli_fetch_array( $result );
                ?>
             <div class="row">
                <div class="column column-60 column-offset-20">
                    <form action="add.php" method="POST">
                        <label for="fname">First Name</label>
                        <input type="text" name="fname" id="fname" value="<?php echo $student['fname']; ?>">
                        <label for="lname">Last Name</label>
                        <input type="text" name="lname" id="lname" value="<?php echo $student['lname']; ?>">
                        <label for="roll">Roll</label>
                        <input type="number" name="roll" id="roll" value="<?php echo $student['roll']; ?>">
                        <input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>">
                        <button type="submit" class="button-primary" name="update">Save</button>
                    </form>
                </div>
            </div>
        <?php }}?>

        <?php
            if ( isAdmin() ) {
                if ( 'delete' == $task ) {
                    $id = $_REQUEST['id'];
                    $query = "DELETE FROM students WHERE id={$id} ";
                    mysqli_query( $connection, $query );
                    header( "location:index.php" );
                }
            }
        ?>

    </div>

</body>
<script src="script/script.js"></script>
</html>
