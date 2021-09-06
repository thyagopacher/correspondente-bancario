// Node objeto
function Node(id, pid, name, url, title, target, isopen, img)
{
	this.id			= id;
	this.pid		= pid;
	this.name		= name;
	this.url		= url;
	this.title	= title;
	this.target	= target;
	this.img		= img;

	this._io		= isopen || false;
	this._ls		= false;
	this._hc		= false;
	this._is		= false;
}
// Tree objetos
function dTree(objName)
{

// Variaveis
// ----------------------------------------------------------------------------

	this.arrNodes			= [];
	this.arrRecursed	= [];
	this.arrIcons			= [];
	this.rootNode			= -1;
	this.strOutput		= '';
	this.selectedNode	= null;

	this.instanceName = objName;
	this.imgFolder		= 'img/';
	this.target				= null;
	this.hasLines			= true;
	this.clickSelect	= true;
	this.folderLinks	= true;
	this.useCookies		= true;


// Funçoes
// ----------------------------------------------------------------------------


	// adiciona um node novo à disposição do node
	this.add = function(id, pid, name, url, title, target, isopen, img)
	{
		this.arrNodes[this.arrNodes.length] = new Node(id, pid, name, url, title, target, isopen, img);
	}

	// Outputs a árvore à página 
	this.draw = function()
	{
		if (document.getElementById)
		{
			this.preloadIcons();
			if (this.useCookies) this.selectedNode = this.getSelected();
			this.addNode(this.rootNode);

			document.writeln(this.strOutput);
		}
		else
		{
			document.writeln('Navegador não suportado');
		}
	}

	this.openAll = function()
	{
		this.oAll(true);
	}

	this.closeAll = function()
	{
		this.oAll(false);
	}


// Confidencial
// ----------------------------------------------------------------------------

	// Imagens dePrealoads que são usadas na árvore
	this.preloadIcons = function()
	{
		if (this.hasLines)
		{
			this.arrIcons[0] = new Image();
			this.arrIcons[0].src = "/visao/recursos/" + this.imgFolder + 'plus.gif';
			this.arrIcons[1] = new Image();
			this.arrIcons[1].src = "/visao/recursos/" + this.imgFolder + 'plusbottom.gif';
			this.arrIcons[2] = new Image();
			this.arrIcons[2].src = "/visao/recursos/" + this.imgFolder + 'minus.gif';
			this.arrIcons[3] = new Image();
			this.arrIcons[3].src = "/visao/recursos/" + this.imgFolder + 'minusbottom.gif';
		}
		else
		{
			this.arrIcons[0] = new Image();
			this.arrIcons[0].src = "/visao/recursos/" + this.imgFolder + 'nolines_plus.gif';
			this.arrIcons[1] = new Image();
			this.arrIcons[1].src = "/visao/recursos/" + this.imgFolder + 'nolines_plus.gif';
			this.arrIcons[2] = new Image();
			this.arrIcons[2].src = "/visao/recursos/" + this.imgFolder + 'nolines_minus.gif';
			this.arrIcons[3] = new Image();
			this.arrIcons[3].src = "/visao/recursos/" + this.imgFolder + 'nolines_minus.gif';
		}
		this.arrIcons[4] = new Image();
		this.arrIcons[4].src = "/visao/recursos/" + this.imgFolder + 'folder.gif';
		this.arrIcons[5] = new Image();
		this.arrIcons[5].src = "/visao/recursos/" + this.imgFolder + 'folderopen.gif';
	}

	// Função Recursive de que cría a estrutura de árvore
	this.addNode = function(pNode)
	{
		for (var n=0; n<this.arrNodes.length; n++)
		{
			if (this.arrNodes[n].pid == pNode)
			{
				var cn = this.arrNodes[n];
				cn._hc = this.hasChildren(cn);
				cn._ls = (this.hasLines) ? this.lastSibling(cn) : false;
				if (cn._hc && !cn._io && this.useCookies) cn._io = this.isOpen(cn.id);

				if (this.clickSelect && cn.id == this.selectedNode)
				{
						cn._is = true;
						this.selectedNode = n;
				}

				if (!this.folderLinks && cn._hc) cn.url = null;


				// se o node atual não for a raiz 
				if (this.rootNode != cn.pid)
				{
					// escreve para fora a linha & ícones vazios 
					for (r=0; r<this.arrRecursed.length; r++)
						this.strOutput += '<img src="/visao/recursos/' + this.imgFolder + ( (this.arrRecursed[r] == 1 && this.hasLines) ? 'line' : 'empty' ) + '.gif" alt="" />';

					// Linha & ícones vazios 
					(cn._ls) ? this.arrRecursed.push(0) : this.arrRecursed.push(1);

					// escreve junta para fora ícones 
					if (cn._hc)
					{
						this.strOutput += '<a href="javascript: ' + this.instanceName + '.o(' + n + ');">'
							+ '<img id="j' + this.instanceName + n + '" src="/visao/recursos/' + this.imgFolder;
						if (!this.hasLines)
							this.strOutput += 'nolines_';

						this.strOutput += ( (cn._io) ? ((cn._ls && this.hasLines) ? 'minusbottom' : 'minus') : ((cn._ls && this.hasLines) ? 'plusbottom' : 'plus' ) )
							+ '.gif" alt="" /></a>';
					}
					else
						this.strOutput += '<img src="/visao/recursos/' + this.imgFolder + ( (this.hasLines) ? ((cn._ls) ? 'joinbottom' : 'join' ) : 'empty') + '.gif" alt="" />';
				}

				// Começo da ligação do node 
				if (cn.url)
				{
					this.strOutput += '<a href="' + cn.url + '"';
					if (cn.title) this.strOutput += ' title="' + cn.title + '"';
					if (cn.target) this.strOutput += ' target="' + cn.target + '"';
					if (this.target && !cn.target) this.strOutput += ' target="' + this.target + '"';

					// se a ligação do hightlight for sobre 
					if (this.clickSelect)
					{
						if (cn._hc)
						{
							if (this.folderLinks)
								this.strOutput += ' onclick="javascript: ' + this.instanceName + '.o(' + n + ');"';
						}
						else
						{
							this.strOutput += ' onclick="javascript: ' + this.instanceName + '.s(' + n + ');"';
						}
					}

					this.strOutput += '>';
				}
				if ((!this.folderLinks || !cn.url) && cn._hc && cn.pid != this.rootNode)
				{
					this.strOutput += '<a href="javascript: ' + this.instanceName + '.o(' + n + ');">';
				}

				// escreve para fora o dobrador & pagina ícones
				this.strOutput += '<img id="i' + this.instanceName + n + '" src="/visao/recursos/' + this.imgFolder;
				this.strOutput += (cn.img) ? cn.img : ((this.rootNode == cn.pid) ? 'base' : (cn._hc) ? ((cn._io) ? 'folderopen' : 'folder') : 'page') + '.gif';
				this.strOutput += '" alt="" />';

				// escreve para fora a extensão 
				this.strOutput += '<span id="s' + this.instanceName + n + '" class="' + ((this.clickSelect) ? ((cn._is ? 'nodeSel' : 'node')) : 'node') + '">';


				// escreve para fora o nome do node
				this.strOutput += cn.name;

					this.strOutput += '</span>';

				// Fim de linck
				if (cn.url || (!this.folderLinks && cn._hc)) this.strOutput += '</a>';

				this.strOutput += '<br />\n';

				// ----
				if (cn._hc)
				{
					this.strOutput += '<div id="d' + this.instanceName + n + '" style="display:'
					+ ((this.rootNode == cn.pid || cn._io) ? 'block' : 'none')
					+ ';">\n';
					this.addNode(cn.id);
					this.strOutput += '</div>\n';
				}
				this.arrRecursed.pop();
			}
		}
	}

	// verifica se um node tiver alguma children
	this.hasChildren = function(node)
	{
		for (n=0; n<this.arrNodes.length; n++)
			if (this.arrNodes[n].pid == node.id) return true;
		return false;
	}

	// verifica se um node for o último sibling 
	this.lastSibling = function(node)
	{
		var lastId;
		for (n=0; n< this.arrNodes.length; n++)
			if (this.arrNodes[n].pid == node.pid) lastId = this.arrNodes[n].id;
		if (lastId==node.id) return true;
		return false;
	}

	// verifica se uma identificação do node esta em um cookie 
	this.isOpen = function(id)
	{
		openNodes = this.getCookie('co' + this.instanceName).split('.');
		for (n=0;n<openNodes.length;n++)
			if (openNodes[n] == id) return true;
		return false;
	}

	// Verificações se um node esta selecionado
	this.isSelected = function(id)
	{
		selectedNode = this.getCookie('cs' + this.instanceName);
		if (selectedNode)
		{
			if (id==selectedNode)
			{
				this.selectedNode = id;
				return true
			}
		}
		return false;
	}
	// retorna o node selecionado 
	this.getSelected = function()
	{
		selectedNode = this.getCookie('cs' + this.instanceName);
		if (selectedNode)	return selectedNode;
		return null;
	}

	// destaca o node selecionado 
	this.s = function(id)
	{
		cn = this.arrNodes[id];
		if (this.selectedNode != id)
		{
			if (this.selectedNode)
			{
				eOldSpan = document.getElementById("s" + this.instanceName + this.selectedNode);
				eOldSpan.className = "node";
			}

			eNewSpan = document.getElementById("s" + this.instanceName + id);
			eNewSpan.className = "nodeSel";

			this.selectedNode = id;
			if (this.useCookies) this.setCookie('cs' + this.instanceName, cn.id);
		}
	}

	// Toggle de aberto ou próximo 
	this.o = function(id)
	{
		cn = this.arrNodes[id];

		(cn._io) ? this.nodeClose(id,cn._ls) : this.nodeOpen(id,cn._ls);
		cn._io = !cn._io;

		if (this.useCookies) this.updateCookie();
	}

	// aberto ou próximo todos os nodes
	this.oAll = function(open)
	{
		for (n=0;n<this.arrNodes.length;n++)
		{
			if (this.arrNodes[n]._hc && this.arrNodes[n].pid != this.rootNode)
			{
				if (open)
				{
					this.nodeOpen(n, this.arrNodes[n]._ls);
					this.arrNodes[n]._io = true;
				}
				else
				{
					this.nodeClose(n, this.arrNodes[n]._ls);
					this.arrNodes[n]._io = false;
				}
			}
		}
		if (this.useCookies) this.updateCookie();
	}

	// abre um node
	this.nodeOpen = function(id, bottom)
	{
		eDiv	= document.getElementById('d' + this.instanceName + id);
		eJoin	= document.getElementById('j' + this.instanceName + id);
		eIcon	= document.getElementById('i' + this.instanceName + id);
		eJoin.src = (bottom) ?  this.arrIcons[3].src : this.arrIcons[2].src;
		if (!this.arrNodes[id].img) eIcon.src = this.arrIcons[5].src;
		eDiv.style.display = 'block';
	}

	// finaliza um node
	this.nodeClose = function(id, bottom)
	{
		eDiv	= document.getElementById('d' + this.instanceName + id);
		eJoin	= document.getElementById('j' + this.instanceName + id);
		eIcon	= document.getElementById('i' + this.instanceName + id);
		eJoin.src = (bottom) ? this.arrIcons[1].src : this.arrIcons[0].src;
		if (!this.arrNodes[id].img) eIcon.src = this.arrIcons[4].src;
		eDiv.style.display = 'none';
	}

	// cancela um Cookie
	this.clearCookie = function()
	{
		var now = new Date();
		var yesterday = new Date(now.getTime() - 1000 * 60 * 60 * 24);
		this.setCookie('co'+this.instanceName, 'cookieValue', yesterday);
		this.setCookie('cs'+this.instanceName, 'cookieValue', yesterday);
	}

	// Sets value in a cookie
	this.setCookie = function(cookieName, cookieValue, expires, path, domain, secure) {
		document.cookie =
			escape(cookieName) + '=' + escape(cookieValue)
			+ (expires ? '; expires=' + expires.toGMTString() : '')
			+ (path ? '; path=' + path : '')
			+ (domain ? '; domain=' + domain : '')
			+ (secure ? '; secure' : '');
	}

	// começa um valor de um cookie
	this.getCookie = function(cookieName) {
		var cookieValue = '';
		var posName = document.cookie.indexOf(escape(cookieName) + '=');
		if (posName != -1)
		{
			var posValue = posName + (escape(cookieName) + '=').length;
			var endPos = document.cookie.indexOf(';', posValue);
			if (endPos != -1)
				cookieValue = unescape(document.cookie.substring(posValue, endPos));
			else
				cookieValue = unescape(document.cookie.substring(posValue));
		}
		return (cookieValue);
	}

	// retorna ids de nós abertos como uma string 
	this.updateCookie = function()
	{
		sReturn = '';
		for (n=0;n<this.arrNodes.length;n++)
		{
			if (this.arrNodes[n]._io && this.arrNodes[n].pid != this.rootNode)
			{
				if (sReturn) sReturn += '.';
				sReturn += this.arrNodes[n].id;
			}
		}
		this.setCookie('co' + this.instanceName, sReturn);
	}

// fim dos objetos do menu tree
}


// As funções usadas pelo objeto do dTree mas não são realmente uma parte dela 
// ------------------------------------------------------------------------------------------------


if (!Array.prototype.push) {
	Array.prototype.push = function array_push() {
		for(var i=0;i<arguments.length;i++)
			this[this.length]=arguments[i];
		return this.length;
	}
}
if (!Array.prototype.pop) {
	Array.prototype.pop = function array_pop() {
		lastElement = this[this.length-1];
		this.length = Math.max(this.length-1,0);
		return lastElement;
	}
} 