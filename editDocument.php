<!-- ΕΠΕΞΕΡΓΑΣΙΑ ΕΓΓΡΑΦΟΥ ΜΑΘΗΜΑΤΟΣ -->

<?php

    session_start();
    include("functions.php");
    connectToDb($conn);

    $id = "";
    $title = "";
    $description = "";
    $location = "";

    $errorMessage = "";

    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        // Ελέγχουμε αν έχει οριστεί ο παράμετρος id στο URL
        if (!isset($_GET["id"]))
        {
            header("Location: documents.php");
            exit;
        }   

        // Αποθηκεύουμε την τιμή του id από το URL
        $id = $_GET["id"];

        // Επιλέγουμε το έγγραφο με βάση το id από τη βάση δεδομένων
        $sql = "SELECT * FROM documents WHERE id=$id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        // Αν δεν βρεθεί έγγραφο με το συγκεκριμένο id, ανακατευθύνουμε στην αρχική σελίδα
        if(!$row)
        {
            header("Location: index.php");
            exit;
        }

        // Αποθηκεύουμε τις πληροφορίες του εγγράφου από τη βάση στις αντίστοιχες μεταβλητές
        $title = $row['title'];
        $description = $row['description'];
        $location = $row['location'];

    }
    else 
    {
        // Αν η φόρμα έχει υποβληθεί με μέθοδο POST, αναλαμβάνουμε την ενημέρωση των δεδομένων του εγγράφου
        $id = $_POST["id"];
        $title = $_POST["title"];
        $description = $_POST["description"];
        $location = uploadFile("documentToUpload");

        do
        {
            // Έλεγχος για την ύπαρξη κενών πεδίων
            if(empty($title) || empty($description) || empty($location) ||
            stringIsNullOrWhitespace($title) || 
            stringIsNullOrWhitespace($description) || 
            stringIsNullOrWhitespace($location))
            {
                $errorMessage = "All fields are required!";
                break;
            }

            // Ενημέρωση των δεδομένων του εγγράφου στη βάση δεδομένων
            $sql = "UPDATE documents 
            SET title ='$title', description ='$description', location ='$location'
            WHERE id='$id' ";

            $result = $conn->query($sql);

            // Αν η ενημέρωση πραγματοποιηθεί με επιτυχία, ανακατευθύνουμε στη σελίδα εγγράφων
            if ($result) 
            {
                echo "record edited successfully";
                header("Location: documents.php");
                die;
            } 
            else 
            {
                echo "Error: " . $sql . "<br>" . $conn->error;
                break;
            }

        }
        while(true);
    
    }

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
        <title>Επεξεργασία Εγγράφου</title>

    </head>

    <body>
    <header>
            <h1>Επεξεργασία Εργασίας</h1>
        </header>


        <div class="edit_2">

            <div class="edit_body">

            <!-- Φόρμα επεξεργασίας εγγράφου -->
            <form  action="" method="post" enctype="multipart/form-data">
                
                <br>
                <input type="hidden" name="id" value="<?php echo $id;?>">

                <!-- Πεδίο τίτλου -->
                <label for="title"> <?php echo "Τίτλος:"?> </label>
                <textarea type="text"  id="title" name="title"><?php echo $title?></textarea><br><br>

                <!-- Πεδίο περιγραφής -->
                <label for="description"> <?php echo "Περιγραφή:"?> </label>
                <textarea  type="text"   id="description" name="description"><?php echo $description ?></textarea><br><br>

                <!-- Πεδίο ανέβασματος αρχείου -->
                <label for="fileToUpload" >Αρχείο:</label>
                <input style="font-size: 20px; vertical-align: middle;" type="file" name="documentToUpload" id="fileToUpload"><br><br>

                <!-- Κουμπί υποβολής φόρμας -->
                <input class="button" style="font-size:20px; " type="submit" value="Ενημέρωση" name="submitButton">
                <!-- Κουμπί επιστροφής πίσω -->
                <input class="button" style="font-size:20px;" type="button" onclick="window.location.href='./documents.php'" value="Πίσω"> <br><br>

            </form>

        </div>

        <!-- Εμφάνιση μηνύματος σφάλματος -->
        <p class="center"><?php echo $errorMessage ?></p>

        </div>
        
    </body>

</html>
