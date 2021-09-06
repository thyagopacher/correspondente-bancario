<div class="row">
<!-- left column -->
<div class="col-md-6">
    <!-- general form elements -->
    <div class="box box-primary">
        <div class="box-header with-border">
            
        </div><!-- /.box-header -->
        <!-- form start -->
        <form role="form" id="fstatusproposta" name="fstatusproposta" method="post" onsubmit="return false;">
            <input type="hidden" name="codstatus" id="codstatus" value="<?=$_GET["codstatus"]?>"/>
            <div class="box-body">

                <div class="form-group">
                    <label for="localnascimento">Nome</label>
                    <input type='text' class="form-control" name="nome" id="nome" placeholder="Digite nome do status" value="<?php if(isset($status["nome"]) && $status["nome"] != ""){echo $status["nome"];}?>">
                </div>                                     
                <div class="form-group">
                    <label for="localnascimento">Cor</label>
                    <select class="form-control" name="cor" id="cor">
                        <option <?php if(isset($status["cor"]) && $status["cor"] == "azul"){echo "selected";}?>>azul</option>
                        <option <?php if(isset($status["cor"]) && $status["cor"] == "preto"){echo "selected";}?>>preto</option>
                        <option <?php if(isset($status["cor"]) && $status["cor"] == "vermelho"){echo "selected";}?>>vermelho</option>
                        <option <?php if(isset($status["cor"]) && $status["cor"] == "verde"){echo "selected";}?>>verde</option>
                        <option <?php if(isset($status["cor"]) && $status["cor"] == "amarelo"){echo "selected";}?>>amarelo</option>
                        <option <?php if(isset($status["cor"]) && $status["cor"] == "laranja"){echo "selected";}?>>laranja</option>
                        <option <?php if(isset($status["cor"]) && $status["cor"] == "roxo"){echo "selected";}?>>roxo</option>
                        <option <?php if(isset($status["cor"]) && $status["cor"] == "rosa"){echo "selected";}?>>rosa</option>
                    </select>     
                </div> 
                <div class="form-group">
                    <label>Status Prioritário</label>
                    <input type="checkbox" name="statusprioritario" id="statusprioritario" style="width: 15px;height: 15px;margin-left: 35px;" value="<?php if(isset($status["statusprioritario"]) && $status["statusprioritario"] != ""){echo $status["statusprioritario"];}else{ echo "s";}?>">
                </div>                                     
            </div><!-- /.box-body -->
            <div class="box-footer">
                <?php if(isset($nivelp["inserir"]) && $nivelp["inserir"] == 1 && !isset($_GET["codstatus"])){?>
                <button class="btn btn-primary" onclick="inserirStatus()" id="btinserirStatusProposta">Cadastrar</button>
                <?php }?>
                <?php if(isset($nivelp["atualizar"]) && $nivelp["atualizar"] == 1 && isset($_GET["codstatus"])){?>
                <button class="btn btn-primary" id="btatualizarStatusProposta" onclick="atualizarStatus()">Atualizar</button>
                <?php }?>
                <?php if(isset($nivelp["excluir"]) && $nivelp["excluir"] == 1 && isset($_GET["codstatus"])){?>
                <button class="btn btn-primary" id="btexcluirStatusProposta" onclick="excluirStatus()">Excluir</button>    
                <?php }?>
                <button class="btn btn-primary" onclick="btNovoStatus()">Novo</button>
            </div>
        </form>
    </div><!-- /.box -->
</div><!--/.col (left) -->
<!--/.col (right) -->
</div>   <!-- /.row -->