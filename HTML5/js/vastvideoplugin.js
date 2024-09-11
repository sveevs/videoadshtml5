	window.chet = 0;

function AdSlot(_name,_type,_time,_zone) {
	this.name = _name;
	this.type = _type;
	this.time = _time;
	this.zone = _zone;
	this.source = "";
	this.seen = false;
	this.playOnce = true;	
	jQuery('#videohtml5_video').get(0).volume = php_vars_vol.volume;
}
	
function convertTimeFormat(hhmmss) {
	var _time = hhmmss.substr(0,1)*3600+hhmmss.substr(3,2)*60+hhmmss.substr(6,2)*1;
	return _time
}
	
function constructAdList(responseObj){
		for (v in AdList) {
			if (AdList[v].type.indexOf("roll") +1 ) {
				var adElement = responseObj.getElementById(AdList[v].name);
				MediaFiles = adElement.getElementsByTagName("MediaFiles");
				URL = MediaFiles[0].getElementsByTagName("URL");
        if(URL[0] == undefined){
          URL = MediaFiles[0].getElementsByTagName("MediaFile");
        }
				AdList[v].source = URL[0].childNodes[0].data;
			}
		}
		videoTag.addEventListener('timeupdate',showAdSlots,false);
}	
	 
	 
	 // Loading ads data from defined server
AdsRequest = function (AdObj){
	var http_request = new XMLHttpRequest();
	var script = "bannerTypeHtml:vastInlineBannerTypeHtml:vastInlineHtml";
		
	//constructing list for further populating and sorting
		
	var i1 = 0;
	var i2 = 0;
	var i3 = 0;
	var i4 = 0;
	var zones = "";
	
	for (v in AdObj.schedule) {
		switch (AdObj.schedule[v].position) {
			case "pre-roll":
				var a = new AdSlot("pre-roll-"+i1,"pre-roll",0,AdObj.schedule[v].zone);
				i1++;
				AdList.push(a);
				break
			case "mid-roll":
				var a = new AdSlot("mid-roll-"+i2,"mid-roll",convertTimeFormat(AdObj.schedule[v].startTime),AdObj.schedule[v].zone);
				i2++;
				AdList.push(a);
				break
			case "post-roll":
				var a = new AdSlot("post-roll-"+i3,"post-roll",0,AdObj.schedule[v].zone);
				i3++;
				AdList.push(a);
				break
			case "auto:bottom":
				var a = new AdSlot("auto:bottom-"+i4,"auto:bottom",convertTimeFormat(AdObj.schedule[v].startTime),AdObj.schedule[v].zone);
				i4++;
				AdList.push(a);
				break
			default:
				break
		} 
	}
	videoTag.addEventListener("canplay",setPostRollTime,false);
	videoTag.load();
		
	for (v in AdList) {
		zones += AdList[v].name +"=" +AdList[v].zone + "|";
	}
	zones = zones.substr(0,zones.length - 1)
		
		
	var nz = "1";
	var format = "vast";
	var charset = "UTF-8";
	var params = "script="+script+"&zones="+encodeURIComponent(zones)+"&nz="+nz+"&format="+format+"&charset="+charset;
	
	http_request.open( "GET",AdObj.servers[0]["apiAddress"], true );
	http_request.send(null);
	http_request.onreadystatechange = function () {
		if ( http_request.readyState == 4 ) {
			if ( http_request.status == 200 ) {
				var xml = http_request.responseXML;
				constructAdList(xml);
			}
			http_request = null;
		}
	}
};
	 
	 
	 //Parsing parameters from video tag
