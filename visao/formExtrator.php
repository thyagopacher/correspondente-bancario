<div class="row">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Relatório</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <form id="formExtrator" target="_blank" action="../control/Extrator.php" method="post">
                    <input type="hidden" name="codnivel" id="codnivel" value="<?= $_GET["codnivel"] ?>"/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Tabela</label>
                            <select name="tabela" id="tabela" class="form-control">
                                <?php
                                $resTabelaExtrator = $conexao->comando("show tables");
                                $qtd = $conexao->qtdResultado($resTabelaExtrator);
                                if ($qtd > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($tabela = $conexao->resultadoArray($resTabelaExtrator)) {
                                        if ($tabela["Tables_in_sitesesistemas01"] == "ajuda" || $tabela["Tables_in_sitesesistemas01"] == "smtp" || $tabela["Tables_in_sitesesistemas01"] == "advertencia" || strpos($tabela["Tables_in_sitesesistemas01"], "status") !== FALSE || strpos($tabela["Tables_in_sitesesistemas01"], "categoria") !== FALSE || strpos($tabela["Tables_in_sitesesistemas01"], "erro") !== FALSE || strpos($tabela["Tables_in_sitesesistemas01"], "upload") !== FALSE || $tabela["Tables_in_sitesesistemas01"] == "consumoluz" || $tabela["Tables_in_sitesesistemas01"] == "nivelpagina" || $tabela["Tables_in_sitesesistemas01"] == "valorcampo" || $tabela["Tables_in_sitesesistemas01"] == "campoextra" || $tabela["Tables_in_sitesesistemas01"] == "arquivo" || $tabela["Tables_in_sitesesistemas01"] == "pagina" || $tabela["Tables_in_sitesesistemas01"] == "paginamorador" || $tabela["Tables_in_sitesesistemas01"] == "achado" || $tabela["Tables_in_sitesesistemas01"] == "atestado" || $tabela["Tables_in_sitesesistemas01"] == "comunicado" || $tabela["Tables_in_sitesesistemas01"] == "aviso" || $tabela["Tables_in_sitesesistemas01"] == "tipoachado" || $tabela["Tables_in_sitesesistemas01"] == "tipoinformativo" || $tabela["Tables_in_sitesesistemas01"] == "votoenquete" || $tabela["Tables_in_sitesesistemas01"] == "enquete" || $tabela["Tables_in_sitesesistemas01"] == "mudanca" || $tabela["Tables_in_sitesesistemas01"] == "comentarioclassificado" || $tabela["Tables_in_sitesesistemas01"] == "consumoagua" || $tabela["Tables_in_sitesesistemas01"] == "modulo" || $tabela["Tables_in_sitesesistemas01"] == "classificado" || $tabela["Tables_in_sitesesistemas01"] == "servico" || $tabela["Tables_in_sitesesistemas01"] == "ramo" || $tabela["Tables_in_sitesesistemas01"] == "produto" || $tabela["Tables_in_sitesesistemas01"] == "manutencao" || $tabela["Tables_in_sitesesistemas01"] == "mensagem") {
                                            continue;
                                        }
                                        $tabelaSelecionada = str_replace("configuracao", "configuração ", $tabela["Tables_in_sitesesistemas01"]);
                                        $tabelaSelecionada = str_replace("correspondencia", "correspondência", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("observacao", "observação ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("manutencao", "manutenção ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("arquivo", "arquivo ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("agenda", "agenda ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("servico", "serviço", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("acesso", "acesso ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("email", " e-mail ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("mudanca", " mudança ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("ambiente", " ambiente ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("classificado", " classificado ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("ocorrencia", " ocorrência ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("importacao", " importação ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("eletronico", " eletrônico ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("agua", " água ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("morador", " morador ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("tipo", " tipo ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("visitante", " visitante ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("mensagem", " mensagem ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("orcamento", " orçamento ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("horario", " horário ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("voto", " voto ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("pedido", " pedido ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("beneficio", " beneficio ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("cliente", " cliente ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("estado", " estado ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("orgao", " órgão ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("sms", " sms ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("padrao", " padrão ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("ligacao", " ligação ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("cotacao", " cotação ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("conteudo", " conteúdo ", $tabelaSelecionada);
                                        $tabelaSelecionada = str_replace("agencia", " agência ", $tabelaSelecionada);
                                        echo '<option value="', $tabela["Tables_in_sitesesistemas01"], '">', trim($tabelaSelecionada), '</option>';
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="sexo">Campos</label>
                            <div id="listagemCamposTabela"></div>
                        </div>
                        <!-- /.form-group -->
                    </div>                    
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="submit" name="submit" id="btGerarRelatorio" value="Gerar Relatório" class="btn btn-primary"/>
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->