var offsetx=20;
var offsety=0;

function hideAtkpBox(e,Inhalte) {
      document.getElementById('atkp-infobox').style.visibility = "hidden";
}

function showAtkpBox(e,Inhalte,offsetX,offsetY)
{
   
        if (offsetX) {offsetx=offsetX;} else {offsetx=20;}
        if (offsetY) {offsety=offsetY;} else {offsety=0;}
        var PositionX = 0;
        var PositionY = 0;
        if (!e) var e = window.event;
        if (e.pageX || e.pageY)
        {
                PositionX = e.pageX;
                PositionY = e.pageY;
        }
        else if (e.clientX || e.clientY)
        {
                PositionX = e.clientX + document.body.scrollLeft;
                PositionY = e.clientY + document.body.scrollTop;
        }
        document.getElementById("BoxInhalte").innerHTML = document.getElementById(Inhalte).innerHTML;
        document.getElementById('atkp-infobox').style.left = (PositionX+offsetx)+"px";
        document.getElementById('atkp-infobox').style.top = (PositionY+offsety)+"px";
        document.getElementById('atkp-infobox').style.visibility = "visible";

}


