<?php
/*
Plugin Name: WP jQuery Vertical Scroller
Plugin URI: http://sirisgraphics.com/wordpress-plugins/wp-scroller
Description: A plugin to add a widget to scroll posts in your sidebar or footer widgets for WordPress powered by jQuery
Version: 1.1
Author: Vamsi Pulavarthi
Author URI: http://sirisgraphics.com/author/vamsi
License: GPLv2
*/

// use widgets_init action hook to execute custom function
add_action( 'widgets_init', 'sg_jquery_scroller_widget_plugin' );

 //register our widget
function sg_jquery_scroller_widget_plugin() {
    register_widget( 'sg_jquery_scroller_widget' );
}

//boj_widget_my_info class
class sg_jquery_scroller_widget extends WP_Widget {

    //process the new widget
    function sg_jquery_scroller_widget() {
        $widget_ops = array(
			'classname' => 'sg_jquery_scroller_widget_plugin_class',
			'description' => 'Drop this in a widget area to add jQuery Vertical Scroller to your pages.'
			);
        $this->WP_Widget( 'sg_jquery_scroller_widget', 'jQuery Vertical Scroller', $widget_ops );

        /* Register all javascripts used in the plugin. */
        wp_register_script( 'scrollerscript', plugins_url('/scripts/jquery-scroller-v1.min.js', __FILE__), array('jquery') );
        wp_enqueue_script( 'scrollerscript' );
    }

     //build the widget settings form
    function form($instance) {
        $defaults = array( 'title' => 'Recent Posts', 'mycategory' => '1', 'mycount' => '5', 'mydirection' => 'bottom', 'myvelocity' => '50', 'myposttype' => 'post', 'myreadmoretext' => 'read more...' );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = $instance['title'];
        $mycategory = $instance['mycategory'];
        $mycount = $instance['mycount'];
        $mydirection = $instance['mydirection'];
        $myvelocity = $instance['myvelocity'];
        $myposttype = $instance['myposttype'];
        $myreadmoretext = $instance['myreadmoretext'];
?>

    <p>
        <p>
            Title:
            <input class="widefat"
                    name="<?php echo $this->get_field_name( 'title' ); ?>"
                    type="text"
                    value="<?php echo esc_attr( $title ); ?>" />

        </p>
        <p>
            Velocity:
            <input class="widefat"
                name="<?php echo $this->get_field_name( 'myvelocity' ); ?>"
                type="text"
                value="<?php echo esc_attr( $myvelocity ); ?>" />
            &nbsp; ex: 1 - 100
        </p>
        <p>
            Direction:<br />
            <input type="radio"
                name="<?php echo $this->get_field_name( 'mydirection' ); ?>"
                value="top" <?php checked( $mydirection, 'top' ); ?> >Top-to-Bottom
            <br />
            <input type="radio"
                name="<?php echo $this->get_field_name( 'mydirection' ); ?>"
                value="bottom" <?php checked( $mydirection, 'bottom' ); ?> >Bottom-to-Top
        </p>
        <p>
            Post Type:
            <input class="widefat"
                name="<?php echo $this->get_field_name( 'myposttype' ); ?>"
                type="text"
                value="<?php echo esc_attr( $myposttype ); ?>" />
        </p>
        <p>
        Category:
            <input class="widefat"
                name="<?php echo $this->get_field_name( 'mycategory' ); ?>"
                type="text"
                value="<?php echo esc_attr( $mycategory ); ?>" /> &nbsp; ex: 1 or 2 or 3 etc.
        </p>
        <p>
            Count:
            <input class="widefat"
                    name="<?php echo $this->get_field_name( 'mycount' ); ?>"
                    type="text"
                    value="<?php echo esc_attr( $mycount ); ?>" />
        </p>
        <p>
            Read more text:
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
        $instance['mycategory'] = strip_tags( $new_instance['mycategory'] );
        $instance['mycount'] = strip_tags( $new_instance['mycount'] );
        $instance['mydirection'] = strip_tags( $new_instance['mydirection'] );
        $instance['myvelocity'] = strip_tags( $new_instance['myvelocity'] );
        $instance['myposttype'] = strip_tags( $new_instance['myposttype'] );
        $instance['myreadmoretext'] = strip_tags( $new_instance['myreadmoretext'] );
        return $instance;
    }

    //display the widget
    function widget($args, $instance) {
        extract($args);

        echo $before_widget;
        $title = apply_filters( 'widget_title', $instance['title'] );
        $mycategory = empty( $instance['mycategory'] ) ? '&nbsp;' : $instance['mycategory'];
        $mycount = empty( $instance['mycount'] ) ? '&nbsp;' : $instance['mycount'];
        $myvelocity = empty( $instance['myvelocity'] ) ? '&nbsp;' : $instance['myvelocity'];
        $myposttype = empty( $instance['myposttype'] ) ? '&nbsp;' : $instance['myposttype'];
        $mydirection = empty( $instance['mydirection'] ) ? '&nbsp;' : $instance['mydirection'];
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
        width:100%;
        height:200px;
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
                </li>
                <?php endforeach; ?>
                <?php $post = $tmp_post; ?>
            </ul>
        </div> <!-- end of scrolling text -->
    </div>  <!-- end of Vertical Scroller -->
	<?php if ( $myposttype == 'post' ) { ?>
		<br />
		<div style="min-height: 20px; vertical-align: bottom;">
			<br />
			<?php $category_link = get_category_link( $mycategory ); ?>
			<center><a href="<?php echo esc_url( $category_link ); ?>"><?php echo $myreadmoretext ?></a></center>
		</div>

	<?php } else { ?>
		<br />
		<div style="min-height: 20px; vertical-align: bottom;">
			<br />
			<center><a href="<?php echo get_post_type_archive_link( $myposttype ); ?>"><?php echo $myreadmoretext ?></a></center>
		</div>
	<?php } ?>
</div> <!-- end of Grid Container -->
<?php
        echo $after_widget;
    }
}
?>