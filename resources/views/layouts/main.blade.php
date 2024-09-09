<!DOCTYPE html>
<html>
<head>
	
	<!-- Meta -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<meta name="description" content="Jobie - Job Portal Mobile App Template ( Bootstrap 5 + PWA )" />
	<meta property="og:title" content="Jobie - Job Portal Mobile App Template ( Bootstrap 5 + PWA )" />
	<meta property="og:description" content="Jobie - Job Portal Mobile App Template ( Bootstrap 5 + PWA )" />
	<meta property="og:image" content="https://jobie.dexignzone.com/mobile-app/xhtml/social-image.png"/>
	
	<!-- Title -->
	<title>Jobie - Job Portal Mobile App Template ( Bootstrap 5 + PWA )</title>
	
	<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
	
	<!-- Css Style -->
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/scrollbar.css">
	<link rel="stylesheet" href="css/font-awesome.css">
	<link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="plugins/jstree/dist/themes/default/style.min.css">
	
	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900|Open+Sans:300,400,600,700,800|Raleway:100,200,300,400,500,600,700,800,900" rel="stylesheet">
	@include('user.layout.css') @yield('css')
</head>
<body data-spy="scroll" data-target=".nav-bar" data-offset="50">
<div class="wrapper" id="tableofcontent">

	<!-- Sidebar Holder -->
	<nav id="nevbarleft">
		<div class="side-nav full-height">
			<div class="sidebar-header">
				<div class="main-logo">
					<img class="icon" src="images/logo-white.png" alt="logo"/>
					<div class="logo-text">
						<span class="text">Jobie</span>
					</div>
				</div>
			</div>
			<div class="nav-bar">
				<ul class="list-unstyled content-scroll components navbar-nav nav" id="download-button">
					<li class="active"><a href="#introduction">Introduction</a></li>
					<li><a href="#installation">Installation</a></li>
					<li><a href="#folder_directories">Folder Directories</a></li>
                    <li><a href="#theme_features">Theme Features</a></li>
					<li><a href="#html_structure">HTML Structure</a></li>
					<li><a href="#plugins"> Credits</a></li>
					<li><a href="#pwa"> PWA Settings</a></li>
					<li><a href="#our_product">Our Products </a></li>
					<li><a href="#custom_work">Custom Work Requirements </a></li>
					<li><a href="#version_history">Version History</a></li>
				</ul>
			</div>
		</div>
	</nav>
	
	<!-- Page Content Holder -->
	<div id="content">
		
		<!-- Navber -->
		<nav class="navbar navbar-default top-nav-bar ">
			<div class="container-fluid">
				<div class="navbar-header">
				   <button type="button" id="sidebarCollapse" class="navbar-btn">
						<span></span>
						<span></span>
						<span></span>
					</button>
				</div>
				<a href="https://support.w3itexperts.com/" target="_blank" class="site-button support-button">Support</a>
				<a href="https://1.envato.market/0J3P5L" target="_blank" class="site-button support-button">Buy Now</a>
			</div>
		</nav>
		
		<!-- Banner -->
		<section class="app-brief slide-banner">
			<div class="container">
				<div class="section-header">
					<h2>Jobie - Job Portal Mobile App Template</h2>
					<div class="colored-line"></div>
					<div class="section-description">
						Jobie - Job Portal Mobile App Template ( Bootstrap + PWA )
					</div>
					<div class="colored-line"></div>
				</div>
			</div>
		</section>
		
		<!-- Introduction -->
		<section class="app-brief" id="introduction">
			<div class="container center-align">
				<h1>Jobie</h1>
				<h3>Jobie - Job Portal Mobile App Template ( Bootstrap + PWA )</h3>
				<p>This documentation is last updated on 30 June 2022.</p>
				<p>Thank you for purchasing this Mobile App template.</p>
				<p><strong>If you like this template, Please support us by rating this template with 5 stars </strong> </p>
			</div>
		</section>
		<hr/>
		
		<!-- Installation -->
		<section class="app-brief" id="installation">
			<div class="container left-align">
				<div class="section-header">
					<h2 class="dark-text title">Installation - </h2>
				</div>
				<div class="sass-compile">
					<h3 class="title">1.- Install Node.js</h3>
					<p>To compile Sass via the command line first, we need to install <a href="https://nodejs.org/en/" target="_blank">node.js</a>. The easiest way is downloading it from the official website nodejs.org open the package and follow the wizard.</p>
					
					<h3 class="title">2.- Initialize NPM</h3>
					<p>NPM is the Node Package Manager for JavaScript. NPM makes it easy to install and uninstall third party packages. To initialize a Sass project with NPM, open your terminal and CD (change directory) to your project folder.</p>
