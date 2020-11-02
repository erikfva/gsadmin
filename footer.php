<?php if (@$gsExport == "") { ?>
<?php if (@!$gbSkipHeaderFooter) { ?>
				<!-- right column (end) -->
				<?php if (isset($gTimer)) $gTimer->Stop() ?>
			</div>
		</div>
	</div>
	<!-- content (end) -->
	<!-- footer (begin) --><!-- ** Note: Only licensed users are allowed to remove or change the following copyright statement. ** -->
	<div id="ewFooterRow" class="ewFooterRow">	
		<div class="ewFooterText"><?php echo $Language->ProjectPhrase("FooterText") ?></div>
		<!-- Place other links, for example, disclaimer, here -->		
	</div>
	<!-- footer (end) -->	
</div>
<?php } ?>
<!-- search dialog -->
<div id="ewSearchDialog" class="modal" role="dialog" aria-labelledby="ewSearchDialogTitle" aria-hidden="true"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="ewSearchDialogTitle"></h4></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton"><?php echo $Language->Phrase("Search") ?></button><button type="button" class="btn btn-default ewButton" data-dismiss="modal"><?php echo $Language->Phrase("CancelBtn") ?></button></div></div></div></div>
<!-- add option dialog -->
<div id="ewAddOptDialog" class="modal" role="dialog" aria-labelledby="ewAddOptDialogTitle" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="ewAddOptDialogTitle"></h4></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton"><?php echo $Language->Phrase("AddBtn") ?></button><button type="button" class="btn btn-default ewButton" data-dismiss="modal"><?php echo $Language->Phrase("CancelBtn") ?></button></div></div></div></div>
<!-- message box -->
<div id="ewMsgBox" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton" data-dismiss="modal"><?php echo $Language->Phrase("MessageOK") ?></button></div></div></div></div>
<!-- prompt -->
<div id="ewPrompt" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton"><?php echo $Language->Phrase("MessageOK") ?></button><button type="button" class="btn btn-default ewButton" data-dismiss="modal"><?php echo $Language->Phrase("CancelBtn") ?></button></div></div></div></div>
<!-- session timer -->
<div id="ewTimer" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton" data-dismiss="modal"><?php echo $Language->Phrase("MessageOK") ?></button></div></div></div></div>
<!-- tooltip -->
<div id="ewTooltip"></div>
<?php } ?>
<?php if (@$gsExport == "") { ?>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/userevt12.js"></script>
<script type="text/javascript">

// Write your global startup script here 
function splashLoadingOff(){
	if(top) top.$('.pageload-overlay').fadeOut();
	 $('.pageload-overlay').fadeOut();        
}
jQuery(window).on('load', function(){
	splashLoadingOff();
	if(top.jQuery.fn.block) top.jQuery.unblockUI();    
});                
coolTemplate();

//Personalizando los controles despues de adionar nueva opcion.
ew_RenderOptPHPMaker = ew_RenderOpt ; 
ew_RenderOpt = function(obj) {
	var $ = jQuery, id = ew_GetId(obj), f = ew_GetForm(obj), $p = $(obj).parent().parent().find("#dsl_" + id); // Parent element
	if (!$p[0] || !$p.data("options")) return;
	var $t = $p.parent().find("#tp_" + id);
	if (!$t[0]) return;
	ew_RenderOptPHPMaker(obj);
	coolRadioCheckBtn($p);
	resizeIFRM(200);
}
ew_AddOptSuccessPHPMaker = ew_AddOptSuccess;
ew_AddOptSuccess = function (o) {
	$dlg = ewAddOptDialog;
	var frm = ewForms($dlg.data("args").lnk); // ew_Form object
	var form = frm.Form; // HTML form object    
	var el = $dlg.data("args").el; // HTML element
	var obj = ew_GetElements(el, form);
	var idGroupBtn = 'dsl_' + ew_GetId(obj); // Container element
	ew_AddOptSuccessPHPMaker(o);

	//Personalizando los checkbox
	var GroupBtn = $('#' + idGroupBtn);
	var typeBtn = GroupBtn.has('input:checkbox').length?'checkbox':'radio';
	coolRadioCheckBtn(GroupBtn, 1000, typeBtn );       
}
<?php if(CurrentPage()->PageID == 'changepwd'){ ?>
	$('#fchangepwd').attr('target','_top');
<?php } ?>  

//********
//*Realizando el mejor ajuste del ancho del iframe basado en su contenido y el ancho del navegador
//********
function doResizeIFRM() {
	resizeIFRM(250);
};
if(window.frameElement){
	$(window.frameElement).css({'width':'98%'});

	//Ajustando el contenido de iframe al cambiar de tamanio la pantalla principal
		top.$(top).bind('resize', function () {
				if(top && top.resizeTimer) top.clearTimeout(top.resizeTimer);
				if(top) top.resizeTimer = top.setTimeout(function(){if(window.frameElement) $(window.frameElement).css({'width':'98%'}); resizeIFRM();}, 500);
		});
}
jQuery(window).ready(function(){
	doResizeIFRM();	
});
$('.collapse').on('shown.bs.collapse hidden.bs.collapse', function(){
	resizeIFRM();
});
$('.ewBreadcrumbs li, .ewListOptionBody .btn').on('click',function(){ $('.pageload-overlay').show(); });
PHPMaker_ew_OnError =  ew_OnError;
ew_OnError = function (frm, el, msg) {	
	setTimeout(function(){ splashLoadingOff(); }, 200);
	PHPMaker_ew_OnError(frm, el, msg);
}

//*******
</script> 
<?php
	foreach ($JSLibs as $JSLibname) {
	   addJSLib($JSLibname,"footer");
	}
?>
<script type="text/javascript" > 
</script>
<?php } ?>
</body>
</html>
