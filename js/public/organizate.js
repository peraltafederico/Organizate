//chequar el documento
jQuery(document).ready(function($){

  var url_wordpress = document.location.protocol + '//' +
  document.location.host + '/wordpress/';

  var wpajax_url = document.location.protocol + '//' +
  document.location.host + '/wordpress/wp-admin/admin-ajax.php';

  var guardar_clientea = wpajax_url + '?action=org_guardar_datos';
  var inscripcion_clientela = wpajax_url + '?action=org_inscribir_clientes';
  var opciones_clientela = wpajax_url + '?action=org_sel_opciones_clientela';
  var edicion_clientes = wpajax_url + '?action=org_update_clientes';


  $('form#org_form_id').bind('submit', function(){

    //obtenemos el objeto form jquery
    $form = $(this);
    //configurar el formulario para nuestro post ajax
    var form_data = $form.serialize();

    // enviar la data del formulario con ajax
    $.ajax({
      'method' : 'post',
      'url' : guardar_clientea,
      'data' : form_data,
      'dataType': "json",
      'cache' : false,
      'success' : function ( data, textStatus ) {

        if(data.estado == 1 ) {

          $form[0].reset();
          alert(data.mensaje);
        } else {

          var msj = data.mensaje + '\r' + data.error + '\r';
          $.each(data.errores ,function(key, value){

            msj += '\r';
            msj += '- ' + value;
          });

          alert(msj);
        }
    },
    'error' : function ( jqXHR, textStatus, errorThrown) {

    }

    });
      return false;

  });

  $('form#org_eliminar_usuario_id').bind('submit', function(){

    //obtenemos el objeto form jquery
    $form = $(this);
    //configurar el formulario para nuestro post ajax
    var form_data = $form.serialize();

    // enviar la data del formulario con ajax
    $.ajax({
      'method' : 'post',
      'url' : opciones_clientela,
      'data' : form_data,
      'dataType': "json",
      'cache' : false,
      'success' : function ( data, textStatus ) {

        switch(data.estado) {

          case 0:
            alert(data.mensaje);
            console.log("prueba");
            location.reload();
            break;
          case 1:
            $form[0].reset();
            alert(data.mensaje);
            location.reload();
            break;
          case 2:
            $('#id_unico').val(data.id);
            $('#org_editar_clientes').submit();
        }

    },

    'error' : function ( jqXHR, textStatus, errorThrown) {

    }

    });
      return false;

  });

  $('form#update_clientes').bind('submit', function(){

    $form = $(this);

    var form_data = $form.serialize();

    $.ajax({
      'method' : 'post',
      'url' : edicion_clientes,
      'data' : form_data,
      'dataType' : "json",
      'cache' : false,
      'success' : function ( data, textStatus ) {

        if(data.estado == 1){
          alert(data.mensaje);
        }
        else{
          alert(data.mensaje);
        }

      },

      'error' : function ( jqXHR, textStatus, errorThrown) {

      }

    });

    return false;
  });


  $('form#org_form_insc_clientes').bind('submit', function(){

    //obtenemos el objeto form jquery
    $form = $(this);
    //configurar el formulario para nuestro post ajax
    var form_data = $form.serialize();

    // enviar la data del formulario con ajax
    $.ajax({
      'method' : 'post',
      'url' : inscripcion_clientela,
      'data' : form_data,
      'dataType': "json",
      'cache' : false,
      'success' : function ( data, textStatus ) {


        if(data.estado == 1) {

          $form[0].reset();
          alert("Tu cliente ha sido creado correctamente");
          location.reload();
        } else {
          alert("Ha habido un error");
        //  location.reload();
        }

    },

    'error' : function ( jqXHR, textStatus, errorThrown) {

    }

    });
      return false;

  });

 var clientes_total = $('#cant_clientes').val();

  $('#check_all').on('click', function(){

    if( $('#check_all').is(':checked')) {

      for(x=0; x<=clientes_total ; x++){

        $("#"+x).prop('checked', true);
      }

    }

    else{

      for(x=0; x<=clientes_total ; x++){

        $("#"+x).prop('checked', false);
      }

    }

  });


  $('[name="clientes[]"]').on('click', function(){

    if( $('#check_all').is(':checked')) {

        $("#check_all").prop('checked', false);
  }

  });

  $('#editar_usuario').on('click', function(e){

    e.preventDefault();
    $('#form_editar_usuario').submit();
  });

});