parseAdsParameters = function (input) {
	var AdObj = !(/[^,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]/.test(input.replace(/"(\\.|[^"\\])*"/g, ''))) &&eval('(' + input + ')');
	return AdObj;
}	 

function enforcePrecision(n, nDecimalDigits){
	return +(n).toFixed(nDecimalDigits);
}
	 
function seekToOriginalPoint() {
	videoTag.removeEventListener('canplaythrough', seekToOriginalPoint, false);
	videoTag.removeEventListener('load', seekToOriginalPoint, false);
	videoTag.currentTime = enforcePrecision(tempTime,1);
	videoTag.setAttribute("controls", "true");
	videoTag.play();
	videoTag.addEventListener('timeupdate',showAdSlots,false);
	
}
	
function resumePlayBackAfterSlotShow() {
	videoTag.removeEventListener('ended',resumePlayBackAfterSlotShow,false);
	jQuery(".skipBtn").hide();
	jQuery(".BtnVar1").show();
	jQuery(".BtnVar2").show();
	jQuery(".BtnVar3").show();
	jQuery(".BtnVar4").show();
	jQuery(".BtnVar5").show();
	jQuery(".BtnVar6").show();
	jQuery(".BtnVar7").show();
	jQuery(".BtnVar8").show();
	jQuery(".urldirect").show();

	
	videoTag.src = videoTag.mainTrack;
	videoTag.play();
	if(videoTag.readyState !== 4){ //HAVE_ENOUGH_DATA
		videoTag.addEventListener('canplaythrough', seekToOriginalPoint, false);
		videoTag.addEventListener('load', seekToOriginalPoint, false); //add load event as well to avoid errors, sometimes 'canplaythrough' won't dispatch.
		videoTag.pause();
		}
}
		
function showSlot(slot) {
	
	jQuery(".skipBtn").show().addClass("disabled");
	jQuery(".BtnVar1").hide();
	jQuery(".BtnVar2").hide();
	jQuery(".BtnVar3").hide();
	jQuery(".BtnVar4").hide();
	jQuery(".BtnVar5").hide();
	jQuery(".BtnVar6").hide();
	jQuery(".BtnVar7").hide();
	jQuery(".BtnVar8").hide();
	jQuery(".urldirect").hide();
	
	videoTag.src = slot.source;
	videoTag.play();

  var intervalAd = setInterval(function(){
    if(jQuery('#videohtml5_video').get(0).currentTime > (php_vars.schet+".20")){
	//alert (jQuery('#videohtml5_video').get(0).currentTime);
      jQuery(".skipBtn").removeClass("disabled");
	 
      clearInterval(intervalAd);
      
    }
	if(jQuery('#videohtml5_video').get(0).currentTime < (php_vars.schet+".20")){

		videoTag.removeAttribute("controls");
		
		chet = jQuery('#videohtml5_video').get(0).currentTime;
		var chetsub = Math.floor(chet);  //parseFloat(chet.substr(-10,10)); 
		 jQuery("#div_schet").text(chetsub);

    }
  }, 100);
	videoTag.addEventListener('ended',resumePlayBackAfterSlotShow,false);
}
	
	
function slotForCurrentTime(currentTime){
	for (v in AdList){ 
		if (!AdList[v].seen){
			if (AdList[v].time == currentTime) {
				return AdList[v];
			}
		}
	}
	return null;
		
}
	
	function showAdSlots() {
		
		var slot = slotForCurrentTime(Math.floor(videoTag.currentTime));
		if (slot) {
			slot.seen = true;
			tempTime = videoTag.currentTime;
			videoTag.removeEventListener('timeupdate',showAdSlots,false);
			showSlot(slot);
			
				
		}
      
    }
		
function initAdsFor(videoID) {
	window.tempTime = 0;
	
	window.counterOfStreams = 0;
	window.videoTag = document.getElementById(videoID);
	videoTag.mainTrack = videoTag.src;
	window.AdList = new Array;
	
	
	window.AdObj = parseAdsParameters(videoTag.getAttribute('ads'));
	window.AdsRequest(AdObj);

}
	
function setPostRollTime() {
		videoTag.removeEventListener("canplay",setPostRollTime,false);
		for (v in AdList) {
			if (AdList[v].type == "post-roll") {
				AdList[v].time = Math.floor(videoTag.duration);
			}
		}		
}

jQuery(document).on('click','.skipBtn', function(e){
 controls='false';
	if(jQuery('#videohtml5_video').get(0).currentTime < (php_vars.schet+".20")){
		return;
	}
	resumePlayBackAfterSlotShow();
});



jQuery(document).on('click','.BtnVar1', function(e){
 controls='false';
	
	videoTag.src = php_vars1.silki; 
	
	videoTag.play();
	if(videoTag.readyState !== 4){ 
		videoTag.addEventListener('canplaythrough', seekToOriginalPoint, false);
		videoTag.addEventListener('load', seekToOriginalPoint, false);
		videoTag.pause();
		}
	jQuery('div.BtnVar1').addClass('BtnVarActiv');	
	jQuery('div.BtnVar2').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar3').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar4').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar5').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar6').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar7').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar8').removeClass('BtnVarActiv');	
		
});



