<!-- ΠΡΟΣΘΗΚΗ ΕΓΓΡΑΦΟΥ -->

<?php

    session_start();
    include("functions.php");

    $errorMessage = "";

    // Έλεγχος εάν η φόρμα υποβλήθηκε και η μέθοδος αιτήματος είναι POST
    if (isset($_POST['submitButton']) && $_SERVER['REQUEST_METHOD'] == "POST") 
    {

        // Λήψη των δεδομένων από τη φόρμα
        $title = $_POST["title"];
        $description = $_POST["description"];
        $location = uploadFile("documentToUpload");

        // Σύνδεση στη βάση δεδομένων
        connectToDb($conn);

        do 
        {
            // Έλεγχος εάν όλα τα πεδία έχουν συμπληρωθεί
            if (empty($title) || empty($description) || empty($location) ||
            stringIsNullOrWhitespace($title) || 
            stringIsNullOrWhitespace($description)) 
            {
                $errorMessage = "All fields are required!";
                break;
            }

            // Εισαγωγή των δεδομένων στη βάση δεδομένων
            $sql = "INSERT INTO documents (title, description, location)
                VALUES ('$title', '$description', '$location')";

            if ($conn->query($sql)) 
            {

            // Αν η εισαγωγή ολοκληρωθεί επιτυχώς, μετάβαση στη σελίδα documents.php
            header("Location: documents.php");
            die;

            } 
            else 
            {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        
        } while (true);

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
        <title>Προσθήκη Εγγράφου</title>

    </head>

    <body> 
        <header>
            <h1>Προσθήκη Εγγράφου</h1>
        </header>

        <div class="edit_2">

            <div class="edit_body">

                <br>
                <!-- Φόρμα για την προσθήκη εγγράφου -->
                <form  action="" method="post" enctype="multipart/form-data">

                    <input type="hidden" name="id" value="<?php echo $id?>">

                    <label for="title" style="font-size: larger;">Τίτλος:</label>
                    <input type="text" id="title" name="title" placeholder="Τίτλος..."><br><br>


                    <label for="description" style="font-size: larger;">Περιγραφή:</label>
                    <input type="text" id="description" name="description" placeholder="Περιγραφή..."><br><br>


                    <label for="fileToUpload" style="font-size: larger;">Αρχείο:</label>
                    <input style="font-size:20px;" type="file" name="documentToUpload" id="fileToUpload"><br><br>

                    <!-- Κουμπί υποβολής φόρμας -->
                    <input class="button" style="font-size:20px; " type="submit" value="Προσθήκη" name="submitButton">
                    <!-- Κουμπί επιστροφής στη λίστα με τα έγγραφα -->
                    <input class="button" style="font-size:20px;" type="button" onclick="window.location.href='./documents.php'" value="Πίσω"> <br> <br>
                
                </form>
            </div>

            <!-- Εμφάνιση ενδεχόμενου μηνύματος λάθους -->
            <p class="center"><?php echo $errorMessage ?></p>

        </div>

    </body>

</html>
