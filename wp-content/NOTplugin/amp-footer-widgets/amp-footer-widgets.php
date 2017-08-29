<?php

/*
 * Plugin Name: AMP footer widgets
 * Version: 1.0.6.1
 * Plugin URI: https://jaaadesign.nl/en/blog/amp-footer-widgets/
 * Description: Extends the excellent AMP plugin (Automattic) by adding 3 widget areas in the footer of your website. Additionally the plugin options page allows to add a fully marked up (Microdata) credits footer.
 * Author: Nick van de Veerdonk
 * Author URI: https://jaaadesign.nl/
 */
 
 
 
// create custom plugin settings menu

add_action('admin_menu', 'amp_footer_widgets_menu');

function amp_footer_widgets_menu() {

	//create new top-level menu

	add_menu_page('AMP Footer Widgets settings', 'AMP Footer Widgets', 'administrator', __FILE__, 'amp_footer_widgets__options_page' , plugins_url('/images/icon.png', __FILE__), 104 );



	//call register settings function

	add_action( 'admin_init', 'register_amp_footer_widgets_settings' );

}


function register_amp_footer_widgets_settings() {

	//register our settings
	register_setting( 'amp_footer_settings_group', 'footer_copy_on_amp' );
	register_setting( 'amp_footer_settings_group', 'org_schema_jd_amp' );
	register_setting( 'amp_footer_settings_group', 'org_name_schema_amp' );
	register_setting( 'amp_footer_settings_group', 'org_social_schema_amp' );	
	
	register_setting( 'amp_footer_settings_group', 'creator_on_amp' );
	register_setting( 'amp_footer_settings_group', 'add_creator_amp_name' );	
	register_setting( 'amp_footer_settings_group', 'add_creator_amp_social' );	
	register_setting( 'amp_footer_settings_group', 'add_creator_amp_url' );	
	
	register_setting( 'amp_footer_settings_group', 'hide_foot_nat_amp' );		
	

}