<pre class="brush: javascript; h-100">npm init
</pre>
<p>Once in the correct folder, run the command <code>npm init</code>. You will be prompted to answer several questions about the project, after which NPM will generate a <code>package.json</code> file in your folder.</p>


					<h3 class="title">3.- Install Node-Sass</h3>
					<p>Node-sass is an NPM package that compiles Sass to CSS (which it does very quickly too). To install node-sass run the following command in your terminal:  <code>npm install node-sass</code></p>
<pre class="brush: javascript; h-100">npm install node-sass
</pre>

					<h3 class="title">4.- Write Node-sass Command</h3>
					<p>Everything is ready to write a small script in order to compile Sass. Open the package.json file in a code editor. You will see something like this:</p>
					<p>In the scripts section add an <strong>scss command</strong></p>
					<img src="images/jsn.png" alt="">
<pre class="brush: javascript; h-100">"scripts": {
  "sass": "node-sass --watch scss/main.scss css/style.css"
},
</pre>
					
					<h3 class="title">5.- Run the Script</h3>
					<p>To execute our one-line script, we need to run the following command in the terminal: <code>npm run scss</code></p>
<pre class="brush: javascript; h-100">npm run sass
</pre>
				</div>
			</div>
		</section>
		<hr/>
		
		<!-- Folder Directories -->
		<section class="app-brief" id="folder_directories">
			<div class="container left-align">
				<div class="section-header ">
					<h2 class="dark-text title">Folder Directories - </h2>
				</div>
				<div id="dz_tree" class="tree-demo">
					<ul>
						<li data-jstree='{ "opened" : true }'>xhtml
							<ul>
                                <li data-jstree='{ "opened" : true }'>assets
                                    <ul>
                                        <li data-jstree='{ "selected" : false }'>css</li>
                                        <li data-jstree='{ "selected" : false }'>images</li>
                                        <li data-jstree='{ "selected" : false }'>js</li>
                                        <li data-jstree='{ "selected" : false }'>scss</li>
                                    </ul>    
                                </li>
                                <li data-jstree='{ "type" : "file" }'>app.js</li>
                                <li data-jstree='{ "type" : "file" }'>index.js</li>
                                <li data-jstree='{ "type" : "file" }'>manifest.json</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</section>
		<hr/>
		
        <!-- Theme Features -->
		<section class="app-brief" id="theme_features">
			<div class="container left-align">
				<div class="section-header ">
					<h2 class="dark-text title">Theme Features - </h2>
				</div>
			
				<h3 class="title">Dark Theme</h3>
				<pre class="brush: javascript; h-100">
&lt;body class="theme-dark"&gt;
</pre>
				<h3 class="title">Color Theme</h3>
				<p>So many color option available</p>
				<pre class="brush: javascript; h-100">
