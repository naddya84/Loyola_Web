//Variables para la carga de documentos
var my_drop;
var doc_declaracion = "";
var doc_memory = "";
var eliminar_declaracion = "";
var eliminar_memory = "";

Dropzone.options.myDropzone = {
  paramName: "file", // The name that will be used to transfer the file
  maxFilesize: 20, // MB
  maxFiles: 1,
  acceptedFiles: "image/*",
  forceChunking: true,
  resizeQuality: 1,
  resizeWidth: 1500,
  dictDefaultMessage: "<center><div class='desc_datos left'>Sube la foto del curso</div><img src='img/ico_subir_foto.png' class='left'></center><br>",
  dictFallbackMessage: "Tu navegador no soporta la subida de archivos",
  dictFileTooBig: "El archivo que intentas subir pesa mucho {{filesize}}, límite {{maxFilesize}} ",
  dictInvalidFileType: "Solo se puede subir una imágen",
  dictRemoveFile: "<div class='font_borrar_img'>Borrar</div>",
  addRemoveLinks: true,
  init: function () {
    my_drop = this;
    this.on("success", function (file, response) {
      try{
        response = JSON.parse( response );
        if( response.success ){          
          file_upload = file;
          foto_firma = file.name;
        } else {
          mostrar_alerta("No se pudo subir la firma, guarde los cambios y actualice la pagina: "+response.reason);
        }
      } catch ( error ){
        mostrar_alerta("No se pudo subir la firma, guarde los cambios y actualice la pagina");
      } 
    });       
    this.on("removedfile", function(file) {             
      console.log("Borrar el archivo: "+file.name);
      foto_firma = null;
      delete_foto(file.name);
    });
    
  }
};

function iniciar(){ 
  $(".font_opt_menu").removeClass("opc_seleccionado")
  $("#curso").addClass("opc_seleccionado");
  $("#div_cargando").fadeOut();   
  
  $('.form_datetime').datetimepicker({
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
	forceParse: 0,
    showMeridian: 1,
    pickerPosition: "bottom-left"
  });
  
  $("input#virtual").on('change', this, function(){
    $("#lugar_curso").slideUp();
    if( $("#datos_curso").css("display") == "none" ){
      $("#datos_curso").slideDown();
    } 
  });
  
  $("input#presencial").on('change', this, function(){
    $("#datos_curso").slideUp();
    if( $("#lugar_curso").css("display") == "none" ){
      $("#lugar_curso").slideDown();
    } 
  });
    
  $("#btn_guardar").click( function (){ 
    if( validar_datos() ){
      $("#div_cargando").fadeIn();
      registro_curso();
    }
  });

  $("#btn_cerrar,#btn_cancelar").click(function () {       
    cerrar_alerta();
  }); 
    
} 

function registro_curso(){

  var curso = {
    nombre: $("#nombre_curso").val(),
    duracion: $.trim($("#duracion_curso").val()),
    expositor:$.trim($("#expositor").val()),
    fecha_inicio: $("#fecha_inicio").val(),
    fecha_fin: $("#fecha_fin").val()
  };
    
  if ($("input[name='radio']:checked").val() == 0 ) {
    curso.url = $.trim( $("#url_curso").val());
    curso.codigo = $.trim( $("#codigo").val());
    curso.password = $.trim( $("#password").val());
    curso.tipo = "Virtual";
    }
    
  if ($("input[name='radio']:checked").val() == 1 ) {
    curso.lugar = $.trim( $("#lugar").val());
    curso.tipo = "Presencial";
  }
    
  fetch("services/set_curso.php", {
      method: 'POST',
      credentials: 'same-origin',
      body: JSON.stringify(curso),
      headers: {
        'Content-Type': 'application/json'
      }
    })
    .then(function (response) {
      return response.json();
    })
    .then(function (response) {      
      if (response.success) {        
        window.location.href = "curso.php";          
      } else {
        $("#div_cargando").fadeOut();
        mostrar_alerta(response.reason);
      }
    })
    .catch(function (error) {
      console.error(error);
      $("#div_cargando").fadeOut();
      mostrar_alerta("No se pudo crear el curso");
    });
}

function delete_file(name_file, array) {
  var url = 'services/deletesupload.php';
  var data = {name_delete: name_file};

  fetch(url, {
    method: 'POST', 
    body: JSON.stringify(data),
    headers: {
      'Content-Type': 'application/json'
    }
  }).then(res => res.json())
  .catch(error => console.error('Error:', error))
  .then( function (response) {    

    var index = array.indexOf(name_file);
    if (index > -1) {
      array.splice(index, 1);
    }    

  });
}
//validacion del formulario
function validar_datos() {
  if ($.trim($("#nombre_curso").val()) === '') { 
    mostrar_alerta("Ingresa el titulo del curso");
    return false;
  } 
  if ($.trim($("#duracion_curso").val()) === '') {
    mostrar_alerta("Ingresa la duración del curso, por favor");
    return false;
  }
  if ($.trim($("#expositor").val()) === '') {
    mostrar_alerta("Ingresa el nombre del expositor, por favor");
    return false;
  }
  if ($.trim( $("#fecha_inicio").val()) === ''){
    mostrar_alerta("Debes ingresar la fecha de inicio");
    return false;
  }
  if ($.trim( $("#fecha_fin").val()) === ''){
    mostrar_alerta("Debes ingresar la fecha de de finalización");
    return false;
  }
  if (!$("input[name='radio']:checked").val()) {  
      mostrar_alerta("Seleccione una opción, en tipo de curso");        
      return false;
  } 
  if ($("input[name='radio']:checked").val() == 0 ) {
    if ($.trim($("#url_curso").val()) === '') {
      mostrar_alerta("Ingresa la URL del curso, por favor");
      return false;
    }
    if ($.trim( $("#codigo").val()) === ''){
      mostrar_alerta("Debes ingresar el código");
      return false;
    }
    if ($.trim( $("#codigo").val()).length > 45){
      mostrar_alerta("Solo puede ingresar 45 caracteres en código");
      return false;
    }
    if ($.trim( $("#password").val()) === ''){
      mostrar_alerta("Ingresar la contraseña, por favor");
      return false;
    }
    if ($.trim( $("#password").val()).length > 45){
      mostrar_alerta("Solo puede ingresar 45 caracteres en contraseña");
      return false;
    }
}
if ($("input[name='radio']:checked").val() == 1 ) {
  if ($.trim( $("#lugar").val()) === ''){
      mostrar_alerta("Debe ingresar el lugar o dirección del curso");
      return false;
    }
}
  return true;
}

function mostrar_alerta(mensaje){
  $('#texto_mensaje').html(mensaje);
  $('#mensaje_form').fadeIn('slow');
  $('#fondo_pop').fadeIn();
}

function cerrar_alerta(){
  $('#mensaje_form').fadeOut('slow');
  $("#popup").fadeOut();
  $('.popup-overlay').fadeOut('slow');
  $('.popup-bloqueo').fadeOut();
}
