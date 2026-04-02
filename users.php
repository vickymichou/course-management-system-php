<!-- ΧΡΗΣΤΕΣ -->

<?php

    session_start();

        // Συμπερίληψη του αρχείου functions.php που περιέχει συναρτήσεις χρήσιμες για το πρόγραμμα
        include("functions.php");

        // Έλεγχος αν ο χρήστης δεν έχει συνδεθεί. Αν δεν έχει, τον ανακατευθύνει στη σελίδα σύνδεσης.
        if(!isset($_SESSION['username']))
        { 
            header("Location: login.php");
        }

        $_SESSION['table'] = 'users';
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
        height: 100%;
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
        border: 1px solid;
        text-align:center;
        justify-content: center;
	    display:block;
        border-bottom: 1px solid;

    } 
    header h1{
        font-size: 20px;
        padding: 10px 24px;
        margin:0;																													
    }
    .user{
        margin: 1%;
         flex: 1 1;
         flex-wrap: nowrap;
         background-color: #7aa8b7;
         color: white;
        }
        
        .user_body{
            margin: 1%;
            background-color: #7aa8b7;
            
        } 

        .fl-table td {
            padding: 10px;
        } 
    
    
    </style>

        <head>
            <meta charset="UTF-8">
            <title>Διαχείριση Χρηστών</title>
        </head>

        <body>
        <header>
            <h1>Διαχείριση Χρηστών</h1>
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
                
            
                <div class="user"  >

                <div class="user_body" >

                    <thead>
                    <?php

                        // Ερώτημα SQL για την επιλογή όλων των χρηστών από τον πίνακα users
                        $query = "SELECT * FROM users"; 

                        // Έναρξη γραμμής πίνακα
                        echo '<table class="fl-table"> 
                        <tr> 
                            <td> <b>First Name</font> </td> 
                            <td> <b>Last Name</font> </td> 
                            <td> <b>Email</font> </td> 
                            <td> <b>Password</font> </td> 
                            <td> <b>Role</font> </td> 
                        </tr>';
                        // Τέλος γραμμής πίνακα
                    ?>
                    </thead>

                    <?php   

                        // Εκτέλεση του ερωτήματος προς τη βάση δεδομένων και εμφάνιση των αποτελεσμάτων
                        if ($result = $conn->query($query)) 
                        {
                            while ($row = $result->fetch_assoc()) 
                            {
                                // Ανάθεση τιμών σε μεταβλητές από τα αποτελέσματα του ερωτήματος
                                $firstName = $row["firstName"];
                                $lastName = $row["lastName"];
                                $loginame = $row["loginame"];
                                $password = $row["password"];
                                $role = $row["role"];

                                $password = hidePwd($password); 

                                // Εμφάνιση των δεδομένων σε μια γραμμή πίνακα
                                echo "<tr> 
                                    <td>$firstName</td> 
                                    <td>$lastName</td> 
                                    <td>$loginame</td> 
                                    <td>$password</td> 
                                    <td>$role</td>
                                    <td> <a href='./editUser.php?loginame=$row[loginame]'><b>[Επεξεργασία] </a>
                                    <td> <a href='./deleteUser.php?loginame=$row[loginame]'><b>[Διαγραφή] </a>
                                </tr>";
                            }
                            $result->free(); // Απελευθέρωση του αποτελέσματος
                        }
                ?>
            
            </div>
            <div style="text-align:center;float:left;">
                <!-- Σύνδεσμος για προσθήκη νέου χρήστη -->
                <a  style="font-size: larger;" href='./addUser.php' > <b>Προσθήκη Χρήστη</a>
                
            </div> 

            </main>
        </div>
    </body>

</html>