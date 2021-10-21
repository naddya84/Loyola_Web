//Variables para la carga de documentos
var my_drop;
var doc_declaracion = "";
var doc_memory = "";
var eliminar_declaracion = "";
var eliminar_memory = "";

Dropzone.options.myDropzoneD = {
  paramName: "file", // The name that will be used to transfer the file
  maxFilesize: 20, // MB
  maxFiles: 50,
  acceptedFiles: "application/pdf",
  forceChunking: true,
  resizeQuality: 0.2,
  resizeWidth: 1500,
  dictDefaultMessage: "<div class='centrear_elemento'><div class='left margen_sms_dropzone'>Sube tus documentos aquí &nbsp;</div><img src='img/ico_documento.png' class='left'></div><br>",
  dictFallbackMessage: "Tu navegador no soporta la subida de archivos",
  dictFileTooBig: "El archivo que intentas subir pesa mucho {{filesize}}, límite {{maxFilesize}} ",
  dictInvalidFileType: "Solo se pueden subir imágenes",
  dictRemoveFile: "<div class='font_borrar_img'>Borrar</div>",
  addRemoveLinks: true,
  init: function () {
    my_drop = this;
    this.on("success", function (file, response) {      
      try{
        response = JSON.parse( response );
        if( response.success ){          
          file_upload = file;
          doc_memory = file.name ;      
        } else {
          mostrar_alerta("No se pudo subir el documento, guarde los cambios y actualice la pagina: "+response.reason);
        }
      } catch ( error ){
        mostrar_alerta("No se pudo subir el documento, guarde los cambios y actualice la pagina");
      }      
    });

    this.on("removedfile", function(file) {      
      delete_file(file.name, doc_memory);
    });

  }
};

Dropzone.options.myDropzoneDoc = {
  paramName: "file", // The name that will be used to transfer the file
  maxFilesize: 20, // MB
  maxFiles: 50,
  acceptedFiles: "application/pdf",
  forceChunking: true,
  resizeQuality: 0.2,
  resizeWidth: 1500,
  dictDefaultMessage: "<div class='centrear_elemento'><div class='left margen_sms_dropzone'>Sube tus documentos aquí &nbsp;</div> <img src='img/ico_documento.png' class='left'></div><br>",
  dictFallbackMessage: "Tu navegador no soporta la subida de archivos",
  dictFileTooBig: "El archivo que intentas subir pesa mucho {{filesize}}, límite {{maxFilesize}} ",
  dictInvalidFileType: "Solo se pueden subir archivos en pdf",
  dictRemoveFile: "<div class='font_borrar_img'>Borrar</div>",
  addRemoveLinks: true,
  init: function () {
    my_drop = this;
    this.on("success", function (file, response) {      
      try{
        response = JSON.parse( response );
        if( response.success ){          
          file_upload = file;
          doc_declaracion = file.name ;      
        } else {
          mostrar_alerta("No se pudo subir el documento, guarde los cambios y actualice la pagina: "+response.reason);
        }
      } catch ( error ){
        mostrar_alerta("No se pudo subir el documento, guarde los cambios y actualice la pagina");
      }      
    });

    this.on("removedfile", function(file) {      
      delete_file(file.name, doc_declaracion);
    });
  }
};

function iniciar(){
  $(".font_opt_menu").removeClass("opc_seleccionado")
  $("#asamblea").addClass("opc_seleccionado");
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
  
  $("#btn_Eliminar").click(function() {
    $(".btn_confirmacion").fadeIn(); 
    mostrar_alerta("¿Seguro que desea eliminar la asamblea?");
  });
    
  $("#eliminar_memoria").click( function (){
    eliminar_memory = $("#url_memory").val();
    $(".cont_doc_memoria").html("");
  });
  
  $("#eliminar_declaracion").click( function (){
    eliminar_declaracion = $("#url_dec").val();
    $(".cont_doc_declaracion").html("");
  });

  $("#btn_guardar").click( function (){
    if( validar_datos() ){
      $("#div_cargando").fadeIn();
      registrar_asamblea();
    }
  });
  $("#btn_actualizar").click(function() {
    registrar_asamblea();    
  });
     
  $("#btn_confirmar").click(function () {       
    $("#div_cargando").fadeIn();
    eliminar_asamblea();
  });

  $("#btn_cerrar,#btn_cancelar").click(function () {       
    cerrar_alerta();
  }); 
    
} 

function registrar_asamblea(){

  var asamblea = {
    nombre: $("#nombre_asamblea").val(),
    periodo: $("#periodo").val(),
    fecha_asamblea: $("#fecha_asamblea").val(),
    codigo_zoom: $.trim( $("#codigo_zoom").val()),
    password_zoom : $.trim( $("#password_zoom").val())
  };
    
  if( $("#id_asamblea").val() > 0 ){
    asamblea.id = $("#id_asamblea").val();
  }
  
  if( CKEDITOR.instances['doc_jornada'].getData() != "" ){ 
    asamblea.doc_jornada = CKEDITOR.instances['doc_jornada'].getData();
  }
  if( doc_memory != "" ){ 
    asamblea.doc_memory = doc_memory;
  } 
  if( doc_declaracion != "" ){ 
    asamblea.doc_declaracion = doc_declaracion;
  }
  if( eliminar_memory != "" ){ 
    asamblea.eliminar_memory = eliminar_memory; 
  }
  if( eliminar_declaracion != "" ){ 
    asamblea.eliminar_declaracion = eliminar_declaracion;
  }

  
  fetch("services/set_asamblea.php", {
      method: 'POST',
      credentials: 'same-origin',
      body: JSON.stringify(asamblea),
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
      console.error(error);
      $("#div_cargando").fadeOut();
      mostrar_alerta("No se pudo crear la asamblea");
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
  if ($.trim($("#nombre_asamblea").val()) === '') {
    mostrar_alerta("Ingresa el titulo de la asamblea");
    return false;
  } 

  /*if ($.trim($("#periodo").val()) === '') {
    mostrar_alerta("Ingresa el periodo de duración de la asamblea");
    return false;
  }*/
  
  if ($.trim( $("#fecha_asamblea").val()) === ''){
    mostrar_alerta("Debes ingresar la fecha de la asamblea");
    return false;
  }
  if ($.trim( $("#codigo_zoom").val()) === ''){
    mostrar_alerta("Debes ingresar el código del zoom");
    return false;
  }
  if ($.trim( $("#codigo_zoom").val()).length > 45){
    mostrar_alerta("Solo puede ingresar 45 caracteres en código zoom");
    return false;
  }
  if ($.trim( $("#password_zoom").val()) === ''){
    mostrar_alerta("Ingresar la contraseña del zoom, por favor");
    return false;
  }
  if ($.trim( $("#password_zoom").val()).length > 45){
    mostrar_alerta("Solo puede ingresar 45 caracteres en contraseña zoom");
    return false;
  }
  return true;
}

function eliminar_asamblea(){
  var data = {
    id_asamblea : $("#id_asamblea").val()
  };    
 
  fetch('services/del_asamblea.php',  {
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
      window.location.href = "asamblea.php";      
    } else {
      $("#div_cargando").fadeOut();
      mostrar_alerta("No se pudo eliminar la asamblea: "+response.reason);
    }
  })
  .catch( function(error){        
    console.error(error);        
    $("#div_cargando").fadeOut();
    mostrar_alerta("No se pudo acceder a eliminar");
  });
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
