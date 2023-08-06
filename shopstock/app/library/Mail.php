<?php
class Mail
{
    static function sendMail($content, $destination, $message)
    {
        $sender         = EMAIL;
        if ($content == "OTPLogin") {
            $subject = "Verifikasi OTP";
        } else if ($content == "resetPassword") {
            $subject = "Reset Password";
        } else {
            $subject = "INFO";
        }
        $headers = "From: KASIR RTRW.NET <" . $sender . ">";
        mail($destination, $subject, $message, $headers);
    }
}
