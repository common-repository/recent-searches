<?php
/*
	Recent Searches Widget Class
	@since 0.0.1
	
	Copyright 2014 | zourbuth.com | zourbuth@gmail.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Recent_Searches_Widget extends WP_Widget {
	
	/**
	 * Textdomain for the widget.
	 * @since 1.0
	 */
	var $textdomain;
	var $version;
	var $url;
	var $searches;
	
	function __construct() {
		
		// Set the widget textdomain
		$this->textdomain = RECENTSEARCHES_SLUG;	//super-post
		$this->url = RECENTSEARCHES_URL;
		$this->searches = new Recent_Searches();

		// Set up the widget options
		$widget_options = array(
			'classname' => 'recent-sarches-widget',
			'description' => esc_html__( 'Your site’s most recent searches.', $this->textdomain )
		);

		// Set up the widget control options
		$control_options = array(
			'id_base' => RECENTSEARCHES_SLUG
		);

		$this->WP_Widget( RECENTSEARCHES_SLUG, RECENTSEARCHES_NAME, $widget_options, $control_options );		
			
		if ( is_active_widget( false, false, $this->id_base, false ) && ! is_admin() ) {
			wp_enqueue_style( RECENTSEARCHES_SLUG, RECENTSEARCHES_URL . 'css/styles.css' );
		}		
	}
	
	
	/**
	 * Widget front end function
	 * @since 1.3
	**/	
	function widget($args, $instance) {			
		extract( $args, EXTR_SKIP );

		// Output the theme's $before_widget wrapper.
		echo $before_widget;

		// If a title was input by the user, display it.
		if ( !empty( $instance['title'] ) )
			echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;
		
		// echo '<pre style="font-size:10px;line-height:10px;">'. print_r( get_option( 'recent_searches_data' ), true ) .'</pre>';
		
		// Output the recent searches widget for the front-end!
		echo $this->searches->recent_searches( $instance );
			
		// Close the theme's widget wrapper.
		echo $after_widget;
	}
		

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = array(
			'title' 	=> strip_tags( $new_instance['title'] ),
			'number' 	=> (int) $new_instance['number'],
		);
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title'		=> __( 'Recent Searches', $this->textdomain),
			'number'	=> 20,
		);

		// Merge the user-selected arguments with the defaults.
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of searches to show', $this->textdomain ); ?></label>
		<input type="text" size="3" value="<?php echo $instance['number']; ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" id="<?php echo $this->get_field_id( 'number' ); ?>"></p>		
		<?php
	}
}
?>