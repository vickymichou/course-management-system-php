<!-- ΑΝΑΚΟΙΝΩΣΕΙΣ -->

<?php

  session_start(); 

    include("functions.php"); // Συμπερίληψη του αρχείου functions.php που περιέχει χρήσιμες συναρτήσεις

    // Έλεγχος εάν ο χρήστης δεν έχει συνδεθεί, στην περίπτωση αυτή γίνεται ανακατεύθυνση προς την σελίδα σύνδεσης
    if(!isset($_SESSION['username']))
    { 
      header("Location: login.php");
    }

    $_SESSION['table'] = 'announcements'; // Ορισμός του πίνακα στον οποίο θα γίνουν οι ενέργειες (στην περίπτωσή μας, ο πίνακας 'announcements')
    
    connectToDb($conn); 

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
        padding-left: 10%;
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
    .to-top {
        display: block;
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 10px;
        color: #7aa8b7;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
    main r{
        color: red;
    }
    main k{
        padding-left: 20%;
    }

    </style>
        <head> <meta charset="UTF-8">
            <title>Ανακοινώσεις</title>
        </head>
        <body>
            <header>
                <h1>Ανακοινώσεις</h1>
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

                    <!-- Εμφάνιση κουμπιού προσθήκης ανακοίνωσης μόνο για διαχειριστές -->
                    <?php if ($_SESSION['role'] == "Tutor") { ?>
                        <div>
                            <a style="font-size: larger;" href="./addAnnouncement.php">Προσθήκη νέας ανακοίνωσης</a> <br> 
                        </div>
                        <!-- Γραμμή -->
                        <hr>
                    <?php } ?>

                    <!-- Εμφάνιση ανακοινώσεων -->
                     <?php 
                     $sql = "SELECT * FROM announcements";
                     $result = $conn->query($sql);
                     
                     
                     if($result->num_rows > 0) {
                      while($row = $result->fetch_assoc()) {
                        $subject = [];
                        $message = [];
                        $date = [];
                        $id = [];
                    
                        
                        $id = $row["id"];
                        $subject = $row['subject'];
                        $message=$row['message'];
                        $date=$row['date'];

                        
                        ?>  

                        <!-- Εμφάνιση των ανακοινώσεων -->
                         <h2>Ανακοίνωση <?php echo $id?></h2>
                         <!-- Εμφάνιση κουμπιών διαγραφής και επεξεργασίας μόνο για διαχειριστές -->
                          <?php 
                          if ($_SESSION['role'] == "Tutor"): ?>
                            <a href='./deleteAnnouncement.php?id=<?php echo  $id; ?>' style="font-size: smaller;">[Διαγραφή]</a> 
                            <a href='./editAnnouncement.php?id=<?php echo  $id; ?>' style="font-size: smaller;">[Επεξεργασία]</a>
                            <?php 
                          endif; ?></h2>
                          
                          <!-- Περιεχόμενο ανακοίνωσης -->
                          <b1>
                           <div class="border">
                           <p> <b>Ημερομηνία:</b> 
                           <?php 

                              $dateObject = new DateTime($date);

                              // Μορφοποίηση σε DD/MM/YYYY
                              $formattedDate = $dateObject->format("d/m/Y");
                              echo $formattedDate; ?></p> 
                              
                              <p><b>Θέμα:</b><?php echo $subject?></p>

                              <?php 
                              
                              
                              // Έλεγχος αν περιέχει σελίδα PHP
                              if (strpos($message, ".php") !== false) {
                                $output = "";
                                
                                if (preg_match('/homework\.php\d*/', $message))  {
                                    $before = strstr($message, "homework.php", true); // Κείμενο πριν από "homework.php" 
                                    $after = substr(strstr($message, "homework.php"), strlen("homework.php")); // Κείμενο μετά από "homework.php"

                                
                                    $output .=  htmlspecialchars($before); // Εκτύπωση του κειμένου πριν το "homework.php"
                                    $output .=  "<a href='homework.php'><p> «Εργασίες»</p></a>"; // Αντικατάσταση με "εδω" και σύνδεσμο
                                    $output .=  htmlspecialchars($after); // Εκτύπωση του κειμένου μετά το "homework.php"
                                }
                                elseif(preg_match('/documents.\.php\d*/', $message)){
                                    $before = strstr($message, "documents.php", true); // Κείμενο πριν από "documents.php" 
                                    $after = substr(strstr($message, "documents.php"), strlen("documents.php")); // Κείμενο μετά από "documents.php"

                                    $output .=  htmlspecialchars($before); // Εκτύπωση του κειμένου πριν το "documents.php"
                                    $output .=  "<a href='documents.php'><p> «Έγγραφα μαθήματο»</p></a>"; // Αντικατάσταση με "εδω" και σύνδεσμο
                                    $output .=  htmlspecialchars($after); // Εκτύπωση του κειμένου μετά το "documents.php"

                                }
                                elseif(preg_match('/communication\.php\d*/', $message)){
                                    $before = strstr($message, "communication.php", true); // Κείμενο πριν από "communication.php" 
                                    $after = substr(strstr($message, "communication.php"), strlen("homework.php")); // Κείμενο μετά από "communication.php"

                                    $output .=  htmlspecialchars($before); // Εκτύπωση του κειμένου πριν το "communication.php"
                                    $output .=  "<a href='communication.php'><p> Επικοινωνία</p></a>"; // Αντικατάσταση με "εδω" και σύνδεσμο
                                    $output .=  htmlspecialchars($after); // Εκτύπωση του κειμένου μετά το "communication.php"
                                }
                                ?>
                                <p><?php echo $output?></p><?php 
                               
                             }
                             else
                             ?>
                               <p><?php echo $message?></p>
                            
                            </div>
                          </b1>

                            <?php
                            }
                        }
                          else 
                          {
                            ?> 
                            <!-- Μήνυμα εάν δεν υπάρχουν εργασίες -->
                             <h2> ΔΕΝ ΥΠΑΡΧΟΥΝ ΑΝΑΚΟΙΝΩΣΕΙΣ</h2> 
                             <?php }
                            ?>
                    
                    <a href="#" class="to-top">< top ></a>
                     <script>
                     // Στοιχείο DOM του υπερσυνδέσμου
                     var toTopButton = document.querySelector('.to-top');
                     // Προσθήκη ακροατών συμβάντων για τον υπερσύνδεσμο
                     toTopButton.addEventListener('click', function() {
                        // Μεταφορά στην κορυφή της σελίδας με μία ομαλή κίνηση
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    });
                    </script>
                </main>
            </div> 
        </body>
        
    </html>