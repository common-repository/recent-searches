<?php
/**
	Recent Searches Class
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

class Recent_Searches {
	/**
	 * Textdomain for the widget.
	 * @since 1.0
	 */
	var $textdomain;
	
	/**
	 * Class constructor
	 * @since 0.0.1
	 */	
	function __construct() {
		add_action( 'template_redirect', array( &$this, 'template_redirect' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_script' ) );
		add_action( 'wp_ajax_delete_search', array( &$this, 'delete_search' ) );
		add_shortcode( 'recent-searches', array( $this, 'shortcode' ) );
	}
	

	/**
	 * Get search query
	 * Check if current page is not 404
	 * @param none
	 * @since 0.0.1
	 */	
	function template_redirect() {
		global $wp_query;
	
		if ( is_search() && $wp_query->found_posts ) {

			$search = sanitize_text_field( strtolower( trim( get_search_query() ) ) );
			
			// proceed only if the search keyword contain more than 2 characters
			if( strlen( $search ) < 3 )
				return;
			
			$option = get_option( 'recent_searches_data' );
			
			if( isset( $option[$search] ) )
				$option[$search]++;
			else
				$option[$search] = 1;			
			
			update_option( 'recent_searches_data', apply_filters( 'recent_searches_data', $option ) );
		}
	}
	
	
	/**
	 * Enqueue custom script to the front end
	 * @param none
	 * @since 0.0.1
	 */	
	function enqueue_script() {
		if( ! current_user_can( 'manage_options' ) )
			return;
			
		wp_enqueue_script( RECENTSEARCHES_SLUG, RECENTSEARCHES_URL . 'js/jquery.scripts.js', array( 'jquery' ), RECENTSEARCHES_VERSION );
		wp_localize_script( RECENTSEARCHES_SLUG, 'recentsearches', array(
			'nonce'		=> wp_create_nonce( RECENTSEARCHES_SLUG ),
			'action'	=> 'delete_search',
			'ajaxurl'	=> admin_url( 'admin-ajax.php' )
		));
	}
	
	
	/**
	 * Delete search using ajax
	 * @param none
	 * @since 0.0.1
	 */	
	function delete_search() {
		check_ajax_referer( RECENTSEARCHES_SLUG, 'nonce' );
		
		$data = get_option( 'recent_searches_data' );
		
		$keyword = sanitize_text_field( strtolower( trim( $_POST['keyword'] ) ) ); // again validation
		
		if( isset( $data[$keyword] ) )
			unset( $data[$keyword] );
		
		update_option( 'recent_searches_data', $data );
		
		echo 'deleted!'; 
		exit();
	}
	
	/**
	 * Delete search using ajax
	 * @param none
	 * @since 0.0.1
	 */	
	function default_args() {
		$defaults = array(
			'title'		=> __( 'Recent Searches', $this->textdomain ),
			'number'	=> 20,
		);
		
		return $defaults;
	}
	

	/**
	 * Output a list of recent searches keyword with link
	 * @param $args is the current processed widget settings or shortcode parameters
	 * @filter recent_searches
	 * @since 0.0.1
	 */
	public function recent_searches( $args ) {
		$data = get_option( 'recent_searches_data' );
		
		$html = apply_filters( 'recent_searches', null, $data, $args ); // apply filters for templating
		
		if( $html )	// bail early
			return $html;
			
		arsort( $data ); // sort by most searched
		$data = array_slice( $data, 0, $args['number'], true );	// get the first 'number';

		$html .= '<p class="recent-searches">';
		foreach( $data as $keyword => $total ) {			
			$html .= '<span class="search-keyword">';
			$html .= '<a href="'. get_search_link( $keyword ) .'" rel="nofollow">'. esc_html( $keyword ). '</a>';
			if( current_user_can( 'manage_options' ) )
				$html .= '&nbsp;<a href="#" class="delete" data-keyword="'. $keyword .'">&#215;</a>';
			$html .= '</span>';
		}
		$html .= '</p>';
		
		return $html;
	}

	
	/**
	 * Shortcode function
	 * @param $atts
	 * @param $content
	 * @since 0.0.1
	 */
	function shortcode( $atts, $content ) {
		$atts = shortcode_atts( $this->default_args(), $atts );
		$html = $this->recent_searches( $atts );
		return $html;
	}
};

