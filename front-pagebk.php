<?php
/**
 * Index Template
 * Template Name: Frontpage
 * This is the custom template.  created by AA date Oct/20/2017 It is used for front page template front-page.php 
 * 
 *
 * @package WordPress
 * @subpackage Directory
 */

?>
<style>/*
* multi-line comment
*/


@media only screen { 
	.column, .columns { position: relative; padding-left: 0.9375rem; padding-right: 0.9375rem; float: left; }
	.xsmall-1 { width: 8.33333%; }
	.xsmall-2 { width: 16.66667%; }
	.xsmall-3 { width: 25%; }
	.xsmall-4 { width: 33.33333%; }
	.xsmall-5 { width: 41.66667%; }
	.xsmall-6 { width: 50%; }
	.xsmall-7 { width: 58.33333%; }
	.xsmall-8 { width: 66.66667%; }
	.xsmall-9 { width: 75%; }
	.xsmall-10 { width: 83.33333%; }
	.xsmall-11 { width: 91.66667%; }
	.xsmall-12 { width: 100%; }
	}
	@media only screen and (min-width: 480px) { 
	.column, .columns { position: relative; padding-left: 0.9375rem; padding-right: 0.9375rem; float: left; }
	.small-1 { width: 8.33333%; }
	.small-2 { width: 16.66667%; }
	.small-3 { width: 25%; }
	.small-4 { width: 33.33333%; }
	.small-5 { width: 41.66667%; }
	.small-6 { width: 50%; }
	.small-7 { width: 58.33333%; }
	.small-8 { width: 66.66667%; }
	.small-9 { width: 75%; }
	.small-10 { width: 83.33333%; }
	.small-11 { width: 91.66667%; }
	.small-12 { width: 100%; }
	}
	@media only screen and (min-width: 768px) { 
	.column, .columns { position: relative; padding-left: 0.9375rem; padding-right: 0.9375rem; float: left; }
	.medium-1 { width: 8.33333%; }
	.medium-2 { width: 16.66667%; }
	.medium-3 { width: 25%; }
	.medium-4 { width: 33.33333%; }
	.medium-5 { width: 41.66667%; }
	.medium-6 { width: 50%; }
	.medium-7 { width: 58.33333%; }
	.medium-8 { width: 66.66667%; }
	.medium-9 { width: 75%; }
	.medium-10 { width: 83.33333%; }
	.medium-11 { width: 91.66667%; }
	.medium-12 { width: 100%; }
	}
	@media only screen and (min-width: 1024px) {
	.column, .columns { position: relative; padding-left: 0.9375rem; padding-right: 0.9375rem; float: left; }
	.large-1 { width: 8.33333%; }
	.large-2 { width: 16.66667%; }
	.large-3 { width: 20.0%; }
	.large-4 { width: 25%; }
	.large-5 { width: 41.66667%; }
	.large-6 { width: 50%; }
	.large-7 { width: 58.33333%; }
	.large-8 { width: 66.66667%; }
	.large-9 { width: 72%; }
	.large-10 { width: 83.33333%; }
	.large-11 { width: 91.66667%; }
	.large-12 { width: 100%; }
	.large-offset-2 { margin-left: 16.66667% !important; }
	.sidebar.large-3 { margin-left: 1.2%; }
	.sidebar .large-4 { width: 100%; }
	}

	@media only screen and (min-width: 1201px) {
		.full-width-map #site-title a, .full-width-map .menu-container{padding: 5px;}
		.full-width-map .location_fld_wrapper{margin-top:6px;}

	}		
	
	
	@media only screen and (max-width: 767px) {
		.full_map_template .map_sidebar{display: block;}
		.mobile-view .directory_manager_tab ul.view_mode li a,
		.mobile-view a#mobile_listing_popup_link{display: block !important;}
	}

	/* 1.2 Animations */
	a{
		-webkit-transition:all .3s ease 0s;
		-moz-transition:all .3s ease 0s;
		-ms-transition:all .3s ease 0s;
		-o-transition:all .3s ease 0s;
		transition:all .3s ease 0s;
	}
	.tevolution-directory #searchform .ui-slider-horizontal .ui-slider-handle{
		-webkit-transition:none;
		-moz-transition:none;
		-ms-transition:none;
		-o-transition:none;
		transition:none;
	}
	#loop_listing_archive .post .listing_img .featured_tag,#loop_listing_taxonomy .post .listing_img .featured_tag,#tmpl-search-results.list .hentry .listing_img .featured_tag,.hfeed .post .listing_img .featured_tag,.user #content .author_cont div[id*=post] .listing_img .featured_tag,.user #content .hentry .listing_img .featured_tag {
		z-index:2
	}
	.primary_menu_wrapper { position:relative; background:#0165BD;}
	/*.full-width-map .primary_menu_wrapper{padding: 10px;}*/
	.full-width-map .menu-container .mega-menu, .full-width-map .menu-container, .full-width-map #nav-secondary{padding: 0; float: right; width: auto !important;}
	
	.full-width-map .menu-container .nav_bg{float: right;}
	.menu li ul.sub-menu ul.sub-menu {
		left: 500px;
	}
	.menu li ul.sub-menu ul.sub-menu ul.sub-menu {
		left: 1000px;
	}
	
	.menu li ul,.nav_bg .widget-nav-menu li ul {
		left:0px;
		top: 60px;
		list-style:none;
		min-width:240px;
		padding-left:0;
		/*visibility:hidden;*/
		z-index: -1;
	}
	@media screen and (min-width: 1200px){
		.menu li ul,.nav_bg .widget-nav-menu li ul{
			position: absolute;
		}
	}


@import url(http://fonts.googleapis.com/css?family=Signika:300,400,500,600,700,800,900);
p{ line-height: 1em; }
h1, h2, h3, h4{
    color: orange;
	font-weight: normal;
	line-height: 1.1em;
	margin: 0 0 .5em 0;
}
h1{ font-size: 1.7em; }
h2{ font-size: 1.5em; }
a{
	color: black;
	text-decoration: none;
}
	a:hover,
	a:active{ text-decoration: underline; }

/* you can structure your code's white space so that it is as readable for when you come back in the future or for other people to read and edit quickly */

#body{
    font-family: Signika; font-size: 80%; line-height: 1.2em; width: 100%; margin: 0; background: #C8C8C8; opacity: 57%;
}
/* you can put your code all in one line like above */
#page{ margin: 20px; }

/* or on different lines like below */
#mainlogo{
	width: 13%;
	;
	/*font-family: @import url(http://fonts.googleapis.com/css?family=Signika:300,400,500,600,700,800,900); */
	display: inline-block;
	text-align: left;
	float: left;
}
/* Navigation */
#nav{
	width: 60%;

	text-align: left;
	float: left;
	font-family: Signika;


}
	#nav ul{}
		#nav ul li{
		   	margin-top: 15px;
		   	padding-right: 10px; 
            padding-left: 10px;
            font-family: Signika;
            font-size: 20px;
			display: inline-block;
			height: 62px;
		}
		

