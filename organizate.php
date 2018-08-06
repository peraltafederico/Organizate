<?php
/*
Plugin Name: Organiza tus clientes
Plugin URI: http://www.facebook.com/peralta.federico.manuel
Description: Esta plugin va a ayudarte a organizar tus clientes a traves de tablas.
Version: 1.0
Author: Peralta Federico Manuel
Author URI: http://www.facebook.com/peralta.federico.manuel
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: organiza-tus-clientes
*/
$test= "git";
add_action('init', 'start_session', 1);
add_action('init', 'org_shortcode');
add_action('wp_ajax_nopriv_org_guardar_datos', 'org_guardar_datos'); //manejar ajax
add_action('wp_ajax_org_guardar_datos', 'org_guardar_datos'); //manejar ajax
add_action('wp_ajax_nopriv_org_inscribir_clientes', 'org_inscribir_clientes');
add_action('wp_ajax_org_inscribir_clientes', 'org_inscribir_clientes');
add_action('wp_ajax_org_eliminar_clientes', 'org_eliminar_clientes');
add_action('wp_ajax_nopriv_org_eliminar_clientes', 'org_eliminar_clientes');
add_action('wp_ajax_org_update_clientes', 'org_update_clientes');
add_action('wp_ajax_nopriv_org_update_clientes', 'org_update_clientes');
add_action('wp_ajax_org_sel_opciones_clientela', 'org_sel_opciones_clientela');
add_action('wp_ajax_nopriv_org_sel_opciones_clientela', 'org_sel_opciones_clientela');
add_action('wp_ajax_retornarnombres', 'retornarnombres');
add_action('wp_ajax_nopriv_retornarnombres', 'retornarnombres');
add_action('wp_ajax_org_log_out', 'org_log_out');
add_action('wp_ajax_nopriv_org_log_out', 'org_log_out');
add_action('wp_ajax_org_saber_url', 'org_saber_url');
add_action('wp_ajax_nopriv_saber_url', 'org_saber_url');

add_action('wp_logout', 'end_session');
add_action('wp_login', 'end_session');
add_action('end_session_action', 'end_session');

add_filter('manage_edit-org_usuarios_columns', 'org_usuarios_headers');
add_filter('manage_org_usuarios_posts_custom_column', 'org_usuarios_columnas',1,2);
add_action('admin_head-edit.php', 'org_register_custom_admin_titles');

register_activation_hook(__FILE__,'org_tabla_al_inicio');

function start_session() {
if(!session_id()){
session_start();}
}

function end_session() {
session_destroy ();
}

//adheriendo una una funcion para lugar acceder a add_filter
//para cambiar el titulo de la tabla del plugin del admin panel

function org_register_custom_admin_titles() {
  add_filter('the_title', 'org_custom_admin_titles', 99, 2);
}

//adheriendo shortcodes
function org_shortcode() {
  add_shortcode('org_formulario', 'org_formulario_shortcode');
  add_shortcode('org_logueo_form', 'org_logueo_shortcode');
  add_shortcode('org_clientela', 'org_clientela_shortcode');
  add_shortcode('org_edicion', 'org_edicion_shortcode');
  add_shortcode('org_edicion_usuario', 'org_edicion_usuario_shortcode');
}

function org_public_scripts() {
  wp_register_script('organizate-js',
                  plugins_url('/js/public/organizate.js?v='.time(), __FILE__),
                  array('jquery'),'', true);
  wp_enqueue_script('organizate-js');

  wp_enqueue_style('organizate-css', plugin_dir_url( __FILE__ ) . '/css/public/organizate.css?v='.time() );
}

add_action('wp_enqueue_scripts' , 'org_public_scripts');

function org_usuarios_headers( $columns ) {

  $columns = array(
    'cb' => '<input type="checkbox" />',
    'title' => __('Nombre del usuario'),
    'email' => __('Mail del usuario'),
    'password' => __('Contraseña'),
  );

  //retornar cada columna (cb, titulo y email)
  return $columns;
}

