<!-- ΠΡΟΣΘΗΚΗ ΕΡΓΑΣΙΑΣ -->

<?php

    session_start();

    include("functions.php");

    $errorMessage = "";

    // Έλεγχος αν η φόρμα έχει υποβληθεί και η μέθοδος αποστολής είναι POST
    if (isset($_POST['submitButton']) && $_SERVER['REQUEST_METHOD'] == "POST") 
    {
        // Έλεγχος αν έχει οριστεί η μεταβλητή session 'id'
        if (isset($_SESSION['id'])) 
        {
            $id = $_SESSION['id'];
        } 
        else 
        {
            $id = 0; // Προεπιλεγμένη τιμή
        }
            
        // Λήψη των δεδομένων από τη φόρμα
        $goals = $_POST["goals"];
        $required_files = $_POST["required_files"];
        $date = $_POST["date"];
        $location = uploadFile("homeworkToUpload"); // Ανέβασμα του αρχείου εκφώνησης

        connectToDb($conn);

        do 
        {
            // Έλεγχος για κενά πεδία ή εσφαλμένη μορφή ημερομηνίας
            if (empty($goals) || empty($location) || empty($required_files) || empty($date) ||
                stringIsNullOrWhitespace($goals) || 
                stringIsNullOrWhitespace($required_files) || 
                stringIsNullOrWhitespace($date) ||
                !validateDate($date)) 
            {
                $errorMessage = "All fields are required or wrong date format!";
                break;
            }

            // Εισαγωγή εργασίας στον πίνακα 'homework'
            $sql1 = "INSERT INTO homework (goals, location, required_files, date)
                    VALUES ('$goals', '$location', '$required_files', '$date')";

            // Αυξάνουμε το id των εργασιών κατά ένα
            $id++;

            // Εισαγωγή ανακοίνωσης στον πίνακα 'announcements'
            $current_date = date("Y-m-d");
            $subject = "Υποβλήθηκε η εργασία $id";
            $message = "Η ημερομηνία παράδοσης της εργασίας είναι $date";
            $sql2 = "INSERT INTO announcements (date, subject, message)
                    VALUES ('$current_date', '$subject', '$message')";

            // Εκτέλεση των ερωτημάτων SQL
            if ($conn->query($sql1) && $conn->query($sql2)) 
            {
                echo "New records created successfully";

                // Ανακατεύθυνση στη σελίδα των εργασιών μετά την επιτυχή υποβολή
                header("Location: homework.php");
                die;
            } 
            else 
            {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } 
        while (true);
        
        // Κλείσιμο της σύνδεσης με τη βάση δεδομένων
        $conn->close();
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
    .edit_body
{
  margin-left: 50px;
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
        <title>Προσθήκη Εργασίας</title>
    </head>

    <body>
        <header>
            <h1>Προσθήκη Εργασίας</h1>
        </header>

        <div class="edit_2">

            <div class="edit_body">

                <!-- Έναρξη φόρμας για προσθήκη νέας εργασίας -->
                <form   method="post" enctype="multipart/form-data">

                    <!-- Κρυφό πεδίο για την αποθήκευση του id του χρήστη -->
                    <input type="hidden" name="id" value="<?php echo $id;?>">
                    <br>

                    <!-- Εισαγωγή στόχων της εργασίας -->
                    <label for="goals">Στόχοι:</label>
                    <input type="text" id="goals" name="goals" placeholder="1..2.."><br><br>

                    <!-- Εισαγωγή παραδοτέων της εργασίας -->
                    <label for="required_files">Παραδοτέα:</label>
                    <input type="text" id="required_files" name="required_files" placeholder="1..2..."><br><br>


                    <!-- Εισαγωγή ημερομηνίας παράδοσης της εργασίας -->
                    <label for="date">Ημερομηνία Παράδοσης:</label>
                    <input type="text" id="date" name="date" placeholder="yyyy-mm-dd"><br><br>

                    <!-- Επιλογή αρχείου εκφώνησης της εργασίας -->
                    <label for="fileToUpload">Εκφώνηση:</label>
                    <input style="font-size: 20px;" type="file" name="homeworkToUpload" id="fileToUpload"> <br><br>

                    <!-- Κουμπί υποβολής φόρμας -->
                    <input class="button" style="font-size:20px; " type="submit" value="Προσθήκη" name="submitButton">
                    <!-- Κουμπί επιστροφής στη λίστα με τις εργασίες -->
                    <input class="button" style="font-size:20px;" type="button" onclick="window.location.href='./homework.php'" value="Πίσω"> <br>

                </form>

            </div>

            <!-- Εμφάνιση ενδεχόμενου μηνύματος λάθους -->
            <p class="center"><?php echo $errorMessage ?></p>

        </div>

    </body>

</html>