/* content section not used for now */

#maincontent{
width:100%; margin: 0; background-color: #C8C8C8; 
	clear: both;
}

/* footer section */

#homefooter{
	margin-bottom: 100px;
	background: grey;
	height: 100px;
    margin: 0px 0px 0px 0px;
    font-family: Signika;
    font-weight: bold ;
    font-size: 11px;
    background-color: rgba(102, 102, 102, 0.57);
}
	#homefooter p{
		text-align: right;
		text-transform: uppercase;
		font-size: 80%;
		color: white;
		font-family: Signika;

	}
	.homefooter_container {
    width: 960px;
    margin: 0 auto;
}

/* footer sections 4 columns divided equally */

.part1, .part2, .part3, .part4 {
   display:inline-block;
   position:relative;
   	float: left;
   	font-family: Signika;
	color: white;
   width:calc(100% / 4);
}

/* multiple styles seperated by a , */
#content,
/* ul li a{ box-shadow: 0px 1px 1px #999; } */

.btn-group1 .button1 {
    background-color: #f1c40f; /* Yellow orange*/
    border: 1px solid green;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 18px;
    cursor: pointer;
    width: 320px;
   /* display: block; */
    font-family: Signika;
    box-shadow: 0 0 5px 0px black;
}

.btn-group1 .button1:not(:last-child) {
   /*  border-bottom: none; Prevent double borders */
}

.btn-group1 .button1:hover {
    background-color: #d4ca8c;
}

/* Image slider  */
.mainimage { 
   position: relative; 
   width: 100%; /* for IE 6 */
      margin-bottom: 40px;

}
/* logo image in top of image slider  */

.topimage { 
    top: 100px; 
   left: 0; 
   position: absolute; 
   width: 100%; /* for IE 6 */
   /*background: rgb(0, 0, 0); /* fallback color */
   /*background: rgba(0, 0, 0, 0.7);*/
     
}
/* Text in top of image slider */
mainh2 { 
   position: absolute; 
   top: 200px; 
   left: -100; 
   width: 100%; 
   color: white; 
   font: 32px Signika;
   font-weight: 100;
   letter-spacing: 2px;
   padding: 100px; 
   font-family: Signika;

}
 findlawyer findreferfile findclient {
     text-transform: lowercase;
 }