//configurando las columnas del admin panel del plugin
function org_usuarios_columnas( $column, $post_id) {

  //retonar el texto
  $output = '';

  switch ( $column ) {

    case 'email':
      // obtener el mail desde el formulario completando
      $email = get_field('org_email', $post_id );
      $output .= $email;
      break;

    case 'password':
      // obtener el mail desde el formulario completando
      $pass = get_field('org_password', $post_id );
      $output .= $pass;
      break;
  }

  //echo texto
  echo $output;
}

//configurando el nombre del usuario en el admin panel del plugin
function org_custom_admin_titles( $title, $post_id) {

  global $post;

  $output = $title;

  if ( isset($post->post_type) ) :

    switch ($post->post_type) {
      case 'org_usuarios':
        $fname = get_field('org_nombre', $post_id);
        $output = $fname;
        break;
    }
  endif;

  return $output;
}

//------------------------------//

// FORMULARIOS PRINCIPALES

function org_formulario_shortcode($args, $content) {

  $output = '

  <div class="slb">

    <form id="org_form_id" name="org_form_name" class="slb-form" method="post"
    action="/wordpress/wp-admin/admin-ajax.php?action=org_guardar_datos">


      <p>
      <label class="sorete">Nombre completo</label></br>
      <input type="text" name="org_nombre" placeholder="Tu nombre" />
      </p>

      <p>
      <label>Email</label><br />
      <input type="text" name="org_email" placeholder="tu@email.com" />
      </p>

      <p>
      <label>Contraseña</label><br />
      <input type="password" name="org_password" placeholder="password" />
      </p>

      <p class="slb-input-container">
      <input type="submit" name="org_submit" value="Inscribite" />
      </p>

    </form>

    </div>';

  return $output;
}

function org_logueo_shortcode($args, $content) {

  $mi_url = org_saber_url();

  $output = '

  <div class="slb">

    <form id="org_form_login" name="org_form_login_name" class="slb-form" method="post"
    action="'. $mi_url .'/clientela/">


      <p>
      <label>Email</label><br />
      <input type="text" name="org_email_login" placeholder="tu@email.com" />
      </p>

      <p>
      <label>Contraseña</label><br />
      <input type="password" name="org_password_login" placeholder="contraseña" />
      </p>

      <p class="slb-input-container">
      <input type="submit" name="org_submit" value="Ingresa!" />
      </p>

    </form>

    </div>';

  return $output;

}

function org_edicion_shortcode(){

  $mi_url = org_saber_url();

  global $wpdb;
  $id_clientes = $_POST['uid'];
  $separar_id = explode(',', $id_clientes);
  $tabla_clientes = $wpdb->prefix . "org_clientes";
  $output = '<form id="update_clientes" name="update_clientes_nombre" method="post"  action="/wordpress/wp-admin/admin-ajax.php?action=org_update_clientes">';
  $x = 0;
  foreach($separar_id as $id_unico){

    $x = $x + 1;
    $datos_cliente = $wpdb->get_results("SELECT * from $tabla_clientes WHERE id = $id_unico");

    $output .= '<p>
                <strong>Nombre</strong>
                <input type="text" name="nombre'. $x .'" placeholder="'. $datos_cliente[0]->nombre .'" value="'. $datos_cliente[0]->nombre .'"></input>
                <strong class="negrita">Partido</strong>
                <input type="text" name="partido'. $x .'" placeholder="'. $datos_cliente[0]->partido.'" value="'. $datos_cliente[0]->partido .'"></input>
                <strong class="negrita">Localidad</strong>
                <input type="text" name="localidad'. $x .'" placeholder="'. $datos_cliente[0]->localidad.'" value="'. $datos_cliente[0]->localidad .'"></input>
                <strong class="negrita">Dirección</strong>
                <input type="text" name="direccion'. $x .'" placeholder="'. $datos_cliente[0]->direccion.'" value="'. $datos_cliente[0]->direccion .'"></input>
                </p>
                <input type="hidden" name="id_unico'.$x .'" value="'. $id_unico .'"></input>
                ';
  }

  $output .= '<input type="hidden" value="'. $x .'" name="cant_clientes"></input>
              <p><input type="submit" value="Actualizar datos"></input></p></form>

              <div>
                <a href="'. $mi_url .'/clientela">Volver</a>
              </div>';

  return $output;
}

