<?php
/**
 *	Template Name: Search
 **/


add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
add_filter('body_class', 'codeart_search_body_classes');
function codeart_search_body_classes( $classes )
{
	$classes[] = 'codeart-search';
	return $classes;
}


remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'codeart_search_loop');
function codeart_search_loop()
{
	$search_params_list = codeart_get_search_paramethers();
	// _debug($search_params_list);
	extract($search_params_list); ?>


	<div class="entry">
		<div class="entry-content">
			
			<div class="search-top">
				<span class="search-query">
					<strong>Search Query:</strong>
                    <span><?php echo $search_text ? esc_attr($search_text) : 'All'; ?></span>
				</span>
				<div class="share-on">
					<h4>Share on</h4>
					<a href="#" class="tw">
					    <?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/icon-twitter.svg"); ?>
					</a>
					<a href="#" class="gp">
					    <?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/icon-gplus.svg"); ?>
					</a>
					<a href="#" class="fb">
					    <?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/icon-facebook.svg"); ?>
					</a>
				</div>
			</div> <!-- .search-top -->
			<div class="form-box">
				<form id="main-search-form" action="" method="get">
					<input type="text" autocomplete="off" name="query" id="search" value="<?php echo esc_attr( $search_text ); ?>" placeholder="Search...">
					<input type="submit" id="search-button" value="Search">
				</form>
			</div> <!-- .form-box -->
			<div class="search-wrap">

				<div class="search-overlay">
					<div class="overlay"></div>
					<div class="loader-home-search">
				        <div class="showbox">
				        	<div class="loader">
				        		<svg class="circular" viewBox="25 25 50 50">
				        			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
				        		</svg>
				        	</div>
				        </div>
				    </div>
				</div>

				<div class="left ca-filters">
					<div class="box box-type main-filter">
						<h4>Type</h4>
						<div class="item checkbox">
							<input type="checkbox" id="select-mediators" <?php codeart_class_checked('', $search_type); ?> <?php checked('', $search_type); ?>>
							<label for="select-mediators">
								<a href="<?php echo codeart_get_search_url(); ?>" data-url="<?php echo codeart_get_search_url(); ?>">All</a>
							</label>
						</div>
						<div class="item checkbox">
							<input type="checkbox" id="select-topics" <?php codeart_class_checked('topics', $search_type); ?> <?php checked('topics', $search_type); ?>>
							<label for="select-topics">
								<a href="<?php echo codeart_get_search_url('topics'); ?>" data-url="<?php echo codeart_get_search_url('topics'); ?>">Topics</a>
							</label>
						</div>
						<div class="item checkbox">
							<input type="checkbox" id="select-mediators" <?php codeart_class_checked('mediators', $search_type); ?> <?php checked('mediators', $search_type); ?>>
							<label for="select-mediators">
								<a href="<?php echo codeart_get_search_url('mediators'); ?>" data-url="<?php echo codeart_get_search_url('mediators'); ?>">Mediators</a>
							</label>
						</div>
					</div> <!-- .box -->
					
					<?php codeart_print_type_categories('topics', $search_category); ?>

				</div> <!-- .left -->

				<div class="right">
					
					<?php
					global $member;

					$title 				= ucfirst($search_type);
					$all_types 			= codeart_get_search_types();
					$query_arguments 	= codeart_get_search_args();

					$url_category 		= empty($url_category) 		? [] : explode(',', $url_category);
    				$url_specialism 	= empty($url_specialism) 	? [] : explode(',', $url_specialism);
    				$url_service 		= empty($url_service) 		? [] : explode(',', $url_service);

    				$is_all = 0;
    				$stype = get_query_var('searchtype');
    				if (is_page_template('template-search.php') && empty($stype)):
    					$is_all = 1;
    				endif;

					codeart_ajax_search_func($all_types, $query_arguments, $search_type, false, false, $url_category, $url_specialism, $url_service, $search_text, $is_all); ?>

				</div> <!-- .right -->
				<div class="ca-mobile-filter-trigger">
				    <span>Filter</span>
				</div>
			</div> <!-- .search-wrap -->
		</div> <!-- .entry-content -->
	</div> <!-- .entry -->
	<?php
}








/**
 * Function to print the taxonomies
 * 
 * @param string $type Post type name
 * @param string $title Box title
 */
