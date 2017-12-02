/**
 * Create a Custom Alert Message, with a text and title, and callback function to do whatever after you push Ok button
 * 
 * @param {strig} info Message of Alert
 * @param {function} [callback=undefined] Function executed after the disparition of Alert
 * @param {string} [titulo="Información"] Title for the Alert message
 */
function toastInfo(info,callback=function(){},titulo="Información")
{
    if(document.querySelector("#toastInfo") != undefined)
    {
        document.querySelector("#toastInfo").remove();
    }
    var div = document.createElement("div");
    div.id = "toastInfo";
    div.style.background = "linear-gradient(#eeeeee,#ddd)";
    div.style.border = "solid 1px #d3d3d3";
    div.style.borderRadius = "10px";
    div.style.position = "fixed";
    div.style.left = "36%";
    div.style.top = "30%";
    div.style.width = "400px";
    div.style.color = "black";
    div.style.padding = "5px 10px";
    div.style.wordWrap = "break-word";
    div.style.textAlign = "justify";
    div.style.boxShadow = "0 0 10px black";
    div.style.zIndex = "400";
    div.innerHTML = "<h3 style='margin: 0 0 5px 0;background: #cac9c9;padding: 5px;color: black;'>"+titulo+"</h3><span style='display:block;text-align: center;font-family: sans-serif;'>"+info + "</span><p style='margin-top:5px;margin-bottom: 0px;text-align:center;'><button id='acceptToastInfo' style='padding: 5px 10px 5px 10px;margin-top: 10px;width: 100%;background: linear-gradient(#EEE,#DDD);border: solid 1px lightgrey;border-radius: 5px;font-weight: bold;max-width: 374px;'>Aceptar</button></p>";
    document.body.appendChild(div);
    document.querySelector("#acceptToastInfo").onclick = function(){acceptToastInfo(this,callback);}
}
function acceptToastInfo(esto,callback)
{
    esto.parentNode.parentNode.remove()
    callback();
}