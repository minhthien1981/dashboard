<?php defined('BASEPATH') OR exit('No direct script access allowed');
    class Phpmailer_lib{
        public function __construct(){
            log_message('Debug', 'PHPMailer class is loaded.');
        }

        public function smtpmailer($recipients , $from, $from_name, $subject, $body){
            require_once(APPPATH."third_party/phpmailer.php");
            $mail = new PHPMailer;
            $mail->IsSMTP();
            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'ssl';
            $mail->Host = GHOST;
            $mail->Port = GPORT; 
            $mail->Username = GUSER;
            $mail->Password = GPWD;           
            $mail->SetFrom($from, $from_name);

            if(is_array($recipients)){
                foreach($recipients as $key => $value){
                    $mail->AddAddress($value['email'], $value['name']);
                }
            }else{
                $mail->AddAddress($recipients);
            }
            
            //$mail->addReplyTo($from, $from_name);
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');
            
            $mail->Subject = $subject;
            $mail->Body = $body;

            
            $mail->CharSet = 'UTF-8';
            $mail->ContentType = 'text/html';
            if(!$mail->Send()) {
                $error_email = 'Error please try again: '.$mail->ErrorInfo;
                return false;
            } else {
                $error_email = 'Email sent';
                return true;
            }
        }
    }