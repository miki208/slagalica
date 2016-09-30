	var pahulje=Array();
        var imgDir="plugin/slike/";
	var docWid;
	var docH;
	var brzina=100;//200
	var vetar=0;
	var dozvola_list=1;
	var korak=4;//8
	var verovatnoca=60;//5
	var deda_mraz_korak=2;
	var deda_mraz_objekat;
	var vatromet=0;
	var text=null;
	var time;
	
	function pomeriPahulje()
	{
		document.getElementById("ov").style.backgroundImage="url:(http://25.media.tumblr.com/tumblr_lupna6JZdD1qzjl9xo1_500.gif)";
		if(nova_godina())
		{
			var vr=nova_godina();
                        time=new Date();
			if(vr==1)
			{
				if(!vatromet)
				{
					document.getElementById("ov").style.backgroundSize="100% 100%";
					document.getElementById("ov").style.backgroundImage="url(http://www.boat1.org/images/tumblr_luxknxCWO31qlsiq8o1_500.gif)";
					vatromet=1;
				}
				if(text==null)
				{
					text=document.createElement("p");
					text.setAttribute("id","sngtext");
					text.setAttribute("style","font-size:50px; color:red; position:absolute; width:100%; margin:0px; text-align:center; top:"+(parseInt(docH/2)-20).toString()+"px;");
					document.getElementById("ov").appendChild(text);
					text.innerHTML="Srecna nova "+time.getFullYear()+" godina!!!";
				}
				text.innerHTML="Srecna nova "+time.getFullYear()+" godina!!!";
			}
			else if(vr==2)
			{
				if(!vatromet)
				{
					document.getElementById("ov").style.backgroundSize="100% 100%";
					document.getElementById("ov").style.backgroundImage="url(http://www.boat1.org/images/tumblr_luxknxCWO31qlsiq8o1_500.gif)";
					vatromet=1;
				}
				if(text==null)
				{
					text=document.createElement("p");
					text.setAttribute("id","sngtext");
					text.setAttribute("style","font-size:50px; color:red; position:absolute; width:100%; margin:0px; text-align:center; top:"+(parseInt(docH/2)-20).toString()+"px;");
					document.getElementById("ov").appendChild(text);
					text.innerHTML=(3600*24-(3600*23+60*time.getMinutes()+time.getSeconds())).toString();
				}
				text.innerHTML=(3600*24-(3600*23+60*time.getMinutes()+time.getSeconds())).toString();
			}
		}
		else
		{
			if(text!=null)
			{
				document.getElementById("ov").removeChild(text);
				text=null;
			}
			if(vatromet)
			{
				vatromet=0;
				document.getElementById("ov").style.backgroundImage="none";
			}
		}
		var len=pahulje.length;
		if(Math.floor((Math.random()*verovatnoca)+1)==parseInt((verovatnoca+1)/2))
		{
			vetar=Math.floor((Math.random()*11)-5);
			if((vetar==5 || vetar==-5) && dozvola_list)
			{
				dodajList();
				dozvola_list=0;
				setTimeout("podesiDozvolu()",2000);
			}
		}
		
		/*deda mraz*/
		var dm_pos=parseInt(deda_mraz_objekat.style.left.substr(0,deda_mraz_objekat.style.left.length-2));
		if(deda_mraz_objekat.src.substr(deda_mraz_objekat.src.lastIndexOf("/")+1)=="deda_mraz_desno.png")
		{
			dm_pos+=deda_mraz_korak;
			if(dm_pos>docWid)
			{
				dm_pos=docWid;
				deda_mraz_objekat.src=imgDir+"deda_mraz_levo.png";
			}
		}
		else
		{
			dm_pos-=deda_mraz_korak;
			if(dm_pos<(-deda_mraz_objekat.offsetWidth))
			{
				dm_pos=-deda_mraz_objekat.offsetWidth;
				deda_mraz_objekat.src=imgDir+"deda_mraz_desno.png";
			}
		}
		deda_mraz_objekat.style.left=dm_pos.toString()+"px";
		/*deda mraz*/
		
		for(var i=0;i<len;i++)
		{
			var val=parseInt(pahulje[i].style.top.substr(0,pahulje[i].style.top.length-2));
			val+=korak;
			var val1=parseInt(pahulje[i].style.left.substr(0,pahulje[i].style.left.length-2));
			val1+=vetar;
			if(val1>docWid)
			val1=-pahulje[i].offsetWidth;
			else if(val1<(-pahulje[i].offsetWidth))
			val1=docWid;
			var pr=isList(pahulje[i]);
			if(val>docH)
			{
				document.getElementById("ov").removeChild(pahulje[i]);
				pahulje.splice(i,1);
				i=i-1;
				len=len-1;
				if(!pr)
				setTimeout("dodajPahulju()",Math.floor((Math.random()*901)+100));
				continue;
			}
			if(pr)
			{
				if(vetar==0)
				pahulje[i].src=imgDir+"list_dole.png";
				else if(vetar<0)
				pahulje[i].src=imgDir+"list_levo.png";
				else if(vetar>0)
				pahulje[i].src=imgDir+"list_desno.png";
			}
			pahulje[i].style.top=val.toString()+"px";
			pahulje[i].style.left=val1.toString()+"px";
		}
		setTimeout("pomeriPahulje()",brzina);
	}
	
	function dodajPahulju()
	{
		var wid=Math.floor((Math.random()*36)+15);
		var lft=Math.floor((Math.random()*(docWid-wid))+1);
		pahulje.push(document.createElement("img"));
		var pahid=pahulje.length-1;
		pahulje[pahid].setAttribute("id","img"+pahulje.length.toString());
		pahulje[pahid].setAttribute("src",imgDir+"pahulja.png");
		pahulje[pahid].setAttribute("style","top:"+(-wid).toString()+"px; left:"+lft.toString()+"px; position:absolute; overflow:hidden;");
		pahulje[pahid].setAttribute("width",wid.toString());
		document.getElementById("ov").appendChild(pahulje[pahid]);
	}
	
	function dodajList()
	{
		var wid=Math.floor((Math.random()*36)+15);
		var lft=Math.floor((Math.random()*(docWid-wid))+1);
		pahulje.push(document.createElement("img"));
		var pahid=pahulje.length-1;
		pahulje[pahid].setAttribute("id","img"+pahulje.length.toString());
		if(vetar==0)
		pahulje[pahid].setAttribute("src",imgDir+"list_dole.png");
		else if(vetar<0)
		pahulje[pahid].setAttribute("src",imgDir+"list_levo.png");
		else if(vetar>0)
		pahulje[pahid].setAttribute("src",imgDir+"list_desno.png");
		pahulje[pahid].setAttribute("style","top:"+(-wid).toString()+"px; left:"+lft.toString()+"px; position:absolute;");
		pahulje[pahid].setAttribute("width",wid.toString());
		document.getElementById("ov").appendChild(pahulje[pahid]);
	}
	
	function deda_mraz()
	{
		var wid=200;
		var lft=-wid;
		deda_mraz_objekat=document.createElement("img");
		deda_mraz_objekat.setAttribute("id","deda_mraz");
		deda_mraz_objekat.setAttribute("src",imgDir+"deda_mraz_desno.png");
		deda_mraz_objekat.setAttribute("width",wid.toString());
		deda_mraz_objekat.setAttribute("style","top:"+(docH-49).toString()+"px; left:"+lft.toString()+"px; position:absolute;");
		document.getElementById("ov").appendChild(deda_mraz_objekat);
	}
	
	function isList(obj)
	{
		if(obj.src.substr(obj.src.lastIndexOf("/")+1,4)=="list")
		{
			return 1;
		}
		return 0;
	}
	
	function podesiDozvolu()
	{
		dozvola_list=1;
	}
	
	function podesi(idOkvir)
	{
		var okvir=document.createElement("div");
		okvir.setAttribute("id","ov");
		okvir.setAttribute("style","margin:0px; width:100%; height:100%; z-index:1; position:relative; overflow:hidden;");
		document.getElementById(idOkvir).appendChild(okvir);
	}
	
	function nova_godina()
	{
		var time=new Date();
		var datum=time.getDate();
		var mesec=time.getMonth()+1;
		var minut=time.getMinutes();
		var sekund=time.getSeconds();
		var sat=time.getHours();
		if(mesec==12&&datum==31&&sat==23&&minut>=58)
		{
			return 2;
		}
		else if(mesec==1&&datum==1&&sat==0&&minut<20)
		{
			return 1;
		}
		return 0;
	}
	
	function init()
	{
		podesi("logo");
		docWid=document.getElementById("ov").offsetWidth;
		docH=document.getElementById("ov").offsetHeight;
		var brPah=Math.floor((Math.random()*16)+5);
		deda_mraz();
		pomeriPahulje();
		for(var i=0;i<brPah;i++)
		{
			setTimeout("dodajPahulju()",Math.floor((Math.random()*(parseInt(docH/korak-1)*brzina))+brzina));
		}
	}
	