function org_clientela_shortcode($args, $content) {

  global $wpdb;

  $tabla_empleadores = $wpdb->prefix . "org_empleadores";
  $tabla_clientes = $wpdb->prefix . "org_clientes";

  if(isset($_POST['org_email_login'])){

    $email = $_POST['org_email_login'];
    $pass = $_POST['org_password_login'];

    if($_SESSION['email'] !== $email || $_SESSION['pass'] !== $pass){
      $_SESSION['email'] = "";
      $_SESSION['pass'] = "";
    }
  }


  if(org_chequeo_usuarios($email, $pass) || org_chequeo_usuarios($_SESSION['email'], $_SESSION['pass'])){

  if(org_chequeo_usuarios($email, $pass)){
    $_SESSION['email'] = $email;
    $_SESSION['pass'] = $pass;
  } elseif(org_chequeo_usuarios($_SESSION['email'], $_SESSION['pass'])){
      $email = $_SESSION['email'];
      $pass = $_SESSION['pass'];
  }



  $uid = $wpdb->get_var(
           $wpdb->prepare("SELECT id from $tabla_empleadores WHERE nombre = %s
           ",
           $email
           )
         );

  $nombres_clientes_db = $wpdb->get_results("SELECT * from $tabla_clientes WHERE uid = $uid");


    $nombresdeclientes =  retornarnombres($uid, $nombres_clientes_db, $email, $pass);
    $output = '

    <div>'. $nombresdeclientes .'</div>

    <div class="slb">

      <form id="org_form_insc_clientes" name="org_form_insc_clientes_nombre" class="slb-form" method="post"
      action="/wordpress/wp-admin/admin-ajax.php?action=org_inscribir_clientes">

        <input type="hidden" name="org_email_cliente" value="'. $email .'"/>

        <p>
        <label>Nombre</label><br />
        <input type="text" name="org_nombre_cliente" placeholder="Nombre del cliente" />
        </p>

        <p>
        <label>Partido</label><br />
        <input type="text" name="org_partido_cliente" placeholder="Nombre de la zona" />
        </p>

        <p>
        <label>Localidad</label><br />
        <input type="text" name="org_localidad_cliente" placeholder="Nombre de la zona" />
        </p>

        <p>
        <label>Direccion</label><br />
        <input type="text" name="org_direccion_cliente" placeholder="Nombre de la dirección" />
        </p>

        <p class="slb-input-container">
        <input type="submit" name="org_submit" value="Agrega tu cliente!" />
        </p>

      </form>

      </div>

      <div>
        <a href="/wordpress/wp-admin/admin-ajax.php?action=org_log_out">Log out</a>
      </div>
      <div>
        <a href="/wordpress/wp-admin/admin-ajax.php?action=org_saber_url">Editar usuario</a>
      </div>';
}
  else{

    $output = 'Usuario y/o contraseña incorrecto/s';
}

  return $output;


}
function org_edicion_usuario_shortcode(){
  echo "enzo puto";
}



//------------------------------//

// ACCIONES

//chequeamos por errorees y guardamos los datos del usuario
function org_guardar_datos () {

  $resultados = array(
    'estado' => 0,
    'mensaje' => 'No se pudo inscribir',
    'error' => '',
    'errores' => array()
  );

  try {

  $datos = array(
    'nombre' => esc_attr($_POST['org_nombre']),
    'email' => esc_attr($_POST['org_email']),
    'password' => esc_attr($_POST['org_password'])
  );

  $errores = array();
  $comparar_email = "org_email";
  $chequear = org_obtener_id($datos['email'], $comparar_email);

  if (!strlen($datos['nombre']))
    $errores['nombre'] = "El nombre es requerido";
  if (!strlen($datos['email']))
    $errores['email'] = "El email es requerido";
  if (strlen($datos['nombre']) && !is_email($datos['email']))
    $errores['email'] = "El email debe ser valido";
  if($chequear)
    $errores['mismoemail'] = "El email ya existe";

  if(count($errores)):

    $resultados['error'] = "Se encontraron errores en el formulario";
    $resultados['errores'] = $errores;

  else:

    $resultados['mensaje'] = "Inscripcion correcta";
    $resultados['estado'] = 1;
    org_inscribir_usuario($datos);
    org_registro_tabla($datos['email']);

  endif;
  }

  catch ( Exception $e ) {


  }

  org_json($resultados); #funcion mas abajo
}

