var user;

function ajustar_fondo() {  
  var ancho_pantalla = $(window).width();
  var ancho_pop = $(".contenedor").width();

  var margen = (ancho_pantalla - ancho_pop) / 2;
  if (margen >= 0) {
    var porcentaje = Math.ceil((margen / ancho_pantalla) * 100);
    $(".contenedor").css("left", porcentaje + "%");
  } else {
    $(".contenedor").css("left", "0%");
  }
}

function iniciar(){  
  $("#div_cargando").fadeOut();
  
  ajustar_fondo();
  $(window).resize(ajustar_fondo);         
    
  $("#btn_ingresar").click(function(){    
    ingresar_sistema();
  });
  
  $('#btn_cerrar').click(function () {       
    $('#mensaje_form').fadeOut();
    $('#fondo_pop').fadeOut();
  });
}

//validacion del formulario
function validar_datos() {

    if ($.trim($("#usuario").val()) === '') {
        $('#texto_mensaje').html("Ingresa el usuario, por favor");
        $('#mensaje_form').fadeIn('slow');
        $('#fondo_pop').fadeIn();
        $("#div_cargando").fadeOut();
        return false;
    }
    if ($.trim($("#clave").val()) === '') {
        $('#texto_mensaje').html("Ingresa tu clave, por favor");
        $('#mensaje_form').fadeIn('slow');
        $('#fondo_pop').fadeIn();
        $("#div_cargando").fadeOut();
        return false;
    }
   
    return true;
}

function ingresar_sistema(){      
  if (validar_datos()) {
    var data = {
      usuario: $("#usuario").val(),
      clave: $("#clave").val()
    } 
    
    fetch('services/get_login.php',  {
      method: 'POST',
      credentials: 'same-origin',
      body: JSON.stringify(data), // data can be `string` or {object}!
      headers:{
        'Content-Type': 'application/json'
      }
    })
    .then(function(response) {      
      return response.json();
    })
    .then(function(response) {            
      if( response.success ){
        window.location.href = "home.php"        
      } else {               
        $("#div_cargando").fadeOut();
        if( response.reason ){
          mostrar_alerta( response.reason );
        } else {
          mostrar_alerta("Los datos ingresados son incorrectos, vuelve a intentar por favor");
        }
      }
    })
    .catch( function(error){      
      console.error(error);
      $("#div_cargando").fadeOut();
      mostrar_alerta("Ocurrio un problema con el servidor, intentelo nuevamente por favor");      
    });
   }
}
function mostrar_alerta(mensaje){
  $('#texto_mensaje').html(mensaje);
  $('#mensaje_form').fadeIn('slow');
  $('#fondo_pop').fadeIn();
}