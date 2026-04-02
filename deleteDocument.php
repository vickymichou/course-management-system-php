<!-- ΔΙΑΓΡΑΦΗ -->

<?php

    session_start(); 

    include("functions.php"); 

    // Έλεγχος εάν έχει περαστεί το id μέσω του URL
    if(isset($_GET["id"]))
    {
        // Απόκτηση του id από το URL
        $id = $_GET["id"];

        // Σύνδεση στη βάση δεδομένων
        connectToDb($conn);

        // Ανάκτηση του ονόματος του πίνακα από την συνεδρία
        $table = $_SESSION['table'];
        
        // Δημιουργία ερωτήματος SQL για διαγραφή εγγραφής με βάση το id
        $sql = "DELETE FROM $table WHERE id='$id' ";

        // Εκτέλεση του ερωτήματος SQL
        $conn->query($sql);

        // Ανακατεύθυνση προς τη σελίδα $table.php μετά τη διαγραφή
        header("Location: $table.php");
        exit;
    }
    else
    {
        // Αν η παράμετρος loginame υπάρχει στο URL
        $loginame = $_GET["loginame"];

        // Σύνδεση στη βάση δεδομένων
        connectToDb($conn);

        // Ανάκτηση του ονόματος του πίνακα από τη συνεδρία
        $table = $_SESSION['table'];
        
        // Δημιουργία ερωτήματος SQL για διαγραφή εγγραφής με βάση το loginame
        $sql = "DELETE FROM $table WHERE loginame='$loginame' ";

        // Εκτέλεση του ερωτήματος SQL
        $conn->query($sql);

        header("Location: $table.php");
        exit;
    }

?>
