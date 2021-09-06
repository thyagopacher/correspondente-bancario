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
                <form action="../control/ProcurarPessoaRelatorio.php" name="fPpessoa" id="fPpessoa" onsubmit="return false;" target="_blank">
                    <input type="hidden" name="tipo" id="tipo" value="pdf"/>
                    <input type="hidden" name="ehUsuario" id="ehUsuario" value="s"/>
                    <div class="col-md-6">
                        <!-- /.form-group -->
                        <div class="form-group">
                            <label for="statusPessoa">Status</label>
                            <select class="form-control" name="status" id="status" title="Escolha aqui se a pessoa está ativa ou não">
                                <option value="">--Selecione--</option>
                                <option value="a">ativo</option>
                                <option value="i">inativo</option>
                            </select>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rg">CPF</label>
                            <input type='text' class="form-control" name="rg" id="rg" placeholder="Digite rg">
                        </div>                                               
                    </div><!-- /.col -->
                    <!-- /.col -->
                    
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <button class="btn btn-primary" type="button" onclick="procurarPessoa2(false)">Procurar</button>
                        <button class="btn btn-primary" type="button" onclick="abreRelatorioPessoa()">Gerar PDF</button>
                        <button class="btn btn-primary" type="button" onclick="abreRelatorioPessoa2()">Gerar Excel</button>
                    </div>                                        
                </div>
            </div>
            </form>
            <div class="row">
                <div class="col-sm-12" id="listagem"></div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>