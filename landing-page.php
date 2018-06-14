<?php
/**
 * Index Template
 * Template Name: Landing Page
 * This is the custom template.  created by AA date Oct/23/2017 It is used for landing page template landing-page.php 
 * 
 *
 * @package WordPress
 * @subpackage Directory
 */

?>
<style>/*
* multi-line comment
*/
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
    font-family: Signika; font-size: 80%; line-height: 1.2em; width: 100%; margin: 0; background: #ffffff;
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
	display: inline-block;
	text-align: left;
	float: left;
	font-family: Signika;


}
	#nav ul{}
		#nav ul li{
		   	margin-top: 15px;
		   	padding-right: 10px !important;
            padding-left: 10px !important;
            font-family: Signika;
            font-size: 20px;
			display: inline-block;
			height: 62px;
		}
		

	
/* content section not used for now */

#maincontent{
	margin: 30px 0;
	background: white;
	padding: 20px;
	clear: both;
}

/* footer section */

#homefooter{
	border-bottom: 1px #ccc solid;
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

ul li a{  }
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
    top: 20px; 
   left: 0; 
   position: absolute; 
   width: 100%; /* for IE 6 */
   /*background: rgb(0, 0, 0); /* fallback color */
   /*background: rgba(0, 0, 0, 0.7);*/
     
}
/* Text in top of image slider */
mainh2 { 
   position: absolute; 
   top: 100px; 
   left: -100; 
   width: 100%; 
   color: white; 
   font: 34px Signika;
   font-weight: 100;
   letter-spacing: 2px;
   padding: 100px; 
   font-family: Signika;

}
/* Text sub title in top of image slider */

subh3 { 
   position: absolute; 
   top: 150px; 
   left: -100; 
   width: 100%; 
   color: white; 
   font: 22px Signika;
   font-weight: 100;
   letter-spacing: 2px;
   font-family: Signika;
   padding: 100px; 

}


/* button text in top of image slider */


.buttonfirst {
    background-color: #F1c40f; /* Yellow */
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    width: 200px;
   top: 300px; 
   left: 28%; 
}
.buttonsecond {
    background-color: #F1c40f; /* Yellow */
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    width: 200px;
   top: 300px; 
   left: 54%; 
}

/* from css file theme for primary menu */

.btn-groupleft .button2left {
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
    top: 300px; 
   position: absolute; 

    
}


.btn-groupright .button2right {
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
    top: 300px; 
   position: absolute; 
   left: 30%;

    
}


</style>
<head>
    




<!-- your webpage info goes here -->

    <title>Planet Legal</title>
	
	<meta name="author" content="Planet Legal" />
	<meta name="description" content="front page" />

<!-- you should always add your stylesheet (css) in the head tag so that it starts loading before the page html is being displayed -->	
<!-- 	<link rel="stylesheet" href="style.css" type="text/css" />-->	



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
<!-- you should always add your stylesheet (css) in the head tag so that it starts loading before the page html is being displayed	
	<link rel="stylesheet" href="style.css" type="text/css" /> -->



</head>

		
		
		
		<div id="page"  >


<!-- webpage content goes here in the body -->
      <body style="width:100%; margin: 0; background-color: rgb(200,200,200,0.57) ">

	 
		
			
			<!--main image slider -->

		<!--	<div class="mainimage">-->
		             <!-- <center><img border="0" alt="slide Planetlegal" src="http://henry2.planet-legal.com/wp-content/uploads/revslider/comingsoon/background-1.png" width="100%" height="300"></center>
		             	<!--top image logo on image slider -->
	            <!-- <div class="topimage"><center><img border="0" alt="" src="http://henry2.planet-legal.com/wp-content/uploads/2017/10/Planet-Legal-Logo-transparent2.png" width="28%" ></center></div>   <div class="boxslider">-->
                      <!--http://henry2.planet-legal.com/wp-content/uploads/2017/10/center-bk.png grey bkround -->
        <div class="mainimage">
       
            	<!--top image logo on image slider -->
	            <center><img border="0" alt="slide Planetlegal" src="http://henry2.planet-legal.com/wp-content/uploads/revslider/comingsoon/background-1.png" width="100%" height="300"></center> 
            
                             		
            	<!--top text  on image slider -->

                 <center><mainh2>Opening up The Legal World</mainh2></center>
                 <center><subh3>Sub Title</subh3></center>
                
			    
        </div>

            	<!--add two buttons center lower to main slider -->

			  <div class="btn-group1"><center>
  <button class="button1">Find a Lawyer <img border="0" alt="search icon"src="http://henry2.planet-legal.com/wp-content/uploads/2017/10/search_filled1600.png" width="15" height="15" align="right"></button>
  <button class="button1">Find a Legal File <img border="0" alt="search icon"src="http://henry2.planet-legal.com/wp-content/uploads/2017/10/search_filled1600.png" width="15" height="15" align="right"></button>
 </center>
</div>

	<!--add 2 breaks line before the body content page -->
<br><br>


	<!--standard code from wordpress page.php to add body content -->
<section id="content" class="large-9 small-12 columns">
	<?php do_action( 'open_content' );
	do_action( 'templ_after_container_breadcrumb' ); ?>
	<div class="hfeed">
<?php apply_filters( 'tmpl_before-content', supreme_sidebar_before_content() ); // Loads the sidebar-before-content.
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		do_action( 'before_entry' );  ?>
<div id="post-<?php the_ID(); ?>" class="<?php supreme_entry_class(); ?>">
	<?php do_action( 'open_entry' );

 ?>
	<section class="entry-content">
		<?php
		the_post_thumbnail();
		do_action( 'open-post-content' );
		the_content( esc_html__( 'Continue reading <span class="meta-nav">&rarr;</span>', 'templatic' ) );
		wp_link_pages( array(
						'before' => '<p class="page-links">' . esc_html__( 'Pages:', 'templatic' ),
						'after' => '</p>',
					)
		);
		do_action( 'entry-edit-link' );
		do_action( 'close-post-content' );
		?>
	</section>
	<!-- .entry-content -->
	<?php do_action( 'close_entry' );  ?>
</div>
<!-- .hentry -->
<?php
do_action( 'after_entry' );
do_action( 'after_singular' );
do_action( 'before_comments' );

// If comments are open or we have at least one comment, load the comments template.
if ( supreme_get_settings( 'enable_comments_on_page' ) ) {
	comments_template( '/comments.php', true ); // Loads the comments.php template.
}
do_action( 'after_comments' ); // after_comments.
endwhile;
endif;
apply_filters( 'tmpl_after-content', supreme_sidebar_after_content() ); // Afetr-content-sidebar use remove filter to dont display it. ?>
</div>
<!-- .hfeed -->
<?php do_action( 'close_content' ); ?>
</section>
<!-- ?> #content -->


	<!--add 2 breaks line after the body content page -->
<br><br>	
	
	
		<!-- <div id="content">  	

		    
		    
	    
			
		</div> -->
		
</body>			
		<footer>
		<div id="homefooter">
		    
		   

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
</footer>



</html>

