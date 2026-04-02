<!-- ΕΠΕΞΕΡΓΑΣΙΑ ΕΡΓΑΣΙΑΣ -->

<?php

    session_start();
    include("functions.php");
    connectToDb($conn);

    $id = "";
    $goals = "";
    $location = "";
    $required_files = "";
    $date = "";
    $errorMessage = "";

    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {

        // Ελέγχουμε αν έχει οριστεί ο παράμετρος id στο URL
        if (!isset($_GET["id"]))
        {
            header("Location: homework.php");
            exit;
        }

        // Αποθηκεύουμε την τιμή του id από το URL
        $id = $_GET["id"];

        // Επιλέγουμε την εργασία με βάση το id από τη βάση δεδομένων
        $sql = "SELECT * FROM homework WHERE id=$id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        // Αν δεν βρεθεί εργασία με το συγκεκριμένο id, ανακατευθύνουμε στην αρχική σελίδα
        if(!$row)
        {
            header("Location: index.php");
            exit;
        }

        // Αποθηκεύουμε τις πληροφορίες της εργασίας από τη βάση στις αντίστοιχες μεταβλητές
        $goals = $row['goals'];
        $location = $row['location'];
        $required_files = $row['required_files'];
        $date = $row['date'];

    }
    else
    {

        // Αν η φόρμα έχει υποβληθεί με μέθοδο POST, αναλαμβάνουμε την ενημέρωση των δεδομένων της εργασίας
        $id = $_POST["id"];
        $goals = $_POST["goals"];
        $required_files = $_POST["required_files"];
        $date = $_POST["date"];
        $location = uploadFile("homeworkToUpload");

        do
        {
            // Έλεγχος για την ύπαρξη κενών πεδίων ή μη έγκυρης μορφής ημερομηνίας
            if(empty($goals) || empty($location) || empty($required_files) || empty($date) || 
            stringIsNullOrWhitespace($goals) || 
            stringIsNullOrWhitespace($location) ||
            stringIsNullOrWhitespace($required_files) ||
            stringIsNullOrWhitespace($date))
            {
                $errorMessage = "All fields are required or wrong date format";
                break;
            }

            // Ενημέρωση των δεδομένων της εργασίας στη βάση δεδομένων
            $sql = "UPDATE homework
            SET goals ='$goals', location ='$location', required_files ='$required_files', date = '$date'
            WHERE id='$id' ";

            $result = $conn->query($sql);

            // Αν η ενημέρωση πραγματοποιηθεί με επιτυχία, ανακατευθύνουμε στη σελίδα εργασιών
            if ($result) 
            {

            echo "record edited successfully";
            header("Location: homework.php");
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
        <title>Επεξεργασία Εργασίας</title>

    </head>

    <body>
        <header>
            <h1>Επεξεργασία Εργασίας</h1>
        </header>


        <div class="edit_2">

            <div class="edit_body">

                <!-- Φόρμα επεξεργασίας πληροφοριών εργασίας -->
                <form  action="" method="post" enctype="multipart/form-data">

                    <br>

                    <!-- Κρυφό πεδίο για την αποθήκευση του ID της εργασίας -->
                    <input type="hidden" name="id" value="<?php echo $id;?>">

                    <label for="goals"> <?php echo "Στόχοι:"?> </label>
                    <textarea type="text" id="goals" name="goals"><?php echo $goals?></textarea> <br><br>

                    <label for="required_files"> <?php echo "Απαιτούμενα αρχεία:"?> </label>
                    <textarea type="text" id="required_files" name="required_files"><?php echo $required_files ?></textarea><br><br>

                    <label for="date"> <?php echo "Ημερομηνία Παράδοσης:"?> </label>
                    <textarea type="text" id="date" name="date"><?php echo $date ?></textarea><br><br>

                    <label for="fileToUpload">Εκφώνηση:</label>
                    <input style="font-size: 20px;" type="file" name="homeworkToUpload" id="fileToUpload"> <br><br>

                    <!-- Κουμπί υποβολής φόρμας για ενημέρωση -->
                    <input class="button" style="font-size:20px;" type="submit" value="Ενημέρωση" name="submitButton">
                    <!-- Κουμπί επιστροφής στην προηγούμενη σελίδα -->
                    <input class="button" style="font-size:20px;" type="button" onclick="window.location.href='./homework.php'" value="Πίσω"> <br> <br>

                </form>
            </div>

            <!-- Εμφάνιση πιθανού μηνύματος σφάλματος -->
            <p class="center"><?php echo $errorMessage ?></p>

        </div>

    </body>

</html>
