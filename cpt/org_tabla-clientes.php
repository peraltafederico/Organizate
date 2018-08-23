<?php

function cptui_register_my_cpts_org_clientes() {

	/**
	 * Post Type: Clientes.
	 */

	$labels = array(
		"name" => __( "Clientes", "twentyfifteen" ),
		"singular_name" => __( "Cliente", "twentyfifteen" ),
	);

	$args = array(
		"label" => __( "Clientes", "twentyfifteen" ),
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
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "org_clientes", "with_front" => false ),
		"query_var" => true,
		"supports" => false,
	);

	register_post_type( "org_clientes", $args );
}

add_action( 'init', 'cptui_register_my_cpts_org_clientes' );

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_tabla-clientes',
		'title' => 'Tabla Clientes',
		'fields' => array (
			array (
				'key' => 'field_5b72466920314',
				'label' => 'Nombre',
				'name' => 'org_nombre_cliente',
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
				'key' => 'field_5b7246ac20315',
				'label' => 'Partido',
				'name' => 'org_partido_cliente',
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
				'key' => 'field_5b7246c720316',
				'label' => 'Localidad',
				'name' => 'org_localidad_cliente',
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
				'key' => 'field_5b7246d720317',
				'label' => 'Direccion',
				'name' => 'org_direccion_cliente',
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
				'key' => 'field_5b7246d720319',
				'label' => 'Propietario',
				'name' => 'org_propietario_cliente',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'org_clientes',
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
