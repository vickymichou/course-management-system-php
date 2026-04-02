<!-- ΑΠΟΣΥΝΔΕΣΗ -->

<?php

    session_start(); 
        unset($_SESSION); // Καταργήστε όλες τις μεταβλητές της συνεδρίας
        session_destroy(); // Καταστροφή της συνεδρίας
        header('Location: login.php'); // Ανακατεύθυνση στη σελίδα σύνδεσης
    exit; 

?>