//inscribimos al usuario
function org_inscribir_usuario($datos) {

  $id_usuario = 0;

  try {

    $comparar_email = "org_email";
    $id_usuario = org_obtener_id($datos['email'], $comparar_email);

    if(!$id_usuario):
      $id_usuario = wp_insert_post(
        array(
          'post_type' => 'org_usuarios',
          'post_title' =>  $datos['nombre'],
          'post_status' => 'publish',
        ),
        true
      );
      update_field(org_unico_codigo_acf('org_nombre'), $datos['nombre'], $id_usuario);
      update_field(org_unico_codigo_acf('org_email'), $datos['email'], $id_usuario);
      update_field(org_unico_codigo_acf('org_password'), $datos['password'], $id_usuario);

    endif;


  }
  catch (Exception $e) {

  }

  return $id_usuario;
}

function org_crear_tablas() {

  global $wpdb;

  $resultados = false;

  try {

    $tabla_empleadores = $wpdb->prefix . "org_empleadores";
    $tabla_clientes = $wpdb->prefix . "org_clientes";
    $charset_collate = $wpdb->get_charset_collate();

    $tabla = array();

    $tabla[] = "CREATE TABLE  $tabla_empleadores (
      id int(11) NOT NULL AUTO_INCREMENT,
      nombre varchar(180),
      UNIQUE KEY id (id)
    ) $charset_collate;";

    $tabla[] = "CREATE TABLE $tabla_clientes (
      id int(11) NOT NULL AUTO_INCREMENT,
      nombre varchar(80),
      partido varchar(80),
      localidad varchar(80),
      direccion varchar(80),
      uid int(11),
      UNIQUE KEY id (id)
    ) $charset_collate;";

    foreach ($tabla as $sql) {

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

      dbDelta($sql);
    }



  $resultados = true;

  }

  catch (Exception $e) {


  }

  return $resultados;
}

function org_tabla_al_inicio() {

  org_crear_tablas();

}

function org_registro_tabla($email) {

  global $wpdb;

  $resultados = false;

  try {

    $nombre_tabla = $wpdb->prefix . "org_empleadores";

    $wpdb->insert(
      $nombre_tabla,
      array(
        'nombre' => $email,
      ),
      array(
        '%s',
      )
    );

  $resultados = true;

  } catch ( Exception $e ) {

  }

  return $resultados;
}

function org_sel_opciones_clientela() {

  $data = array(
    "estado" => 0,
    "mensaje" => "Error"
  );

  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $accion_eliminar = $_POST['acciontablaclientes'] == "eliminar";
  $accion_editar = $_POST['acciontablaclientes'] == "editar";
  $tabla_clientes = $wpdb->prefix . "org_clientes";
  $uid = $_POST['uid'];

  $id_array = $_POST['clientes'];

  if( $accion_eliminar && isset($id_array) ) {

    foreach ($id_array as $id_unico) {
      org_eliminar_clientes($id_unico);
    }

    $data[mensaje] = "Usuario/s eliminado/s";
    $data[estado] = 1;
  }

  if( $accion_editar && isset($id_array) ) {

    $data[estado] = 2;
    $data[id] = $id_array;
  }

  org_json($data);
  return $data;
}

