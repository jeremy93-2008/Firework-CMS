$(function()
{
    let search = window.location.search.substring(1)
    //name=JSON&JSON_doc.md
    let argument = search.split("&");
    document.title = "Documentación "+argument[0].replace("name=","");
    $.get("../api/?get_doc="+argument[1]).done(function(data)
    {
        let code = new MdCode();
        let html = code.convert(data);
        document.querySelector("#doc").innerHTML = html;
    });
});