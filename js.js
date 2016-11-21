document.addEventListener("DOMContentLoaded", function (event)
{
    var seriatsb = document.querySelector("input[name='SERIYA_TSB[0][VALUE]']").value;
    if( seriatsb.length == 0 )
    {
        document.querySelector('.property-SERIYA_TSB').style.display = 'none';
    }
});
