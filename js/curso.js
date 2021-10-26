var pagina_actual = 0;  
var busqueda = "";  

function iniciar(){
  $(".font_opt_menu").removeClass("opc_seleccionado")
  $("#curso").addClass("opc_seleccionado");
  $("#div_cargando").fadeOut(); 
  
    
  //Botones paginacion
  $('.btn_paginacion').click( function (){   
    $("#div_cargando").fadeIn();
    pagina_actual = $(this).data("pagina");
    window.location.href = 'curso.php?'+"pagina_actual="+pagina_actual; 
  });
  
  $("#btn_buscar").click( function (){
    var texto = "";  
    if( $.trim($("#buscar_texto").val()) != "" ){
      texto = 'texto='+$("#buscar_texto").val();
    } 
    window.location.href = 'curso.php?'+texto+"&estado="+$("#filtro_estado").val();
  });
  
}