jQuery(document).on('click','.BtnVar2', function(e){
 controls='false';
	
	videoTag.src = php_vars2.silki;
	
	videoTag.play();
	if(videoTag.readyState !== 4){ 
		videoTag.addEventListener('canplaythrough', seekToOriginalPoint, false);
		videoTag.addEventListener('load', seekToOriginalPoint, false); 
		videoTag.pause();
		}
	jQuery('div.BtnVar2').addClass('BtnVarActiv');
	jQuery('div.BtnVar1').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar3').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar4').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar5').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar6').removeClass('BtnVarActiv');
	jQuery('div.BtnVar7').removeClass('BtnVarActiv');
	jQuery('div.BtnVar8').removeClass('BtnVarActiv');		
	
});



jQuery(document).on('click','.BtnVar3', function(e){
 controls='false';
	
	videoTag.src = php_vars3.silki;
	
	videoTag.play();
	if(videoTag.readyState !== 4){ 
		videoTag.addEventListener('canplaythrough', seekToOriginalPoint, false);
		videoTag.addEventListener('load', seekToOriginalPoint, false); 
		videoTag.pause();
		}
	 jQuery('div.BtnVar3').addClass('BtnVarActiv');	
	 jQuery('div.BtnVar2').removeClass('BtnVarActiv');	
	 jQuery('div.BtnVar1').removeClass('BtnVarActiv');	
	 jQuery('div.BtnVar4').removeClass('BtnVarActiv');	
	 jQuery('div.BtnVar5').removeClass('BtnVarActiv');	
	 jQuery('div.BtnVar6').removeClass('BtnVarActiv');	
	 jQuery('div.BtnVar7').removeClass('BtnVarActiv');	
	 jQuery('div.BtnVar8').removeClass('BtnVarActiv');	
	
});



jQuery(document).on('click','.BtnVar4', function(e){
 controls='false';
	
	videoTag.src = php_vars4.silki;
	
	videoTag.play();
	if(videoTag.readyState !== 4){ 
		videoTag.addEventListener('canplaythrough', seekToOriginalPoint, false);
		videoTag.addEventListener('load', seekToOriginalPoint, false); 
		videoTag.pause();
		}
	jQuery('div.BtnVar4').addClass('BtnVarActiv');	
	jQuery('div.BtnVar2').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar3').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar1').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar5').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar6').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar7').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar8').removeClass('BtnVarActiv');	

});


jQuery(document).on('click','.BtnVar5', function(e){
 controls='false';

	videoTag.src = php_vars5.silki; 
	
	videoTag.play();
	if(videoTag.readyState !== 4){ 
		videoTag.addEventListener('canplaythrough', seekToOriginalPoint, false);
		videoTag.addEventListener('load', seekToOriginalPoint, false); 
		videoTag.pause();
		}

		 jQuery('div.BtnVar5').addClass('BtnVarActiv');
		 jQuery('div.BtnVar2').removeClass('BtnVarActiv');	
		 jQuery('div.BtnVar3').removeClass('BtnVarActiv');	
		 jQuery('div.BtnVar4').removeClass('BtnVarActiv');	
		 jQuery('div.BtnVar1').removeClass('BtnVarActiv');	
		 jQuery('div.BtnVar6').removeClass('BtnVarActiv');	
		 jQuery('div.BtnVar7').removeClass('BtnVarActiv');	
		 jQuery('div.BtnVar8').removeClass('BtnVarActiv');	
	
		
});


