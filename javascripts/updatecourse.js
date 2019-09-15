function update(category) {
    if (category=="hole_length_far"){
	var holeLengthTotal=0;
	var backHoleLengthTotal=0;
	var frontHoleLengthTotal=0;
	for(i=1;i<=18;i++)
        {
	    curHole=document.getElementsByName("hole_length[FAR]["+i+"]")[0].value;
	    if (curHole=="") curHole = 0;
	    if (i<=9) frontHoleLengthTotal+=parseInt(curHole);
	    else backHoleLengthTotal+=parseInt(curHole);
	}
	HoleLengthTotal=frontHoleLengthTotal+backHoleLengthTotal;
	document.getElementById("hlFarOut").innerHTML=frontHoleLengthTotal;
	document.getElementById("hlFarIn").innerHTML=backHoleLengthTotal;
	document.getElementById("hlFarTotal").innerHTML=HoleLengthTotal;
    }
    else if (category=="hole_length_mid")
    {
	var holeLengthTotal=0;
	var frontHoleLengthTotal=0;
	var backHoleLengthTotal=0;
	for(i=1;i<=18;i++)
	{
	    curHole=document.getElementsByName("hole_length[MIDDLE]["+i+"]")[0].value;
	    if (curHole=="") curHole = 0;
	    if (i<=9) frontHoleLengthTotal+=parseInt(curHole);
	    else backHoleLengthTotal+=parseInt(curHole);
	}
	holeLengthTotal=frontHoleLengthTotal+backHoleLengthTotal;
	document.getElementById("hlMidOut").innerHTML=frontHoleLengthTotal;
	document.getElementById("hlMidIn").innerHTML=backHoleLengthTotal;
	document.getElementById("hlMidTotal").innerHTML=holeLengthTotal;
    }
    else if (category=="hole_length_close")
    {
	var holeLengthTotal=0;
	var frontHoleLengthTotal=0;
	var backHoleLengthTotal=0;
	for(i=1;i<=18;i++)
	{
	    curHole=document.getElementsByName("hole_length[CLOSE]["+i+"]")[0].value;
	    if (curHole=="") curHole = 0;
	    if (i<=9) frontHoleLengthTotal+=parseInt(curHole);
	    else backHoleLengthTotal+=parseInt(curHole);
	}
	holeLengthTotal=frontHoleLengthTotal+backHoleLengthTotal;
	document.getElementById("hlCloseOut").innerHTML=frontHoleLengthTotal;
	document.getElementById("hlCloseIn").innerHTML=backHoleLengthTotal;
	document.getElementById("hlCloseTotal").innerHTML=holeLengthTotal;
    }
    else if (category=="par")
	{
	    var parTotal=0;
	    var frontParTotal=0;
	    var backParTotal=0;
	    for(i=1;i<=18;i++)
		{
		    curHole=document.getElementsByName("par["+i+"]")[0].value
			if (curHole=="") curHole = 0;
		    if (i<=9) frontParTotal+=parseInt(curHole);
		    else backParTotal+=parseInt(curHole);
		}
	    parTotal=frontParTotal+backParTotal;
	    document.getElementById("parOut").innerHTML=frontParTotal;
	    document.getElementById("parIn").innerHTML=backParTotal;
	    document.getElementById("parTotal").innerHTML=parTotal;
	}
}
