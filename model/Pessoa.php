<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Pessoa{
    
    public $codpessoa;
    public $nome;
    public $login;
    public $email;
    public $senha;
    public $telefone;
    public $celular;
    public $codempresa;
    public $imagem;
    public $codnivel;
    public $dtcadastro;
    public $apartamento;
    public $bloco;
    public $status;
    public $recebemsg;
    public $respocorrencia;
    public $fazreserva;
    public $sexo;
    public $dtnascimento;
    public $codparentesco;
    public $morador;
    public $aceitacopiaadm;
    public $acessapainel;
    public $liberaacesso;
    public $aposentado;
    public $cpf;
    public $rg;
    public $estadocivil;
    public $tipologradouro;
    public $logradouro;
    public $numero;
    public $bairro;
    public $cidade;
    public $estado;
    public $dtemissaorg;
    public $ufrg;
    public $localnascimento;
    public $mae;
    public $pai;
    public $codimportacao;
    public $codstatus;
    public $sistema;
    public $porcentagem;
    public $usuarioViper;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir(){
        if(!isset($this->dtcadastro) || $this->dtcadastro == NULL || $this->dtcadastro == ""){
            $this->dtcadastro = date("Y-m-d H:i:s");
        }
        if(!isset($this->codempresa) || $this->codempresa == NULL || $this->codempresa == ""){
            $this->codempresa = $_SESSION["codempresa"];
        }
        if(!isset($this->dtcadastro) || $this->dtcadastro == NULL || $this->dtcadastro == ""){
            $this->dtcadastro = date("Ymd");
        }
        if(isset($this->senha) && $this->senha != NULL && $this->senha != ""){
            $this->senha = base64_encode(strtolower($this->senha));
        }
        if(isset($this->cep) && $this->cep != NULL && $this->cep != ""){
            $this->cep = $this->soNumero($this->cep);
        }
        return $this->conexao->inserir("pessoa", $this);
    }
    
    public function atualizar(){
        if(isset($this->senha) && $this->senha != NULL && $this->senha != ""){
            $this->senha = base64_encode(strtolower($this->senha));
        }        
        if(isset($this->nome) && $this->nome != NULL && $this->nome != ""){
            $this->nome = addslashes($this->nome);
        }        
        if(isset($this->cep) && $this->cep != NULL && $this->cep != ""){
            $this->cep = $this->soNumero($this->cep);
        }        
        return $this->conexao->atualizar("pessoa", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("pessoa", $this);
    }
    
    public function procuraCodigo($codpessoa){
        return $this->conexao->comandoArray(("select * from pessoa where codpessoa = '{$codpessoa}'"));
    }
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from pessoa where nome like '%{$nome}%' and codempresa='{$this->codempresa}' order by nome");
    } 
    
    public function procuraPessoas($and){
        $sql = "select pessoa.nome, pessoa.cpf, 
        pessoa.tipologradouro, pessoa.logradouro, pessoa.numero, pessoa.bairro, pessoa.cidade, pessoa.estado,
        statuspessoa.nome as status, telefone.numero, obs.texto as observacao_ligacao, 
        DATE_FORMAT(obs.dtcadastro, '%d/%m/%Y') as dtcadastro_obsligacao,
        beneficiocliente.numbeneficio, beneficiocliente.salariobase, beneficiocliente.margem, especie.nome as especie,
        emprestimo.prazo, emprestimo.quitacao, emprestimo.vlparcela, emprestimo.meio, emprestimo.situacao
        from pessoa 
        inner join empresa on empresa.codempresa = pessoa.codempresa
        inner join statuspessoa on statuspessoa.codstatus = pessoa.codstatus
        inner join telefone on telefone.codpessoa = pessoa.codpessoa
        inner join observacaocliente as obs on obs.codpessoa = pessoa.codpessoa
        inner join beneficiocliente on beneficiocliente.codpessoa = pessoa.codpessoa
        inner join especie on especie.codespecie = beneficiocliente.codespecie
        inner join emprestimo on emprestimo.codpessoa = pessoa.codpessoa
        where pessoa.codstatus <> '1' and pessoa.codcategoria in(1,6) 
        and pessoa.estado <> '' {$and}";
        return $this->conexao->comando($sql);
    } 
    
    public function login(){
        $this->login = addslashes($this->login);
        $sql = "select codpessoa, nome, codnivel, codempresa, imagem, status, primeiroacesso
            from pessoa 
        where login = '{$this->login}' 
        and senha = '".base64_encode($this->senha)."'"; 
        return $this->conexao->comandoArray($sql);
    }
    
    public function soNumero($str) {
        return preg_replace("/[^0-9]/", "", $str);
    }       
 
    public function validaCPF($cpf = null) {
        if(empty($cpf)) {return false;}// Verifica se um número foi informado
        $cpf = str_pad(preg_replace("/[^0-9]/", "", $cpf), 11, '0', STR_PAD_LEFT);// Elimina possivel mascara
        if (strlen($cpf) != 11) {// Verifica se o numero de digitos informados é igual a 11 
            return false;
        }else if ($cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || 
            $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {// Verifica se nenhuma das sequências invalidas abaixo  foi digitada. Caso afirmativo, retorna falso
            return false;
         } else {// Calcula os digitos verificadores para verificar se o CPF é válido   
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }
            return true;
        }
    }  
    

    public function geraSenha($tamanho = 8, $maiusculas = false, $numeros = false, $simbolos = false) {
        $lmin = 'abcdefghijklmnopqrstuvwxyz';
        $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '1234567890';
        $simb = '!@#$%*-';
        $retorno = '';
        $caracteres = '';
        $caracteres .= $lmin;
        if ($maiusculas) {
            $caracteres .= $lmai;
        }
        if ($numeros) {
            $caracteres .= $num;
        }
//        if ($simbolos) {
//            $caracteres .= $simb;
//        }
        $len = strlen($caracteres);
        for ($n = 1; $n <= $tamanho; $n++) {
            $rand = mt_rand(1, $len);
            $retorno .= $caracteres[$rand - 1];
        }
        return $retorno;
    }    
    
}