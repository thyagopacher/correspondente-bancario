<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include 'PHPMailer/PHPMailerAutoload.php';

class Email {

    public $origem = "South Negócios";
    public $origem_email  = "aviso.eletronico@southnegocios.com";
    public $usuario_email = "aviso.eletronico@southnegocios.com";
    public $para;
    public $para_email;
    public $copia;
    public $copia_email;
    public $mensagem;
    public $assunto;
    public $rodape = "<br><b>Mensagem eletrônica do sistema @ South Negócios</b></br>";

    /*     * phpmailer */
    public $host = "southnegocios.com";
    public $senha = "6vdBnApf3J";
    public $ehHtml = true;
    public $porta = 587; //porta padrão
    public $seguranca = "tls";
    public $autenticacao = true;
    public $erro = "";
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer;
        $this->mail->IsSMTP();
    }

    public function __destruct() {
        unset($this);
    }

    public function envioSimples() {
        $headers = "From: {$this->origem} <{$this->origem_email}>\n";
        if ($this->ehHtml == true) {
            $headers .= "Content-type: text/html; charset=utf-8\n";
            if(isset($this->copia_email) && $this->copia_email != NULL && $this->copia_email != ""){
                $headers .= "Bcc: {$this->copia_email}\r\n";
            }
        }
        return mail($this->para_email, $this->assunto, $this->mensagem . $this->rodape, $headers);
    }

    public function envia() {
        $this->mail->setLanguage('br');   
        $this->mail->CharSet='UTF-8';
        $this->mail->isSMTP();  
        $this->mail->Host       = $this->host;
        $this->mail->SMTPAuth   = $this->autenticacao;
        $this->mail->Username   = $this->usuario_email;
        $this->mail->Password   = $this->senha;
        $this->mail->Port       = $this->porta;
      
        $this->mail->SMTPSecure = $this->seguranca;
        $this->mail->SetFrom($this->origem_email_final); // E-mail do remetente
        $this->mail->FromName = $this->origem; // Nome do remetente
        $this->mail->AddAddress($this->para_email, $this->para);
        if(isset($this->copia_email) && $this->copia_email != NULL && $this->copia_email != ""){
            $this->mail->addBCC($this->copia_email, $this->copia);
        }
        $this->mail->AddReplyTo($this->origem_email, $this->origem);
        $this->mail->IsHTML($this->ehHtml);
        $this->mail->Subject = $this->assunto;
        $this->mail->Body = $this->mensagem . $this->rodape;           
        $resenvio = $this->mail->send(); 
        $this->erro = $this->mail->ErrorInfo;
        if(!$resenvio || ($this->erro != NULL && $this->erro != "")){
            $resenvio = $this->envioSimples();
        }
        return $resenvio;
    }

}
//
$email = new Email();
$email->mensagem = "teste de envio - weblink";
$email->assunto = "teste de envio - weblink";
$email->para_email = "thyago.pacher@gmail.com";
$email->para = "Thyago henrique pacher";
$email->envia();
if($email->erro != NULL && $email->erro != NULL && $email->erro != ""){
    echo "Erro:".$email->erro;
}else{
    echo "Enviado com sucesso";
}
