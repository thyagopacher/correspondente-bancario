<div class="row">
    <div class="box box-default">
        <div class="box-header with-border">
            

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <form action="<?= $action ?>" id="fProcurarNivel" name="fnivel" method="post">
                    <input type="hidden" name="codnivel" id="codnivel" value="<?=$_GET["codnivel"]?>"/>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Módulo</label>
                            <select class='form-control' name="codmodulo" id="codmodulo" required title="Selecione aqui o módulo ao qual pertence a funcionalidade">
                                <?php
                                $res = $conexao->comando("select * from modulo order by nome");
                                $qtd = $conexao->qtdResultado($res);
                                if($qtd > 0){
                                    echo '<option value="">--Selecione--</option>';
                                    while($pagina = $conexao->resultadoArray($res)){
                                        echo '<option value="',$pagina["codmodulo"],'">',$pagina["nome"],'</option>';
                                    }
                                }else{
                                    echo '<option value="">Nada encontrado</option>';
                                }
                                ?>
                            </select>                        
                        </div>                        
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type='text' class="form-control" name="nome" id="nome" placeholder="Digite nome" value="">
                        </div>
                                        

                        <!-- /.form-group -->
                    </div>

                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <button class="btn btn-primary" onclick="procurarPagina(false)">Procurar</button>
                    </div>                                        
                </div>
            </div>
            <div class="row">
                <div id="listagemPagina" class="col-md-12"></div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>