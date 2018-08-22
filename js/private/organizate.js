jQuery(document).ready(function($){

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
