<!-- ΕΡΓΑΣΙΕΣ -->

<?php 

  // Έλεγχος σύνδεσης χρήστη και επιστροφή στην αρχική σελίδα σύνδεσης εάν δεν είναι συνδεδεμένος
  session_start();
  include("functions.php");
  if(!isset($_SESSION['username'])) { 
    header("Location: login.php");
  }

  $_SESSION['table'] = 'homework';
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
   

    </style>
        <head> 
            <meta charset="UTF-8">
            <title>Εργασίες Έγγραφα μαθήματος</title>
        </head>
        
        <body>
            <header>
                <h1>Εργασίες Έγγραφα μαθήματος</h1>
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

                    <!-- Εμφάνιση κουμπιού προσθήκης εργασίας μόνο για διαχειριστές -->
                     <?php if ($_SESSION['role'] == "Tutor") { ?>
                        <div>
                            <a style="font-size: larger;" href="./addHomework.php">Προσθήκη νέας εργασίας</a> <br> 
                        </div>
                        <!-- Γραμμή -->
                        <hr>
                    <?php } ?>

                    <!-- Εμφάνιση εργασιών -->
                     <?php 
                     $sql = "SELECT * FROM homework";
                     $result = $conn->query($sql);
                     
                     
                     if($result->num_rows > 0) {
                      while($row = $result->fetch_assoc()) {
                        $goals = [];
                        $location = [];
                        $required_files = [];
                        $date = [];
                        $id = [];
                        $processedGoals = [];
                        $dataArrayGoals = [];
                        $dataArrayRequiredFiles = [];
                        $processedRequiredFiles = [];

                        
                        $id = $row["id"];
                        $goals = $row['goals'];
                        $location=$row["location"];
                        $required_files=$row['required_files'];
                        $date=$row['date'];
                        $dataArrayGoals = [];
                        
                        // Διαχωρισμός των αριθμητικών χαρακτήρων
                        $processedGoals = preg_replace('/(\d+)/', "$1", $goals);
                        $processedRequiredFiles = preg_replace('/(\d+)/', "$1", $required_files);

                        // Αποθήκευση των επεξεργασμένων δεδομένων στο array
                        $dataArrayGoals[] = nl2br($processedGoals);
                        $dataArrayRequiredFiles[] = nl2br($processedRequiredFiles);?>  

                        <!-- Κάθε εργασία -->
                        <h2>Εργασία <?php echo $id?></h2>
                         <!-- Εμφάνιση κουμπιών διαγραφής και επεξεργασίας μόνο για διαχειριστές -->
                          <?php 
                          if ($_SESSION['role'] == "Tutor"): ?><span style="font-size: smaller; margin-left: 10px;">
                            <a href='./deleteHomework.php?id=<?php echo $id; ?>' style="font-size: smaller;">[Διαγραφή]</a> 
                            <a href='./editHomework.php?id=<?php echo $id; ?>' style="font-size: smaller;">[Επεξεργασία]</a></span>
                            <?php 
                          endif; ?></h2>
                          
                          
                          <!-- Περιεχόμενο εργασίας -->
                          <b1>
                           <div class="border">
                            <i>Στόχοι: Οι στόχοι της εργασίας είναι:</i>
                            <div style="margin-left: 5%;">
                              <?php  
                              // Εκτύπωση των δεδομένων σε ξεχωριστή επανάληψη
                              foreach ($dataArrayGoals as $processedGoals) {
                                echo "<tr><td><i>" . $processedGoals . "</i></td></tr>";
                              }
                              echo "</table>";?> 
                             </div>

                              <i>Εκφώνηση:</i> 
                              <div style="margin-left: 5%;">
                                Κατεβάστε την εκφώνηση από -> <a href="./<?php echo $location ?>">εδώ</a>
                              </div>  

                              <i>Παραδοτέα:</i>
                              <div style="margin-left: 5%;">
                                <?php   
                                // Εκτύπωση των δεδομένων σε ξεχωριστή επανάληψη
                                foreach ($dataArrayRequiredFiles as $processedRequiredFiles) {
                                  echo "<tr><td><i>" . $processedRequiredFiles . "</i></td></tr>";
                                }
                                echo "</table>";?> 
                              </div>

                              <b><i><r>Ημερομηνία παράδοσης: </r></i></b>
                              <?php 

                              $dateObject = new DateTime($date);

                              // Μορφοποίηση σε DD/MM/YYYY
                              $formattedDate = $dateObject->format("d/m/Y");
                              echo $formattedDate; ?></p>   
                            </div>
                          </b1>

                            <?php
                            }
                          }
                          else 
                          {
                            ?> 
                            <!-- Μήνυμα εάν δεν υπάρχουν εργασίες -->
                             <h2> ΔΕΝ ΥΠΑΡΧΟΥΝ ΕΡΓΑΣΙΕΣ</h2> 
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