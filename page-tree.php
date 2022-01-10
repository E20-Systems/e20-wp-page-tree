<?php
/*
Plugin Name: Page Tree
Plugin URI: https://www.gaelanlloyd.com
Description: Shows a page tree view for easy site browsing.
Version: 1.1.2
Author: Gaelan Lloyd
Author URI: http://www.gaelanlloyd.com
*/

namespace e20_page_tree;

use Walker_Page;

if ( !defined( 'ABSPATH' ) ) exit;

// -----------------------------------------------------------------------------

function add_menu() {
	add_submenu_page('edit.php?post_type=page', 'Page tree', 'Page tree', 'edit_pages', 'page_tree', 'e20_page_tree\show');
}

add_action( 'admin_menu', 'e20_page_tree\add_menu' );

// -----------------------------------------------------------------------------

function show() {

	// Caution! This same item is declared twice in the file.
	// If you change one, be sure to change the other.
	$css_data = 'font-family: monospace; width: 100%; overflow: hidden; display: block; word-wrap: break-word; margin: 0; padding: 0;';

	$separator = "<hr style=\"margin: 1em 0;\">";

?>

<style type="text/css">
	.page-tree .children { padding-left: 2em; }
	.page-tree li { padding-top: 0.5em; border-top: 1px dotted #C0C0C0; }
	.item-meta { line-height: 8px; border: 1px solid #D0D0D0; padding: 0.5em; background-color: #F0F0F0; }
	.page-item { display: inline-block; }
	.page-item-header { background-color: rgba(34,113,177,0.3); line-height: 2em; border-top: none !important; }
	.page-meta-header,
	.page-meta-input-header { font-weight: bold; }
	.page-meta { float: right; margin-right: 1em; display: inline; }
	.page-meta-spacer { margin-right: 2em; }
	.page-meta-input,
	.page-meta-input[readonly] { font-family: monospace; padding: 2px; margin: 0; border: 1px solid #E0E0E0; border-style: solid; }
	.page-meta-header.page-title { padding-left: 1em; }
	.page-links-description { }
	.page-has-children { font-weight: bold; cursor: pointer; cursor: hand; }
	.page-control { display: inline-block; }
	.clearfix { clear: both; }
	.public { background-color: #C5E0B3; }
	pre.code { border: 1px solid #D0D0D0; background-color: #F0F0F0; padding: 0.25em; }
</style>

<script>

	function toggleParent( id ) {

	  	console.log("Toggle parent " + id);

	    jQuery("#parent-" + id).children(".children").slideToggle();

	    // Toggle the arrow
	    jQuery("#arrow-" + id).toggleClass("dashicons-arrow-down");
	    jQuery("#arrow-" + id).toggleClass("dashicons-arrow-right");

	}

	function expandAll() {
		jQuery(".item-has-children").children(".children").show();
	    jQuery(".item-has-children").find(".dashicons").removeClass("dashicons-arrow-right");
	    jQuery(".item-has-children").find(".dashicons").addClass("dashicons-arrow-down");
	}

	function collapseAll() {
		jQuery(".item-has-children").children(".children").hide();
	    jQuery(".item-has-children").find(".dashicons").removeClass("dashicons-arrow-down");
	    jQuery(".item-has-children").find(".dashicons").addClass("dashicons-arrow-right");
	}

	function showTable() {
		jQuery("#pageTreeTable").show();
	}

	function showLPR() {
		jQuery("#linePrinter").show();
	}

	function showIDURL() {
		jQuery("#idurl").show();
	}

</script>

	<div class="wrap">

		<form>

			<h1 class="wp-heading-inline">Page tree</h1>

			<div class="page-tree">
				<ul>

					<?php // Table key ?>

					<li class="page-item-header">

						<div class="page-item">
							<div class="page-meta"><a href="javascript:void(0)" onclick="expandAll()">Expand all</a></div>
							<div class="page-meta"><a href="javascript:void(0)" onclick="collapseAll()">Collapse all</a></div>
							<div class="page-title page-meta page-meta-header">Page title</div>
						</div>

						<?php // Items in backwards order because of float right ?>

						<div class="page-meta" style="width: 90px;">
							<pre style="<?php echo $css_data; ?>">Modified</pre>
						</div>

						<div class="page-meta" style="width: 200px;">
							<pre style="<?php echo $css_data; ?>">Slug</pre>
						</div>

						<div class="page-meta" style="width: 60px;">
							<pre style="<?php echo $css_data; ?>">ID</pre>
						</div>

						<div class="page-links-description page-meta page-meta-spacer">
							Edit/View links will open in a new tab
						</div>

					</li>

					<?php echo page_tree_output(); ?>

				</ul>
			</div><!-- /.page-tree -->

		</form>

		<h2 style="margin-top: 5em; font-size: 1.5rem;">Other formats</h2>

		<?php echo $separator; ?>

		<h3><a href="javascript:void(0);" onclick="showTable()">Table</a></h3>

		<div id="pageTreeTable" style="display: none;">

			<p>Copy this entire table into Microsoft Excel then manipulate away!</p>

			<table border="1" style="border-collapse: collapse;">

			<tr>
				<th class="cell-table-layout">ID</th>
				<th class="cell-table-layout">Date</th>
				<th class="cell-table-layout">Slug</th>
				<th class="cell-table-layout">Title</th>
			</tr>

			<?php echo page_tree_output_table(); ?>
			</table>

		</div>

		<?php echo $separator; ?>

		<h3><a href="javascript:void(0);" onclick="showIDURL()">ID / URL map</a></h3>

		<div id="idurl" style="display: none;">

			<pre class="code"><?php echo page_tree_output_idurl(); ?></pre>

		</div>

		<?php echo $separator; ?>

		<h3><a href="javascript:void(0);" onclick="showLPR()">ASCII page tree</a></h3>

		<div id="linePrinter" style="display: none;">

			<p>Print using a monospaced font for best results.</p>

			<pre class="code"><?php echo page_tree_output_lpr(); ?></pre>

		</div>

	</div><!-- /.wrap -->

	<?php
}

// -----------------------------------------------------------------------------

function page_tree_output() {

	$args = array(
		'title_li' => '',
		'show_date' => 'modified',
		'walker' => new Page_Walker()
	);

	echo wp_list_pages( $args );

}

// -----------------------------------------------------------------------------

function page_tree_output_table() {

	$args = array(
		'title_li' => '',
		'show_date' => 'modified',
		'walker' => new Page_Walker_Table()
	);

	echo wp_list_pages( $args );

}

// -----------------------------------------------------------------------------

function page_tree_output_lpr() {

	$args = array(
		'title_li' => '',
		'show_date' => 'modified',
		'walker' => new Page_Walker_LPR()
	);

	echo wp_list_pages( $args );

}

// -----------------------------------------------------------------------------

function page_tree_output_idurl() {

	$args = array(
		'title_li' => '',
		'show_date' => 'modified',
		'walker' => new Page_Walker_IDURL()
	);

	echo wp_list_pages( $args );

}

// -----------------------------------------------------------------------------

class Page_Walker extends \Walker_page {

	public $db_fields = array(
		'parent' => 'post_parent',
		'id' => 'ID'
	);

	public function start_lvl( &$out, $depth = 0, $args = array() ) {
		$out .= "<ul class='children'>\n";
	}

	public function end_lvl( &$out, $depth = 0, $args = array() ) {
		$out .= "</ul>\n";
	}

	public function start_el( &$out, $page, $depth = 0, $args = array(), $current_object_id = 0 ) {

		$dateModified = $page->post_modified;
		$dateModified = date( "Y-m-d", strtotime($dateModified) );

		// Caution! This same item is declared twice in the file.
		// If you change one, be sure to change the other.
		$css_data = 'font-family: monospace; width: 100%; overflow: hidden; display: block; word-wrap: break-word; margin: 0; padding: 0;';

		// If this item has children, make it collapsible and styled differently

		if ( $args['has_children'] == 1 ) {

			$liClass = 'item-has-children';
			$titleClass = 'page-has-children';
			$itemIsParent = TRUE;

		} else {

			$liClass = '';
			$titleClass = '';
			$itemIsParent = FALSE;

		}

		ob_start(); ?>

		<li class="<?php echo $liClass; ?> clearfix" id="parent-<?php echo $page->ID; ?>">

		<div class="page-item">
			<div class="page-title page-meta <?php echo $titleClass; ?>">

			<?php if ( $itemIsParent ) { ?>
				<div class="page-control">
					<span id="arrow-<?php echo $page->ID; ?>" class="dashicons dashicons-arrow-down" onclick="toggleParent(<?php echo $page->ID; ?>)"></span>
				</div>

				<a href="javascript:void(0);" onclick="toggleParent(<?php echo $page->ID; ?>)" style="text-decoration: none; color: inherit;">
			<?php } ?>

			<?php echo $page->post_title; ?>

			<?php if ( $itemIsParent ) { ?>
				</a>
			<?php } ?>

			</div>
		</div>

		<?php // Write items in backwards order because of float right ?>

		<div class="page-meta" style="width: 90px;">
			<pre style="<?php echo $css_data; ?>"><?php echo $dateModified; ?></pre>
		</div>

		<div class="page-meta" style="width: 200px;">
			<pre style="<?php echo $css_data; ?>"><?php echo $page->post_name; ?></pre>
		</div>

		<div class="page-meta" style="width: 60px;">
			<pre style="<?php echo $css_data; ?>"><?php echo $page->ID; ?></pre>
		</div>

		<div class="page-view page-meta page-meta-spacer"><a href="<?php echo get_page_link( $page->ID ); ?>" target="_blank" rel="noopener">View</a></div>

		<div class="page-edit page-meta"><a href="<?php echo get_edit_post_link( $page->ID ); ?>" target="_blank" rel="noopener">Edit</a></div>

		<?php

		$out .= ob_get_clean();

	}

	public function end_el( &$out, $page, $depth = 0, $args = array() ) {
		$out .= "</li>\n";
	}
}

// -----------------------------------------------------------------------------

class Page_Walker_Table extends Walker_page {

	public $db_fields = array(
		'parent' => 'post_parent',
		'id' => 'ID'
	);

	public function start_lvl( &$out, $depth = 0, $args = array() ) { }

	public function end_lvl( &$out, $depth = 0, $args = array() ) { }

	public function start_el( &$out, $page, $depth = 0, $args = array(), $current_object_id = 0 ) {

		$dateModified = $page->post_modified;
		$dateModified = date( "Y-m-d", strtotime($dateModified) );

		$style = "padding: 3px; font-family: monospace;";

		// If this item has children, make it collapsible and styled differently

		if ( $args['has_children'] == 1 ) {

			$liClass = 'item-has-children';
			$titleClass = 'page-has-children';
			$itemIsParent = TRUE;

		} else {

			$liClass = '';
			$titleClass = '';
			$itemIsParent = FALSE;

		}

		ob_start(); ?>

		<tr>

			<td style="<?php echo $style; ?>"><?php echo $page->ID; ?></td>
			<td style="<?php echo $style; ?>"><?php echo $dateModified; ?></td>
			<td style="<?php echo $style; ?>"><?php echo $page->post_name; ?></td>

			<td style="<?php echo $style; ?>">

				<?php for ( $d = 0; $d < $depth; $d++ ) {
					echo " > ";
				} ?>

				<a href="<?php echo get_page_link( $page->ID ); ?>"><?php echo $page->post_title; ?></a>

			</td>
		</tr>

		<?php $out .= ob_get_clean();

	}

	public function end_el( &$out, $page, $depth = 0, $args = array() ) { }
}

// -----------------------------------------------------------------------------

class Page_Walker_LPR extends Walker_page {

	public $db_fields = array(
		'parent' => 'post_parent',
		'id' => 'ID'
	);

	public function start_lvl( &$out, $depth = 0, $args = array() ) { }

	public function end_lvl( &$out, $depth = 0, $args = array() ) { }

	public function start_el( &$out, $page, $depth = 0, $args = array(), $current_object_id = 0 ) {

		$dateModified = $page->post_modified;
		$dateModified = date( "Y-m-d", strtotime($dateModified) );

		ob_start();

		echo str_pad( $page->ID, 10, " ", STR_PAD_LEFT );
		echo "   ";

		for ( $d = 0; $d < $depth; $d++ ) {

			if ( $d < $depth - 1 ) {
				echo " |   ";
			} else {
				echo " |-- ";
			}

		}

		echo $page->post_name;

		echo "\n";

		$out .= ob_get_clean();

	}

	public function end_el( &$out, $page, $depth = 0, $args = array() ) { }
}

// -----------------------------------------------------------------------------

class Page_Walker_IDURL extends Walker_page {

	public $db_fields = array(
		'parent' => 'post_parent',
		'id' => 'ID'
	);

	public function start_lvl( &$out, $depth = 0, $args = array() ) {
		$out .= "";
	}

	public function end_lvl( &$out, $depth = 0, $args = array() ) {
		$out .= "";
	}

	public function start_el( &$out, $page, $depth = 0, $args = array(), $current_object_id = 0 ) {

		ob_start();

		echo $page->ID;
		echo ",";
		echo get_the_title( $page->ID );
		echo ",";
		echo get_page_link( $page->ID );
		echo "\n";

		$out .= ob_get_clean();
	}

	public function end_el( &$out, $page, $depth = 0, $args = array() ) {
		$out .= "";
	}
}
