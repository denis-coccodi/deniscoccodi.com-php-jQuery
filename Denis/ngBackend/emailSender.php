<?php
	if((array_key_exists("sender",$_POST)) and (array_key_exists("recipient",$_POST)) 
	and (array_key_exists("subject",$_POST)) and (array_key_exists("message",$_POST)))
	{
		$email=$_POST["recipient"];
		$subject=$_POST["subject"];
		$message=$_POST["message"];
		$headers='From: '.$_POST['sender'] . "\r\n" .
					'Reply-To: '.$_POST['sender'] . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
		$answer = array();
		if(mail($email,$subject,$message,$headers))
		{
			$answer[0] = "The message was sent successfully!";
			$answer[1] = "Messaggio inviato!";
			$answer[2] = true;
			echo json_encode(array("eng"=>$answer[0], "ita"=>$answer[1], "flag"=>$answer[2]));
			
		}
		else
		{
			$answer[0] = "The message could not be sent, Try again later.";
			$answer[1] = "E\' stato riscontrato un errore del server, prova più tardi.";
			$answer[2] = false;
			echo json_encode(array("eng"=>$answer[0], "ita"=>$answer[1], "flag"=>$answer[2]));
		}
	}
?>