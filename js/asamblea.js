var pagina_actual = 0;  
var busqueda = "";  

function iniciar(){
  $(".font_opt_menu").removeClass("opc_seleccionado")
  $("#asamblea").addClass("opc_seleccionado");
  $("#div_cargando").fadeOut(); 
  
  $(".btn_toggle_item").change( function (){
    $("#div_cargando").fadeIn(); 
    registrar_estado_asamblea( $(this).data("id"), $(this).prop('checked') );
  });
  
  //Botones paginacion
  $('.btn_paginacion').click( function (){   
    $("#div_cargando").fadeIn();
    pagina_actual = $(this).data("pagina");
    window.location.href = 'asamblea.php?'+"pagina_actual="+pagina_actual; 
  });
  
  $("#btn_buscar").click( function (){
    var texto = "";  
    if( $.trim($("#buscar_texto").val()) != "" ){
      texto = 'texto='+$("#buscar_texto").val();
    } 
    window.location.href = 'asamblea.php?'+texto+"&estado="+$("#filtro_estado").val();
  });
  
}
function registrar_estado_asamblea( id_asamblea, estado){  
  
  var asamblea_estado = {
      id: id_asamblea,
      vigente: estado
  };      
    
  fetch("services/set_asamblea.php", {
      method: 'POST',
      credentials: 'same-origin',
      body: JSON.stringify(asamblea_estado),
      headers: {
        'Content-Type': 'application/json'
      }
    })
    .then(function (response) {
      return response.json();
    })
    .then(function (response) {      
      if (response.success) {  
        window.location.href = "asamblea.php";
      } else {
        $("#div_cargando").fadeOut();
        mostrar_alerta(response.reason);
      }
    })
    .catch(function (error) {      
      $("#div_cargando").fadeOut();        
      mostrar_alerta("No se pudo actualizar el estado de la asamblea en el servidor");
    });
}