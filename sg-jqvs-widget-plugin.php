<?php
/*
Plugin Name: jQuery Vertical Scroller
Plugin URI: http://sirisgraphics.com/development/jquery-vertical-scroller-2-0
Description: A plugin to add a widget to scroll posts in your sidebar or footer widgets for WordPress powered by jQuery
Version: 2.1
Author: Vamsi Pulavarthi
Author URI: http://sirisgraphics.com/
License: GPLv2
*/

// Register the shortcode
add_shortcode( 'sgvscroller', 'sg_jquery_scroller_shortcode' );

// use widgets_init action hook to execute custom function
add_action( 'widgets_init', 'sg_jquery_scroller_widget_plugin' );

 //register our widget
function sg_jquery_scroller_widget_plugin() {
    $plugin_dir = basename(dirname(__FILE__));
    load_plugin_textdomain('sg-jqvs', false, $plugin_dir . '/languages' );
    register_widget( 'sg_jquery_scroller_widget' );
}

//Vertical Scroller class
class sg_jquery_scroller_widget extends WP_Widget {

    //process the new widget
    function sg_jquery_scroller_widget() {

        $plugin_description = __('Drop this in a widget area to add jQuery Vertical Scroller to your pages.', 'sg-jqvs' );
        $plugin_title = __( 'jQuery Vertical Scroller', 'sg-jqvs' );
        $widget_ops = array(
			'classname' => 'sg_jquery_scroller_widget_plugin_class',
			'description' => $plugin_description
			);
        $this->WP_Widget( 'sg_jquery_scroller_widget', $plugin_title, $widget_ops );

        /* Register all javascripts used in the plugin. */
        wp_register_script( 'scrollerscript', plugins_url('/scripts/jquery-scroller-v1.min.js', __FILE__), array('jquery') );
        wp_enqueue_script( 'scrollerscript' );
    }

