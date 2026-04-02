<!-- Κεφαλίδα σελίδας -->

<?php
  // Εκκίνηση της συνεδρίας
  session_start();

  // Έλεγχος εάν ο χρήστης δεν έχει συνδεθεί, αν όχι τον ανακατευθύνουμε στη σελίδα σύνδεσης
  if(!isset($_SESSION['username']))
  { 
    header("Location: login.php");
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
    .image{
         height: 400px;
         width: 600px;
         display: block;
         margin-left: auto;
         margin-right: auto;
         margin-bottom: 15px;
    }
    </style>

    <head> 
        <meta charset="UTF-8">
		<title>Αρχική σελίδα</title>
	</head>
	<body>
		<header>
            <h1>Αρχική σελίδα</h1>
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
                <p>Καλώς ήρθατε στον ιστοχώρο μας, στόχος του ιστοχώρου αυτού είναι η εκμάθηση της HTML (HyperText Markup Language) ή Γλώσσα Σήμανσης Υπερκειμένου που αποτελεί την κύρια γλώσσα σήμανσης για τη δημιουργία ιστοσελίδων.
                    Οι επιμέρους ιστοσελίδες του site έιναι οι παρακάτω στις οποίες περιέχεται:</br>
                    <div style="margin-left: 5%;">
                        <b>1. Ανακοινώσεις: </b>Η συγκεκριμένη ιστοσελίδα περιέχει ανακοινώσεις σχετικές με το μάθημα. </br>
                        <b>2. Επικοινωνία: </b>Η συγκεκριμένη ιστοσελίδα παρέχει δύο δυνατότητες για την αποστολή e-mail στον καθηγητή:
                        <div style="margin-left: 5%;">
                            •	Μέσω web φόρμας</br>
                            •	Με χρήση e-mail διεύθυνσης</br>
                        </div>
                        <b>3. Έγραφα μαθήματος: </b>Η συγκεκριμένη ιστοσελίδα περιέχει έγγραφα σχετικά με το μάθημα τα οποία μπορούν να καταβιβάσουν (download) οι μαθητές</br>
                        <b>4. Εργασίες: </b>Η συγκεκριμένη ιστοσελίδα περιέχει εκφωνήσεις εργασιών που μπορούν να καταβιβάσουν (download) οι μαθητές. </br>
                    </div>
                <img src="img.png" class="image" >
            </main>
        </div>
    </body>

</html>