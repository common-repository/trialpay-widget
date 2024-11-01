<?php
/*
Plugin Name: TrialPay Widget by Thornsoft
Plugin URI: http://www.alternativepaymentresources.com/trialpay_widget.htm
Description: Easily integrate TrialPay payments into your Wordpress site.  TrialPay lets your visitors donate to your site by signing up for offers from TrialPay partners such as Geico, Discover, American Express, Blockbuster, and 100s of others.  When they sign up, YOU get paid!  Configure the Widget settings in the 'Presentation | Widgets' section of the Dashboard..
Author: Chris Thornton, Thornsoft Development, Inc.
Version: 1.07
Author URI: http://www.alternativepaymentresources.com
*/

$apr_trialpay_plugin_version = 'v1.07';    /* Be sure to update the one in the top block too */
$apr_trialpay_plugin_date = '2007-07-018'; /* These show in the widget config screen */


/*
Copyright 2007 Chris Thornton / Thornsoft Development, Inc. (chris@thornsoft.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


/*
  Credits: Thanks to http://headzoo.com/tutorials/making-your-wordpress-plugin-widget-ready
           for their great tutorial on writing widgets.
*/

/* NOTES NOTES NOTES NOTES NOTES NOTES NOTES NOTES NOTES NOTES NOTES NOTES NOTES NOTES

   Note: When first installed, MY TrialPay URL will be used by default.
   This is just to give you something to look at, and it not some attempt to hijack your
   visitors.  When your TrialPay store is ready, please edit the settings
   (in the WordPress Widget panel) to use your own URL.

   Note: Please consider signing up for TrialPay using our affiliate
         link: http://merchant.trialpay.com/ref?tp=QreQl

   Visit http://www.alternativepaymentresources.com for other great ideas on how
   to make $$$ with TrialPay.
*/

add_action('plugins_loaded', 'apr_init');



function apr_init() {
        if (!function_exists('register_sidebar_widget')) {
                return;
        }
        register_sidebar_widget('TrialPay Widget', 'apr_trialpay_plugin_function');
        register_widget_control('TrialPay Widget', 'apr_trialpay_plugin_menu',500, 400);  /* Need the extra width, height */

        /* If URIs aren't set, go with defaults. See notice in NOTES section above. */
        if ((strlen(get_option('apr_trialpay_plugin_tpstoreuri')) ==0) and
            (strlen(get_option('apr_trialpay_plugin_imageuri')) ==0)) {
           update_option('apr_trialpay_plugin_tpstoreuri','http://www.trialpay.com/stores/thornsoft/tprsample?tid=A7DZlP2'); /* use my URL, so it'll work */
           update_option('apr_trialpay_plugin_imageuri','http://www.alternativepaymentresources.com/images/trialpay_widget_sample.gif');/* use my IMG, so it'll work */
           update_option('apr_trialpay_plugin_titletext','Support Our Site');
           update_option('apr_trialpay_plugin_footertext','Setup Required! <br>Please Configure TrialPay Widget In The WordPress Dashboard - Presentation - Widgets.');
           update_option('apr_trialpay_plugin_aligncenter','on');  /* I think center alignment looks nice. */
        }
}