     //Widget settings form
    function form($instance) {

        //Setup the default setting strings for localization
        $default_title = __( 'Recent posts', 'sg-jqvs');
        $default_readmore = __( 'read more...', 'sg-jqvs');

        //Setup the default values for the widget
        $defaults = array( 'title' => $default_title, 
                            'mywidth' => '100',
                            'myheight' => '200',
                            'mycategory' => '1', 
                            'mycount' => '5', 
                            'mydirection' => 'bottom', 
                            'myvelocity' => '50', 
                            'myposttype' => 'post', 
                            'myincludecontent' => 'no',
                            'myshowdate' => 'false',
                            'myreadmoretext' => $default_readmore );
        
        //Assign the default values or original settings to current widget
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = $instance['title'];
        $mywidth = $instance['mywidth'];
        $myheight = $instance['myheight'];
        $mycategory = $instance['mycategory'];
        $mycount = $instance['mycount'];
        $mydirection = $instance['mydirection'];
        $myvelocity = $instance['myvelocity'];
        $myposttype = $instance['myposttype'];
        $myincludecontent = $instance['myincludecontent'];
        $myshowdate = $instance['myshowdate'];
        $myreadmoretext = $instance['myreadmoretext'];
?>

    <p>
        <p>
            <?php _e( 'Title:', 'sg-jqvs' ); ?>
            <input class="widefat"
                    name="<?php echo $this->get_field_name( 'title' ); ?>"
                    type="text"
                    value="<?php echo esc_attr( $title ); ?>" />

        </p>
        <p>
            <?php _e( 'Width:', 'sg-jqvs' ); ?>
            <input class="widefat"
                    name="<?php echo $this->get_field_name( 'mywidth' ); ?>"
                    type="text"
                    value="<?php echo esc_attr( $mywidth ); ?>" />

        </p>
        <p>
            <?php _e( 'Height:', 'sg-jqvs' ); ?>
            <input class="widefat"
                    name="<?php echo $this->get_field_name( 'myheight' ); ?>"
                    type="text"
                    value="<?php echo esc_attr( $myheight ); ?>" />

        </p>
        <p>
            <?php _e( 'Velocity:', 'sg-jqvs' ); ?>
            <input class="widefat"
                name="<?php echo $this->get_field_name( 'myvelocity' ); ?>"
                type="text"
                value="<?php echo esc_attr( $myvelocity ); ?>" />
            &nbsp; ex: 1 - 100
        </p>
        <p>
            <?php _e( 'Direction:', 'sg-jqvs' ); ?><br />
            <input type="radio" 
                name="<?php echo $this->get_field_name( 'mydirection' ); ?>" 
                value="top" <?php checked( $mydirection, 'top' ); ?> ><?php _e( 'Top-to-Bottom', 'sg-jqvs' ); ?>
            <br />
            <input type="radio" 
                name="<?php echo $this->get_field_name( 'mydirection' ); ?>" 
                value="bottom" <?php checked( $mydirection, 'bottom' ); ?> ><?php _e( 'Bottom-to-Top', 'sg-jqvs' ); ?>
        </p>
        <p>
            <?php _e( 'Post Type:', 'sg-jqvs' ); ?>
            <input class="widefat"
                name="<?php echo $this->get_field_name( 'myposttype' ); ?>"
                type="text"
                value="<?php echo esc_attr( $myposttype ); ?>" />
        </p>
        <p>
        <?php _e( 'Category:', 'sg-jqvs' ); ?>
            <input class="widefat"
                name="<?php echo $this->get_field_name( 'mycategory' ); ?>"
                type="text"
                value="<?php echo esc_attr( $mycategory ); ?>" /> &nbsp; ex: 1 or 2 or 3 etc.
        </p>
        <p>
            <?php _e( 'Count:', 'sg-jqvs' ); ?>
            <input class="widefat"
                    name="<?php echo $this->get_field_name( 'mycount' ); ?>"
                    type="text"
                    value="<?php echo esc_attr( $mycount ); ?>" />
        </p>
        <p>
            <?php _e( 'Include Content:', 'sg-jqvs' ); ?>
            <br />
            <input type="radio" 
                name="<?php echo $this->get_field_name( 'myincludecontent' ); ?>" 
                value="no" <?php checked( $myincludecontent, 'no' ); ?> ><?php _e( 'No', 'sg-jqvs' ); ?>
            <br />
            <input type="radio" 
                name="<?php echo $this->get_field_name( 'myincludecontent' ); ?>" 
                value="content" <?php checked( $myincludecontent, 'content' ); ?> ><?php _e( 'Full Content', 'sg-jqvs' ); ?>
            <br />
            <input type="radio" 
                name="<?php echo $this->get_field_name( 'myincludecontent' ); ?>" 
                value="excerpt" <?php checked( $myincludecontent, 'excerpt' ); ?> ><?php _e( 'Excerpt only', 'sg-jqvs' ); ?>
        </p>
        <p>
            <?php _e( 'Show Date:', 'sg-jqvs' ); ?><br />
            <input type="radio" 
                name="<?php echo $this->get_field_name( 'myshowdate' ); ?>" 
                value="true" <?php checked( $myshowdate, 'true' ); ?> ><?php _e( 'True', 'sg-jqvs' ); ?>
            <br />
            <input type="radio" 
                name="<?php echo $this->get_field_name( 'myshowdate' ); ?>" 
                value="false" <?php checked( $myshowdate, 'false' ); ?> ><?php _e( 'False', 'sg-jqvs' ); ?>
        </p>
        <p>
            <?php _e( 'Read more text:', 'sg-jqvs' ); ?>
            <input class="widefat"
                    name="<?php echo $this->get_field_name( 'myreadmoretext' ); ?>"
                    type="text"
                    value="<?php echo esc_attr( $myreadmoretext ); ?>" />
        </p>
    </p>
<?php
    }

