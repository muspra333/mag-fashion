<?php
// Include the PHPMailer files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';  // Adjust path if needed
require 'PHPMailer/Exception.php';
require 'PHPMailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $position = htmlspecialchars($_POST['position']);
    $message = htmlspecialchars($_POST['message']);
    
    // Qualification Details
    $qualification = htmlspecialchars($_POST['qualification']);
    $university = htmlspecialchars($_POST['university']);
    $year_of_graduation = htmlspecialchars($_POST['year_of_graduation']);

    // File upload handling (resume)
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $resume = $_FILES['resume'];
        $resumeName = $resume['name'];
        $resumeTmpName = $resume['tmp_name'];
        $resumeType = $resume['type'];
        $resumeSize = $resume['size'];

        // Set allowed file types for the resume
        $allowedExts = ['pdf', 'doc', 'docx'];
        $fileExt = pathinfo($resumeName, PATHINFO_EXTENSION);

        // Check for allowed file types
        if (in_array(strtolower($fileExt), $allowedExts)) {
            // PHPMailer instance
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.example.com'; // Your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'your-email@example.com'; // Your email
                $mail->Password = 'your-email-password'; // Your email password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('your-email@example.com', 'Bridal Studio');
                $mail->addAddress('your-email@example.com', 'Bridal Studio HR'); // Your email

                // Attachments
                $mail->addAttachment($resumeTmpName, $resumeName); // Attach resume

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Career Application - Bridal Studio';
                $mail->Body    = "
                    <h3>New Career Application</h3>
                    <p><strong>Name:</strong> $name</p>
                    <p><strong>Email:</strong> $email</p>
                    <p><strong>Phone:</strong> $phone</p>
                    <p><strong>Position Applied For:</strong> $position</p>
                    <p><strong>Message:</strong> $message</p>
                    <p><strong>Highest Qualification:</strong> $qualification</p>
                    <p><strong>University/Institution:</strong> $university</p>
                    <p><strong>Year of Graduation:</strong> $year_of_graduation</p>
                ";

                // Send the email
                $mail->send();
                echo "<p>Application submitted successfully. We will contact you soon.</p>";

            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "<p>Invalid file type. Please upload a PDF or Word document.</p>";
        }
    } else {
        echo "<p>No resume uploaded or error with the upload.</p>";
    }
}
?>
