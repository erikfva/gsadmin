$(document).ready(function(){
	$('#form-busqueda').submit(function() {
		//alert('Handler for .submit() called.');
		buscar($('#texto-busqueda').val());
		return false;
	});	
	$('#btnBuscar').click(function(){$('#form-busqueda').submit();});
	$('#marco-resultado-busqueda').load(function(){
		if($(this).attr("src") != ""){success()};								  
	});
});

		
function success(){
	$('#mensaje-buscando').hide();
    $('#resultado-busqueda').fadeIn();  
}

function buscar(q){
	if(!q){return}
var url = 'busquedalist.php?opciones=hidetitle&psearchtype=AND&psearch=' + q.replace(" ","+");
$('#resultado-busqueda').hide();
$('#mensaje-buscando').show();
$('#marco-resultado-busqueda').attr('src',url);
}