function codeart_print_type_categories($type, $selected = '', $title = 'Categories')
{
	if (empty($type)) {
		return;
	}

	$current_vars = codeart_get_search_paramethers(false);

	$taxonomies = get_object_taxonomies($type, 'objects');
	if($taxonomies):

		$all_url_categories = isset($_GET['category']) ? $_GET['category'] : false;
		$all_categories = [];
		$empty_categories = true;
		if ($all_url_categories) { $all_categories = explode(',', $all_url_categories); } else { $empty_categories = false; }

		$all_url_specialism = isset($_GET['specialism']) ? $_GET['specialism'] : false;
		$all_specialism = [];
		$empty_specialisms = true;
		if ($all_url_specialism) { $all_specialism = explode(',', $all_url_specialism); } else { $empty_specialisms = false; }

		$all_url_services = isset($_GET['services']) ? $_GET['services'] : false;
		$all_services = [];
		$empty_services = true;
		if ($all_url_services) { $all_services = explode(',', $all_url_services); } else { $empty_services = false; }

		$all_categories = array_merge($all_categories, $all_specialism);
		$all_categories = array_merge($all_categories, $all_services);

		$tmp_categories = [
			'topic_cat' 	=> $empty_categories,
			'specialism' 	=> $empty_specialisms,
			'service' 		=> $empty_services,
		];

		foreach($taxonomies as $tax): ?>
			<?php if($tax->name == 'specialism' && $current_vars['search_type'] == 'topics'): continue; endif; ?>
			<?php if(strtolower($tax->label) == 'services' && $current_vars['search_type'] != 'mediators') continue; ?>
			<?php if($tax->name == 'topic_cat' && $current_vars['search_type'] == 'mediators') continue; ?>

			<div class="box box-ajax-search search-categories-box box-slug-<?php echo $tax->name; ?>">
				<h4><?php echo $tax->label; ?></h4>

				<div class="item checkbox">
					<input type="checkbox" class="tax-cb all" id="select-mediators" <?php echo $tmp_categories[$tax->name] == false ? ' checked' : ''; ?>>
					<label for="select-mediators">
						<a  class="tax-anchor" href="#" data-value="">All</a>
					</label>
				</div>

				<?php
				$taxonomy_terms = get_terms( $tax->query_var, ['hide_empty' => false] );
				foreach($taxonomy_terms as $tax_term): ?>
					<?php if($tax_term->slug == 'uncategorized') continue; ?>
					<?php if( $tax_term->parent ) continue; ?>
					<div class="cb-item">
					<?php
					if( empty($tax_term->parent) ): ?>

						<?php
						$terms_count = $tax_term->count;
						if ($tax->name == 'specialism') {
							$terms_count = codeart_get_specialisms_count($tax_term->term_id);
						}

						if ($tax->name == 'service') {
							$terms_count = codeart_get_services_count($tax_term->term_id);
						}
						?>
						<div class="item checkbox <?php echo $tax_term->parent ? 'parent-item' : ''; ?>">
							<input type="checkbox" data-id="<?php echo $tax_term->slug; ?>" class="tax-cb" id="select-mediators" <?php echo in_array($tax_term->slug, $all_categories) ? ' checked' : ''; ?>>
							<label for="select-mediators">
								<a  class="tax-anchor" href="#"><?php echo $tax_term->name; ?> <span class="count-items">(<?php echo $terms_count; ?>)</span></a>
							</label>
						</div>
						<?php

						$child_terms = get_terms( $tax->query_var, ['hide_empty' => true, 'orderby' => 'name', 'order' => 'ASC', 'parent' => $tax_term->term_id] );
						if( $child_terms ):
							printf('<div class="child-term-items">');
								foreach($child_terms as $ct): ?>
									<div class="item checkbox <?php echo $ct->parent ? 'parent-item' : ''; ?>">
										<input type="checkbox" data-id="<?php echo $ct->slug; ?>" class="tax-cb" id="select-mediators" <?php echo in_array($ct->slug, $all_categories) ? ' checked' : ''; ?>>
										<label for="select-mediators">
											<a href="#"><?php echo $ct->name; ?></a>
										</label>
									</div>
								<?php endforeach;
							printf('</div>');
						endif; 
					
					endif; ?>
					</div>
				<?php
				endforeach; ?>
			</div> <!-- .box -->
		<?php endforeach;
	endif;
} // End of function codeart_print_type_categories();








function codeart_print_mediator_specialisms($selected)
{
	$taxonomy 	= 'specialism';
	$type 		= 'mediators';

	$taxonomy_object = get_taxonomy($taxonomy);
	$specialisms = get_terms($taxonomy, array('hide_empty' => false));

	if($specialisms): ?>

		<?php
		$all_url_specialism = isset($_GET['specialism']) ? $_GET['specialism'] : false;
		$all_specialism = [];
		if ($all_url_specialism) {
			$all_specialism = explode(',', $all_url_specialism);
		} ?>

		<div class="box box-ajax-search box-slug-specialism search-specialisms-box">
			<h4><?php echo $taxonomy_object->label; ?></h4>

			<div class="item checkbox">
				<input type="checkbox" class="tax-cb all" id="select-mediators" <?php checked('', $selected); ?>>
				<label for="select-mediators">
					<a  class="tax-anchor" href="<?php bloginfo('url'); ?>/search/<?php echo $type; ?>/" data-value="">All</a>
				</label>
			</div>
			<?php
			foreach($specialisms as $spec): ?>
			<div class="item checkbox <?php echo $spec->parent ? 'parent-item' : ''; ?>">
				<input type="checkbox" data-id="<?php echo $spec->slug; ?>" id="select-mediators" <?php echo in_array($spec->slug, $all_specialism) ? ' checked' : ''; ?>>
				<label for="select-mediators">
					<a class="tax-anchor" href="<?php bloginfo('url'); ?>/search/<?php echo $type . '/' . $spec->slug; ?>/"><?php echo $spec->name; ?></a>
				</label>
			</div>
			<?php endforeach; ?>
		</div>
		<?php
	endif;
}




function codeart_class_checked( $need, $check)
{
	if ($need == $check)
		printf('class="checked"');
}







add_action('wp_head', 'codeart_add_search_seo_link_rel');
function codeart_add_search_seo_link_rel()
{
	$search_type 		= get_query_var('searchtype');
	$search_category 	= get_query_var('searchcategory');
	$search_text 		= get_query_var('query');
	// var_dump($search_category);

	if( empty($search_type) || $search_text )
		return;

	$item_page = isset($_GET['item-page']) ? intval($_GET['item-page']) : 1;

	if($item_page < 1)
		return;
	
	$final_url = get_bloginfo('url') . '/search/' . $search_type . '/' . (($search_category) ? $search_category . '/' : '');

	if($search_text)
		$final_url = add_query_arg('query', $search_text, $final_url);

	if($item_page > 1):
		$final_url_prev = add_query_arg('item-page', $item_page - 1, $final_url);
		printf(
			'<link rel="prev" href="%s">',
			$final_url_prev
		);
	endif;

	$final_url_next = add_query_arg('item-page', $item_page + 1, $final_url);

	printf(
		'<link rel="next" href="%s">',
		$final_url_next
	);
}





genesis();

?>