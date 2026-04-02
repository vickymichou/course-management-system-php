<!-- ΕΠΕΞΕΡΓΑΣΙΑ ΧΡΗΣΤΗ -->

<?php

    session_start();

    include("functions.php");

   connectToDb($conn);

    $firstName = "";
    $lastName = "";
    $loginame = "";
    $password = "";
    $role = "";

    $errorMessage = "";

    // Έλεγχος της μεθόδου αίτησης HTTP
    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        // Έλεγχος εάν δόθηκε έγκυρο loginame στην παράμετρο του URL
        if (!isset($_GET["loginame"]))
        {
            // Αν δεν δόθηκε έγκυρο loginame, επιστροφή στη σελίδα users.php
            header("Location: users.php");
            exit;
        }
  
        $loginame = $_GET["loginame"];

        // Εκτέλεση ερωτήματος SQL για την ανάκτηση των στοιχείων του χρήστη με βάση το loginame
        $sql = "SELECT * FROM users WHERE loginame= '$loginame' ";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        // Έλεγχος εάν βρέθηκαν στοιχεία για το συγκεκριμένο loginame
        if(!$row)
        {
            // Αν δεν βρέθηκαν στοιχεία, επιστροφή στην αρχική σελίδα
            header("Location: index.php");
            exit;
        }
     
        // Ανάθεση των στοιχείων του χρήστη στις μεταβλητές
         $firstName = $row['firstName'];
        $lastName = $row['lastName'];
        $password = $row['password'];
        $role = $row['role'];

    }
    else 
    {
        // Εκτέλεση εάν η αίτηση HTTP είναι POST, δηλαδή όταν υποβάλλεται η φόρμα ενημέρωσης
        // Λήψη των στοιχείων από τη φόρμα
        $loginame = $_POST["loginame"];
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $password = $_POST["password"];
        $role = $_POST["role"];

        // Έλεγχος εάν όλα τα πεδία είναι συμπληρωμένα
        do
        {
            if(empty($loginame) || empty($firstName) || empty($lastName) || empty($password) || empty($role) ||
            stringIsNullOrWhitespace($loginame) ||
            stringIsNullOrWhitespace($firstName) ||
            stringIsNullOrWhitespace($lastName) ||
            stringIsNullOrWhitespace($password) ||
            stringIsNullOrWhitespace($role))
            {
                $errorMessage = "All fields are required!";
                break;
            }

            // Ενημέρωση των στοιχείων
            // Εκτέλεση ερωτήματος SQL για την ενημέρωση των στοιχείων του χρήστη
            $sql = "UPDATE users 
            SET firstName='$firstName', lastName='$lastName', password='$password', role = '$role'
            WHERE loginame='$loginame' ";

            $result = $conn->query($sql);

            // Έλεγχος εάν η ενημέρωση πραγματοποιήθηκε με επιτυχία
            if ($result) 
            {
                echo "record edited successfully";
                // Αν η ενημέρωση πραγματοποιήθηκε επιτυχώς, επιστροφή στη σελίδα users.php
                header("Location: users.php");
                die;
            }
            else
            {
                // Αν συνέβη σφάλμα κατά την ενημέρωση, εκτύπωση μηνύματος σφάλματος
                echo "Error: " . $sql . "<br>" . $conn->error;
                break;
            }

        } 
        while(true);
    }

    // Κλείσιμο σύνδεσης με τη βάση δεδομένων
    $conn->close();

?>

<!DOCTYPE html>
<html>
    <style>
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
            <title>Επεξεργασία Χρήστη</title>
    </head>

    <body>
        <header>
            <h1>Επεξεργασία Χρήστη</h1>
        </header>

        <div class="edit_2">

            <div class="add_body">

                <!-- Φόρμα για επεξεργασία πληροφοριών χρήστη -->
                <form  action="" method="post">
                    <br>

                    <input type="hidden" name="loginame" value="<?php echo $loginame;?>">

                    <label for="firstName"> <?php echo "Όνομα:"?> </label>
                    <textarea type="text" id="firstName" name="firstName" ><?php echo $firstName?></textarea><br><br>

                    <label for="lastName"> <?php echo "Επίθετο:"?> </label>
                    <textarea type="text" id="lastName" name="lastName"><?php echo $lastName?></textarea><br><br>

                    <label for="password"> <?php echo "Κωδικός:"?> </label>
                    <textarea type="text" id="password" name="password"><?php echo $password?></textarea><br><br>

                    <!-- Επιλογή ρόλου του χρήστη -->
                    <label for="student">Student</label>
                    <input type="radio" id="student" name="role" value="Student" 

                    <?php if($role == "Student") { ?> checked <?php } ?> > 

                    <br><br>
            
                    <label for="tutor">Tutor</label>

                    <input type="radio" id="tutor" name="role" value="Tutor"

                    <?php if($role == "Tutor") { ?> checked <?php } ?> > 
                    
                    <br><br>

                    <!-- Κουμπί υποβολής φόρμας για ενημέρωση -->
                    <input class="button" style="font-size:20px;" type="submit" value="Ενημέρωση" name="submitButton">
                    <!-- Κουμπί επιστροφής στην προηγούμενη σελίδα -->
                    <input class="button" style="font-size:20px;" type="button" onclick="window.location.href='./users.php'" value="Πίσω"> <br><br>
                 </form>

            </div>

            <!-- Εμφάνιση πιθανού μηνύματος σφάλματος -->
            <p class="center"><?php echo $errorMessage ?></p>

        </div>

    </body>

</html>
