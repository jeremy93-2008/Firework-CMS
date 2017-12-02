/**
	Convert Markdown Code in HTML Code with the Firework Style
*/
function MdCode()
{
	this.html = "";
	this.multiline = false;
}
MdCode.prototype.convert = function(mdText)
{
	mdText = mdText.trim().replace(/</g,"&lt;").replace(/>/g,"&gt;");
	var arrText = mdText.split(/\r?\n/);
	for(let a = 0;a < arrText.length;a++)
	{
		let line = arrText[a].trim();
		line = this.convertLineTo("######","h6",line);
		line = this.convertLineTo("#####","h5",line);
		line = this.convertLineTo("####","h4",line);
		line = this.convertLineTo("###","h3",line);
		line = this.convertLineTo("##","h2",line);
		line = this.convertLineTo("#","h1",line);
		line = this.convertTextTo("__","em",line);
		line = this.convertLineWithStyleTo("&gt;","div",line,"background-color:beige;padding: 5 10;color:black;border-left: solid 18px #b5b590;",false);
		
		if(this.multiline)
			line = this.convertMultiLineTo("```","div",line,false);
		line = this.convertMultiLineTo("```","div",line,true);
		
		if(this.multiline)
		{
			line = this.syntaxCode(line)
		}
		else
		{
			line = this.convertTextTo("_","strong",line);
			line = this.convertLineTo("\\*\\s","li",line);
		    line = this.convertTextTo("\\*","strong",line);
			line = this.convertTextTo("\\*\\*","em",line);
		}
		
		this.html += line + "<br />";
	}
	return "<div style='background-color:lightgrey;padding:2 5;'>"+this.html+"</div>";
}
MdCode.prototype.convertLineTo = function(oldChar,newChar,line)
{
	line = line.trim();
	let reg = new RegExp(oldChar,"g");
	let buscar = oldChar.replace("\\s"," ").replace("\\","");
	if(line.indexOf(buscar) == 0)
	{
		line = line.replace(reg,"<"+newChar+"> ");
		line = line+"</"+newChar+"> ";
	}
	return line;
}
MdCode.prototype.convertLineWithStyleTo = function(oldChar,newChar,line,style,conserva)
{
	line = line.trim();
	let reg = new RegExp(oldChar,"g");
	let buscar = oldChar.replace("\\s"," ").replace("\\","");
	if(line.indexOf(buscar) == 0)
	{
		if(conserva == undefined || conserva)
			line = line.replace(reg,"<"+newChar+" style='"+style+"'> "+buscar);
		else
			line = line.replace(reg,"<"+newChar+" style='"+style+"'> ");
		line = line+"</"+newChar+"> ";
	}
	return line;
}
MdCode.prototype.convertTextTo = function(oldChar,newChar,line)
{
	if(line.indexOf(/\s_\s/g) == -1)
	{
		let reg = new RegExp("\\s"+oldChar,"g");
		let endReg = new RegExp(oldChar,"g");
		line = line.replace(reg," <"+newChar+">");
		line = line.replace(endReg,"</"+newChar+">");
		return line;
	}
}
MdCode.prototype.convertMultiLineTo = function(oldChar,newChar,line,apertura)
{
	line = line.trim();
	let reg = new RegExp(oldChar,"g");
	let buscar = oldChar.replace("\\s"," ").replace("\\","");
	if(line.indexOf(buscar) != -1)
	{
		if(apertura)
		{
			line = line.replace(line,"<"+newChar+" style='background-color:grey;color:white;margin:5 10;padding:10 5;border: 1px solid darkgrey'> Código");
			this.multiline = true;
		}else
		{
			line = line.replace(reg,"</"+newChar+"> ");
			this.multiline = false;
		}
	}
	return line;
}
MdCode.prototype.syntaxCode = function(line)
{
	line = line.trim();
	if(line.indexOf("<div") == -1)
	{
		line = this.convertLineWithStyleTo("{","span",line,"color:#bd0606;");
		line = this.convertLineWithStyleTo("\\[","span",line,"color:#bd0606;");
		line = this.convertLineWithStyleTo("\\]","span",line,"color:#bd0606;");
		line = this.convertLineWithStyleTo("&lt;\\?","span",line,"color:blue;");
		line = this.convertLineWithStyleTo("}","span",line,"color:#bd0606;");
		line = this.convertLineWithStyleTo("//","span",line,"color:#97e67f;");
		line = this.convertLineWithStyleTo("class","span",line,"color:blue;");
		line = this.convertLineWithStyleTo("public","span",line,"color:blue;");
		line = this.convertLineWithStyleTo("function","span",line,"color:#bd0606;");
		line = this.convertLineWithStyleTo("Plugin","span",line,"color:#e4e4a3;");
		line = this.convertLineWithStyleTo("...\\/","span",line,"color:#e4e4a3;");
	}
	return line;
}