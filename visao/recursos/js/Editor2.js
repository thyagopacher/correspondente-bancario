function insereTexto(valor, componente) {
    //Pega a textarea  
    var textarea = document.getElementById(componente);

    //Texto a ser inserido  
    var texto = valor;

    //inicio da seleção  
    var sel_start = textarea.selectionStart;

    //final da seleção  
    var sel_end = textarea.selectionEnd;


    if (!isNaN(textarea.selectionStart))
            //tratamento para Mozilla  
            {
                var sel_start = textarea.selectionStart;
                var sel_end = textarea.selectionEnd;

                mozWrap(textarea, texto, '')
                textarea.selectionStart = sel_start + texto.length;
                textarea.selectionEnd = sel_end + texto.length;

            }

    else if (textarea.createTextRange && textarea.caretPos)
    {
        if (baseHeight != textarea.caretPos.boundingHeight)
        {
            textarea.focus();
            storeCaret(textarea);
        }
        var caret_pos = textarea.caretPos;
        caret_pos.text = caret_pos.texto.charAt(caret_pos.texto.length - 1) == ' ' ? caret_pos.text + text + ' ' : caret_pos.text + text;

    }
    else //Para quem não é possível inserir, inserimos no final mesmo (IE...)  
    {
        textarea.value = textarea.value + texto;
    }
}

/* 
 Essa função abre o texto em duas strings e insere o texto bem na posição do cursor, após ele une novamento o texto mas com o texto inserido 
 Essa maravilhosa função só funciona no Mozilla... No IE não temos as propriedades selectionstart, textLength... 
 */
function mozWrap(txtarea, open, close)
{
    var selLength = txtarea.textLength;
    var selStart = txtarea.selectionStart;
    var selEnd = txtarea.selectionEnd;
    var scrollTop = txtarea.scrollTop;

    if (selEnd == 1 || selEnd == 2)
    {
        selEnd = selLength;
    }
    //S1 tem o texto do começo até a posição do cursor  
    var s1 = (txtarea.value).substring(0, selStart);

    //S2 tem o texto selecionado  
    var s2 = (txtarea.value).substring(selStart, selEnd);

    //S3 tem todo o texto selecionado  
    var s3 = (txtarea.value).substring(selEnd, selLength);

    //COloca o texto na textarea. Utiliza a string que estava no início, no meio a string de entrada, depois a seleção seguida da string  
    //de fechamento e por fim o que sobrou após a seleção  
    txtarea.value = s1 + open + s2 + close + s3;
    txtarea.selectionStart = selEnd + open.length + close.length;
    txtarea.selectionEnd = txtarea.selectionStart;
    txtarea.focus();
    txtarea.scrollTop = scrollTop;
    return;
}
/* 
 Insert at Caret position. Code from 
 http://www.faqts.com/knowledge_base/view.phtml/aid/1052/fid/130 
 */
function storeCaret(textEl)
{
    if (textEl.createTextRange)
    {
        textEl.caretPos = document.selection.createRange().duplicate();
    }
}  