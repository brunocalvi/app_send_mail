<?php
	require"./bibliotecas/PHPMailer/Exception.php";
	require"./bibliotecas/PHPMailer/OAuth.php";
	require"./bibliotecas/PHPMailer/PHPMailer.php";
	require"./bibliotecas/PHPMailer/POP3.php";
	require"./bibliotecas/PHPMailer/SMTP.php";

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	//print_r($_POST);

	class Mensagem {
		private $para = null;
		private $assunto = null;
		private $mensagem = null;
		public $status = array(	'codigo_status' => null,
								'descricao_status' => '' );

		public function __get($atributo) {
			return $this->$atributo;
		}

		public function __set($atributo, $valor) {
			$this->$atributo = $valor;
		}

		public function mensagemValida() {
			if (empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
				return false;
			}

			return true;
		}
		
	}

	$mensagem = new Mensagem();

	$mensagem->__set('para', $_POST['para']);
	$mensagem->__set('assunto', $_POST['assunto']);
	$mensagem->__set('mensagem', $_POST['mensagem']);

	//print_r($mensagem);

	if(!$mensagem-> mensagemValida()) {
		echo "Mensagem invalida !";
		header('Location: index.php');
	}

	$mail = new PHPMailer(true);

try {
    //Configurações do servidor
    $mail->SMTPDebug = false;                      	//Ativar saída de depuração detalhada
    $mail->isSMTP();                                            	//Enviar usando SMTP
    $mail->Host       = 'smtp.gmail.com';                     		//Defina o servidor SMTP para enviar
    $mail->SMTPAuth   = true;                                   	//Habilitar autenticação SMTP
    $mail->Username   = 'calvireis@gmail.com';                     	//SMTP username
    $mail->Password   = 'colocar_senha';                            //SMTP password ->trocar a senha !!
    $mail->SMTPSecure = 'tls';            							//Ativar criptografia TLS implícita
    $mail->Port       = 587;                                    	//Porta TCP para conectar; use 587 se você tiver definido `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('brunocalvi@hotmail.com', 'Bruno Calvi Remetente');
    $mail->addAddress($mensagem->__get('para'));     								//Adicionar um destinatário
    //$mail->addAddress('ellen@example.com');               						//O nome é opcional -> se caso for ter mais de um destinatario
    //$mail->addReplyTo('info@example.com', 'Information');  						//remetente que ira receber a resposta 
    //$mail->addCC('cc@example.com'); 												//Adiciona o destinatario em copia
    //$mail->addBCC('bcc@example.com'); //copia oculta

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         						//Adicionar Anexos
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    						//Nome opcional

    //Content
    $mail->isHTML(true);                                  							//Definir formato de e-mail para HTML
    $mail->Subject = $mensagem->__get('assunto'); 									//assunto do e-mail
    $mail->Body    = $mensagem->__get('mensagem');
    $mail->AltBody = 'É necessario utilizar um cliente que suporte HTML';

    $mail->send();

    $mensagem->status['codigo_status'] = 1;
    $mensagem->status['descricao_status'] = 'E-mail enviado com sucesso !!';
    
} catch (Exception $e) {

	$mensagem->status['codigo_status'] = 2;
    $mensagem->status['descricao_status'] = "Não foi possivel enviar este e-mail, Por favor tente mais tarde !!: 
    										<br/><br/> Detalhes do erro: " . $mail->ErrorInfo;

}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	
</head>
<body>
	<div class="container">

		<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>
	</div>

	<div class="row">
		<div class="col-md-12">

			<?php if($mensagem->status['codigo_status'] == 1) {?>

				<div class="container">
					<h1 class="display-4 text-success">Sucesso</h1>
					<p><?= $mensagem->status['descricao_status']?></p>
					<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
				</div>

			<?php }?>

			<?php if($mensagem->status['codigo_status'] == 2) {?>

				<div class="container">
					<h1 class="display-4 text-danger">Ops!</h1>
					<p><?= $mensagem->status['descricao_status']?></p>
					<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
				</div>
				

			<?php }?>
		
	   </div>
    </div>
</body>
</html>