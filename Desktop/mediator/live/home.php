<?php

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action( 'genesis_loop', 'genesis_do_loop' );





add_action( 'wp_enqueue_scripts', 'codeart_include_home_scriptsand_styles' );
function codeart_include_home_scriptsand_styles()
{
	wp_register_style(
		'owl-css',
		get_stylesheet_directory_uri() . '/inc/owl/owl-carousel/owl.carousel.css'
	);
	wp_enqueue_style( 'owl-css' );

	wp_register_style(
		'owl-theme',
		get_stylesheet_directory_uri() . '/inc/owl/owl-carousel/owl.theme.css'
	);
	wp_enqueue_style( 'owl-theme' );



	wp_register_script(
		'owl-js',
		get_stylesheet_directory_uri() . '/inc/owl/owl-carousel/owl.carousel.js',
		array('jquery'),
		CHILD_THEME_VERSION,
		FALSE
	);
	wp_enqueue_script( 'owl-js' );

}





add_action('wp_head', 'codeart_home_head');
function codeart_home_head()
{
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
        var owl = $("#owl-example");
        
		owl.owlCarousel({
            items:4,
            center:true,
            navigation:true,
            pagination:false,
            afterInit : AfterCustomLoader
        });
        
        function AfterCustomLoader(){
            owl.closest('div.popupar-mediators').find('.ca_custom_loader').addClass('go');
            
            setTimeout(function(){
                owl.closest('div.popupar-mediators').find('.ca_custom_loader').fadeOut().delay('1500').queue(function(i){
                    $(this).remove();
                    i();
                });
            },500)
        }
        
        
	});
	</script>
	<?php
}




