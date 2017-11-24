document.addEventListener("DOMContentLoaded", function (event)
{
    var seriatsb = document.querySelector("input[name='SERIYA_TSB[0][VALUE]']").value;
    if( seriatsb.length == 0 )
    {
        document.querySelector('.property-SERIYA_TSB').style.display = 'none';
    }
});


BX.showWait();
var dataSend = new Object({
    sessid:BX("sessid").value,
    DEAL_ID:deal_id,
    LOCATION_ID:document.querySelector("[name='UF_LOCATION']").value
});
var url = "/bitrix/admin/test.php";
BX.ajax({
    url: url,
    data: dataSend,
    method: 'POST',
    dataType: 'json',
    timeout: 30,
    async: true,
    processData: true,
    scriptsRunFirst: true,
    emulateOnload: true,
    start: true,
    cache: false,
    onsuccess: function(data)
    {
        BX.closeWait();

    },
    onfailure: function()
    {

    }
});


//elem --- элемент,  который нужно вставить
//refElem --- элемент после которого нужно вставить
 window.ExtDownload.prototype.insertAfter = function (elem, refElem)
    {
        var parent = refElem.parentNode;
        var next = refElem.nextSibling;
        if (next) {
            return parent.insertBefore(elem, next);
        } else {
            return parent.appendChild(elem);
        }
    };



