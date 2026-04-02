<!-- ΠΡΟΣΘΗΚΗ ΧΡΗΣΤΗ -->

<?php

    session_start(); 

    include("functions.php"); 
    $errorMessage = ""; 

    // Έλεγχος εάν έχει υποβληθεί η φόρμα και αν η μέθοδος αιτήματος είναι POST
    if (isset($_POST['submitButton']) && $_SERVER['REQUEST_METHOD'] == "POST") 
    { 
        // Λήψη δεδομένων από τη φόρμα
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $loginame = $_POST["loginame"];
        $password = $_POST["password"];
        
        if (isset($_SESSION['role'])) 
        {
            $role = $_SESSION['role'];
        } 
        else 
        {
            $role = 0; // Ορισμός προεπιλεγμένης τιμής για τον ρόλο του χρήστη
        }

        connectToDb($conn); 

        do 
        {
            // Έλεγχος εάν τα πεδία είναι κενά ή δεν έχουν οριστεί
            if (empty($firstName) || empty($lastName) || empty($loginame) || empty($password) || empty($role) ||
                stringIsNullOrWhitespace($firstName) || 
                stringIsNullOrWhitespace($lastName) || 
                stringIsNullOrWhitespace($loginame) ||
                stringIsNullOrWhitespace($password) || 
                !isset($_POST['role']))
                {
                    $errorMessage = "All fields are required!"; 
                    break;
                }
                // Έλεγχος εάν η διεύθυνση email υπάρχει ήδη στη βάση δεδομένων
                if(emailExists($loginame))
                {
                    $errorMessage = "Email already exists. Please enter a different one!"; 
                    break;
                }

                // Εκτέλεση εντολής SQL για την εισαγωγή νέου χρήστη στη βάση δεδομένων
                $sql = "INSERT INTO users (firstName, lastName, loginame, password, role)
                    VALUES ('$firstName', '$lastName', '$loginame', '$password', '$role')";

                if ($conn->query($sql)) // Έλεγχος επιτυχίας εκτέλεσης της εντολής SQL
                {
                    echo "New record created successfully"; // Μήνυμα επιτυχίας
                    header("Location: users.php"); // Ανακατεύθυνση στη σελίδα users.php
                    die; // Τερματισμός εκτέλεσης του σεναρίου PHP
                } 
                else 
                {
                    echo "Error: " . $sql . "<br>" . $conn->error; // Μήνυμα σφάλματος SQL
                }
            }
            while(true);
            
            $conn->close(); // Κλείσιμο σύνδεσης με τη βάση δεδομένων
    }
?>

<!DOCTYPE html> 
<html>
<style>
    nav{
        display: table-cell;
        width:20%;
        text-align:left;
        border-left: 1px solid;
        border-bottom: 1px solid;
    }
    main{
        padding-left: 10px;
        padding-right: 550px;
        line-height: 1.7;
        display: table-cell;
        width:60%;
        text-align:left;
        border-right: 1px solid;
        border-left: 1px solid;
        border-bottom: 1px solid;
    }
    .content {
        display: table;
        Width: 100%;
    }
    
    .button {
        padding: 10px 20px;
        margin: 5%;
        text-align: center;
        text-decoration: none;
        color: #7aa8b7;
        background-color:aliceblue;
        transition: 0.3s;
        display: block;
        width: fit-content;
        border-radius: 5px;
        border: none;
  
    }
    .button:hover {
        background-color: #c2c7c7;
    }
    header{
        text-align:center;
        display:block;
        border: 1px solid;
    }
    header h1{
        font-size: 20px;
        padding: 10px 24px;
        margin:0;																												
    }
    .add_body
    {
        margin-left: 170px;
        font-size: 20px;
        line-height: 35px;
    }
    .edit_2{
        display: flex;
        flex-direction: column;
        margin-left: 400px;
        margin-top: 30px;
        margin-right: 400px;
        margin-bottom: 20px;
        border: none;
        background-color: #7aa8b7;
        border-radius: 10px;
    }
    .center{
        display: flex;
        margin-left: 5px;
        padding-bottom: 0px;
        font-size: x-large;
        color: red;
    }

    </style>

        <head>
            <meta charset="UTF-8">
            <title>Προσθήκη Χρήστη</title>
        </head>
    <body >
        <header>
            <h1>Προσθήκη Χρήστη</h1>
        </header>

    <div class="edit_2">

        <div class="add_body">

            <!-- Έναρξη φόρμας για προσθήκη νέου χρήστη -->
            <form  method="post">
                <br>

                <!-- Κρυφό πεδίο για την αποθήκευση του email του χρήστη -->
                <input type="hidden" name="loginame" value="<?php echo $loginame;?>">

                <!-- Εισαγωγή ονόματος -->
                <label for="firstName"> <?php echo "Όνομα:"?> </label>
                <input type="text" id="firstName" name="firstName" placeholder="Όνομα..."><br><br>


                <!-- Εισαγωγή επωνύμου -->
                <label for="lastName"> <?php echo "Επίθετο:"?> </label>
                <input type="text" id="lastName" name="lastName" placeholder="Επίθετο..."><br><br>
            
                <!-- Εισαγωγή email -->
                <label for="loginame"> <?php echo "Email:"?> </label>
                <input type="text" id="loginame" name="loginame" placeholder="Email..."><br><br>


                <!-- Εισαγωγή κωδικού πρόσβασης -->
                <label for="password"> <?php echo "Κωδικός:"?> </label>
                <input type="text" id="password" name="password" placeholder="Κωδικός..."><br><br>

                <!-- Επιλογή ρόλου του χρήστη (Φοιτητής ή Καθηγητής) -->
                <label for="student">Student</label>
                <input type="radio" id="student" name="role" value="Student"> <br><br>

                <label for="tutor">Tutor</label>
                <input type="radio" id="tutor" name="role" value="Tutor"> <br><br>

                <!-- Κουμπί υποβολής φόρμας -->
                <input class="button" style="font-size:20px;" type="submit" value="Προσθήκη" name="submitButton">
                <!-- Κουμπί επιστροφής στη λίστα των χρηστών -->
                <input class="button" style="font-size:20px;" type="button" onclick="window.location.href='./users.php'" value="Πίσω"> <br>

            </form>

            <!-- Μήνυμα λάθους σε περίπτωση που δεν συμπληρωθούν όλα τα πεδία -->
            <p class="center"><?php echo $errorMessage ?></p>

            </div>

        </div>

    </body>

</html>