add_action('genesis_after_header', 'codeart_after_home_header');
function codeart_after_home_header()
{
	?>
	<div class="home-top-section" style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/images/top_section_bg.jpg')">
		<div class="wrap">
            <div class="to-hide">
			<?php if(get_field('homepage_heading_text', 'option')): ?>
			<h2><?php the_field('homepage_heading_text', 'option'); ?></h2>
			<?php endif; ?>

			<?php if(get_field('homepage_sub_heading_text', 'option')): ?>
			<p><?php the_field('homepage_sub_heading_text', 'option'); ?></p>
			<?php endif; ?>
            </div>
			<div class="form-live-search">
			    <form action="<?php bloginfo('url'); ?>/search/" method="get">
				    <input type="text" id="search" autocomplete="off" name="query" placeholder="What would you like to learn today?" class="trigger-live-search">
				    <input type="submit" style="display: none;">
				    <div class="search-icon">
				    </div>
			    </form>

			    <div class="home-search-wrap">
                    <div class="home-search-border">
	                <?php
	                	// codeart_search_grid_for_mediators_custom( 'Mediators', '', [], [], true );
	                	global $member;

	                	// Mediators
	                	$popular_mediators_home = get_field('homepage_popular_mediators', 'option');
	                	printf('<div class="results results-mediators">');
							printf('<h4>%s</h4>', 'Mediators');
							if( $popular_mediators_home ):
								foreach( $popular_mediators_home as $mediator ): $mediator = $mediator['home_popular_mediator']; ?>
								<div class="result-item">
									<a href="<?php echo codeart_get_mediators_url($mediator['user_nicename']); ?>" class="mediator-avatar">
									    <div class="avatar-holder">
										    <?php $member->print_avatar($mediator['ID'], 'thumbnail'); ?>
						                </div>
									</a>

									<div class="name-and-title">
										<a href="<?php echo codeart_get_mediators_url($mediator['user_nicename']); ?>">
											<h4 class="title"><?php echo $mediator['display_name']; ?></h4>
										</a>

										<?php
										$mediator_title = codeart_get_user_title($mediator['ID']);
										if($mediator_title): ?>
										<span class="mediator-title"><?php echo apply_filters('the_title', $mediator_title); ?></span>
										<?php endif; ?>
									</div>
								</div>
								<?php endforeach;
							endif;
						printf('</div> <!-- .results -->');




	                	// Topics
	                	$popular_home_topics = get_field('home_popular_topics', 'option');
	                	$popular_home_topics_ids = [];
	                	if ($popular_home_topics) {
	                		foreach($popular_home_topics as $pt) {
	                			$popular_home_topics_ids[] = $pt['home_topic_item']->ID;
	                		}
	                	}

	                	$search_args = [
	                		'post_type' 		=> 'topics',
	                		'posts_per_page' 	=> 5,
	                		'post_status' 		=> 'publish',
	                		'post__in'			=> $popular_home_topics_ids
	                	];

	                	codeart_search_grid( 'Topics', $search_args, true );
	                ?>
                    </div>
	                <a href="<?php bloginfo('url'); ?>/search/" data-url="<?php bloginfo('url'); ?>/search/" class="view-mediators-and-topics">View all results</a>
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
           </div>
           <div class="simple-height-block"></div>
           <div class="to-hide">
            <p class="browse_videos">or <a href="<?php bloginfo('url'); ?>/search/topics/" class="green_font2"><strong>browse topics</strong></a></p>
            <div class="additional_links">
            	<?php if(get_field('home_hint_mediators', 'option')): ?>
                <span class="icon link_mediator"><?php the_field('home_hint_mediators', 'option'); ?></span>
            	<?php endif; ?>

            	<?php if(get_field('home_hint_topics', 'option')): ?>
                <span class="icon link_videos"><?php the_field('home_hint_topics', 'option'); ?></span>
                <?php endif; ?>

                <?php if(get_field('home_hint_access', 'option')): ?>
                <span class="icon link_devices"><?php the_field('home_hint_access', 'option'); ?></span>
                <?php endif; ?>
            </div>
            </div>
            <div class="form-live-overlay"></div>
		</div>
	</div> <!-- .home-top-section -->

	<div class="popupar-mediators">
	    <div class="ca_custom_loader">
            <div class="ca-align-center">
                <?php ca_get_svg('logo_loader.svg'); ?>
            </div>
        </div>
		<div class="wrap">
			<h4><a href="<?php bloginfo('url'); ?>/search/mediators/">Popular Mediators</a></h4>

			<?php
			$popular_mediators = get_field('homepage_popular_mediators', 'option');

			$popular_mediators = array();
			$mediator_ids = array();
			if( have_rows('homepage_popular_mediators', 'option') ):
				while( have_rows('homepage_popular_mediators', 'option') ): the_row();
					$current_mediator = get_sub_field('home_popular_mediator');
					$popular_mediators[] = $current_mediator;
					$mediator_ids[] = $current_mediator['ID'];
				endwhile;
			endif;

			global $member;

			$mediator_ids = $member->get_popular_mediators($mediator_ids);

			if( $popular_mediators ):
				printf('<div id="owl-example" class="owl-carousel">');
				foreach($popular_mediators as $mediator): ?>
				<div>
					<a href="<?php echo codeart_get_mediators_url($mediator['user_nicename']); ?>">
					    <div class="image">
					    	<?php codeart_member_avatar( codeart_get_member_avatar_id($mediator_ids, $mediator['ID']), 'popular-mediator-avatar' ); ?>
                        </div>
						<h3><?php echo esc_attr($mediator['user_firstname']) . ' ' . esc_attr($mediator['user_lastname']); ?></h3>
						<?php $member_title = codeart_get_member_title($mediator_ids, $mediator['ID']); ?>
						<?php if($member_title): ?><p><?php echo $member_title; ?></p><?php endif; ?>
					</a>
				</div>
				<?php endforeach;
				printf('</div>');
			endif; ?>
			
		</div>

	</div> <!-- .popupar-mediators -->



	<div class="popular-topics popular-topics-home">

		<div class="wrap">
			<h4><a href="<?php bloginfo('url'); ?>/search/topics/">Popular Topics</a></h4>

			<?php
			$popular_topics = get_field('home_popular_topics', 'option');

			array_pop($popular_topics);

			if ($popular_topics):
				printf('<div class="popular-topics-item">');
    
                $counter = 0;
    
				foreach ($popular_topics as $index => $topic): 
    
                    $classes = array();
                        
                    if($counter % 2 == 0){
                        $classes[] = 'even';
                    }
                        
                    else{
                        $classes[] = 'odd';
                    }
                    ?>
					<?php $topic = $topic['home_topic_item'];  ?>
					<div class="ppt-item <?php echo implode(' ', $classes); ?>">
						<a href="<?php echo get_permalink($topic->ID); ?>" class="image-holder">

							<?php codeart_print_video_overlay( $topic ); ?>

							<?php echo get_the_post_thumbnail( $topic->ID, 'topics-thumbnail' );?>
						</a>

						<?php
						$content = $topic->post_content;
						$content = strip_tags($content);
						$content = strip_shortcodes( $content );
						$content = substr($content, 0, 120);
						?>
                        <div class="ppl-item-content">
                            <h3>
                            	<a href="<?php echo get_permalink($topic->ID); ?>">
                            		<?php echo apply_filters('the_title', $topic->post_title); ?>
                            	</a>
                            </h3>
						    <div class="entry-popular-topics-content"><?php echo '<p>' . $content . '...</p>'; ?></div>

						    <?php codeart_print_grid_rating($topic->ID, true); ?>
						
                        </div>
					</div>
					
				<?php
    
                $counter++;
				endforeach;
				printf('</div>');
			endif; ?>
		</div>
	</div> <!-- .popular-topics -->


	<div class="features-wrap more-features">
		<div class="wrap">
			<h4 class="features">Features</h4>

			<div class="feature feature0">
			    <a href="#">
				    <div class="icon">
				
					    <?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/head.svg"); ?>
					
				    </div>
				    <h3>HIGH CALIBRE MEDIATORS</h3>
				    <p>Learn from experienced practitioners and international thought leaders</p>
                </a>
			</div>

			<div class="feature feature1">
			    <a href="#">
				    <div class="icon">
					
				        <?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/award.svg"); ?>
				    
				    </div>
				    <h3>EARN WHILE YOU LEARN</h3>
                    <p>Keep your professional development up to date. Earn accredited CPD points each time you learn</p>
                </a>
			</div>

			<div class="feature feature2">
			    <a href="#">
				    <div class="icon">
                
				        <?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/book.svg"); ?>
					
				    </div>
				    <h3>FRESH, RELEVANT CONTENT</h3>
				    <p>New and relevant Masterclasses, Courses and Features tailored just for you</p>
                </a>
			</div>

			<div class="feature feature3">
			    <a href="#">
				    <div class="icon">
                
				        <?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/apple.svg"); ?>
					
				    </div>
				    <h3>CONVENIENT, SIMPLE AND EASY TO USE</h3>
				    <p>Learn at your own pace, in your own time and in your own style</p>
                </a>s
			</div>

			<a href="<?php bloginfo('url'); ?>/features/" class="more-features-link">View more features</a>
		</div>
	</div> <!-- .features -->
	<?php
}

?>

<?php genesis(); ?>