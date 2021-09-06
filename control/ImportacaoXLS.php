<?php

set_time_limit(0);
ini_set('max_execution_time', '-1');

header('Content-Type: text/html; charset=utf-8');


function __autoload($class_name) {
    if (file_exists("../model/" . $class_name . '.php')) {
        include "../model/" . $class_name . '.php';
    } elseif (file_exists("../visao/" . $class_name . '.php')) {
        include "../visao/" . $class_name . '.php';
    } elseif (file_exists("./" . $class_name . '.php')) {
        include "./" . $class_name . '.php';
    }
}

$conexao = new Conexao();
$sql = 'select importacao.arquivo, carteira.nome as carteira, 
    importacao.codempresa, importacao.atualizar_cliente, importacao.adicionar_carteira, 
    importacao.categoriacliente, importacao.codfuncionario , importacao.codcarteira, 
    importacao.codimportacao, importacao.qtdlinha, importacao.qtdimportado
    from importacao 
    inner join carteira on carteira.codcarteira = importacao.codcarteira
    where importacao.terminado = "n" and importacao.arquivo like "%.xls" order by importacao.data asc limit 1';
echo "<pre>{$sql}</pre>";
$resimportacao = $conexao->comando($sql);
$qtdimportacao = $conexao->qtdResultado($resimportacao);


