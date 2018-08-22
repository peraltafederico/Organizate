// wait until the page and jQuery have loaded before running the code below
jQuery(document).ready(function($){

	// setup our wp ajax URL
  var wpajax_url = document.location.protocol + '//' +
                   document.location.host + '/wordpress/wp-admin/admin-ajax.php';

	// stop our admin menus from collapsing

	if( $('body[class*="org_"]').length || $('body[class*="post-type-org_"]').length ) {

		$slb_menu_li = $('#toplevel_page_org_dashboard_admin_page');

		$slb_menu_li
		.removeClass('wp-not-current-submenu')
		.addClass('wp-has-current-submenu')
		.addClass('wp-menu-open');

		$('a:first',$slb_menu_li)
		.removeClass('wp-not-current-submenu')
		.addClass('wp-has-submenu')
		.addClass('wp-has-current-submenu')
		.addClass('wp-menu-open');

	}

});
