<h3 style="text-align: center;"><?= $_POST["titulo"] ?></h3>
<p style="text-align: center"><?= $_POST["texto"] ?></p>

<p style="width: 185px;margin: 0 auto;">
    <button id="btIntervalo"   onclick="verificaIntervalo()" class="btn btn-primary" style="background: red; color: white;">Intervalo</button>
    <button id="btAtendimento" onclick="verificaAtendimento()" class="btn btn-primary" style="background: green; color: white;">Atendimento</button>    
</p>

<p style="display: none;float: right;margin-right: 270px;" id="opcoes_atendimento">
    <input type="checkbox" name="opcoes_atendimento" value="1"/>Cliente em Andamento<br>
    <input type="checkbox" name="opcoes_atendimento" value="2"/>Fornecedor<br>
    <input type="checkbox" name="opcoes_atendimento" value="3" onclick="vendaNova();"/>Venda Nova<br>
</p>

<table style="display: none; width: 100%;" id="venda_nova">
    <tr>
        <td>CPF</td>
        <td>Data</td>
        <td>Valor</td>
        <td></td>
    </tr>
    <tr>
        <td><input style="width: 95%;" type="text" class="form-control" name="cpf" placeholder="Digite cpf" maxlength="11" minlength="5" value=""/></td>
        <td><input style="width: 95%;" type="date" class="form-control" name="data" placeholder="Digite data" value=""/></td>
        <td><input style="width: 95%;" type="text" class="form-control" name="valor" placeholder="Digite valor" value=""/></td>
        <td><img style="width: 20px;" src="../visao/recursos/img/confirmar.png"/></td>;
    </tr>
</table>
