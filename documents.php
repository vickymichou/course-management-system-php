<!-- ΕΓΓΡΑΓΑ ΜΑΘΗΜΑΤΟΣ -->

<?php

  include("functions.php");

  session_start();

  // Έλεγχος εάν ο χρήστης δεν έχει συνδεθεί, σε αυτή την περίπτωση τον ανακατευθύνει στη σελίδα σύνδεσης
  if(!isset($_SESSION['username']))
  { 
    header("Location: login.php");
  }

  $_SESSION['table'] = 'documents';

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
        <head> <meta charset="UTF-8">
            <title>Έγγραφα μαθήματος</title>
        </head>
        <body>
            <header>
                <h1>Έγγραφα μαθήματος</h1>
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
                    
                    <!-- Εμφάνιση κουμπιού προσθήκης εγγράφου μόνο για τους εκπαιδευτικούςς -->
                    <?php if ($_SESSION['role'] == "Tutor") { ?>
                        <div>
                            <a style="font-size: larger;" href="./addDocument.php">Προσθήκη νέου εγγράφου</a> <br> 
                        </div>
                        <!-- Γραμμή -->
                        <hr>
                    <?php } ?>

                    <?php 
                
                // Εκτέλεση SQL ερωτήματος για ανάγνωση εγγράφων
                $sql = "select * from documents";
            
                $result = $conn->query($sql);
                          
                if($result->num_rows > 0)
                {
                  while($row = $result->fetch_assoc())
                  {
                    
                    ?>
                    <!-- Εμφάνιση εγγράφου -->
                    <h2> <?php echo  $row['title']?></h2>
                         <!-- Εμφάνιση κουμπιών διαγραφής και επεξεργασίας μόνο για εκπαιδευτικούς -->
                          <?php 
                          if ($_SESSION['role'] == "Tutor"): ?>
                            <a href='./deleteDocument.php?id=<?php echo  $row['id']; ?>' style="font-size: smaller;">[Διαγραφή]</a> 
                            <a href='./editDocument.php?id=<?php echo  $row['id']; ?>' style="font-size: smaller;">[Επεξεργασία]</a>
                            <?php 
                          endif; ?>
                          </h2>
                          
              
                        <!-- Περιγραφή εγγράφου -->

                        <b1>
                        <div class="border">
                            <p><i>Περιγραφή: </i><?php echo $row['description']?></p>
                            <a href="./<?php echo $row['location']; ?>">Download</a>
                        </div>
                        </b1>              
                        
                    <?php 
                    }
                      } 
                      else
                      {
              
                    ?> 
                    
                    <h2> ΔΕΝ ΥΠΑΡΧΟΥΝ ΕΓΓΡΑΦΑ </h2> 
                      
                    <?php 
                      }
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