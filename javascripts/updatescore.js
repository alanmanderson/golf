function update(category) {
    if (category=="strokes"){
	var strokesTotal=0;
	var backStrokesTotal=0;
	var frontStrokesTotal=0;
	for(i=1;i<=18;i++)
	    {
		curHole=document.getElementsByName("strokes["+i+"]")[0].value
		    if (curHole=="") curHole = 0;
		if (i<=9) frontStrokesTotal+=parseInt(curHole);
		else backStrokesTotal+=parseInt(curHole);
	    }
	strokesTotal=frontStrokesTotal+backStrokesTotal;
	document.getElementById("strokesOut").innerHTML=frontStrokesTotal;
	document.getElementById("strokesIn").innerHTML=backStrokesTotal;
	document.getElementById("strokesTotal").innerHTML=strokesTotal;
    }
    else if (category=="fairway")
	{
	    var fairwayTotal=0;
	    var frontFairwayTotal=0;
	    var backFairwayTotal=0;
	    for(i=1;i<=18;i++)
		{
		    if (document.getElementsByName("fairway["+i+"]")[0].checked)
			{
			    if (i<=9) frontFairwayTotal++;
			    else backFairwayTotal++;
			}
		}
	    fairwayTotal=frontFairwayTotal+backFairwayTotal;
	    document.getElementById("fairwayOut").innerHTML=frontFairwayTotal;
	    document.getElementById("fairwayIn").innerHTML=backFairwayTotal;
	    document.getElementById("fairwayTotal").innerHTML=fairwayTotal;
	}
    else if (category=="green")
	{
	    var greenTotal=0;
	    var backGreenTotal=0;
	    var frontGreenTotal=0;
	    for(i=1;i<=18;i++)
		{
		    if (document.getElementsByName("green["+i+"]")[0].checked)
			{
			    if (i<=9) frontGreenTotal++;
			    else backGreenTotal++;
			}
		}
	    greenTotal=frontGreenTotal+backGreenTotal;
	    document.getElementById("greenOut").innerHTML=frontGreenTotal;
	    document.getElementById("greenIn").innerHTML=backGreenTotal;
	    document.getElementById("greenTotal").innerHTML=greenTotal;
	}
    else if (category=="putts")
	{
	    var puttsTotal=0;
	    var frontPuttsTotal=0;
	    var backPuttsTotal=0;
	    for(i=1;i<=18;i++)
		{
		    curHole=document.getElementsByName("putts["+i+"]")[0].value
			if (curHole=="") curHole = 0;
		    if (i<=9) frontPuttsTotal+=parseInt(curHole);
		    else backPuttsTotal+=parseInt(curHole);
		}
	    puttsTotal=frontPuttsTotal+backPuttsTotal;
	    document.getElementById("puttsOut").innerHTML=frontPuttsTotal;
	    document.getElementById("puttsIn").innerHTML=backPuttsTotal;
	    document.getElementById("puttsTotal").innerHTML=puttsTotal;
	}
    else if (category=="penalties")
	{
	    var penaltiesTotal=0;
	    var frontPenaltiesTotal=0;
	    var backPenaltiesTotal=0;
	    for(i=1;i<=18;i++)
		{
		    curHole=document.getElementsByName("penalties["+i+"]")[0].value
			if (curHole=="") curHole = 0;
		    if (i<=9) frontPenaltiesTotal+=parseInt(curHole);
		    else backPenaltiesTotal+=parseInt(curHole);
		}
	    penaltiesTotal=frontPenaltiesTotal+backPenaltiesTotal;
	    document.getElementById("penaltiesOut").innerHTML=frontPenaltiesTotal;
	    document.getElementById("penaltiesIn").innerHTML=backPenaltiesTotal;
	    document.getElementById("penaltiesTotal").innerHTML=penaltiesTotal;
	}
}