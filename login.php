<!-- ΣΥΝΔΕΣΗ -->

<?php
    session_start(); 
    include("functions.php"); 
    $errorMessage = ""; 

    if(isset($_SESSION['username'])) // Αν ο χρήστης είναι ήδη συνδεδεμένος, ανακατεύθυνση στην αρχική σελίδα
    {
        header("Location: index.php");
        exit;
    }

    // Ελέγχουμε εάν έχει υποβληθεί η φόρμα σύνδεσης
    if (isset($_POST['submitButton']) && $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $loginame = $_POST['loginame'];
        $password = $_POST['password'];

        connectToDb($conn);

        // Εκτέλεση ερωτήματος για τον έλεγχο του χρήστη
        $query = "select * from users where loginame = '$loginame'";
        $result = mysqli_query($conn, $query);

        if ($result)
        {
            if ($result && mysqli_num_rows($result) > 0)
            {
                $user_data = mysqli_fetch_assoc($result);

                if ($user_data['password'] == $password)
                {
                    // Αποθήκευση στοιχείων συνεδρίας και ανακατεύθυνση στην αρχική σελίδα
                    $_SESSION['username'] = $loginame;
                    $_SESSION['role'] = $user_data['role'];
                    header("Location: index.php");
                    die;
                }
                else
                {
                    $errorMessage = "Λάθος κωδικός πρόσβασης";
                }
            }
            else
            {
                $errorMessage = "Δεν υπάρχει χρήστης με αυτά τα στοιχεία";
            }
        }
        else
        {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
?>

<!DOCTYPE html>

<html>
    <style>
        header
        {
        text-align:center;
        display:block;
        border: 1px solid;
        }
    
        .login{
            display: flex;
            flex-direction: column;
            margin-left: 450px;
            margin-top: 140px;
            margin-right: 450px;
            background-color: aliceblue;
            border-radius: 10%;
        }
        
        .login_body
        {  
            margin-left: 170px;
            font-size: 20px;
            line-height: 35px;
        }
        .submit_button
        {
            color: #ffffff;
            background-color: #7aa8b7;
            height: 36px;
            width: 100px;
            border: none;
            border-radius: 5px;
            margin-top: 25px;
            cursor: pointer;
            white-space: nowrap;
            text-align: center;
            margin-left: 100px;
        }

        .login_message
        {
            margin-left: 10px;
            color: red;
            font-weight: bold;
        }
        
        .form
        {
            display: flex;
            justify-content: center;
            min-height: 100vh;
            margin-top: 50px
        }
    </style>

    <head>

        <meta charset="UTF-8">
        <title>Login</title>

    </head>

    <body>
         <header>
            <h1>Login</h1>
        </header>

        <div class="login">

            <form method="post">

                <p class="login_body">

                    <label for="login">Email:</label>
                    <input type="text" id="loginame" name="loginame" placeholder="Email..."><br><br>

                    <label for="pwd">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Password..."> <br><br>

                    <input class="submit_button" type="submit" value="Είσοδος" name="submitButton"> <br> <br>

                </p>

                <!-- Εμφάνιση μηνύματος σφάλματος -->
                 <p class="login_message"><?php echo $errorMessage ?></p> 

            </form>

        </div>

    </body>

</html>