</style>
<head>
    



<!-- your webpage info goes here -->

    <title>Planet Legal</title>
	
	<meta name="author" content="Planet Legal" />
	<meta name="description" content="front page" />

<!-- you should always add your stylesheet (css) in the head tag so that it starts loading before the page html is being displayed	
	<link rel="stylesheet" href="style.css" type="text/css" /> -->
<?php	wp_head();	

	if ( file_exists( get_template_directory() . '/custom.css' ) && file_get_contents( get_template_directory() . '/custom.css' ) != '' ) {
		echo '<link href="' . get_template_directory_uri() . '/custom.css" rel="stylesheet" type="text/css" />';
	}
	do_action( 'supreme_enqueue_script' );
	?>


</head>
<!-- top menu -->
<div id="container" class="container-wrap">
	<header class="header_container clearfix">

		<div class="primary_menu_wrapper clearfix">
			<div class="primary_menu_wrap row">
				<?php
				do_action( 'before_desk_menu_primary' );
				supreme_primary_navigation();
				do_action( 'after_desk_menu_primary' ); ?>
			</div>
		</div>
		<?php do_action( 'before_header' );
		$header_image = get_header_image();
		if ( function_exists( 'get_header_image_location' ) ) {
			$header_image_location = get_header_image_location(); // 0 = before secondary navigation menu, 1 = after secondary navigation menu
		} else {
			$header_image_location = 1;
		} ?>
</div>		
		
		
		
		
<!-- webpage content goes here in the body -->
      <body style="width:100%; margin: 0; background-color: rgb(200,200,200,0.57) ">

	<div id="page">
	    <!-- logo hml -->

		<div id="mainlogo">
		    
		    <a href="http://www.henry2.planet-legal.com"><img border="0" alt="Planetlegal logo" src="http://henry2.planet-legal.com/wp-content/uploads/2017/10/Planet-Legal-Logo-Oct23B-1.png" width="90%" >
<!--  original   http://henry2.planet-legal.com/wp-content/uploads/2017/10/Planet-Legal-Logo-Oct20-trans.png   http://henry2.planet-legal.com/wp-content/uploads/2017/10/Planet-Legal-Logo-Oct20-trans2.png  -->
</a>

		</div>
		
		<!-- Navigation html -->

		<div id="nav">
			<ul>
				<li><a href="#/home.html">Lawyers</a></li>
				<li><a href="#/about.html">Clients</a></li>
				<li><a href="#/contact.html">Legal Resources</a></li>
			</ul>	
			</div>
			
			
			<!--main image slider -->

			<div class="mainimage">

              <center><img border="0" alt="slide Planetlegal" src="http://henry2.planet-legal.com/wp-content/uploads/revslider/comingsoon/background-1.png" width="100%" height="300"></center>
            
            	<!--top image logo on image slider -->

	             <div class="topimage"><center><img border="0" alt="" src="http://henry2.planet-legal.com/wp-content/uploads/2017/10/Planet-Legal-Logo-Oct23B-1.png" width="28%" ></center></div>
                             		
            	<!--top text  on image slider -->

                 <center><mainh2>Opening up The Legal World</mainh2></center>

            </div>



	
			  <div class="btn-group1"><center>
  <button class="button1">Find a Lawyer <img border="0" alt="search icon"src="http://henry2.planet-legal.com/wp-content/uploads/2017/10/search_filled1600.png" width="15" height="15" align="right"></button>
  <p class="findlawyer">Anonymously, fast, stress-free.</p>
  <button class="button1">Find Clients <img border="0" alt="search icon"src="http://henry2.planet-legal.com/wp-content/uploads/2017/10/search_filled1600.png" width="15" height="15" align="right"></button>
    <p class="findclient">Grow your practice.</p>
  <button class="button1">File Referral/Share <img border="0" alt="search icon"src="http://henry2.planet-legal.com/wp-content/uploads/2017/10/search_filled1600.png" width="15" height="15" align="right"></button>
    <p class="findreferfile">When you can’t do it yourself .</p>
    </center>
</div>
	<br><br><br><br>

	
	
	
		<!-- <div id="content">  	

			</div> -->
		    
		    
</body>
		    

		<!--div id="homefooter"-->
		    
		   

            <!-- BEGIN .part1 -->
            <div class="part1">
<center>part1</center>
            </div>
             <!-- BEGIN .part2 -->
            <div class="part2">
<center>part2</center>
            </div>
             <!-- BEGIN .part3 -->
            <div class="part3">
<center>part3</center>
            </div>
             <!-- BEGIN .part4 -->
            <div class="part4">
<center>part4</center>
            </div>
		</div>
	</div>	


<?php

get_footer();
?>
</html>