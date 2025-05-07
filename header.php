<!DOCTYPE html>

<?php wp_head(); ?>

<?php
// NEEDED FOR CSS SELECTION
$uri_array = explode("/", $_SERVER['REQUEST_URI']);
$body_id = $uri_array[sizeof($uri_array)-2];
?>
<body id="<?= $body_id ?>">

<header id="header">
	
	<a href="<?php bloginfo('url'); ?>" id="logo" title="Home">
		<img src="<?php bloginfo('template_directory');?>/static/img/geweih.png" class="logoreh">
		<span class="logofont"><em>RLA</em> intranet</span>
	</a>

	<input type="checkbox" id="hamburg">
	<label for="hamburg" class="hamburg">
		<span class="line"></span>
		<span class="line"></span>
		<span class="line"></span>
	</label>
		
	<!-- Main Section -->
	<nav class="" role="navigation" id="main-menu">
	
		<div class="main-menu-inside">
			<div class="main-menu">
				<h4 class="section-title">RLA Archiv</h4>
				<?php wp_nav_menu( array( 'theme_location' => 'rla-archiv', 'menu' => 'nav','container' => '',)); ?>
			</div>
			
			<div class="main-menu">
				<h4 class="section-title">RLA Info</h4>
				<?php wp_nav_menu( array( 'theme_location' => 'rla-info', 'menu' => 'nav','container' => '',)); ?>
			</div>
			
			<div class="main-menu">
				<h4 class="section-title">RLA Intern</h4>
				<?php wp_nav_menu( array( 'theme_location' => 'rla-intern', 'menu' => 'nav','container' => '',)); ?>
			</div>
			
			<div>
			<?php wp_nav_menu( array( 'theme_location' => 'secondary', 'menu' => 'nav','container' => 'div','container_class' => 'admin-menu',)); ?>
			</div>
		</div>
							
	</nav>
	
	<div class="search-wrapper">
		<div class="searchform">
			<form method="post" action="<?=get_site_url();?>/suche/">
				<input name="suche" type="text" placeholder="Intranet durchsuchen" > 
				<div class="square-wrapper">
					<input type="submit" class="search-button-1" value="" type="submit">
				</div>
			</form>
		</div>
	</div>
		
<?php if (function_exists('nav_breadcrumb')) nav_breadcrumb(); ?>
</header>