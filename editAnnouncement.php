<!-- ΕΠΕΞΕΡΓΑΣΙΑ ΑΝΑΚΟΙΝΩΣΗΣ-->

<?php

    session_start();
    include("functions.php");
    connectToDb($conn);

    $errorMessage = "";

    // Έλεγχος εάν η μέθοδος αιτήματος είναι GET
    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        // Έλεγχος εάν έχει οριστεί η παράμετρος id στο URL
        if (!isset($_GET["id"]))
        {
            header("Location: announcement.php");
            exit;
        }
      
        // Ανάγνωση της παραμέτρου id από το URL
        $id = $_GET["id"];

        // Εκτέλεση ερωτήματος SQL για ανάγνωση των στοιχείων της ανακοίνωσης με το συγκεκριμένο id
        $sql = "SELECT * FROM announcements WHERE id=$id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        // Έλεγχος εάν βρέθηκε η ανακοίνωση με το συγκεκριμένο id
        if(!$row){
            header("Location: index.php");
            exit;
        }
         
        // Ανάκτηση των στοιχείων της ανακοίνωσης από την βάση δεδομένων
        $date = $row['date'];
        $subject = $row['subject'];
        $message = $row['message'];
    }
    else 
    {
        // Αν η μέθοδος αιτήματος είναι POST, τότε έχουμε υποβολή της φόρμας επεξεργασίας
        // Ανάκτηση των δεδομένων που υποβλήθηκαν από τη φόρμα επεξεργασίας
        $id = $_POST["id"];
        $date = date("Y-m-d"); // Ενημέρωση της ημερομηνίας σε τρέχουσα ημερομηνία
        $subject = $_POST["subject"];
        $message = $_POST["message"];

        // Έλεγχος εγκυρότητας των δεδομένων
        do
        {
            if(empty($subject) || empty($message) || 
            stringIsNullOrWhitespace($subject) || 
            stringIsNullOrWhitespace($message))
            {
                $errorMessage = "All fields are required!";
                break;
            }
    
            // Εκτέλεση ερωτήματος SQL για ενημέρωση των στοιχείων της ανακοίνωσης
            $sql = "UPDATE announcements 
            SET date='$date', subject='$subject', message='$message'
            WHERE id='$id' ";
    
            $result = $conn->query($sql);
    
            if ($result) 
            {
                echo "record edited successfully";
                header("Location: announcements.php");
                die;
                } 
            else 
            {
                echo "Error: " . $sql . "<br>" . $conn->error;
                break;
            }
    
        }
        while(true);

        $conn->close();

    }
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

    .edit

    {
        display: flex;
        flex-direction: column;
        margin-left: 450px;  
        margin-top: 70px;
        margin-right: 450px;
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
        <title>Επεξεργασία Ανακοίνωσης</title>

    </head>

    <body>
        <header>
            <h1>Επεξεργασία Ανακοίνωσης</h1>
        </header>


        <div class="edit">

            <div class="add_body">

                <form  action="" method="post">

                    <br>
                    <input type="hidden" name="id" value="<?php echo $id;?>">

                    <!-- Πεδίο εισαγωγής για το θέμα της ανακοίνωσης -->
                    <label for="subject"> <?php echo "Θέμα:"?> </label>
                    <textarea type="text" id="subject" name="subject"><?php echo $subject ?></textarea> <br><br>

                    <!-- Πεδίο εισαγωγής για το κείμενο της ανακοίνωσης -->
                    <label for="message"> <?php echo "Κείμενο:"?> </label>
                    <textarea type="text" id="message" name="message"><?php echo $message ?></textarea> <br><br>

                    <!-- Κουμπί υποβολής της φόρμας -->
                    <input class="button" style="font-size:20px;" type="submit" value="Ενημέρωση" name="submitButton">
                    <!-- Κουμπί επιστροφής πίσω στην λίστα ανακοινώσεων -->
                    <input class="button" style="font-size:20px;" type="button" onclick="window.location.href='./announcements.php'" value="Πίσω"> <br> <br>

                </form>

            </div>

            <!-- Εμφάνιση ενδεχόμενου μηνύματος σφάλματος -->
            <p class="center"><?php echo $errorMessage ?></p>

        </div>

    </body>

</html>
