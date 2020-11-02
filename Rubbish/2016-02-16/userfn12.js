// Global user functions
// Global user functions

jQuery.browser={};(function(){jQuery.browser.msie=false;
jQuery.browser.version=0;if(navigator.userAgent.match(/MSIE ([0-9]+)\./)){
jQuery.browser.msie=true;jQuery.browser.version=RegExp.$1;}})();  
jQuery(document).ready(function(){

	/*fix ie css style*/
	if(jQuery.browser.msie){
		var container = $('div[id*="gmp_"]'); 
		container.find('tbody').find('tr').each(function(i,row){
				   $(row).hover(function() {
						$(this).addClass('ewTableHighlightRow');
					}, function() {
						$(this).removeClass('ewTableHighlightRow');
					})
				.click(function(e){
						if($(this).find('input:checkbox:checked').length === 0) $(this).removeAttr('style').toggleClass('ewTableSelectRow');
					})
				.find('input:checkbox').bind('click', function(){
					$(row).removeAttr('style').toggleClass('ewTableSelectRow',this.checked);    
				});
	  });    
	  container.find('#key').click(function(){
		  var key = this;
		  container.find('input[name="key_m[]"]').each(function(){
			  this.checked = key.checked;  
		  });             
	  });
	} 
});
if (!String.prototype.trim) {
  String.prototype.trim = function () {
	return this.replace(/^\s+|\s+$/g, '');
  };
}

function showAge(el){
	var msgContainer = $('#' + $(el).data('idmsgage'));
	if(!msgContainer.length) return;
	msgContainer.html(getAge($(el).val()));
}

function clearHTML(html){
	return $('<div/>').html(html).text();
}

function CreateDialog(url, title, idwindow) {
			title = title ? title : "Dialog";
	var fnClick = "$(this).closest('.modal-content').find('iframe').contents().find('#btnAction').trigger('click');";
	var tplDialog = 		'<div class="modal col-xs-12 fade" id="' + idwindow + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
		  '<div class="modal-dialog" style="width:100%">'+
			'<div class="modal-content">'+
			'  <div class="modal-header">'+
			'	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'+
			'	<h4 class="modal-title" id="myModalLabel">' + title + '</h4>'+
			'  </div>'+
			'  <div class="modal-body">'+
			'  <iframe id="marco-' + idwindow + '" class="autosize fixedwidth empty" scrolling="no" src="" frameborder="0" marginheight="0" marginwidth="0"  style="width:95%"></iframe>'+
			'  </div>'+
			'  <div class="modal-footer">'+
			'	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>'+
			'	<button type="button" onclick="' + fnClick + '" class="btn btn-primary">Grabar</button>'+
			'  </div>'+
			'</div>'+
		  '</div>'+
		'</div>';
		if(!top.jQuery('#' + idwindow).length) top.jQuery('body').append(tplDialog);
		top.jQuery('#marco-' + idwindow).data("url", url);
}

function MostrarVentana(idwindow,url,_title,actualizar){
var mywindow;
	if(idwindow == ''){
		idwindow = top.jQuery(document).find('iframe[src="'+ url +'"]').closest('.modal').attr('id')//ver si el link ya tiene una ventana asignada
		if(!idwindow)//si no se ha encontrado se le asigna un id
		  idwindow = "w" + Math.floor(Math.random() * (1000-20+1)) + 20; // un id entre 20 y 1000 con la letra w al inicio
	} 
	mywindow =  top.jQuery("#" + idwindow);
	if(!mywindow.length){               
		splashScreen();
		CreateDialog(url,(arguments.length == 3 || arguments[3] == 1? _title : 'Medic@l ') + (typeof nombre_usuario !== "undefined" && nombre_usuario!=''? '&nbsp;&nbsp;------&gt;&gt;<span class="underline1">' +  nombre_usuario + '</span>': "") , idwindow) ;      
		mywindow = top.jQuery("#" + idwindow);  
	}else{
		if ((arguments.length == 4) && (arguments[3] == 0) ){
			top.jQuery.unblockUI();    
		}
		if(actualizar){
			splashScreen();
			if(url!='')
			mywindow.find('.modal-title').text((arguments.length == 3 || arguments[3] == 1? _title : 'Medic@l ') + (typeof nombre_usuario !== "undefined" && nombre_usuario!=''? '&nbsp;&nbsp;------&gt;&gt;<span class="underline1">' +  nombre_usuario + '</span>': ""))
			.end().find('iframe').data('url', url);
		}
	}
	var ifrm = mywindow.modal('show').find('iframe');
	if(actualizar || ifrm.attr('src') == '') {ifrm.attr('src', ifrm.data('url'));}
}

