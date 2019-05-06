<?php

require("./sendgrid-php/sendgrid-php.php");


if($_POST) {

    $name = trim(stripslashes($_POST['contactName']));
    $email = trim(stripslashes($_POST['contactEmail']));
    $subject = trim(stripslashes($_POST['contactSubject']));
    $contact_message = trim(stripslashes($_POST['contactMessage']));

    // Check Name
    if (strlen($name) < 2) {
        $error['name'] = "Please enter your name.";
    }
    // Check Email
    if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email)) {
        $error['email'] = "Please enter a valid email address.";
    }
    // Check Message
    if (strlen($contact_message) < 15) {
        $error['message'] = "Please enter your message. It should have at least 15 characters.";
    }
    // Subject
    if ($subject == '') { $subject = "Contact Form Submission"; }

    $semail = new \SendGrid\Mail\Mail(); 
    $semail->setFrom("bwslead@brownwolfstudio.com", "BWS Lead Generation Form");
    $semail->setSubject("$subject:$name:$email");
    $semail->addTo("contact@brownwolfstudio.com", "BWS Lead Generation - Contact");
    $semail->addContent("text/plain", $contact_message);
    $sendgrid = new \SendGrid(getenv("SENDGRID_API_KEY"));
    
    if (!$error) {
        
        try {
            $sendgrid->send($semail);
            echo "OK";
        } catch (Exception $e) {
            echo "Something went wrong. Please try again.";
        }
        
    } # end if - no validation error

    else {

        $response = (isset($error['name'])) ? $error['name'] . "<br /> \n" : null;
        $response .= (isset($error['email'])) ? $error['email'] . "<br /> \n" : null;
        $response .= (isset($error['message'])) ? $error['message'] . "<br />" : null;
        
        echo $response;

    } # end if - there was a validation error

}

?>