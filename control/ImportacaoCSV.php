<?php


function __autoload($class_name) {
    if (file_exists("../model/" . $class_name . '.php')) {
        include "../model/" . $class_name . '.php';
    } elseif (file_exists("../visao/" . $class_name . '.php')) {
        include "../visao/" . $class_name . '.php';
    } elseif (file_exists("./" . $class_name . '.php')) {
        include "./" . $class_name . '.php';
    }
}

$delimitador = ',';
$cerca = '"';
$qtdNImportado = 0;
$conexao = new Conexao();
$sql = 'select importacao.arquivo, carteira.nome as carteira, 
    importacao.codempresa, importacao.atualizar_cliente, importacao.adicionar_carteira, 
    importacao.categoriacliente, importacao.codfuncionario , importacao.codcarteira, 
    importacao.codimportacao, importacao.qtdlinha, importacao.qtdimportado
    from importacao 
    inner join carteira on carteira.codcarteira = importacao.codcarteira
    where importacao.terminado = "n" and importacao.arquivo like "%.csv" order by importacao.data asc limit 1';
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

        $limiteImportacao = 10 + $importacaop["qtdimportado"];
        if ($limiteImportacao >= $importacaop["qtdlinha"]) {
            $limiteImportacao = $importacaop["qtdlinha"];
        }
        //$data->sheets[0]['numRows']
        $inicioEm = 2 + $importacaop["qtdimportado"];
        echo "Inicio em: {$inicioEm} - Limite: {$limiteImportacao}<br>";

        $lines = file('http://' . $_SERVER["SERVER_NAME"] . "/arquivos/" . $importacaop["arquivo"]);
        for ($i = 1 + $importacaop["qtdimportado"]; $i <= $limiteImportacao; $i++) {
            $line = $lines[$i];
            $campos = explode(';', $line);

            $pessoa = new Pessoa($conexao);
            //completando com 0 a esquerda até 11 por que excel tira os 0
            $pessoa->cpf = str_pad($campos[3], 11, "0", STR_PAD_LEFT);
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
                $pessoa->nome = str_replace('"', '', trim($campos[2]));
            } else {
                $pessoa->codpessoa = $pessoap["codpessoa"];
                $pessoa->nome = $pessoap["nome"];
            }

            //campos base de endereço
            $pessoa->logradouro = utf8_encode(trim($campos[5]));
            $pessoa->bairro = utf8_encode(trim($campos[6]));
            $pessoa->cidade = utf8_encode(trim($campos[7]));
            $pessoa->estado = trim($campos[8]);
            $pessoa->cep = trim($campos[9]);

            //conversão da data do padrão excel
            $pessoa->dtnascimento = date(trim(date("Y-m-d", strtotime($campos[4]))));

            $pessoa->codempresa = $importacaop['codempresa'];
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


                //for para telefones
                for ($j = 10; $j <= 14; $j++) {
                    //verificando se o telefone da planilha ja tem
                    $telefonep = $conexao->comandoArray("select codtelefone from telefone where codpessoa = '{$pessoa->codpessoa}' and numero = '" . trim($campos[$j]) . "'");
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
                        $telefone->numero = trim($campos[$j]);
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

                if (trim($campos[0]) != "") {
                    echo "Entrou para importar beneficios<br>";
                    $sql = "select codbeneficio from beneficiocliente where codpessoa = '{$pessoa->codpessoa}' and codempresa = '{$importacaop["codempresa"]}' and numbeneficio = '" . trim($campos[0]) . "'";
                    $beneficiop = $conexao->comandoArray($sql);
                    $beneficio = new BeneficioCliente($conexao);
                    $beneficio->codorgao = 3;
                    $beneficio->codempresa = $importacaop["codempresa"];
                    $beneficio->codfuncionario = $importacaop["codfuncionario"];
                    $beneficio->codpessoa = $pessoa->codpessoa;
                    $beneficio->dtcadastro = date("Y-m-d H:i:s");
                    $codigo_especie = trim($campos[1]);
                    $especiep = $conexao->comandoArray('select codespecie from especie where numinss = "' . $codigo_especie . '"');
                    $beneficio->codespecie = $especiep["codespecie"];
                    $beneficio->matricula = trim($campos[0]);
                    $beneficio->numbeneficio = trim($campos[0]);
                    $beneficio->salariobase = trim($campos[19]);
                    
                    if(strpos($beneficio->salariobase, '.')){
                        $beneficio->salariobase = str_replace('.', '', $beneficio->salariobase);
                    }
                    if(strpos($beneficio->salariobase, ',')){
                        $beneficio->salariobase = str_replace(',', '.', $beneficio->salariobase);
                    }
                    $beneficio->margem = trim($campos[29]);
//                    $beneficio->meio = trim($data->sheets[0]['cells'][$i][22]);
                    $codigoBanco = str_pad($campos[16], 3, "0", STR_PAD_LEFT);
                    $bancop = $conexao->comandoArray("select codbanco from banco where numbanco = '" . trim($codigoBanco) . "'");
                    $beneficio->codbanco = $bancop["codbanco"];
                    $beneficio->agencia = trim($campos[17]);
                    $beneficio->contacorrente = trim($campos[18]);
                    $beneficio->valor_cartao_rmc = trim($campos[30]);
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

                if (trim($campos[20]) != NULL && trim($campos[20]) != "") {
                    $codigoBanco = str_pad($campos[21], 3, "0", STR_PAD_LEFT);
                    $bancop = $conexao->comandoArray("select codbanco from banco where numbanco = '" . trim($codigoBanco) . "'");
                    
                    $vlParcela = trim($campos[22]);
                    if(strpos($vlParcela, '.')){
                        $vlParcela = str_replace('.', '', $vlParcela);
                    }
                    if(strpos($vlParcela, ',')){
                        $vlParcela = str_replace(',', '.', $vlParcela);
                    }                    
                    $sql = 'delete from emprestimo 
                    where codpessoa         = '. $pessoa->codpessoa. ' 
                    and   codbanco          = '.$bancop["codbanco"].'   
                    and   vlparcela         = "'. $vlParcela.'"    
                    and   codbeneficio      = "'.$beneficio->codbeneficio.'"    
                    and   parcelasrestantes = "'.trim($campos[24]).'"  
                    and   prazo             = "'.trim($campos[25]).'"    
                    and   codempresa        = '.$importacaop["codempresa"];
                    echo "<pre>{$sql}</pre>";
                    $conexao->comando($sql);
                    
                    $emprestimo = new Emprestimo($conexao);
                    $emprestimo->codfuncionario = $importacaop["codfuncionario"];
                    $emprestimo->codpessoa = $pessoa->codpessoa;
                    $emprestimo->dtcadastro = date("Y-m-d H:i:s");
                    $emprestimo->codempresa = $importacaop["codempresa"];
                    $emprestimo->codbeneficio = $beneficio->codbeneficio;

                    $emprestimo->codbanco = $bancop["codbanco"];
                    $emprestimo->meio = trim($campos[20]);
                    $emprestimo->vlparcela = trim($campos[22]);
//                    $emprestimo->dtparcela = gmdate("Y-m-d", (trim($data->sheets[0]['cells'][$i][$j + 2]) - 25569) * 86400);
                    $emprestimo->parcelasrestantes = trim($campos[24]);
                    
                    $emprestimo->prazo = trim($campos[25]);
                    $emprestimo->quitacao = trim($campos[26]);
                    if(strpos($emprestimo->quitacao, '.')){
                        $emprestimo->quitacao = str_replace('.', '', $emprestimo->quitacao);
                    }
                    if(strpos($emprestimo->quitacao, ',')){
                        $emprestimo->quitacao = str_replace(',', '.', $emprestimo->quitacao);
                    }
                    $resInserirEmprestimo = $emprestimo->inserir();
                    if ($resInserirEmprestimo == FALSE) {
                        die ('Erro ao importar empréstimo de cliente. Causado por:' . mysqli_error($conexao->conexao));
                    }
                        
                }
            }
        }
        if($importacaop["qtdimportado"] > $importacaop["qtdlinha"]){
            $setar .= ', terminado = "s"';
        }
        $sql = 'update importacao set qtdimportado = qtdimportado + 10, qtdnimportado = ' . $qtdNImportado.$setar . ' where codimportacao = ' . $importacaop["codimportacao"];
        echo "<pre>{$sql}</pre>";
        $conexao->comando($sql);
    }
}