function mainwin(w){
	if(!w.frameElement) return w;
	if(w.frameElement.className == 'ui-dialog-frame') return w;
	return mainwin(w.parent);
}                                                            

function resizeIFRM(delay){
	if(!window.frameElement || $(window.frameElement).hasClass('iframe-resizing') ) return;
	delay = typeof delay == "undefined"?0:delay;
	setTimeout(function(){
		$(window.frameElement).addClass('iframe-resizing');
		if (window.self != window.top) {
			$(parent.document).find('iframe').each(function() {
			if (this.contentWindow.document == window.document) {
					var ifrm = this;
					$(ifrm).css({ height: $('body').height()+ 30 + 'px'});
					$(ifrm).css({'width':document.body.scrollWidth + 'px'});
					$(ifrm).css({'width':document.body.scrollWidth + 'px'});
				}
			});
		}	
		$(window.frameElement).removeClass('iframe-resizing');
	},delay);	
}

function splashScreen(msg){
	 if (typeof msg == "undefined") msg = 'Un momento por favor...';
	 if(typeof top.jQuery.blockUI == 'undefined') return;
	top.jQuery.unblockUI();
	top.jQuery.blockUI({ css:{padding:10},message: '<h3>' + msg + '<img src="' + (top.jQuery('#ja-main').length > 0 ? baseDir : '') + 'busqueda/img/loading.gif"> </h3>',
					 baseZ: 800000, ignoreIfBlocked: true  
	});
} 

function getURLParameter(name) {
	return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
}

//function refreshTable(time, complete ){
function ApplyTemplateTable(containerTable){
	containerTable.find("table." + EW_TABLE_CLASSNAME + ":not(.ewExportTable):not(#" + EW_REPORT_CONTAINER_ID + " table)").each(ew_SetupTable); // Init tables
	containerTable.find("table." + EW_GRID_CLASSNAME + ":not(.ewExportTable):not(#" + EW_REPORT_CONTAINER_ID + " table)").each(ew_SetupGrid); // Init grids
	coolTemplate(containerTable);
}

function refreshTable(options){
	var referencia = '#' + options.containerTable.attr('id');
	if(!$.isUndefined(top) && !$.isUndefined(top.isScrolling) && !top.isScrolling)
	if(( options.condition.call() && !$('.pageload-overlay:visible').length && $(window.frameElement?window.frameElement:window).is(':visible') && $(referencia).is(':visible:not(.updating)') && options.containerTable.find('input:checkbox:checked').length === 0)|| options.forceRefresh ){
		$(referencia).addClass('updating');
		options.onbefore.call(this,options);	
		$.get( options.url + ( options.params != null?(options.url.indexOf('?')==-1 ? '?' : '&') + jQuery.param( options.params ):'') + ' #' + options.containerTable.attr('id') , function(data) {
		$(referencia).empty().append($(data).find(referencia).html());
			options.oncomplete.call();
			ApplyTemplateTable($(referencia));
			resizeIFRM(3000);
			$(referencia).removeClass('updating');
		});
		$('#ewpagerform').load(location.href + ' #ewpagerform .ewPager', function(){ $(this).form(); });
	}    
	if(options.time > 0) //si se desea refrescar en periodos de tiempo
		setTimeout(function(){refreshTable(options);},options.time);
}

function refreshTableOn(options){
	var defaultopt = {
		time: 10000, //10 segundos
		onbefore : function(){},
		oncomplete : function(){},
		containerTable : $('div[id*="gmp_"]'),
		condition : function(){return true},
		params : null,
		forceRefresh : false,
		url : (location.href.indexOf('about:blank')!=-1?$(window.frameElement).data('url'):location.href)    
	}
	if(typeof options  !== 'undefined' ) $.extend(defaultopt, options);
	ApplyTemplateTable(defaultopt.containerTable);
	setTimeout(function(){refreshTable(defaultopt)}, defaultopt.time );
}

