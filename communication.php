<!-- ΕΠΙΚΟΙΝΩΝΙΑ -->

<?php
session_start();
    include("functions.php");

    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\PHPMailer;

    require 'Exception.php';
    require 'PHPMailer.php';
    require 'SMTP.php';

    $displayMessage = "";
    if(!isset($_SESSION['username'])){   // Έλεγχος εάν ο χρήστης δεν έχει συνδεθεί, αν όχι τον ανακατευθύνουμε στη σελίδα σύνδεσης
        header("Location: index.php");
        
    }
    $loginame = $_SESSION['username'];

    if (isset($_POST['sendButton']) && $_SERVER['REQUEST_METHOD'] == "POST") {
        $sender = $_POST["sender"];
        $subject = $_POST["subject"];
        $message = $_POST["message"];

        
        connectToDb($conn);
        do {
            if(empty($sender) || empty($subject) || empty($message) ||
            stringIsNullOrWhitespace($sender) ||
            stringIsNullOrWhitespace($subject) ||
            stringIsNullOrWhitespace($message)){
                $displayMessage = "All fields required";
                break;
            }
            $sql = "SELECT loginame FROM users WHERE role = 'Tutor' ";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {

                    $recipient = $row['loginame'];

                    $mail = new PHPMailer();
                    // Ρυθμισεισ
                    $mail->IsSMTP();
                    $mail->CharSet = 'UTF-8';
                    $mail->Host       = "mail.example.com";    // Παράδειγμα διακομιστή SMTP
                    $mail->SMTPDebug  = 0;                     //ενεργοποιεί τις πληροφορίες εντοπισμού σφαλμάτων SMTP (για δοκιμή)
                    $mail->SMTPAuth   = true;                  // enable SMTP authentication
                    $mail->Port       = 25;                   // ενεργοποιήστε τον έλεγχο ταυτότητας SMTP
                    $mail->Username   = "username";            // Παράδειγμα ονόματος χρήστη λογαριασμού SMTP
                    $mail->Password   = "password";            // Παράδειγμα κωδικου χρήστη λογαριασμού SMTP
                    // Περιεχομενο
                    $mail->setFrom($loginame);   
                    $mail->addAddress($recipient);              
                    $mail->Subject = $subject;
                    $mail->Body    = $message;
                    try{
                        $mail->send();
                        $displayMessage = "Message Successfully Sent to Tutors";
                    } catch(Exception $e){
                        $displayMesasge = "Error!" . $e->errorMessage();
                    } 
                }
                break;
            }else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } while (true);
        
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
        color: #ffffff;
        background-color: #7aa8b7;
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
    main b1{
        text-align: left;
        padding-right: 5px;
        padding-left: 10%;
        padding-bottom: 15%;
    }
    main h2{
        font-size: 15px;
        vertical-align: top;
        text-align: left;
        padding-top: 0%;
        color: green;   
    }
    .border{
        display: table-cell;
        width:30%;
        text-align:left;
        border-bottom: 1px solid;
    }   

  

    </style>
        <head> <meta charset="UTF-8">
            <title>Επικοινωνία</title>
        </head>
        <body>
            <header>
                <h1>Επικοινωνία</h1>
            </header>
            <div class="content">
            <nav>
                <a class="button" href="index.php">Αρχική σελίδα</a>
                    <a class="button" href="announcements.php">Ανακοινώσεις</a>
                    <a class="button" href="communication.php">Επικοινωνία</a>
                    <a class="button" href="documents.php">Έγραφα μαθήματος</a>
                    <a class="button" href="homework.php">Εργασίες</a>
                    <!-- Εμφάνιση κουμπιού για διαχείριση χρηστών μόνο εάν ο ρόλος του χρήστη είναι Tutor -->
      
                       <?php if($_SESSION['role'] == 'Tutor') {?>
                            <a class="button" href="users.php">Διαχείριση Χρηστών </a>
                       <?php } ?>

                    <!-- Κενά και κουμπί αποσύνδεσης -->
                    <br><br><br><br><br><br><br><br><br>
                    <a  class="button" href="logout.php">Logout</a>

                </nav>

                <main>
                Η συγκεκριμένη ιστοσελίδα θα περιέχει δύο δυνατότητες για την αποστολή e-mail στον καθηγητή:
                        <div style="margin-left: 5%;">
                            •	Μέσω web φόρμας</br>
                            •	Με χρήση e-mail διεύθυνσης</br>
                        </div>

                    <h2>Αποστολή e-mail μέσω web φόρμας</h2>
                <b1>
                    <div class="border">
                        <form action="" method="post">
                            <label for="sender"><p><b>Αποστολέας: </b></label>
                            <textarea type="text" id="sender" name="sender" ><?php echo $loginame?></textarea>
                            <br>

                            <label for="subject"><p><b>Θέμα: </b></label>
                            <input type="text" id="subject" name="subject" placeholder="Εισαγωγή θέματος..."></input>
                            <br>

                            <label for="message"><p><b>Κείμενο: </b></label>
                            <input type="text" id="message" name="message" placeholder="Εισαγωγή κειμένου..."></input>
                            <br>

                            <input class="button" type="submit" value="Αποστολή" name="sendButton">


                            <div style="color: red;">
  
                              <?php echo $displayMessage ?>

                            </div>
                        </form><br>
                    </div>
                </b1>
                    <h2>Αποστολή e-mail ε χρήση e-mail διεύθυνσης</h2>
                    <b1>
                        <div class="border">
                            Εναλλακτικά μπορείτε να αποστείλετε e-mail στην<br>
  
                            παρακάτω διεύθυνση ηλεκτρονικού ταχυδρομείου<br>
                            <?php 
                            connectToDb($conn);
                            $sql = "SELECT loginame FROM users where role = 'Tutor' ";$tutor_email = "";
                            $result = $conn->query($sql);
                            if($result)
                            {
                                while($row = $result->fetch_assoc())
                                {
                                    $tutor_email = $row['loginame'];
                                    ?> 
                                    <a style="font-size:large;" href = "mailto: <?php echo $tutor_email ?>"><?php echo $tutor_email ?></a><br></h3> 
                                    <?php
                                     }
                                    }
                                    else
                                    {
                                        echo "Error: " . $sql . "<br>" . $conn->error;
                                    }
                                    ?>                            
                        </div>
                    </b1>
                </main>
            </div> 
        </body>
        
    </html>