function org_eliminar_clientes($id_unico) {

  global $wpdb;
  $resultado = false;

  try {

    $tabla_clientes = $wpdb->prefix . "org_clientes";

      $wpdb->query(
      $wpdb->prepare("DELETE FROM $tabla_clientes WHERE id = %d
      ",
      $id_unico
      )
    );

    $resultados = true;
  }

  catch( Exception $e ) {

  }

  return $resultados;
}

function org_inscribir_clientes() {


  global $wpdb;

  $datos = array(
    "nombre" => $_POST['org_nombre_cliente'],
    "partido" => $_POST['org_partido_cliente'],
    "localidad" => $_POST['org_localidad_cliente'],
    "direccion" => $_POST['org_direccion_cliente'],
    "email" => $_POST['org_email_cliente'],
    "estado" => 0
  );

  //$resultados = false;
  $datos[estado] = false;


  try {

  $tabla_clientes = $wpdb->prefix . "org_clientes";
  $tabla_empleadores = $wpdb->prefix . "org_empleadores";

  $uid = $wpdb->get_var(
            $wpdb->prepare("SELECT id from $tabla_empleadores WHERE nombre = %s
            ",
            $datos['email']
            )
          );

  $wpdb->insert(
    $tabla_clientes,
    array(
      'nombre' => $datos['nombre'],
      'partido' => $datos['partido'],
      'localidad' => $datos['localidad'],
      'direccion' => $datos['direccion'],
      'uid' => $uid,
    ),
    array(
      '%s',
      '%s',
      '%s',
      '%s',
      '%d',
    )
  );

//$resultados = true;
$datos[estado] = true;

}

catch ( Exception $e) {

}

return org_json($datos);
}


function org_update_clientes(){

  global $wpdb;

  $datos = array(
    'estado' => 0,
    'mensaje' => "Error"
  );

  $cant_clientes_editar = $_POST['cant_clientes'];
  $tabla_clientes = $wpdb->prefix . 'org_clientes';

  try {

    for($x = 1 ; $x <= $cant_clientes_editar ; $x++){

        $name = $_POST['nombre' . $x];
        $partido = $_POST['partido' . $x];
        $localidad = $_POST['localidad' . $x];
        $direccion = $_POST['direccion' . $x];
        $id = $_POST['id_unico' . $x];

        $wpdb->query(
          $wpdb->prepare(
            "
            UPDATE $tabla_clientes
            SET nombre = '$name',
                partido = '$partido',
                localidad = '$localidad',
                direccion = '$direccion'
            WHERE id = %d
            ",
            $id
            )
          );
      }

      $datos[mensaje] = "Se han editados los usuarios correctamente.";
      $datos[estado] = 1;

  } catch (Exception $e) {

  }

   return org_json($datos);
}

//------------------------------//

// HERRAMIENTAS

//verificamos que otro usuario no tenga el mismo mail antes de inscribir
function org_obtener_id($a_comparar, $customfield){

  $id_usuario = 0;

  try {

    $consulta_wp = new WP_Query(
      array(
        'post_type' => 'org_usuarios',
        'post_per-page' => 1,
        'meta_key' => $customfield,
        'meta_query' => array(
          array(
            'key' => $customfield,
            'value' => $a_comparar,
            'compare' => '=',
          ),
        ),
      )
    );

    if($consulta_wp->have_posts()):
      $consulta_wp->the_post();
      $id_usuario = get_the_ID();

    endif;

  } catch (Exception $e){

  }

  wp_reset_query();

  return (int)$id_usuario;
}

function org_chequeo_usuarios($email,$pass) {

  $return = 0;

  try {
    $comparar_email = "org_email";
    $id_email = org_obtener_id($email, $comparar_email);
    $pass_id_email = get_field("org_password", $id_email);
    if ($pass_id_email === $pass):
      $return = 1;
    endif;
 }

 catch ( Exception $e) {

 }

 return $return;

}

function org_unico_codigo_acf($nombre_field){

  $key = $nombre_field;

  switch ($nombre_field) {
    case 'org_nombre':
      $key = 'field_5974007866ef5';
      break;
    case 'org_email':
      $key = 'field_5974009b66ef6';
      break;
    case 'org_password':
      $key = 'field_59c9b37355c0a';
      break;
  }

  return $key;
}

