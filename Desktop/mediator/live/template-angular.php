<?php

/**
 *	Template Name: AngularJS Template
 **/

add_filter('body_class', 'angular_body_classes');
function angular_body_classes($calses)
{
	$classes[] = 'angularjs';
	return $classes;
}

add_action( 'wp_enqueue_scripts', 'angular_scripts' );
function angular_scripts()
{
	wp_enqueue_script(
		'angularjs',
		'https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js',
		array(),
		'1.0.0',
		false
	);
}

remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'angular_loop');
function angular_loop()
{
	?>
	<div class="angular-wrap" ng-app="myApp" ng-controller="namesCtrl">
		<p>Search Mediators:</p>
		<p><input type="text" ng-model="test"></p>

		<ul class="angular-list left">
			<li ng-repeat="x in names | filter:test">
				<a href="{{ x.url }}">{{ x.title }}</a>
			</li>
		</ul>

		<ul class="angular-list right">
			<li ng-repeat="x in topics | filter:test">
				<a href="{{ x.url }}" title="{{ x.title }}" class="topic-link" data-id="{{ x.id }}">{{ x.title }}</a>
			</li>
		</ul>

		<?php
		$users = get_users();

		$users_json = [];
		foreach($users as $u)
			$users_json[] = sprintf(
				'{id: %d, title: "%s", url: "%s"}',
				$u->ID,
				$u->data->display_name,
				get_bloginfo('url') . '/network/mediator/' . $u->data->user_nicename
			);

		$posts = get_posts(['post_type' => 'topics', 'posts_per_page' => -1]);
		$posts_json = [];
		foreach($posts as $p) $posts_json[] = sprintf('{id: %d, title: "%s"}', $p->ID, $p->post_title);
		?>

	</div>

	<style type="text/css">
		.angular-wrap { overflow: hidden; }
		.angular-list { display: block; width: 100%; }
		.angular-list li { border-bottom: 1px solid #ccc; font-size: 20px; line-height: normal; padding: 5px 20px; }
		.left, .right { float: left; width: 50%; }
	</style>
	<script type="text/javascript">
	angular.module('myApp', []).controller('namesCtrl', function($scope) {
		$scope.names = [<?php echo implode(',', $users_json); ?>];
		$scope.topics = [<?php echo implode(',', $posts_json); ?>];
	});

	jQuery(document).ready(function($) {
		$('body').on('click', 'a.topic-link', function(e) {
			e.preventDefault();
			location.href = 'http://mediator.codeart.mk/?post_type=interviews&p=' + $(this).data('id');
		});
	});
	</script>
	<?php
}

genesis();

?>