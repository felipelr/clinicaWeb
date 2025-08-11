<?php 
$errors = '';
$myemail = 'contato@conexaovivamais.com';
if(empty($_POST['name'])  ||
   empty($_POST['email']) ||
   empty($_POST['message']))
{
$errors .= "\nError: Required Field";
}

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

//if (!preg_match(
//"^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", 
//$email))
//{
//$errors .= "\n Error: Invalid Email Address";
//}

if( empty($errors))
{
$to = $myemail;
$email_subject = "A New Message Awaits: $subject";
$email_body = "You have received a new message. Details are given below.\nName: $name \nEmail: $email \nMessage: \n$message";
$headers = "From: $myemail";

if(mail($to, $email_subject, $email_body, $headers)){
	echo "Mensagem enviada com sucesso. \n" . $email_body;
} else {
	echo "A mensagem não pode ser enviada";
}
} else {
	echo $errors;
}
?>