if ($qtdimportacao > 0) {
    while ($importacaop = $conexao->resultadoArray($resimportacao)) {
        echo "<br>Começando a importação:<br>";
        
        $qtdNovo = 0;
        $qtdJaTinha = 0;
        $qtdCpfInvalido = 0;
        $sit_retorno = true;
        $first_row = true;

        include("./excel/reader.php");
        $data = new Spreadsheet_Excel_Reader();

        /** lendo o arquivo xls */
        $data->setOutputEncoding('UTF-8');
        $data->read('../arquivos/' . $importacaop["arquivo"]);
        
        $limiteImportacao = 10 + $importacaop["qtdimportado"];
        if($limiteImportacao >= $importacaop["qtdlinha"]){
            $limiteImportacao = $importacaop["qtdlinha"];
        }
        //$data->sheets[0]['numRows']
        $inicioEm = 2 + $importacaop["qtdimportado"];
        echo "Inicio em: {$inicioEm} - Limite: {$limiteImportacao}<br>";
        for ($i = 2 + $importacaop["qtdimportado"]; $i <= $limiteImportacao; $i++) {

            $pessoa = new Pessoa($conexao);
            //completando com 0 a esquerda até 11 por que excel tira os 0
            $pessoa->cpf = str_pad($data->sheets[0]['cells'][$i][8], 11, "0", STR_PAD_LEFT);
            if (trim($pessoa->cpf) == "" || $pessoa->validaCPF($pessoa->cpf) == FALSE) {
                $qtdCpfInvalido++;
                echo "CPf errado: {$pessoa->cpf}<br>";
                continue; //pulando linha de cpf inválido
            }

            /*             * separando um cpf com ponto e outro sem para garantir na query que não tenha seu contrário */
            if (strpos($pessoa->cpf, ".") == FALSE) {
                $cpf1 = $pessoa->cpf{0} . $pessoa->cpf{1} . $pessoa->cpf{2} . '.' . $pessoa->cpf{3} . $pessoa->cpf{4} . $pessoa->cpf{5} . '.' . $pessoa->cpf{6} . $pessoa->cpf{7} . $pessoa->cpf{8} . '-' . $pessoa->cpf{9} . $pessoa->cpf{10}; //com ponto
                $cpf2 = $pessoa->cpf; //sem ponto
            } else {
                $cpf1 = $pessoa->cpf; //com ponto
                $cpf2 = str_replace('.', "", str_replace("-", "", $pessoa->cpf)); //sem ponto
            }
            
            //verificando se ja tem pessoa com cpf cadastrado
            $sql = "select codpessoa, nome 
                from pessoa where (cpf = '{$cpf1}' or cpf = '{$cpf2}')
                and codempresa = '{$importacaop["codempresa"]}'";
            $pessoap = $conexao->comandoArray($sql);
            if (!isset($pessoap['codpessoa'])) {
                $pessoa->nome = str_replace('"', '', trim($data->sheets[0]['cells'][$i][2]));
            } else {
                $pessoa->codpessoa = $pessoap["codpessoa"];
                $pessoa->nome = $pessoap["nome"];
            }

            //campos base de endereço
            $pessoa->logradouro = utf8_encode(trim($data->sheets[0]['cells'][$i][3]));
            $pessoa->bairro = utf8_encode(trim($data->sheets[0]['cells'][$i][4]));
            $pessoa->cidade = utf8_encode(trim($data->sheets[0]['cells'][$i][6]));
            $pessoa->cep = trim($data->sheets[0]['cells'][$i][7]);
            $pessoa->estado = trim($data->sheets[0]['cells'][$i][9]);

            //conversão da data do padrão excel
            $pessoa->dtnascimento = date(trim($data->sheets[0]['cells'][$i][10]));
            $pessoa->dtnascimento = ($pessoa->dtnascimento - 25569) * 86400;
            $pessoa->dtnascimento = gmdate("Y-m-d", $pessoa->dtnascimento);

            $pessoa->codempresa    = $importacaop['codempresa'];
            $pessoa->codimportacao = $importacaop["codimportacao"];
            if (isset($pessoa->codpessoa) && $pessoa->codpessoa != NULL && $pessoa->codpessoa != "") {
                $resInserirPessoa = true;
                if (isset($importacaop["atualizar_cliente"]) && $importacaop["atualizar_cliente"] != NULL && $importacaop["atualizar_cliente"] != "" && isset($pessoa->codpessoa) && $pessoa->codpessoa != NULL && $pessoa->codpessoa != "") {
                    $resInserirPessoa = $pessoa->atualizar();
                }
                $qtdJaTinha++;
            } else {
                $pessoa->codcategoria = $importacaop["categoriacliente"]; //importação de clientes
                $resInserirPessoa = $pessoa->inserir();
                $pessoa->codpessoa = mysqli_insert_id($conexao->conexao);
                echo "Pessoa inserida<br>";
                $qtdNovo++;
            }

            if ($resInserirPessoa == FALSE) {
                error_log("Erro ao importar pessoa causado por:" . mysqli_error($conexao->conexao), 0);
            } else {
                echo "Inseriu/Atualizou Pessoa com CPF: {$pessoa->cpf}<br>";
                //se tiver inserido com sucesso a linha do cliente vai as tabelas relacionais

                $carteiraCliente = new CarteiraCliente($conexao);
                $carteiraCliente->codcarteira = $importacaop["codcarteira"];
                $carteiraCliente->codcliente = $pessoa->codpessoa;
                $carteiraCliente->codempresa = $importacaop["codempresa"];
                $carteiraCliente->codfuncionario = $importacaop["codfuncionario"];
                $carteiraCliente->dtcadastro = date("Y-m-d H:i:s");
                /*
                 * Adicionar se ja existir
                 */
                if (isset($importacaop["adicionar_carteira"]) && $importacaop["adicionar_carteira"] != NULL && $importacaop["adicionar_carteira"] != "" 
                        && isset($pessoap["codpessoa"]) && $pessoap["codpessoa"] != NULL && $pessoap["codpessoa"] != "") {
                    $resInserirCarteira = $carteiraCliente->inserir();
                    if ($resInserirCarteira == FALSE) {
                        error_log("Erro ao importar carteira. Causado por:" . mysqli_error($conexao->conexao), 0);
                    }else{
                        echo "Adicionado na carteira com sucesso para carteira: {$importacaop["codcarteira"]} - pessoa: {$pessoa->codpessoa}<br>";
                    }
                }

                //inserindo observação ao cliente
                if (isset($data->sheets[0]['cells'][$i][54]) && $data->sheets[0]['cells'][$i][54] != NULL && trim($data->sheets[0]['cells'][$i][54]) != "") {
                    $observacaoCliente = new ObservacaoCliente($conexao);
                    $observacaoCliente->codempresa = $importacaop['codempresa'];
                    $observacaoCliente->codfuncionario = $importacaop['codfuncionario'];
                    $observacaoCliente->codpessoa = $pessoa->codpessoa;
                    $observacaoCliente->dtcadastro = date("Y-m-d H:i:s");
                    $observacaoCliente->texto = trim($data->sheets[0]['cells'][$i][54]);
                    $resInserirObservacao = $observacaoCliente->inserir();
                    if ($resInserirObservacao == FALSE) {
                        error_log("Erro ao importar observação de cliente - 1 causado por:" . mysqli_error($conexao->conexao), 0);
                    }
                }

                //for para telefones
                for ($j = 11; $j <= 16; $j++) {
                    //verificando se o telefone da planilha ja tem
                    $telefonep = $conexao->comandoArray("select codtelefone from telefone where codpessoa = '{$pessoa->codpessoa}' and numero = '" . trim($data->sheets[0]['cells'][$i][$j]) . "'");
                    if (!isset($telefonep["codtelefone"]) || $telefonep["codtelefone"] == NULL || $telefonep["codtelefone"] == "") {
                        $telefone = new Telefone($conexao);
                        $telefone->codempresa = $importacaop["codempresa"];
                        $telefone->codfuncionario = $importacaop["codfuncionario"];
                        $telefone->codpessoa = $pessoa->codpessoa;
                        if ($telefone->identificaCelular($telefone->numero)) {
                            $telefone->codtipo = "3";
                        } else {
                            $telefone->codtipo = "1";
                        }
                        $telefone->dtcadastro = date("Y-m-d H:i:s");
                        $telefone->numero = trim($data->sheets[0]['cells'][$i][$j]);
                        $resInserirTelefone = true;
                        if ((isset($_POST["atualizar_cliente"]) && $_POST["atualizar_cliente"] != NULL && $_POST["atualizar_cliente"] != "" && isset($pessoap["codpessoa"]) && $pessoap["codpessoa"] != NULL && $pessoap["codpessoa"] != "") || (!isset($pessoap["codpessoa"]))) {
                            $resInserirTelefone = $telefone->inserir();
                            if ($resInserirTelefone == FALSE) {
                                $indice_telefone = $j - 11;
                                error_log('Erro ao importar pessoa(telefones - ' . $indice_telefone . '), parou em:' . $pessoa->nome, 0);
                            }
                        }
                    }
                }

                if (trim($data->sheets[0]['cells'][$i][1]) != "") {
                    $beneficiop = $conexao->comandoArray("select codbeneficio from beneficiocliente where codpessoa = '{$pessoa->codpessoa}' and codempresa = '{$importacaop["codempresa"]}' and numbeneficio = '" . trim($data->sheets[0]['cells'][$i][1]) . "'");
                    $beneficio = new BeneficioCliente($conexao);
                    $beneficio->codorgao = 3;
                    $beneficio->codempresa = $importacaop["codempresa"];
                    $beneficio->codfuncionario = $importacaop["codfuncionario"];
                    $beneficio->codpessoa = $pessoa->codpessoa;
                    $beneficio->dtcadastro = date("Y-m-d H:i:s");
                    $codigo_especie = trim($data->sheets[0]['cells'][$i][5]);
                    $especiep = $conexao->comandoArray('select codespecie from especie where numinss = "' . $codigo_especie . '"');
                    $beneficio->codespecie = $especiep["codespecie"];
                    $beneficio->matricula = trim($data->sheets[0]['cells'][$i][1]);
                    $beneficio->numbeneficio = trim($data->sheets[0]['cells'][$i][1]);
                    $beneficio->salariobase = trim($data->sheets[0]['cells'][$i][17]);
                    $beneficio->margem = trim($data->sheets[0]['cells'][$i][18]);
                    $beneficio->meio = trim($data->sheets[0]['cells'][$i][22]);
                    $beneficio->codbanco = trim($data->sheets[0]['cells'][$i][19]);
                    $beneficio->agencia = trim($data->sheets[0]['cells'][$i][20]);
                    $beneficio->contacorrente = trim($data->sheets[0]['cells'][$i][21]);
                    if (isset($beneficiop["codbeneficio"]) && $beneficiop["codbeneficio"] != NULL && $beneficiop["codbeneficio"] != "") {
                        $beneficio->codbeneficio = $beneficiop["codbeneficio"];
                        $resInserirBeneficio = $beneficio->atualizar();
                    } else {
                        $resInserirBeneficio = $beneficio->inserir();
                        $beneficio->codbeneficio = mysqli_insert_id($conexao->conexao);
                    }

                    if ($resInserirBeneficio == FALSE) {
                        error_log("Erro ao importar beneficio de cliente causado por:" . mysqli_error($conexao->conexao), 0);
                    }
                }

                for ($j = 23; $j <= 48; $j = $j + 5) {
                    if (trim($data->sheets[0]['cells'][$i][$j]) != NULL && trim($data->sheets[0]['cells'][$i][$j]) != "") {
                        $emprestimo = new Emprestimo($conexao);
                        $emprestimo->codfuncionario = $importacaop["codfuncionario"];
                        $emprestimo->codpessoa = $pessoa->codpessoa;
                        $emprestimo->dtcadastro = date("Y-m-d H:i:s");
                        $emprestimo->codempresa = $importacaop["codempresa"];
                        $emprestimo->codbeneficio = $beneficio->codbeneficio;
                        $bancop = $conexao->comandoArray("select codbanco from banco where numbanco = '" . trim($data->sheets[0]['cells'][$i][$j]) . "'");
                        $emprestimo->codbanco = $bancop["codbanco"];
                        $emprestimo->meio = trim($data->sheets[0]['cells'][$i][20]);
                        $emprestimo->vlparcela = trim($data->sheets[0]['cells'][$i][$j + 1]);
                        $emprestimo->dtparcela = gmdate("Y-m-d", (trim($data->sheets[0]['cells'][$i][$j + 2]) - 25569) * 86400);
                        $emprestimo->prazo = trim($data->sheets[0]['cells'][$i][$j + 3]);
                        $emprestimo->quitacao = trim($data->sheets[0]['cells'][$i][$j + 4]);
                        $resInserirEmprestimo = $emprestimo->inserir();
                        if ($resInserirEmprestimo === FALSE) {
                            error_log('Erro ao importar empréstimo de cliente. Causado por:' . mysqli_error($conexao->conexao), 0);
                        }
                    }
                }
            }//fim else inserido pessoa com sucesso                
        }//fim for            
        $qtdNImportado = $qtdCpfInvalido;
        $qtdImportacao = $conexao->comandoArray('select count(1) as qtd from pessoa where codimportacao = ' . $importacaop["codimportacao"]);
        if ($qtdImportacao["qtd"] >= $importacaop["qtdlinha"]) {
            $conexao->comando('update importacao set qtdimportado = ' . $qtdNovo . ', qtdnimportado = ' . $qtdNImportado . ', terminado = "s" where codimportacao = ' . $importacaop["codimportacao"]);
        }else{
            $qtdJaTinha += $qtdImportacao["qtd"];
            echo "Ja tinha: {$qtdJaTinha}<br>";
            $qtdNovo = $qtdImportacao["qtd"] + 10;
            echo "Atualizando qtd de importado para: {$qtdNovo}<br>";
            $conexao->comando('update importacao set qtdimportado = qtdimportado + 10, qtdnimportado = ' . $qtdNImportado . ' where codimportacao = ' . $importacaop["codimportacao"]);
        }
    }//fim while de importações
}