jQuery(document).on('click','.BtnVar6', function(e){
 controls='false';
	
	videoTag.src = php_vars6.silki;

	videoTag.play();
	if(videoTag.readyState !== 4){
		videoTag.addEventListener('canplaythrough', seekToOriginalPoint, false);
		videoTag.addEventListener('load', seekToOriginalPoint, false);
		videoTag.pause();
		}
	
	jQuery('div.BtnVar6').addClass('BtnVarActiv');	
	jQuery('div.BtnVar2').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar3').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar4').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar5').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar1').removeClass('BtnVarActiv');
	jQuery('div.BtnVar7').removeClass('BtnVarActiv');		
	jQuery('div.BtnVar8').removeClass('BtnVarActiv');	

});

jQuery(document).on('click','.BtnVar7', function(e){
 controls='false';
	
	videoTag.src = php_vars7.silki;

	videoTag.play();
	if(videoTag.readyState !== 4){
		videoTag.addEventListener('canplaythrough', seekToOriginalPoint, false);
		videoTag.addEventListener('load', seekToOriginalPoint, false);
		videoTag.pause();
		}
	jQuery('div.BtnVar7').addClass('BtnVarActiv');		
	jQuery('div.BtnVar6').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar2').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar3').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar4').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar5').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar1').removeClass('BtnVarActiv');
	jQuery('div.BtnVar8').removeClass('BtnVarActiv');	


});

jQuery(document).on('click','.BtnVar8', function(e){
 controls='false';
	
	videoTag.src = php_vars8.silki;

	videoTag.play();
	if(videoTag.readyState !== 4){
		videoTag.addEventListener('canplaythrough', seekToOriginalPoint, false);
		videoTag.addEventListener('load', seekToOriginalPoint, false);
		videoTag.pause();
		}
	jQuery('div.BtnVar8').addClass('BtnVarActiv');	
	jQuery('div.BtnVar7').removeClass('BtnVarActiv');		
	jQuery('div.BtnVar6').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar2').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar3').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar4').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar5').removeClass('BtnVarActiv');	
	jQuery('div.BtnVar1').removeClass('BtnVarActiv');



});

//Показать контекстное меню:

jQuery('#videohtml5_video').ready(function() {
//Контекст меню
//jQuery(document).on('contextmenu', function(e){
	jQuery('#videohtml5_video').on('contextmenu', function(e){

	//Устанавливаем размер окна:
		var winWidth = jQuery('#videohtml5_video').width();
		var winHeight = jQuery('#videohtml5_video').height();
		//Устанавливаем позицию:
		var posX = e.pageX;
		var posY = e.pageY;
		var menuWidth = jQuery(".contextmenu").width();
		var menuHeight = jQuery(".contextmenu").height();
		var secMargin = 1;

//Предотвращение переполнения:
		if(posX + menuWidth + secMargin >= winWidth
			&& posY + menuHeight + secMargin >= winHeight){
			//Случай 1: переполнение правого края:
			posLeft = posX - menuWidth - secMargin + "px";
			posTop = posY - menuHeight - secMargin + "px";
			}
		else if(posX + menuWidth + secMargin >= winWidth){
			//Случай 2: переполнение текста справа:
			posLeft = posX - menuWidth - secMargin + "px";
			posTop = posY + secMargin + "px";
			}
		else if(posY + menuHeight + secMargin >= winHeight){
			// Случай 3: нижнее переполнение:
			posLeft = posX + secMargin + "px";
			posTop = posY - menuHeight - secMargin + "px";
			}
		else {
			//Случай 4: значения по умолчанию :
			posLeft = posX + secMargin + "px";
			posTop = posY + secMargin + "px";
			};
			//Отобразить контекстное меню:
			//alert( "Вызвано событие .contextmenu()" );
	jQuery(".contextmenu").css({
		"left": posLeft,
		"top": posTop
	}).show();
	//Запретить контекстное меню браузера по умолчанию.
	return false;
	});

	//Скрываем контекстное меню:
	jQuery('#videohtml5_video').click(function(){
	jQuery(".contextmenu").hide();
	});
 
});
