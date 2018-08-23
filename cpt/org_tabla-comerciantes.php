<?php

function cptui_register_my_cpts_org_usuarios() {

	/**
	 * Post Type: Comerciantes.
	 */

	$labels = array(
		"name" => __( "Comerciantes", "twentyfifteen" ),
		"singular_name" => __( "Comerciante", "twentyfifteen" ),
	);

	$args = array(
		"label" => __( "Comerciantes", "twentyfifteen" ),
		"labels" => $labels,
		"description" => "",
		"public" => false,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => false,
		"show_in_nav_menus" => true,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "org_usuarios", "with_front" => false ),
		"query_var" => true,
		"supports" => false,
	);

	register_post_type( "org_usuarios", $args );
}

add_action( 'init', 'cptui_register_my_cpts_org_usuarios' );


if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_tabla-comerciantes',
		'title' => 'Tabla Comerciantes',
		'fields' => array (
			array (
				'key' => 'field_5974007866ef5',
				'label' => 'Nombre',
				'name' => 'org_nombre',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5974009b66ef6',
				'label' => 'Email',
				'name' => 'org_email',
				'type' => 'email',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_59c9b37355c0a',
				'label' => 'Contraseña',
				'name' => 'org_password',
				'type' => 'password',
				'required' => 1,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'org_usuarios',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}

?>