function getAge(dateString,formato) {
	if (dateString == '') return '';
	var fmt = typeof formato != 'undefined'? formato : 'dd/mm/aaaa';
  var now = new Date();
  var today = new Date(now.getFullYear(),now.getMonth(),now.getDate());
  var yearNow = now.getFullYear();
  var monthNow = now.getMonth();
  var dateNow = now.getDate();
  var _year = fmt=='aaaa/mm/dd'?dateString.substring(0,4):parseInt(dateString.substring(6,10));
  var _month = fmt=='aaaa/mm/dd'?dateString.substring(5,7)-1:parseInt(dateString.substring(3,5))-1;
  var _day = fmt=='aaaa/mm/dd'?dateString.substring(8,10):parseInt(dateString.substring(0,2));
  var dob = new Date(_year,_month,_day);

	//console.log(dateString,fmt,dob);
  var yearDob = dob.getFullYear();
  var monthDob = dob.getMonth();
  var dateDob = dob.getDate();
  var age = {};
  var ageString = "";
  var yearString = "";
  var monthString = "";
  var dayString = "";
  yearAge = yearNow - yearDob;
  if (monthNow >= monthDob)
	var monthAge = monthNow - monthDob;
  else {
	yearAge--;
	var monthAge = 12 + monthNow -monthDob;
  }
  if (dateNow >= dateDob)
	var dateAge = dateNow - dateDob;
  else {
	monthAge--;
	var dateAge = 31 + dateNow - dateDob;
	if (monthAge < 0) {
	  monthAge = 11;
	  yearAge--;
	}
  }
  age = {
	  years: yearAge,
	  months: monthAge,
	  days: dateAge
	  };
  if ( age.years > 1 ) yearString = " a&ntilde;os";
  else yearString = " a&ntilde;o";
  if ( age.months> 1 ) monthString = " meses";
  else monthString = " mes";
  if ( age.days > 1 ) dayString = " d&iacute;as";
  else dayString = " d&iacute;a";
  if ( (age.years > 0) && (age.months > 0) && (age.days > 0) )
	ageString = age.years + yearString + ", " + age.months + monthString + ", y " + age.days + dayString; // + " old.";
  else if ( (age.years == 0) && (age.months == 0) && (age.days > 0) )
	ageString = "Solo " + age.days + dayString; // + " old!";
  else if ( (age.years > 0) && (age.months == 0) && (age.days == 0) )
	ageString = age.years + yearString; // + " old. Happy Birthday!!";
  else if ( (age.years > 0) && (age.months > 0) && (age.days == 0) )
	ageString = age.years + yearString + " y " + age.months + monthString; // + " old.";
  else if ( (age.years == 0) && (age.months > 0) && (age.days > 0) )
	ageString = age.months + monthString + " y " + age.days + dayString; // + " old.";
  else if ( (age.years > 0) && (age.months == 0) && (age.days > 0) )
	ageString = age.years + yearString + " y " + age.days + dayString; // + " old.";
  else if ( (age.years == 0) && (age.months > 0) && (age.days == 0) )
	ageString = age.months + monthString; // + " old.";
  else ageString = "Error! No se pudo calcular la edad!";
  return ageString;
}

function coolRadioCheckBtn(container ){
	if (typeof container == 'undefined') var container = $('body');

	//Personalizando los controles radio
	container.find('.ewItemList').addClass('btn-group').attr('data-toggle','buttons');
	setTimeout(function(){
	container.find('.radio-inline,.checkbox-inline').addClass('btn alert-success').find('input:radio,input:checkbox').css({'width':'0px'}).filter(':checked').parent().addClass('active').end().end().end().css({'visibility':'inherit'});
	container.find('.checkbox-inline').on('click', function(event ){ if ($(event.target).is('input:checkbox') || $(event.target).is('input:radio')) $(this).toggleClass('active')});	
		}, 200);
}                       

function coolPostIt(container){
	container.find('.post-it').each(function(){ $(this).find('>*').wrapAll('<div class="w1"><div class="w2"><div class="w3"><div class="w4"><div class="w5"><div class="w6"><div class="w7"><div class="w8"></div></div></div></div></div></div></div></div>'); });
}

function preloadImages(arrayOfImages) {
	return jQuery.map(arrayOfImages, function(n,i){ 
		return $('<img/>').attr('src',n);

		// Alternatively you could use:
		// (new Image()).src = this;

  });
}

function coolTemplate(container){   
	if (typeof container == 'undefined') var container = window.$('body');

//Personalizando botones de opciones
container.find('.btn-primary,.ewAddEdit.ewAdd, .ewGridLink.ewInlineUpdate, .ewAddEdit.ewGridAdd, .ewAction.ewGridSave,.ewAction.ewGridInsert, .ewDetailAdd').removeClass('btn-default').addClass('btn-success').css({'visibility':'inherit'});
//container.find('.ewRowLink.ewEdit,.ewAddEdit.ewGridEdit,.ewAction.ewMultiUpdate,.ewAction.ewEdit').removeClass('btn-default').addClass('btn-info');
container.find('.ewGridLink.ewGridDelete, .ewRowLink.ewDelete, .ewAction.ewMultiDelete, .ewGridLink.ewInlineCancel, .ewAction.ewGridCancel').removeClass('btn-default').addClass('btn-danger');
container.find('.ewAddOptBtn').removeClass('btn-default').addClass('btn-primary');
container.find('.ewShowAll').removeClass('btn-default').addClass('btn-warning');
container.find('.ewExportLink.ewPrint').attr('target','_blank');
coolRadioCheckBtn(container);
return true;
}                   
