<div class="row">
<!-- left column -->
<div class="col-md-6">
    <!-- general form elements -->
    <div class="box box-primary">
        <div class="box-header with-border">
            
        </div><!-- /.box-header -->
        <!-- form start -->
        <form role="form" id="ftipoconta" name="ftipoconta" method="post" onsubmit="return false;">
            <input type="hidden" name="codtipo" id="codtipo"  value="<?php if(isset($tipoconta["codtipo"])){echo $tipoconta["codtipo"];}?>"/> 
            <div class="box-body">
                <div class="form-group">
                    <label for="localnascimento">Nome</label>
                    <input type="text" required size="50" name="nome" id="nomeTipo" placeholder="Digite nome aqui" value="<?php if(isset($tipoconta["nome"])){echo $tipoconta["nome"];}else{ echo "";}?>">
                </div>                                     
            </div><!-- /.box-body -->
            <div class="box-footer">
                <button class="btn btn-primary" onclick="inserirTipo()" id="btinserirtipoConta">Cadastrar</button>
                <button class="btn btn-primary"  style="display: none" id="btatualizartipoConta" onclick="atualizarTipo()">Atualizar</button>
                <button class="btn btn-primary"  style="display: none" id="btexcluirtipoConta" onclick="excluirTipo()">Excluir</button> 
                <button class="btn btn-primary" onclick="btNovoTipoConta()">Novo</button>
            </div>
        </form>
        
    </div><!-- /.box -->
</div><!--/.col (left) -->
<!--/.col (right) -->
</div>   <!-- /.row -->

<div class="row">
    <div class="col-sm-12" id="listagemTipoConta"></div>
</div> 