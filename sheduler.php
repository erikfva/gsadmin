
	function cargarCitas(panel){
		if(!panel.is('.sheduler')){ //inicializando el calendario, convirtiendo a sheduler
<?php
				$htmlSheduler = '
<div id="sheduler" class="metro-pivot">
	<div class="pivot-item" style="padding:0px">
		<h3><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span><span style="display:none">Uno</span></h3>
		<div class="page-calendar"></div>
	</div>
	<div class="pivot-item" style="padding:0px">
		<h3><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span><span style="display:none">Dos</span></h3>
		<div id="cal-day-panel-hour" style="max-height:400px;overflow-y: auto">
			<table class="table">';
	for($min=0;$min<780;$min=$min+30){
		$hour = ( ($min/30)%2==0?($min/60)+1:($min)/60+0.5 ) + 6;
		$minute = (($min/30)%2==0?"00":"30") ;
		$htmlSheduler .= '
				<tr>
					<td class="" style="width:50px"><button data-hour="'.$hour.'" data-minute="'.$minute.'" type="button" class="btn-time btn btn-primary btn-xs">'.$hour.':'. $minute.'</button></td>
					<td class="day-info small" data-hour="'.$hour.'" data-minute="'.$minute.'" ></td>				
				</tr>';
	}
	$htmlSheduler .= '
			</table>
		</div>
	</div>	
</div>';
?>
			panel.children().wrapAll('<div class="page-calendar-content"/>');
			panel.prepend('<?php echo ew_JsEncode(ew_RemoveCrLf($htmlSheduler));?>').find('.page-calendar-content').appendTo('#sheduler .page-calendar');
			var defaults = {
				animationDuration: 250,
				headerOpacity: 0.25,
				fixedHeaders: false,
				headerSelector: function (item) { return item.children("h3").first(); },
				itemSelector: function (item) { return item.children(".pivot-item"); },
				headerItemTemplate: function () { return $("<span class='header'>"); },
				pivotItemTemplate: function () { return $("<div class='pivotItem'>"); },
				itemsTemplate: function () { return $("<div class='items'>"); },
				headersTemplate: function () { return $("<div style='position:relative' class='headers'>"); },
				controlInitialized: function(){
					this.data('metro-pivot',this);
				},
				beforeItemChanged: function(index){
				},
				selectedItemChanged: function(index){
					if(this.items != undefined){
						if(index==1){ //haciendo scroll hasta la primera cita
							var shedulerday = this.items.find('#cal-day-panel-hour');
							if(shedulerday.find('.alert:first').length)
								shedulerday.animate({
									scrollTop: (shedulerday.scrollTop() + shedulerday.find('.alert:first').position().top )
								},500);
						}
					}
				}
			};
			panel.find('div.metro-pivot').metroPivot(defaults);
			panel.find('.btn-day, .mes .btn-date').css('font-weight','bold').append('<sub class="invisible label label-warning" style="position: absolute;padding: 2px; font-size:100%">00</sub>')
			.filter('.btn-day').click(function(){
				var shedulerday = $('#sheduler').find('#cal-day-panel-hour');

				//limpiando el contenido
				shedulerday.find('.day-info').empty();
				<?php
					$SQL = "SELECT 
 HOUR(cita_medica.fecha) as hora,
 MINUTE(cita_medica.fecha) as minutos, 
 (SELECT GROUP_CONCAT(motivo_cita.motivo) AS FIELD_1 FROM motivo_cita WHERE FIND_IN_SET(motivo_cita.idmotivo, cita_medica.idmotivo)) AS motivo,
  cita_medica.notas,
  consulta_paciente_edad.nombre_completo,
  consulta_paciente_edad.telefono,
  consulta_paciente_edad.email 
FROM
  cita_medica INNER JOIN consulta_paciente_edad ON (cita_medica.numeroexpediente = consulta_paciente_edad.numeroexpediente)  
WHERE YEAR(cita_medica.fecha) = {query_value} 
and month(cita_medica.fecha) = {query_value_1} 
and DAY(cita_medica.fecha) = {query_value_2}
order by  HOUR(cita_medica.fecha),MINUTE(cita_medica.fecha)"; 
				?>
				if(!$(this).find('sub.invisible').length)
				ew_Ajax('<?php echo ew_Encrypt($SQL); ?>', {'q':String(panel.find('input[id*=yearedit]').val()),'q1':panel.find('.mes .btn-date-selected').attr('value') ,'q2':$(this).attr('value') } , function(data){					
					$.each(data,function(i,row){
						shedulerday.find('td[data-hour="' + row[0] + '"]').filter('[data-minute="' + (parseInt(row[1]) < 30? '00' : '30') + '"]')
						.append('<div class=" alert alert-warning" style="display:inline-block"><span class="label label-warning pull-left">' + xPad(row[0],0) + ':' + xPad(row[1],0) + '</span>&nbsp;' + row[2] + (row[3]!=''?'<br>'+row[3]:'') + '<br><em class="pull-right text-right"><b>' + row[4] + (row[5]!=''?'&nbsp;'+row[5]:'') + (row[6]!=''?'<br>'+row[6]:'') + '</b></em></div>');  
					});
				});			
				$('#sheduler').data('metro-pivot').goToItemByName('Dos');
			}).find('sub').css({'font-size':'70%'}).toggleClass('label-warning badge').end()
			.end().filter(':not(.btn-day)').click(function(){ 
				panel.find('.btn-day sub').addClass('invisible');
				if(!$(this).find('sub.invisible').length){
					$.each($(this).data('citaMes'),function(i,row){
						panel.find('.btn-day:eq(' + (row[1]-1) + ')>sub').text(row[2]).removeClass('invisible')
					});
				}
			});
			panel.find('.btn-time').click(function(){ //al seleccionar un horario
				panel.find('[data-time-component]').filter('.timepicker-hour').text(xPad($(this).data('hour'),0)).end()
				.filter('.timepicker-minute').text(xPad(parseInt($(this).data('minute')),0));
				panel.data('edit-datetime').val(getDate(panel) ).data('btn-calendar').trigger('click');
			});
			panel.addClass('sheduler'); //Identificandolo como sheduler ya inicializado
		}else{
			panel.find('sub').addClass('invisible');
		}
		<?php $SQL = "
	  SELECT
		month(cita_medica.fecha) AS mes,
		DAY(cita_medica.fecha) as dia,
		COUNT(*) AS cantidad
	  FROM
		cita_medica
	  where year(cita_medica.fecha) = {query_value} and cita_medica.idmedico = ".CurrentUserInfo("idmedico")."
	  GROUP BY
		day(cita_medica.fecha)
	  order by month(cita_medica.fecha),  DAY(cita_medica.fecha) ";
  	?> 
		ew_Ajax('<?php echo ew_Encrypt($SQL); ?>', String(panel.find('input[id*=yearedit]').val()) , function(data){ 
			var totalmes = 0, citaMes = [], mes = 0;
			$.each(data,function(i, row){
				if(row[0] != mes){
					if(mes > 0){
						panel.find('.mes .btn-date:eq(' + (mes-1) + ')>sub').text(totalmes).removeClass('invisible').parent().data('citaMes',citaMes);
					}
					mes = row[0]; citaMes=[]; totalmes=0;
				}
				totalmes+= parseInt(row[2]);
				citaMes.push(row);
			});
			if(mes > 0){
				panel.find('.mes .btn-date:eq(' + (mes-1) + ')>sub').text(totalmes).removeClass('invisible').parent().data('citaMes',citaMes);
			}
			panel.find('.mes .btn-date-selected').trigger('click');
		});		
	}