    //save the widget settings
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['mywidth'] = strip_tags( $new_instance['mywidth'] );
        $instance['myheight'] = strip_tags( $new_instance['myheight'] );
        $instance['mycategory'] = strip_tags( $new_instance['mycategory'] );
        $instance['mycount'] = strip_tags( $new_instance['mycount'] );
        $instance['mydirection'] = strip_tags( $new_instance['mydirection'] );
        $instance['myvelocity'] = strip_tags( $new_instance['myvelocity'] );
        $instance['myposttype'] = strip_tags( $new_instance['myposttype'] );
        $instance['myincludecontent'] = strip_tags( $new_instance['myincludecontent'] );
        $instance['myshowdate'] = strip_tags( $new_instance['myshowdate'] );
        $instance['myreadmoretext'] = strip_tags( $new_instance['myreadmoretext'] );
        return $instance;
    }

    //display the widget
    function widget($args, $instance) {
        extract($args);

        echo $before_widget;
        $title = apply_filters( 'widget_title', $instance['title'] );
        $mywidth = empty( $instance['mywidth'] ) ? '&nbsp;' : $instance['mywidth'];
        $myheight = empty( $instance['myheight'] ) ? '&nbsp;' : $instance['myheight'];
        $mycategory = empty( $instance['mycategory'] ) ? '&nbsp;' : $instance['mycategory'];
        $mycount = empty( $instance['mycount'] ) ? '&nbsp;' : $instance['mycount'];
        $mydirection = empty( $instance['mydirection'] ) ? '&nbsp;' : $instance['mydirection'];
        $myvelocity = empty( $instance['myvelocity'] ) ? '&nbsp;' : $instance['myvelocity'];
        $myposttype = empty( $instance['myposttype'] ) ? '&nbsp;' : $instance['myposttype'];
        $myincludecontent = empty( $instance['myincludecontent'] ) ? '&nbsp;' : $instance['myincludecontent'];
        $myshowdate = empty( $instance['myshowdate'] ) ? '&nbsp;' : $instance['myshowdate'];
        $myreadmoretext = empty( $instance['myreadmoretext'] ) ? '&nbsp;' : $instance['myreadmoretext'];

        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
        $myrandom = wp_rand(0, 25);
?>
<style type="text/css">
   	/* CSS for the scrollers */
	.vertical_scroller<?php echo $myrandom; ?> {
        position: relative;
        display: block;
        overflow: hidden;
        float: left;
        width: <?php echo $mywidth ?>%;  /* 100%; */
        height: <?php echo $myheight ?>px; /* 200px; */
        padding: 2px 2px 2px 2px;
        margin-bottom: 10px;
	}
	.scrollingtext {
		position:absolute;
		white-space: normal;
		/*font-family:'Trebuchet MS',Arial;*/
        text-align: left;
	}

    .scrollingtext ul li {
        padding-bottom: 10px;
    }

</style>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        //create a vertical scroller...
        $('.vertical_scroller<?php echo $myrandom; ?>').SetScroller({
                velocity: <?php echo $myvelocity ?>, /*50,*/
                direction: 'vertical',  /*'vertical' */
                startfrom: '<?php echo $mydirection ?>'
        });
    });
