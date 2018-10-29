<?php

/*Check if the Request was of POST type rather than GET
Since these requests are made from form of the website, frontend developer's work is to make the method = POST
*/
 if($_SERVER['REQUEST_METHOD']=="POST"){
            
            //Configure Database
            //Consider there is another .php file called DatabaseConfigFile.php which contains your database's credentials
            include 'DatabaseConfigFile.php';
            $con = mysqli_connect($HostName,$HostUser,$HostPass,$DatabaseName);   //mysqli_connect query will try connecting to the database(sql)
            
            
            //Obtaining Data from form
            $uname = mysqli_real_escape_string($con,$_POST['username']);
            $email = mysqli_real_escape_string($con,$_POST['email']);
            $password = mysqli_real_escape_string($con,$_POST['password']);
           
           /*
            We have used mysqli_real_escape_string() in the above example because it doesn't allow any malicious code to be written to our database
            This is only valid if we connect it to a SQL database
            Incase we don't want the above we can take the values directly from our POST request
            [Note : Whenever we send a form with POST Method, it sends an Array. To use this array we can simply use the name of the element in the form]
            This above could've been written as 
                    $uname = $_POST['username'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];
           */
             
             //Continue only if none of the input fields is empty
             if(!empty($uname)&&!empty($email)&&($password)){
                                
                                //Check if the username already exists in our database, for this we'll be sending query to our SQL and ask for results, if we get something back, it means we already have a username with the input username
                                $check_username_query = "SELECT * FROM datatable WHERE username = '$uname'";      //This is an SQL Query, Learn about SQL inorder to understand this
                                $check_result = mysqli_query($con,$check_username_query);                         //Here $check_result will take the result from the query mysqli_query()
                                
                                //Check if we got some data by testing that username in our SQL,if we get some data, it means username already exists
                                if(isset($check_result))
                                                echo "Username Already Exists";
                                
                                //If no username is present with the given username, we continue to checking if email exists
                                else{
                                       $check_email_query = "SELECT * FROM datatable WHERE email = '$email'";
                                       $check_result = mysqli_query($con,$check_email_query);
                                       
                                       
                                       //Check if we already have someone with the given email
                                       if(isset($check_result))
                                                  echo "Email Already Exists";
                                        
                                        //If no username or email matches, we continue to saving our data
                                        else{
                                                //We hash our password, cause why not ..... ;)
                                                $hash = password_hash($password,PASSWORD_DEFAULT);
                                                $data_entry_query = "INSERT INTO datatable SET (username, email, password) VALUES ('$uname','$email','$hash')";
                                                $check_query = mysqli_query($con,$data_entry_query);        //Query to enter data into SQL database
                                                
                                                //Check if $check_query gave true, which means our query was successful and our data is entered successfully
                                                if($check_entry_query)
                                                          echo "USER REGISTERED SUCCESSFULLY";
                                                else
                                                      echo "Oops, looks like there's some problem in our server, please contact admins";
                                         }
                                  }
                }
                else
                    echo "One of the fields was empty";
 }
 else
      echo "REQUEST ERROR";
?>