function org_json($php_array) {

  //usar la funcion para transformar a json
  $json_result = json_encode( $php_array );

  //retornamos el resultado
  die( $json_result );

  //exit (?)
  exit;
}


function retornarnombres($uid, $nombres_clientes_db, $email, $pass) {

  $mi_url = org_saber_url();

  function reemplazar_espacios($string) {

    $reemplazo = preg_replace('/\s+/', '%20' , $string);

    return $reemplazo;
  }

  function google_maps($partido, $localidad, $direccion) {

    $par = reemplazar_espacios($partido);
    $loc = reemplazar_espacios($localidad);
    $dir = reemplazar_espacios($direccion);

    $enlace = 'https://www.google.com/maps/search/?api=1&query='. $dir . '%20' . $loc . '%20' . $par;
    $link = "<a href =". $enlace . " target=_blanck'> Localizar </a>";

    return $link;
  }

  $cant_clientes = count($nombres_clientes_db);

  $id_referencial = 1;

  $resultados = '

  <form id="org_editar_clientes" method="post" action="'. $mi_url .'/edicion-de-clientes">
    <input type="hidden" id="id_unico" name="uid" value=""></input>
  </form>

  <div class="opciones_tab_clientes">
  <form id="org_eliminar_usuario_id" name="org_eliminar_usuario_name" method="post"
  action="/wordpress/wp-admin/admin-ajax.php?action=org_sel_opciones_clientela">
    <select name="acciontablaclientes"">
      <option value="eliminar" >Eliminar</option>
      <option value="editar" >Editar</option>
    </select>
    <input type="submit" id="submit_tabla_cliente" action="submit" value="Aceptar"></input>
  </div>

  <input type="hidden" id="cant_clientes" value="'. $cant_clientes .'"></input>
  <input type="hidden" name="email" value="'. $email . '"></input>
  <input type="hidden" name="pass" value="'. $pass . '"></input>
  <input type="hidden" name="uid" value ='. $uid .'"></input>

   <table class="clientela">
    <thead>
    <tr>
    <th style="border:1px solid;"><input type="checkbox" name="chequeartodo" id="check_all"></input></th>
    <th style="border:1px solid;">Nombre</th>
    <th style="border:1px solid;">Partido</th>
    <th style="border:1px solid;">Localidad</th>
    <th style="border:1px solid;">Dirección</th>
    <th style="border:1px solid;">Ubicar</th>
    </tr>
    </thead>
    <tbody>';
    for ($x = 0; $x < count($nombres_clientes_db) ; $x++) {

      $nombre_cliente = $nombres_clientes_db[$x]->nombre;
      $partido_cliente = $nombres_clientes_db[$x]->partido;
      $localidad_cliente = $nombres_clientes_db[$x]->localidad;
      $direccion_cliente = $nombres_clientes_db[$x]->direccion;
      $id_unico = $nombres_clientes_db[$x]->id;

      $resultados .= '
      <tr>
      <td style="border:1px solid;"><input type="checkbox" name="clientes[]" value="' . $id_unico . '" id="'. $id_referencial .'"></input></td>
      <td style="border:1px solid;">'. $nombre_cliente . '</td>
      <td style="border:1px solid;">'. $partido_cliente . '</td>
      <td style="border:1px solid;">'. $localidad_cliente . '</td>
      <td style="border:1px solid;">'. $direccion_cliente . '</td>
      <td style="border:1px solid;">
      '. google_maps($partido_cliente, $localidad_cliente, $direccion_cliente).'
      </td>
      </tr>';

      $id_referencial++;

    }
    $resultados .= '
</tbody>
</table>
</form>

  ';

  return $resultados;

}

function org_log_out(){

  $mi_url = org_saber_url();

  do_action('end_session_action');
  header("Location: ". $mi_url);
  die();
}

function org_saber_url(){
  global $wp;
  return home_url($wp);
}
?>
