var cor = ['007AFF', 'FF7000', 'FF7000', '15E25F', 'CFC700', 'CFC700', 'CF1100', 'CF00BE', 'F00'];
var cor_usuario = cor[getRandomInt(0, 8)];
function enviarMsg() {
    
    var mymessage = $('#message').val(); //get message text
    var myname = $('#name').val(); //get user name

    if (myname == "") { //empty name?
        alert("Por favor entre com seu nome!");
        return;
    }
    if (mymessage == "") { //emtpy message?
        alert("Por favor entre com alguma mensagem!");
        return;
    }

    //prepare json data
    var msg = {
        message: mymessage,
        name: myname,
        color: cor_usuario
    };
    //convert and send data to server
    websocket.send(JSON.stringify(msg));
}

// Retorna um  número inteiro entre min (incluso) e max (excluído)
// Usando Math.round() vai lhe dar uma distribuição não uniforme!
function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min)) + min;
}

$(document).ready(function () {
    //create a new WebSocket object.
    var wsUri = "ws://southnegocios.com:9000";
    websocket = new WebSocket(wsUri);

    websocket.onopen = function (ev) { // connection is open 
        $('#chat-box').append("<div class=\"system_msg\">Conectado!</div>"); //notify user
    }

    $("#message").keypress(function (event) {
        var key_code = event.keyCode ? event.keyCode : event.charCode ? event.charCode : event.which ? event.which : void 0;
        if (key_code == 13) {
            enviarMsg();
        }
    });

    $('#send-btn').click(function () { //use clicks message send button	
        enviarMsg();
    });

    //#### Message received from server?
    websocket.onmessage = function (ev) {
        var msg = JSON.parse(ev.data); //PHP sends Json data
        var type = msg.type; //message type
        var umsg = msg.message; //message text
        var uname = msg.name; //user name
        var ucolor = msg.color; //color

        if (type == 'usermsg') {
            $('#chat-box').append("<div class='item'><span class=\"user_name\" style=\"color:#" + ucolor + "\">" + uname + "</span> : <span class=\"user_message\">" + umsg + "</span></div>");
        }
        if (type == 'system') {
            $('#message_box').append("<div class=\"system_msg\">" + umsg + "</div>");
        }

        $('#message').val(''); //reset text
    };

    websocket.onerror = function (ev) {
        $('#message_box').append("<div class=\"system_error\">Erro causado por - " + ev.data + "</div>");
    };
    websocket.onclose = function (ev) {
        $('#message_box').append("<div class=\"system_msg\">Conexão fechada</div>");
    };
});