</script>
<div class="gridContainer clearfix">
    <div class="vertical_scroller<?php echo $myrandom; ?>">
        <div class="scrollingtext">
            <ul>
                <?php
                    global $post;
                    $tmp_post = $post;
                    $args = array(
                        'numberposts'     => $mycount,
                        'category'        => $mycategory,
                        'orderby'         => 'post_date',
                        'order'           => 'DESC',
                        'post_type'       => $myposttype, /*'post',*/
                        'post_status'     => 'publish',
                        'suppress_filters' => false
                        );
                    $posts_array = get_posts( $args );
                    foreach( $posts_array as $post ) : setup_postdata($post);
                ?>
                <li>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    <?php if ( $myshowdate == 'true' ) { ?>
                        <br /><?php 
                                $pfx_date = get_the_date();
                                echo $pfx_date; ?>
                    <?php } ?>
                    <?php if ( $myincludecontent == 'content' ) { ?>
                        <br /><?php the_content(); ?>
                    <?php } elseif ( $myincludecontent == 'excerpt' ) { ?>
                        <br /><?php the_excerpt(); ?>
                    <?php } ?>
                </li>
                <?php endforeach; ?>
                <?php $post = $tmp_post; ?>
            </ul>
        </div> <!-- end of scrolling text -->
    </div>  <!-- end of Vertical Scroller -->
	<?php if ( $myposttype == 'post' ) { ?>
		<br />
		<div style="min-height: 20px; vertical-align: bottom; text-align: center;">
			<br />
			<?php $category_link = get_category_link( $mycategory ); ?>
			<a href="<?php echo esc_url( $category_link ); ?>"><?php echo $myreadmoretext ?></a>
		</div>

	<?php } else { ?>
		<br />
		<div style="min-height: 20px; vertical-align: bottom; text-align: center;">
			<br />
			<a href="<?php echo get_post_type_archive_link( $myposttype ); ?>"><?php echo $myreadmoretext ?></a>
		</div>
	<?php } ?>
</div> <!-- end of Grid Container -->
<?php
        echo $after_widget;
    }
}

//Detailed shortcode functionality
function sg_jquery_scroller_shortcode( $atts ) {
    extract( shortcode_atts( array(
		"postcount" => '5',
        "category" => '1',
        "posttype" => 'post',
        "width" => '250px',
        "height" => '200px',
        "startfrom" => 'bottom',
        "includecontent" => 'none',
        "showdate" => 'false'
	), $atts ) );

    $myscroller = "";
    global $post;
    $tmp_post = $post;
    $myscrandom = wp_rand(0, 25);
    $args = array(
        'numberposts'     => $postcount,
        'category'        => $category,
        'orderby'         => 'post_date',
        'order'           => 'DESC',
        'post_type'       => $posttype,
        'post_status'     => 'publish',
        'suppress_filters' => false
        );
    $posts_array = get_posts( $args );
    $content = "";
    foreach( $posts_array as $post ) : setup_postdata($post);
        $permalink = get_permalink();
        $title = get_the_title();
        $content = $content . "<li><a href=" . $permalink . ">" . $title . "</a>";
        if ( $showdate == 'true' ) {
            $thedate = "<p>" . get_the_date() . "</p>";
            $content = $content . $thedate;
        }
        if ( $includecontent == 'excerpt' ) {
            $theexcerpt = "<p>" . get_the_excerpt() . "</p>";
            $content = $content . $theexcerpt;
        }
        $content = $content . "</li>";
    endforeach;

    $myscroller = "<style type='text/css'>
	    .vertical_scroller{$myscrandom} {
            position: relative;
            display: block;
            overflow: hidden;
            float: left;
            width: $width;
            height: $height;
            padding: 2px 2px 2px 2px;
            margin-bottom: 10px;
	    }
	    .scrollingtext {
		    position:absolute;
		    white-space: normal;
            text-align: left;
	    }
        .scrollingtext ul {
            text-indent: none;        
        }
        .scrollingtext ul li {
            padding-bottom: 10px;
            list-style-type: none;
        }
        </style>
        <script type='text/javascript'>
            jQuery(document).ready(function($) {
                $('.vertical_scroller{$myscrandom}').SetScroller({
                        velocity: 50,
                        direction: 'vertical',
                        startfrom: '{$startfrom}'
                });
            });
        </script>

        <div class='gridContainer clearfix'>
            <div class='vertical_scroller{$myscrandom}'>
                <div class='scrollingtext'>
                    <ul>
                        {$content}
                    </ul>
                </div> <!-- end of scrolling text -->
            </div>  <!-- end of Vertical Scroller -->
        </div>";

    return $myscroller;
}
?>