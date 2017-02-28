<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
add_filter('oembed_result', 'ow_hide_youtube_related_videos', 10, 3);
function ow_hide_youtube_related_videos($data, $url, $args = array()) {
	$data = preg_replace('/(youtube\.com.*)(\?feature=oembed)(.*)/', '$1?' . apply_filters("hyrv_extra_querystring_parameters", "wmode=transparent&amp;") . 'rel=0&showinfo=0$3', $data);
	return $data;
}

if(!class_exists('Ow_Vote_OW_Video')){
    class Ow_Vote_OW_Video{
	
	private static $instance = null;

	public static function instance()
	{
	  if( self::$instance === null )
	  {
	    self::$instance = new self;
	  }
	  return self::$instance;
	}
 
	public function __construct()
	{
		
		if( !shortcode_exists( 'owvideo' ) )
		{
			add_shortcode( 'owvideo', array( $this, 'owvideo_shortcode' ) );
		}
		else
		{
			add_filter( 'wp_owvideo_shortcode_override', array( $this, 'ow_video_mode' ), 10, 4 );
		}
		add_filter( 'embed_defaults', array( $this, 'modify_wp_embed_defaults' ), 10, 1 );
		add_filter( 'oembed_fetch_url', array( $this, 'ow_embed_arguments' ), 10, 3 );		
		
	}
	
	public function owvideo_shortcode( $atts , $content = '' )
	{
		// Attributes
		extract( shortcode_atts(
			array(
				'align' => 'center',
				'aspect_ratio' => '16:9',
				'width' => '100',
				'autoplay' => 0,
			), $atts )
		);
		
		extract( $this->ow_attributes_validations( $align, $aspect_ratio, $width, $autoplay ) );
		
		$aspect_ratio = str_replace( ':', '-', $aspect_ratio );
		
		return $this->get_ow_embed_video( $content, $align, $aspect_ratio, $width, $autoplay );
	}
	
	
	public function ow_video_mode( $empty_string, $atts, $content = '', $instance = 0 )
	{
		if( !empty( $content ) )
		{
			return $this->owvideo_shortcode( $atts, $content );
		}
		return '';
	}
	
	public function modify_wp_embed_defaults( $defaults = array() )
	{
		$defaults['autoplay'] = 0;
		
		return $defaults;
	}
	
	
	public function ow_embed_arguments( $provider, $url, $args = array() )
	{
		if( $args['autoplay'] == 1 )
		{
			$values = array(
				'http://vimeo.com/api/oembed'		=> array( 'autoplay', 1 ),
				'http://soundcloud.com/oembed'		=> array( 'auto_play', true)
			);
			foreach( $values as $oembed_url => $params )
			{
				if( strpos( $provider, $oembed_url ) !== false )
				{
					$provider = add_query_arg( $params[0], $params[1], $provider );
					break;
				}
			}
		}
		
		return $provider;
	}
	
	
	
	public function get_ow_embed_video( $url, $align, $aspect, $width = null, $autoplay = 0 )
	{
		$code = $this->ow_voting_before_video( $align, $aspect, $width );
		$code .= $this->ow_embed_video( $url, $autoplay );
		$code .= $this->ow_voting_after_video();
		return $code;
	}
	
	
	private function ow_voting_before_video( $align, $aspect, $width = null )
	{
		$code = '<div class="resp-video-' . $align . '"';
		if (is_singular('contestants')) {			
			$code .= ' style="width: ' . $width . '"';			
		} else {
			if( isset ( $width ) && $width != 100 ){
				$code .= ' style="max-width: ' . $width . 'px;"';
			}
			else{
				$code .= ' style="width: ' . $width . '%;"';
			}
		}
		$code .= '>';
		$code .= '<div class="ow_video_responsive ow-screen-' . $aspect . '">';
		return $code;
	}
	
	
	private function ow_embed_video( $url, $autoplay = 0 )
	{
		$regex = "/ (width|height)=\"[0-9\%]*\"/";
		$embed_code = wp_oembed_get( $url, array( 'width' => '100%', 'height' => '100%', 'autoplay' => $autoplay, 'rel' => 0 ) );
		if( !$embed_code )
		{
			return '<strong>' . __('Error: Invalid URL!', 'respvid') . '</strong>';
		}
		return preg_replace( $regex, '', $embed_code );
	}
	private function ow_voting_after_video()
	{
		$code = '</div>';
		$code .= '</div>';
		return $code;
	}
	private function ow_attributes_validations( $align, $aspect_ratio, $width, $autoplay )
	{
		$atts = null;
		if( $align != 'left' && $align != 'center' && $align != 'right' )
		{
			$atts['align'] = 'center';
		}
		else
		{
			$atts['align'] = $align;
		}
		
		$allowed_ratios = $this->ow_voting_aspect_ratio();
		
		if( !in_array( $aspect_ratio, $allowed_ratios ) )
		{
			$atts['aspect-ratio'] = '16:9';
		}
		else
		{
			$atts['aspect-ratio'] = $aspect_ratio;
		}
		$atts['aspect-ratio'] = str_replace( ':', '-', $atts['aspect-ratio'] );
		if (!is_singular('contestants')) {
			$width = intval( $width );
		}
		if( $width < 1 )
		{
			$atts['width'] = 100;
		}
		else
		{
			$atts['width'] = $width;
		}
		$autoplay = intval( $autoplay );
		if( $autoplay > 0 )
		{
			$atts['autoplay'] = 1;
		}
		else
		{
			$atts['autoplay'] = 0;
		}
		
		
		return $atts;
	}
	
	public function ow_voting_aspect_ratio()
	{
		$allowed = array(
			'3:1',
			'3:2',
			'4:3',
			'5:6',
			'16:9',
			'21:9',			
		);
		return $allowed;
	}
	
	public static function get_video_thumbnail( $src ) {
		$url_pieces = explode('/', $src);		
		if ( $url_pieces[2] == 'vimeo.com' ) { // If Vimeo
			$id = $url_pieces[3];
			$hash = unserialize(file_get_contents('https://vimeo.com/api/v2/video/' . $id . '.php'));
			$thumbnail = $hash[0]['thumbnail_large'];
		} elseif ( $url_pieces[2] == 'www.youtube.com' ) { // If Youtube
			$extract_id = explode('watch?v=', $url_pieces[3]);
			$id = $extract_id[1]; 
			$thumbnail = 'https://img.youtube.com/vi/' . $id . '/mqdefault.jpg';
		}
		return $thumbnail;
	}
		
    }
}else
die("<h2>".__('Failed to load Voting Video Controller','voting-contest')."</h2>");
return new Ow_Vote_OW_Video();