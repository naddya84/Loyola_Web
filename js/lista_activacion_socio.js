var pagina_actual = 0;  
var busqueda = "";  

function iniciar(){
  $(".font_opt_menu").removeClass("opc_seleccionado")
  $("#act_socio").addClass("opc_seleccionado");
  $("#div_cargando").fadeOut();   
  //Botones paginacion
  $('.btn_paginacion').click( function (){   
    $("#div_cargando").fadeIn();
    pagina_actual = $(this).data("pagina");
    window.location.href = 'lista_activacion_socio.php?'+"pagina_actual="+pagina_actual; 
  });
  
  $("#filtro_estado").change(function() {
    $("#div_cargando").fadeIn();
    window.location.href = 'lista_activacion_socio.php?'+"estado="+$("#filtro_estado").val();
  });
  
  $("#btn_buscar").click( function (){
    var texto = "";  
    if( $.trim($("#buscar_texto").val()) != "" ){
      texto = 'texto='+$("#buscar_texto").val();
    } 
    window.location.href = 'lista_activacion_socio.php?'+texto+"&estado="+$("#filtro_estado").val();
  });
  
}