function apr_trialpay_plugin_menu() {
        global $apr_trialpay_plugin_version, $apr_trialpay_plugin_date; /* reference from above */
		if ( $_POST['apr_trialpay_submit'] )  {
           update_option('apr_trialpay_plugin_tpstoreuri', $_POST['apr_trialpay_plugin_tpstoreuri']);
           update_option('apr_trialpay_plugin_imageuri', $_POST['apr_trialpay_plugin_imageuri']);
           update_option('apr_trialpay_plugin_titletext', $_POST['apr_trialpay_plugin_titletext']);
           update_option('apr_trialpay_plugin_footertext', $_POST['apr_trialpay_plugin_footertext']);
           $aligncenter = ($_POST["apr_trialpay_plugin_aligncenter"] == "on")? "on": "off";
           update_option('apr_trialpay_plugin_aligncenter', $aligncenter);
        }

        /* Get my options from the database */
        $tpstoreuri = get_option('apr_trialpay_plugin_tpstoreuri');
        $imageuri   = get_option('apr_trialpay_plugin_imageuri');
        $titletext  = get_option('apr_trialpay_plugin_titletext');
        $footertext = get_option('apr_trialpay_plugin_footertext');
        $aligncenter = get_option('apr_trialpay_plugin_aligncenter');  /* Values = 'on', 'off' */

        /* If using Thornsoft TrialPay URL, warn them! */
        if (stripos($tpstoreuri, 'thornsoft') >0) {
           echo '<h4>ALERT - Using Sample TrialPay Store URL.</h4>';
        }

        ?>
        <p style="text-align:left">
        <label for="apr_trialpay_plugin_titletext">Title Text:
        <input style="width: 200px;"
        id="apr_trialpay_plugin_titletext" name="apr_trialpay_plugin_titletext" type="text"
        value="<?php echo $titletext; ?>" />
        </label></p>

        <p style="text-align:left">
        <label for="apr_trialpay_plugin_tpstoreuri">TrialPay Store URI:
        <input style="width: 480px;"
        id="apr_trialpay_plugin_tpstoreuri" name="apr_trialpay_plugin_tpstoreuri" type="text"
        value="<?php echo $tpstoreuri; ?>" />
        </label></p>

        <p style="text-align:left">
        <label for="apr_trialpay_plugin_imageuri">Image (button) URI:
        <input  style="width: 480px;"
        id="apr_trialpay_plugin_imageuri" name="apr_trialpay_plugin_imageuri" type="text"
        value="<?php echo $imageuri; ?>" />
        </label> </p>


        <p style="text-align:left">
        <label for="apr_trialpay_plugin_footertext">Footer Text:
        <input style="width: 480px;"
        id="apr_trialpay_plugin_footertext" name="apr_trialpay_plugin_footertext" type="text"
        value="<?php echo $footertext; ?>" />
        </label></p>

        <p style="text-align:left">
        <label for="apr_trialpay_plugin_aligncenter">Center Image (override style)?
        <input
        id="apr_trialpay_plugin_aligncenter" name="apr_trialpay_plugin_aligncenter" type="checkbox"
        <?php if($aligncenter == "on"){echo " checked";}?> />
        </label></p>

        <hr>
        <table border="0" width="100%" style="border-collapse: collapse">
			<tr>
				<td width="25%">
					<?php echo "ver: " . $apr_trialpay_plugin_version; echo "<br>date: " . $apr_trialpay_plugin_date; ?>
				</td>
				<td width="50%">
					<p style="text-align: center">
        			<A target="_blank" href="http://merchant.trialpay.com/ref?tp=QreQl">
        			<b>Sign Up For TrialPay!</b></A>
        		</td>
				<td width="25%">
					<p style="text-align: right">
					<A target="_blank" href="http://www.alternativepaymentresources.com/trialpay_widget.htm">
        			Help/About</A>
        		</td>
			</tr>
		</table>
        <input type="hidden" name="apr_trialpay_submit" id="apr_trialpay_submit" value="1" />

        <?php
}

function apr_trialpay_plugin_function($args) {
        $tpstoreuri = get_option('apr_trialpay_plugin_tpstoreuri');
        $imageuri   = get_option('apr_trialpay_plugin_imageuri');
        $titletext  = get_option('apr_trialpay_plugin_titletext');
        $footertext = get_option('apr_trialpay_plugin_footertext');
        $aligncenter = get_option('apr_trialpay_plugin_aligncenter');

        /* if force center alignment, set the style. Otherwise blank */
        /* Unfortunately, aligning the image requires a P tag and that can cause a border to show. */
        $styleOpen = ($aligncenter == 'on')? '<p style="text-align: center">': '';
        $styleClose = ($aligncenter == 'on')? "</p>": "";

		/* Compulsory before/after tags, for themes that use them */
        echo $args['before_widget'];
        echo $args['before_title'] .  $titletext . $args['after_title'];

        echo $styleOpen;

        /* Now output the image */
        ?>
        <a href="<?php echo $tpstoreuri; ?>" >
        <img class="noborder" border=0 src="<?php echo $imageuri; ?>"></a>
        <?php
        if (strlen($footertext) > 0) {
          echo "<br>" . $footertext;
        }
        echo $styleClose;
        echo $args['after_widget'];
}
?>