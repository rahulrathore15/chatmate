<?php 
    session_start();

    if(isset($_SESSION['unique_id'])){
        //checking configuration first
        include_once "config.php";
        

        //you can checkout all of this in (chat) database (phpmyadmin ->chat->browse)
        //storing outgoing id
        $outgoing_id = $_SESSION['unique_id'];

        //storing incoming id
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);

        //storing user message
        $message = mysqli_real_escape_string($conn, $_POST['message']);

        //checking if message is not empty
        if(!empty($message)){ 
            //let's insert all id with message in the (message) table
            $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
                                        VALUES ({$incoming_id}, {$outgoing_id}, '{$message}')") or die();
        }
    }else{
        header("location: ../login.php");
    }


    
?>