&lt;body data-theme-color="color-red"&gt;
</pre>
				<ul class="color-list">
					<li><div class="overlay-text color-red">Red</div></li>
					<li><div class="overlay-text color-green">Green</div></li>
					<li><div class="overlay-text color-blue">Blue</div></li>
					<li><div class="overlay-text color-pink">Pink</div></li>
					<li><div class="overlay-text color-yellow">Yellow</div></li>
					<li><div class="overlay-text color-orange">Orange</div></li>
					<li><div class="overlay-text color-purple">Purple</div></li>
					<li><div class="overlay-text color-deeppurple">Deeppurple</div></li>
					<li><div class="overlay-text color-lightblue">Lightblue</div></li>
					<li><div class="overlay-text color-teal">Teal</div></li>
					<li><div class="overlay-text color-lime">Lime</div></li>
					<li><div class="overlay-text color-deeporange">Deeporange</div></li>
				</ul>
			</div>
		</section>
		<hr/>
		
		
		<!-- Html Structure -->
		<section class="app-brief" id="html_structure">
			<div class="container left-align">
				<div class="section-header">
					<h2 class="dark-text title">HTML Structure - </h2>
				</div>
				<h3>Head Section</h3>
				<img src="images/screenshot/head-section.png" alt="Head Section"/>
				<h3>Sidenav</h3>
				<img src="images/screenshot/sidebar-nav.png" alt="Sidebar Nav"/>
				<div class="separator"></div>
				<h3>Footer Essentials</h3>
				<img src="images/screenshot/footer-script.png" alt="Footer Script"/>
			</div>
		</section>
		<hr/>
        <!-- Plugins Included -->
		<section class="app-brief" id="plugins">
			<div class="container left-align">
				<div class="section-header">
					<h2 class="dark-text title">Plugins included - </h2>
				</div>
				<ul class="list-files">
					<li>
						<div class="row">
							<div class="col-sm-4">
								<p>Bootstrap</p>
							</div>
							<div class="col-sm-8">
								<p><a href="https://getbootstrap.com/" target="_blank">https://getbootstrap.com/</a></p>
							</div>
						</div>
					</li>
					<li>
						<div class="row">
							<div class="col-sm-4">
								<p>font awesome</p>
							</div>
							<div class="col-sm-8">
								<p><a href="https://fontawesome.com/" target="_blank">https://fontawesome.com/</a></p>
							</div>
						</div>
					</li>
					<li>
						<div class="row">
							<div class="col-sm-4">
								<p>Google Fonts</p>
							</div>
							<div class="col-sm-8">
								<p><a href="https://fonts.google.com/" target="_blank">https://fonts.google.com/</a></p>
							</div>
						</div>
					</li>
                    <li>
						<div class="row">
							<div class="col-sm-4">
								<p>Imageuplodify</p>
							</div>
							<div class="col-sm-8">
								<p><a href="https://github.com/wpic/imageuplodify" target="_blank">https://github.com/wpic/imageuplodify</a></p>
							</div>
						</div>
					</li>
                    <li>
						<div class="row">
							<div class="col-sm-4">
								<p>Peity Chart</p>
							</div>
							<div class="col-sm-8">
								<p><a href="https://benpickles.github.io/peity/" target="_blank">https://benpickles.github.io/peity/</a></p>
							</div>
						</div>
					</li>
                    <li>
						<div class="row">
							<div class="col-sm-4">
								<p>Swiper</p>
							</div>
							<div class="col-sm-8">
								<p><a href="https://swiperjs.com/" target="_blank">https://swiperjs.com/</a></p>
							</div>
						</div>
					</li>
                    <li>
						<div class="row">
							<div class="col-sm-4">
								<p>Wow.js</p>
							</div>
							<div class="col-sm-8">
								<p><a href="https://wowjs.uk/" target="_blank">https://wowjs.uk/</a></p>
							</div>
						</div>
					</li>
                    <li>
						<div class="row">
							<div class="col-sm-4">
								<p>NouiSlider</p>
							</div>
							<div class="col-sm-8">
								<p><a href="https://refreshless.com/nouislider/" target="_blank">https://refreshless.com/nouislider/</a></p>
							</div>
						</div>
					</li>
                    <li>
						<div class="row">
							<div class="col-sm-4">
								<p>Wnumb</p>
							</div>
							<div class="col-sm-8">
								<p><a href="https://refreshless.com/wnumb/" target="_blank">https://refreshless.com/wnumb/</a></p>
							</div>
						</div>
					</li>
                    <li>
						<div class="row">
							<div class="col-sm-4">
								<p>Lightgallery</p>
							</div>
							<div class="col-sm-8">
								<p><a href="https://www.lightgalleryjs.com/demos/thumbnails/" target="_blank">https://www.lightgalleryjs.com/demos/thumbnails/</a></p>
							</div>
						</div>
					</li>
                    <li>
						<div class="row">
							<div class="col-sm-4">
								<p>Toastr</p>
							</div>
							<div class="col-sm-8">
								<p><a href="https://getbootstrap.com/docs/5.0/components/toasts/" target="_blank">https://getbootstrap.com/docs/5.0/components/toasts/</a></p>
							</div>
						</div>
					</li>
                    <li>
						<div class="row">
							<div class="col-sm-4">
								<p>LineAwesome</p>
							</div>
							<div class="col-sm-8">
								<p><a href="https://icons8.com/line-awesome" target="_blank">https://icons8.com/line-awesome</a></p>
							</div>
						</div>
					</li>
                    <li>
						<div class="row">
							<div class="col-sm-4">
								<p>Jstree</p>
							</div>
							<div class="col-sm-8">
								<p><a href="https://www.jstree.com/" target="_blank">https://www.jstree.com/</a></p>
							</div>
						</div>
					</li>
                    <li>
						<div class="row">
							<div class="col-sm-4">
								<p>Flatiocn</p>
							</div>
							<div class="col-sm-8">
								<p><a href="https://www.flaticon.com/" target="_blank">https://www.flaticon.com/</a></p>
							</div>
						</div>
					</li>
                    <li>
						<div class="row">
							<div class="col-sm-4">
								<p>Bootstrap Touchspin</p>
							</div>
							<div class="col-sm-8">
								<p><a href="https://www.virtuosoft.eu/code/bootstrap-touchspin/" target="_blank">https://www.virtuosoft.eu/code/bootstrap-touchspin/</a></p>
							</div>
						</div>
					</li>
                    <li>
						<div class="row">
							<div class="col-sm-4">
								<p>Bootstrap Tagsinput</p>
							</div>
							<div class="col-sm-8">
								<p><a href="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/examples/" target="_blank">https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/examples/</a></p>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</section>
		<hr/>
        
        
        <!-- PWA Setting -->
		<section class="app-brief" id="pwa">
            <div class="container left-align">
				<div class="section-header">
					<h2 class="dark-text title">PWA Settings - </h2>
				</div>
                
				<h3>Manifest file</h3>
                <p>You will find this file in main folder where you can change the configration. It describes the name of the app, the start URL, icons, and all of the other details necessary to transform the website into an app-like format.</p>
                <img src="images/screenshot/manifest.png" alt="Head Section"/>
				
				<h3>Secure contexts (HTTPS)</h3>
				<p>
                    The web application must be served over a secure network. Being a secure site is not only a best practice, but it also establishes your web application as a trusted site especially if users need to make secure transactions. Most of the features related to a PWA such as geolocation and even service workers are available only once the app has been loaded using HTTPS.
                </p>
				
                <div class="separator"></div>
				
                <h3>Service workers</h3>
                <p>A service worker is a script that allows intercepting and control of how a web browser handles its network requests and asset caching. Here you can attached the css and js files.</p>
				<img src="images/screenshot/service.png" alt="Footer Script"/>
			</div>
        </section>
		<hr/>
        
			
		<!-- Our Products -->
		<section class="app-brief" id="our_product">
			<div class="container left-align">
				<div class="section-header">
					<h2 class="dark-text title">Our Products - </h2>
				</div>
				<div class="row other-theme">
					<div class="col-md-4 col-sm-6 m-b30">
						<div class="product-port-bx">
							<a target="_blank" href="https://themeforest.net/item/karier-job-portal-mobile-app-framework7-pwa-template/32229534">
								<img src="images/product/karier.png" alt=""/>
							</a>
							<div class="product-info">
								<h4 class="title">
									<a target="_blank" href="https://themeforest.net/item/karier-job-portal-mobile-app-framework7-pwa-template/32229534">
										Karier - Job Portal Mobile App Framework7 PWA Template
									</a>
								</h4>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 m-b30">
						<div class="product-port-bx">
							<a target="_blank" href="https://themeforest.net/item/biji-coffee-shop-mobile-app-framework7-template/32065257">
								<img src="images/product/biji.png" alt=""/>
							</a>
							<div class="product-info">
								<h4 class="title">
									<a target="_blank" href="https://themeforest.net/item/biji-coffee-shop-mobile-app-framework7-template/32065257">
										Biji - Coffee Shop Framework 7 Mobile App
									</a>
								</h4>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 m-b30">
						<div class="product-port-bx">
							<a target="_blank" href="https://themeforest.net/item/sayur-food-delivery-framework-7-mobile-app/31789387">
								<img src="images/product/sayur.png" alt=""/>
							</a>
							<div class="product-info">
								<h4 class="title">
									<a target="_blank" href="https://themeforest.net/item/sayur-food-delivery-framework-7-mobile-app/31789387">
										Sayur - Food Delivery Framework 7 Mobile App
									</a>
								</h4>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 m-b30">
						<div class="product-port-bx">
							<a target="_blank" href="https://themeforest.net/item/ajari-elearning-mobile-app-template-framework-7-pwa-/31647615">
								<img src="images/product/ajari.png" alt=""/>
							</a>
							<div class="product-info">
								<h4 class="title">
									<a target="_blank" href="https://themeforest.net/item/ajari-elearning-mobile-app-template-framework-7-pwa-/31647615">
										Ajari - E-learning Mobile App Template ( Framework 7 + PWA )
									</a>
								</h4>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 m-b30">
						<div class="product-port-bx">
							<a target="_blank" href="https://1.envato.market/P00OWY">
								<img src="images/product/kede.png" alt=""/>
							</a>
							<div class="product-info">
								<h4 class="title">
									<a target="_blank" href="https://1.envato.market/P00OWY">
										Kede - Grocery Mobile App Template ( Framework 7 + PWA )
									</a>
								</h4>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 m-b30">
						<div class="product-port-bx">
							<a target="_blank" href="https://1.envato.market/gyGa9">
								<img src="images/product/gawee.png" alt=""/>
							</a>
							<div class="product-info">
								<h4 class="title">
									<a target="_blank" href="https://1.envato.market/gyGa9">
										Gawee - ( Framework 7 + PWA ) Mobile HTML Template
									</a>
								</h4>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 m-b30">
						<div class="product-port-bx">
							<a target="_blank" href="https://1.envato.market/0zjyE">
								<img src="images/product/wp-beglide.png" alt=""/>
							</a>
							<div class="product-info">
								<h4 class="title">
									<a target="_blank" href="https://1.envato.market/0zjyE">
										BeGlide: Corporate Business Consultant Agency WordPress Theme 
									</a>
								</h4>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 m-b30">
						<div class="product-port-bx">
							<a target="_blank" href="https://1.envato.market/NE0QP">
								<img src="images/product/wp-bheem.png" alt=""/>
							</a>
							<div class="product-info">
								<h4 class="title">
									<a target="_blank" href="https://1.envato.market/NE0QP">
										Bheem : Construction WordPress Theme RTL Ready 
									</a>
								</h4>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 m-b30">
						<div class="product-port-bx">
							<a target="_blank" href="https://1.envato.market/WEdAZ">
								<img src="images/product/wp-beautyzone.png" alt=""/>
							</a>
							<div class="product-info">
								<h4 class="title">
									<a target="_blank" href="https://1.envato.market/WEdAZ">
										BeautyZone: Beauty Spa Salon WordPress Theme
									</a>
								</h4>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 m-b30">
						<div class="product-port-bx">
							<a target="_blank" href="https://1.envato.market/6MAKK">
								<img src="images/product/wp-bucklin.png" alt=""/>
							</a>
							<div class="product-info">
								<h4 class="title">
									<a target="_blank" href=" ">
										Bucklin - Creative Personal Blog WordPress Theme 
									</a>
								</h4>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 m-b30">
						<div class="product-port-bx">
							<a target="_blank" href="https://1.envato.market/1d1Nm">
								<img src="images/product/industry.png" alt=""/>
							</a>
							<div class="product-info">
								<h4 class="title">
									<a target="_blank" href="https://1.envato.market/1d1Nm">
										Industry - Factory & Industrial HTML Template
									</a>
								</h4>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 m-b30">
						<div class="product-port-bx">
							<a target="_blank" href="https://1.envato.market/oJLNY">
								<img src="images/product/archia.png" alt=""/>
							</a>
							<div class="product-info">
								<h4 class="title">
									<a target="_blank" href="https://1.envato.market/oJLNY">
										Archia - Architecture and Interior Design RTL Ready Template
									</a>
								</h4>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 m-b30">
						<div class="product-port-bx">
							<a target="_blank" href="https://1.envato.market/XEnGb">
								<img src="images/product/agency.png" alt=""/>
							</a>
							<div class="product-info">
								<h4 class="title">
									<a target="_blank" href="https://1.envato.market/XEnGb">
										Agency | Creative Multipurpose Bootstrap 4 HTML Template
									</a>
								</h4>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 m-b30">
						<div class="product-port-bx">
							<a target="_blank" href="https://1.envato.market/3zZ9y">
								<img src="images/product/constructzilla.png" alt=""/>
							</a>
							<div class="product-info">
								<h4 class="title">
									<a target="_blank" href="https://1.envato.market/3zZ9y">
										ConstructZilla : Construction, Renovation & Building HTML Template With RTL Ready
									</a>
								</h4>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 m-b30">
						<div class="product-port-bx">
							<a target="_blank" href="https://1.envato.market/ZEKLg">
								<img src="images/product/cargozone.png" alt=""/>
							</a>
							<div class="product-info">
								<h4 class="title">
									<a target="_blank" href="https://1.envato.market/ZEKLg">
										CargoZone - Transport, Cargo, Logistics & Business Multipurpose HTML Template
									</a>
								</h4>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		
		<!-- Need Help -->
		<section class="app-brief" id="custom_work" style="background-image: url(images/bg1.png); background-position: left center;">
			<div class="container left-align">
				<div class="col-md-12 text-center custom-info">
					<h2 class="m-t0">Do You Need Help To Customization</h2>
					<h3 class="text-primary">After Purchase A Template...</h3>
					<h4>You Will Start Customizing According Your Requirement<br/> <span class="text-primary">BUT</span> What If You Don't Know</h4>
					<h3 class="text-black">SOLUTION IS <span class="text-primary"><u>HIRE DexignZone</u></span></h3>
					<div class="hire">
						<h4><span class="text-black">Hire Same Team For </span> <span class="text-primary">Quality Customization</span></h4>
						<ul>
							<li>We Will Customize Template According To Your Requirement</li>
							<li>We Will Upload On Server And Make Sure Your Website is Live</li>
						</ul>
						<div class="gmail-box">
							<a href="skype:rahulxarma?chat" class="gmail"><i class="fa fa-skype"></i>rahulxarma</a>
							<a target="_blank" href="mailto:dexignzones@gmail.com" class="gmail"><i class="fa fa-envelope"></i> dexignzones@gmail.com</a>
						</div>
					</div>
				</div>
			</div>
		</section>
		
		<!-- Change Log -->
		<section class="app-brief change-log" id="version_history">
			<div class="container left-align">
				<div class="section-header">
					<h2 class="dark-text title">Version History - <small class="topbutton"><a href="#tableofcontent">#back to top</a></small></h2>
				</div>
				
				<h3>18 May 2023</h3>
                <ul>
                    <li>Added: Two level side menu</li>
                </ul>
				
                <h3>28 April 2023</h3>
                <ul>
                    <li>Added : Multiple Color Option</li>
                    <li>Added : Dark Layout</li>
                    <li>Added : 20+ Widget</li>
                    <li>Improvement : HTML Improvement</li>
                    <li>Improvement : JS Improvement</li>
                    <li>Improvement : SCSS Improvement</li>
                </ul>
				
				<h3>16 June 2022</h3>
				<ul>
					<li>New - Created & Upload Jobie</li>
				</ul>
			</div>
		</section>
			
		<!-- Footer -->
		<footer class="app-brief grey-bg">
			<div class="container">
				<p class="copyright">
					© 2023 <a href="https://dexignzone.com/" target="_blank"><strong>DexignZone</strong></a>. All Rights Reserved
				</p>
			</div>
		</footer>
	
	</div>
