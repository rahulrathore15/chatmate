<?php
    session_start();
    include_once "config.php";
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    //checking if all required fields are filled
    if(!empty($fname) && !empty($lname) && !empty($email) && !empty($password)){
        //let's check user email is valid or not
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");

            ///let's check that email exist in the database or not
            if(mysqli_num_rows($sql) > 0){ //if email already exist or taken
                echo "$email - This email already exist!";
            }else{
                 //let's check user uploded img file or not
                if(isset($_FILES['image'])){//if img file is uploaded    //$_FILES[] return us an array with file name, file type, error,file size, tmp_name

                    //getting user uploaded image name
                    $img_name = $_FILES['image']['name'];

                    //checking the type of the image
                    $img_type = $_FILES['image']['type'];

                     //this temporary name is used to save/move file in our folder
                    $tmp_name = $_FILES['image']['tmp_name'];
                    
                    //let's explode image and get the last extension like jpg png
                    $img_explode = explode('.',$img_name);

                    //here we get the extension of an user uploaded img file
                    $img_ext = end($img_explode);

                    //these are some valid img extensions and we've stored them in array
                    $extensions = ["jpeg", "png", "jpg"];

                    //if user uploaded img extension is matched with any array extensions
                    if(in_array($img_ext, $extensions) === true){
                        $types = ["image/jpeg", "image/jpg", "image/png"];
                        if(in_array($img_type, $types) === true){
                            //this will return us the current time..
                            //we need this time because when you uploading user img in our folder we rename user file with current time 
                                        //so all the img file will have a unique name
                            $time = time();

                            //let's move the user uploaded img to our particular folder
                            //current time will be uploaded before the name of user uploaded img.

                            //so if user upload two different images with same name then 
                            //the name of a particular image will be unique with adding time.
                            $new_img_name = $time.$img_name;

                            //Remember we do not upload user uploaded file in the databse 
                            // we just save the file url. 
                            //Actual file will be saved in our particular folder
                            if(move_uploaded_file($tmp_name,"images/".$new_img_name)){
                                //creating random id for user
                                $ran_id = rand(time(), 100000000);

                                //if user upload img move to our folder successfully
                                // once user signed up then his status will be active now
                                $status = "Active now";

                                //storing encrypted password
                                $encrypt_pass = md5($password);

                                //let's insert all user data inside table
                                $insert_query = mysqli_query($conn, "INSERT INTO users (unique_id, fname, lname, email, password, img, status)
                                VALUES ({$ran_id}, '{$fname}','{$lname}', '{$email}', '{$encrypt_pass}', '{$new_img_name}', '{$status}')");

                                //if these data inserted
                                if($insert_query){
                                    $select_sql2 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                                    if(mysqli_num_rows($select_sql2) > 0){
                                        $result = mysqli_fetch_assoc($select_sql2);
                                        //using this session we used user unique_id in other php file
                                        $_SESSION['unique_id'] = $result['unique_id'];
                                        echo "success";
                                    
                                    }else{
                                        echo "This email address not Exist!";
                                    }
                                }else{
                                    echo "Something went wrong. Please try again!";
                                }
                            }
                        }else{
                            echo "Please upload an image file - jpeg, png, jpg";
                        }
                    }else{
                        echo "Please upload an image file - jpeg, png, jpg";
                    }
                }
            }
        }else{
            echo "$email is not a valid email!";
        }
    }else{
        echo "All input fields are required!";
    }
?>