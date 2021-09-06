<?php
    class Upload{
        
        private $imagem;
        public  $nome_final   = "";
        public  $erro         = "";
        public  $tamanhoMax   = 2097152;
        public  $tamanhoArquivo = 0;
        public  $livre_tam;
        private $adicional_nome = "";
        private $array_erro   = array('Não houve erro', 'O arquivo no upload é maior do que o limite do PHP', 
            'O arquivo ultrapassa o limite de tamanho especificado no HTML', 'O upload do arquivo foi feito parcialmente', 'Não foi feito o upload do arquivo');
        
        public function __construct($imagem = NULL, $livre_tam = NULL, $adicionalNome="") {
            $this->imagem = $imagem;
            $this->livre_tam = $livre_tam;
            $this->adicional_nome = $adicionalNome;
            $this->upload();
        }
        
        public function __destruct() {
            unset($this);
        }
        
        public function upload($imagem = NULL){
            if(!isset($this->imagem) && isset($imagem)){
                $this->imagem = $imagem;
            }
            if(isset($this->imagem) && $this->imagem != NULL && isset($this->imagem["name"]) 
                    && (($this->imagem['size'] <= $this->tamanhoMax)||($this->imagem['size'] > $this->tamanhoMax && $this->livre_tam == "true"))){
                if($this->imagem['error'] == 0){
                    
                    $separacao        = explode(".", $this->imagem["name"]);
                    $this->nome_final = $this->adicional_nome . date('Y-m-d').time().'.' . $separacao[1]; 
                    $this->moveArquivo($this->imagem['tmp_name'], "../arquivos/". $this->nome_final);
                    $this->tamanhoArquivo = $this->imagem['size'];
                }elseif(isset($this->imagem['error']) && $this->imagem['error'] !== NULL && $this->imagem['error'] !== ""){
                    $this->erro = "Não foi possível fazer o upload, erro:" . $this->array_erro[$this->imagem['error']];
                }        
            }elseif($this->imagem['size'] > $this->tamanhoMax){
                $this->erro = "Tamanho máximo é 2 Mb";
            }else{
                $this->erro = "Não pode fazer upload sem arquivo!";
            }            
        }
        
        private function moveArquivo($temp, $cam){
            $res = move_uploaded_file($temp, $cam);
            if($res == FALSE){
                $this->erro = "Erro ao carregar imagem para servidor!";
            }            
        }
        

	public function upload_png ($tmp, $nome, $largura, $pasta){
		$img = imagecreatefrompng($tmp);
		$x = imagesx($img);
		$y = imagesy($img);
		$altura = ($largura*$y) / $x;
		$nova = imagecreatetruecolor($largura, $altura);
		imagecopyresampled($nova, $img, 0, 0, 0, 0, $largura, $altura, $x, $y);
		imagepng($nova, "$pasta/$nome");
		imagedestroy($nova);
		imagedestroy($img);
		return($nome);
	}        
        
    }