function amp_footer_widgets__options_page() {

?>

<div class="wrap">

<h1><span class="dashicons dashicons-admin-settings" style="font-size: 35px;
    color: #d0263e;
    padding-right: 20px;"></span>AMP footer widgets</h1>

 
	
	
<table width="100%">

<br /><br />
	<tbody><tr>
    	<td valign="top">
            <table class="wp-list-table widefat fixed ">
                <thead>
                    <tr>
                        <th>AMP footer widgets settings</th>
                    </tr>
                </thead>
                <tbody>

                <tr>

                    <td>

                     

                        <form method="post" action="options.php">

    <?php settings_fields( 'amp_footer_settings_group' ); ?>

    <?php do_settings_sections( 'amp_footer_settings_group' ); ?>
	
	

	
	 

    <table class="form-table">

	 <br />	 

		<blockquote class="mindyou">
<p style="    float: left;
    max-width: 678px;
    margin-top: -5px;">Drag the buttons into your bookmarks bar above, visit any page and click the bookmarklet to test it: results will open in a new window.</p>
<div class="buttcopy"><a href="javascript:void(window.open(%27https://developers.google.com/webmasters/structured-data/testing-tool/?url=%27+window.location.href,%27_blank%27));" title="Drag me!" target="_blank">Structured Data Testing</a></div><div class="buttcopy"><a href="javascript:void(window.open(%27https://validator.ampproject.org/#url=%27+window.location.href,%27_blank%27));" title="Drag me!" target="_blank">AMP validation</a></div>
</blockquote> 
	<br />
	<br />
	 
<p>On activation of the plugin 3 widget areas will be available for your AMP content. Ticking the <strong>'Add copyright footer'</strong> checkbox below will add an extra footer section with Microdata marked up copyright information.</p>	

	 	<tr valign="top">
 
        <th scope="row">Add copyright footer</th>
		<td><input name="footer_copy_on_amp" type="checkbox" value="1" <?php checked( '1', get_option( 'footer_copy_on_amp' ) ); ?> />
		<p>Activates all settings below.</p></td>	       
		</tr> 	
 		
		<tr valign="top">
		 <th scope="row">Schema</th>
        <td><input type="text" name="org_schema_jd_amp" id="mySchemadef" value="<?php echo esc_attr( get_option('org_schema_jd_amp') ); ?>" />
		<br /> 
		<p style="float: left;" >In most cases the general '<strong>Organization</strong>' will do (no apostrophes), </p> <div class="extendd"><label for="toggle-1">click here for more options</label>
<input type="checkbox" id="toggle-1">
<div class="tton">
<strong>National/international:</strong>
<br />

Organization | 
Airline | 
Corporation | 
EducationalOrganization | 
GovernmentOrganization | 
NGO | 
PerformingGroup | 
SportsOrganization 
<br />

<strong>Local:</strong>
<br />

LocalBusiness | 
AnimalShelter | 
AutomotiveBusiness | 
ChildCare | 
Dentist | 
DryCleaningOrLaundry | 
EmergencyService | 
EmploymentAgency | 
EntertainmentBusiness | 
FinancialService | 
FoodEstablishment | 
GovernmentOffice | 
HealthAndBeautyBusiness | 
HomeAndConstructionBusiness | 
InternetCafe | 
LegalService | 
Library | 
LodgingBusiness | 
MedicalOrganization | 
ProfessionalService | 
RadioStation | 
RealEstateAgent | 
RecyclingCenter | 
SelfStorage | 
ShoppingCenter | 
SportsActivityLocation | 
Store | 
TelevisionStation | 
TouristInformationCenter | 
TravelAgency
<br />

<strong>Non-commercial:</strong>
<br />

Person
</div></div> </td>	
		</tr>		

        <tr valign="top">
        <th scope="row">Organization name</th>
        <td><input type="text" name="org_name_schema_amp" value="<?php echo esc_attr( get_option('org_name_schema_amp') ); ?>" />
		<br />
		<p>Name of the organization, etc.</p></td>
		</tr>
		
       <tr valign="top">
        <th scope="row">Organization social account (sameAs)</th>
        <td><input type="text" name="org_social_schema_amp" value="<?php echo esc_attr( get_option('org_social_schema_amp') ); ?>" />
		<br />
		<p>Paste in the URL of the social account.</p></td>
 		</tr>
 		
			 
		
		 
		<tr valign="top">
        <th scope="row">Add creator (Person)?</th>
		<td><input name="creator_on_amp" type="checkbox" value="1" <?php checked( '1', get_option( 'creator_on_amp' ) ); ?> />
		<p>C'mon, give yourself some credit!</p></td>	       
		</tr>	
 
	


		<tr valign="top">
        <th scope="row">Creator name</th>
        <td><input type="text" name="add_creator_amp_name" value="<?php echo esc_attr( get_option('add_creator_amp_name') ); ?>" />
		<br />
		</td>
		</tr>
		
		<tr valign="top">
        <th scope="row">Creator URL</th>
        <td><input type="text" name="add_creator_amp_url" value="<?php echo esc_attr( get_option('add_creator_amp_url') ); ?>" />
		<br />
		<p>About page, dedicated website, Author page etc.</p></td>		
		</tr>		
		
		<tr valign="top">
        <th scope="row">Social account Creator (sameAs)</th>
        <td><input type="text" name="add_creator_amp_social" value="<?php echo esc_attr( get_option('add_creator_amp_social') ); ?>" />
		<br />
		<p>Paste in the URL of the social account.</p></td>		
		</tr>
		
	 	<tr valign="top">
        <th scope="row">Keep the native AMP footer</th>
		<td><input name="hide_foot_nat_amp" type="checkbox" value="1" <?php checked( '1', get_option( 'hide_foot_nat_amp' ) ); ?> />
		<p>If unchecked we use dirty dirty CSS to hide it</p></td>	      
		</tr>		
		

    </table>
 <input type="reset" onclick="myFunction();" value="Reset">
    <br />

    <?php submit_button(); ?>



		

	

</form>

                    	 

						 

                        <br>                        

                        <span style="font-size: 1.4rem;color: #ffe200;">★★★★★</span> If you like this plugin please consider <a href="https://jaaadesign.nl/en/blog/amp-footer-widgets/"target="_blank">rating it</a>, thank you!

                        <br><br>

                   	</td>

                    

                </tr>

                </tbody>

            </table>

            <br>

		</td>

		

		

        <td width="15">&nbsp;</td>

        <td width="250" valign="top">

		

		            <table class="wp-list-table widefat fixed bookmarks">

            	

                <tbody>

                <tr>

                	<td style="padding:4px;">

                    	

 

						<a href="https://wordpress.org/plugins/amp-footer-widgets/" title="Visit Wordpress plugin page" target="_blank">

						<img width="240" alt="AMP footer Widgets" style="border: 1px solid #b3b3b3;
    padding-bottom: 6px;
    border-radius: 1px;
    margin-bottom: -4px;" src="<?php echo plugin_dir_url( __FILE__ ) . 'images/admin-logo.jpg'; ?>" class="aligncenter"></a>

                    </td>

                </tr>

                </tbody>

            </table>

            <br>

		

        	            <table class="wp-list-table widefat fixed bookmarks">

            	<thead>

                <tr>



				

					<th>Our other plugins</th>

                </tr>

                </thead>

               



                <tbody>

                <tr>

                	<td> 

                    <ul class="uaf_list">
<li><a href="https://nl.wordpress.org/plugins/amp-social-share/" target="_blank">AMP Social Share</a></li>
                    	<li><a href="https://wordpress.org/plugins/amp-html-sitemap/" target="_blank">AMP HTML Sitemap</a></li>

                        <li><a href="https://wordpress.org/plugins/amp-recent-posts/" target="_blank">AMP Recent Posts</a></li>

                        <li><a href="https://wordpress.org/plugins/amp-related-posts/" target="_blank">AMP Related Posts</a></li>

                        <li><a href="https://wordpress.org/plugins/amp-recent-posts-widget/" target="_blank">AMP Recent Posts widget</a></li>

                      

                    </ul>

                    </td>

                </tr>

                </tbody>

            </table>

            <br>

			

			<table class="wp-list-table widefat fixed bookmarks">

            	<thead>

                <tr>

                	

					

					<th>Misc.</th>

                </tr>

                </thead>

               



                <tbody>

                <tr>

                	<td>

                    <ul class="uaf_list">

                    	<li><a href="https://jschema_jd_ampdesign.nl/en/blog/wpml-language-switcher-amp/" target="_blank">WPML language switcher in AMP</a></li>

                        <li><a href="https://jschema_jd_ampdesign.nl/en/blog/wordpress-amp/" target="_blank">WordPress AMP tutorial</a></li>
 <li><a href="https://jaaadesign.nl/en/blog/what-is-structured-data/" target="_blank">What is Structured Data?</a></li>
						 <li><a href="https://jschema_jd_ampdesign.nl/en/contact/" target="_blank">Contact Us</a></li>

                   </ul>

				  

                    </td>

                </tr>

                </tbody>

            </table>

            <br>

			

       

</div>

<?php }

 
 
 
 
 
 
 
 
 
 
 
 
 
 
