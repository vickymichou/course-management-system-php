
<?php

    // Συνάρτηση για τη σύνδεση στη βάση δεδομένων
    function connectToDb(&$conn)
    {
        // Σύνδεση στη βάση δεδομένων
        $conn = new mysqli('localhost', 'root', '', 'student3976');

        // Σύνδεση online
// NOTE: Replace with your own database credentials when running locally
        // Έλεγχος εάν η σύνδεση απέτυχε
        if ($conn->connect_error)
        {
            die('Connection Failed : ' . $conn->connect_error);
        }
    }

    // Συνάρτηση για έλεγχο της μορφής μιας ημερομηνίας
    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    // Συνάρτηση για έλεγχο εάν ένα κείμενο είναι κενό ή περιέχει μόνο κενά
    function stringIsNullOrWhitespace($text)
    {
        return ctype_space($text) || $text === "" || $text === null;
    }

    // Συνάρτηση για μεταφόρτωση ενός αρχείου
    function uploadFile($pwd)
    {
        $path_filename_ext = "";

        if (($_FILES[$pwd]['name'] != ""))
        {
            $target_dir = "./uploads/";

            $file = $_FILES[$pwd]['name'];
            $path = pathinfo($file);
            $filename = $path['filename'];
            $ext = $path['extension'];
            $temp_name = $_FILES[$pwd]['tmp_name'];
            $path_filename_ext = $target_dir . $filename . "." . $ext;

            move_uploaded_file($temp_name, $path_filename_ext);
        }

        return $path_filename_ext;
    }

    // Συνάρτηση για απόκρυψη του κωδικού πρόσβασης
    function hidePwd($pwd)
    {
        $length = strlen($pwd);
        $encryptPwd = "";

        for($i = 0; $i < $length; $i++)
        {
            $encryptPwd .= '*';
        }

        return $encryptPwd;
    }

    // Συνάρτηση για έλεγχο εάν ένα email υπάρχει ήδη στη βάση δεδομένων
    function emailExists($email)
    {
        connectToDb($conn);

        $sql = "SELECT loginame FROM users where loginame = '$email' ";
        $result = $conn
        ->query($sql);

        if($result)
        {
            // Εάν υπάρχουν αποτελέσματα, επαναλαμβάνουμε τα δεδομένα και ελέγχουμε το email
            while ($row = $result->fetch_assoc())
            {
                $existing_email = $row['loginame'];

                // Εάν το email υπάρχει, επιστρέφουμε 1
                if($email == $existing_email){
                    return 1;
                }
            }
        }
        else
        {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Επιστροφή 0 αν το email δεν υπάρχει στη βάση δεδομένων
        return 0;
    }

?>