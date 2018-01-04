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



//append js object to FormData
appendArray: function(form_data, values, name)
{
    var _this = this;

    if(!values && name)
        form_data.append(name, '');
    else{
        if(typeof values == 'object'){
            for(key in values)
            {
                if(typeof values[key] == 'object')
                    _this.appendArray(form_data, values[key], name + '[' + key + ']');
                else
                    form_data.append(name + '[' + key + ']', values[key]);
            }
        }
        else
            form_data.append(name, values);
    }

    return form_data;
},
 var formData = new FormData(document.getElementById('table-container'));
formData.append("sessid",BX.bitrix_sessid());
_this.appendArray( formData, _this._arParams, "arParams" );    
    