//^Register sidebars

function afw_register_sidebars() {
 
 register_sidebar( array(
'name' => 'AMP footer 1',
'id' => 'amp-footer-sidebar-1',
'description' => 'Appears in the footer area of your AMP content',
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => '</aside>',
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => 'AMP footer 2',
'id' => 'amp-footer-sidebar-2',
'description' => 'Appears in the footer area of your AMP content',
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => '</aside>',
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => 'AMP footer 3',
'id' => 'amp-footer-sidebar-3',
'description' => 'Appears in the footer area of your AMP content',
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => '</aside>',
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );

}
add_action( 'widgets_init', 'afw_register_sidebars' );



//*  Render widgets

add_action('amp_post_template_footer', 'amp_footer_wids_jd', 99);

function amp_footer_wids_jd( $amp_template ) {
  $post_id = $amp_template->get( 'post_id' );
 if ( function_exists('is_amp_endpoint') ) 

	 
	 
	 
$orgschema = get_option( 'org_schema_jd_amp' );
$schemaname = get_option( 'org_name_schema_amp' );
$schemasocial = get_option( 'org_social_schema_amp' );

$creatorname = get_option( 'add_creator_amp_name' );
$creatorurl = get_option( 'add_creator_amp_url' );
$creatorsocial = get_option( 'add_creator_amp_social' );
 
$siteurl = get_site_url();	
 
$curYear = date('Y'); 

	
	
echo '<div class="jd-css-plugins-afw" role="contentinfo" itemscope="itemscope" itemtype="http://schema.org/WPFooter">';


echo '<div class="jd-css-plugins-inner">';



if(is_active_sidebar('amp-footer-sidebar-1')){
echo '<div id="amp-footer-one">';	
 dynamic_sidebar('amp-footer-sidebar-1');
 echo '</div>';
} 





if(is_active_sidebar('amp-footer-sidebar-2')){
echo '<div id="amp-footer-two">';	
dynamic_sidebar('amp-footer-sidebar-2');
echo '</div>';
}


 

if(is_active_sidebar('amp-footer-sidebar-3')){
echo '<div id="amp-footer-three">';
dynamic_sidebar('amp-footer-sidebar-3');
echo '</div>';
}

echo '</div>';
 
if ( get_option( 'footer_copy_on_amp' ) == '1' ) {
echo '<div class="amp-wp-footer">';
echo '<div>';


echo '<h2>
<a href="'. $siteurl .'" itemprop="copyrightHolder" itemscope itemtype="http://schema.org/'. $orgschema .'"><span itemprop="name">'. $schemaname .'<link itemprop="sameAs" href="'. $schemasocial .'"/></span></a></h2><span class="copyrightsign">© </span><p itemprop="copyrightYear">'. $curYear .'</p><a href="#top" class="back-to-top">Back to top</a></div>';


if ( get_option( 'creator_on_amp' ) == '1' )
echo '<span itemscope="itemscope" itemprop="creator" itemtype="http://schema.org/Person"><meta itemprop="name" content="'. $creatorname .'"/><link itemprop="url" href="'. $creatorurl .'"/><link itemprop="sameas" href="'. $creatorsocial .'" /></span>';
 


echo '</div>';

}
echo '</div>';

}








//*  AMP Plugin extra styles

add_action( 'amp_post_template_css', 'jd_css_plugins_afw' );

function jd_css_plugins_afw( $amp_template ) {

	if ( function_exists('is_amp_endpoint') )

    // only CSS here please...

    ?>

 

div.jd-css-plugins-afw div.jd-css-plugins-inner {
    padding: 16px;
    max-width: 800px;
    margin: auto;
}

div.jd-css-plugins-afw li {
    margin-bottom: initial;
    margin-left: 20px;
}
 
div.jd-css-plugins-afw .amp-wp-footer span.copyrightsign {
    color: #696969;
    font-size: .8em;
    line-height: 2em;
    float: left;
    padding-right: 2px;
}
div.jd-css-plugins-afw h2 a {
	color: #000;
}
div.jd-css-plugins-afw .amp-wp-footer {
	 position: initial;
    top: initial;
    left: initial;
}
div#amp-footer-one,
div#amp-footer-two,
div#amp-footer-three {
     padding: 15px 0 15px 0;
}
	<?php 

}