</div>
	
<!-- JavaScript -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/jquery.localScroll.min.js"></script>
<script src="js/load.js"></script>
<script src="js/custom.js"></script>
<script src="js/scrollbar.min.js"></script>
<script src="plugins/jstree/dist/jstree.min.js"></script>
<script>
// prettyPhoto
jQuery(document).ready(function(){
	jQuery('.dzClickload').click(function(){
		jQuery('.dzClickload').removeClass('active');
		jQuery(this).addClass('active');
	});
	
	jQuery(".content-scroll").mCustomScrollbar({
		setWidth:false,
		setHeight:false,
		axis:"y"
	});	
		
	$(".full-height").css("height", $(window).height());
	
	$("#dz_tree, #dz_tree_rtl").jstree({
		"core": {
			"themes": {
				"responsive": false
			}
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			},
			"file": {
				"icon": "fa fa-file-text"
			}
		},
		"plugins": ["types"]
	});
	
	// Add smooth scrolling to all links
	$(".navbar-nav a").on('click', function(event) {
		// Make sure this.hash has a value before overriding default behavior
		if (this.hash !== "") {
			// Prevent default anchor click behavior
			event.preventDefault();

			// Store hash
			var hash = this.hash;

			// Using jQuery's animate() method to add smooth page scroll
			// The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
			$('html, body').animate({
				scrollTop: $(hash).offset().top
			});
		} // End if
	});
});
</script>
</body>
</html>