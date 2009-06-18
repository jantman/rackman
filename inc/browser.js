function openWindow (nsOie) {
	if (nsOie == 0) { 
		window.open ('http://www.microsoft.com/windows/ie_intl/es/','','width=600,height=400,scrollbars=yes') ;
	  }
	if (nsOie == 1) {
		window.open ('http://home.netscape.com/es/download/index.html?cp=djues','','width=600,height=400,scrollbars=yes') ;
	  }
  }

function Browser() {
	var b=navigator.appName;
	if (b=="Netscape") this.b="ns";
	else if (navigator.userAgent.indexOf("Opera")>0) this.b = "opera";
	else if (b=="Microsoft Internet Explorer") this.b="ie";	
	this.version=navigator.appVersion ;
	this.v=parseInt(this.version) ;
	this.nsmenor=(this.b=="ns" && this.v<4) ;
	this.ns=(this.b=="ns" && this.v>=4) ;
	this.ns4=(this.b=="ns" && this.v==4) ;
	this.ns5=(this.b=="ns" && this.v==5) ;
	this.iemenor=(this.b=="ie" && this.v<4) ;
	this.ie=(this.b=="ie" && this.v>=4) ;
	this.ie4=(this.version.indexOf('MSIE 4')>0) ;
	this.ie5=(this.version.indexOf('MSIE 5')>0) ;
	this.ie55=(this.version.indexOf('MSIE 5.5')>0) ;
	this.opera=(this.b=="opera") ;
	
	if (this.iemenor == true || this.nsmenor == true) {
		var aceptado = confirm ("Tu versión de navegador és muy antigua. Si desea actualizarla pulse Aceptar")
		if (aceptado == true && this.iemenor == true) {
			openWindow (0) ;
		  }
		if (aceptado == true && this.nsmenor == true) {
			openWindow (1) ;
		  }
	  }
  }

is = new Browser();