//*  AMP Plugin remove native footer

add_action( 'amp_post_template_css', 'jd_css_plugins_afw_native', 998 );

function jd_css_plugins_afw_native( $amp_template ) {

	if ( function_exists('is_amp_endpoint') && !get_option( 'hide_foot_nat_amp' ) == '1' ) {

    // only CSS here please...

    ?>

.amp-wp-footer {
    position: absolute;
    top: -9999px;
    left: -9999px;
}

	<?php 

}
}

add_action('admin_head', 'jd_foot_wids_admin_css', 999);

function jd_foot_wids_admin_css() {
  echo '<style>

  blockquote.mindyou{
    margin-left: 20px;
    border-left: 4px solid rgba(27, 128, 0, 0.67);
    padding-left: 13px;
}
  div.buttholder {
	  width:100%;
	  marging: auto;
  }
  div.buttcopy a {
    color: #fff;
}
div.buttcopy {
    display: inline-block;
    text-decoration: none;
    font-size: 13px;
    line-height: 26px;
    height: 28px;
    margin: 0;
    padding: 0 10px 1px;
    margin: 0px 0px 0px 28px;
    cursor: pointer;
    border-width: 1px;
    border-style: solid;
    -webkit-appearance: none;
    -webkit-border-radius: 3px;
    border-radius: 3px;
    white-space: nowrap;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    background: #0085ba;
    border-color: #0073aa #006799 #006799;
    -webkit-box-shadow: 0 1px 0 #006799;
    box-shadow: 0 1px 0 #006799;
    color: #fff;
    text-decoration: none;
    text-shadow: 0 -1px 1px #006799,1px 0 1px #006799,0 1px 1px #006799,-1px 0 1px #006799;
}
div.buttcopy:hover {
    background: #008ec2;
    border-color: #006799;
    color: #fff;
}
.extendd {
    vertical-align: baseline;
    line-height: 1.6rem;
}
.extendd input[type=checkbox] {
   position: absolute;
   top: -9999px;
   left: -9999px;
  
}

/* Default State */
div.tton, div.tttw, div.ttth {
 opacity: 0;
  max-height: 0;
  overflow: hidden;
    background: rgba(239, 239, 239, 0.36);
    margin-top: 15px;
	transform: scale(0.8);
  transition: 0.5s;
}

/* Toggled State */
.extendd input[type=checkbox]:checked ~ div.tton,
.extendd input[type=checkbox]:checked ~ div.tttw,
.extendd input[type=checkbox]:checked ~ div.ttth {
display: block;
transform: scale(1);
 opacity: 1;
    max-height: 400px;
    overflow: visible;
	    padding: 10px 15px 15px 15px;
}
.extendd label {
    cursor: pointer;
    padding-left: 5px;
    color: green;
    font-style: italic;
}
  </style>';
}