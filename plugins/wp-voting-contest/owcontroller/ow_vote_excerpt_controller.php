<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if(!class_exists('Ow_Vote_Excerpt_Controller')){

    class Ow_Vote_Excerpt_Controller {

        // Plugin configuration
        public $name;
        public $options;
        public $default_options = array(
            'length' => 40,
            'use_words' => 1,
            'no_custom' => 1,
            'no_shortcode' => 1,
            'finish_word' => 0,
            'finish_sentence' => 0,
            'ellipsis' => '',
            'read_more' => 'Read the rest',
            'add_link' => 0,
            'allowed_tags' => array('_all')
        );
		
        // Basic HTML tags (determines which tags are in the checklist by default)
        public static $options_basic_tags = array(
            'a', 'abbr', 'acronym', 'b', 'big',
            'blockquote', 'br', 'center', 'cite', 'code', 'dd', 'del', 'div', 'dl', 'dt',
            'em', 'form', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'hr', 'i', 'img', 'ins',
            'li', 'ol', 'p', 'pre', 'q', 's', 'small', 'span', 'strike', 'strong', 'sub',
            'sup', 'table', 'td', 'th', 'tr', 'u', 'ul'
        );
		
        // Almost all HTML tags (extra options)
        public static $options_all_tags = array(
            'a', 'abbr', 'acronym', 'address', 'applet',
            'area', 'b', 'bdo', 'big', 'blockquote', 'br', 'button', 'caption', 'center',
            'cite', 'code', 'col', 'colgroup', 'dd', 'del', 'dfn', 'dir', 'div', 'dl',
            'dt', 'em', 'fieldset', 'font', 'form', 'frame', 'frameset', 'h1', 'h2', 'h3',
            'h4', 'h5', 'h6', 'hr', 'i', 'iframe', 'img', 'input', 'ins', 'isindex', 'kbd',
            'label', 'legend', 'li', 'map', 'menu', 'noframes', 'noscript', 'object',
            'ol', 'optgroup', 'option', 'p', 'param', 'pre', 'q', 's', 'samp', 'script',
            'select', 'small', 'span', 'strike', 'strong', 'style', 'sub', 'sup', 'table',
            'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'tr', 'tt', 'u', 'ul',
            'var'
        );
		
        // Singleton
        private static $inst = null;

        public static function Instance($new = false) {
            if (self::$inst == null || $new) {
                self::$inst = new Ow_Vote_Excerpt_Controller();
            }
            return self::$inst;
        }

        public function __construct() {
            $this->name = strtolower(get_class());
            $this->load_options();
            // Replace the default filter (see /wp-includes/default-filters.php)
            //remove_filter('get_the_excerpt', 'wp_trim_excerpt');
            // Replace everything
            global $post;
            if(isset($post) && $post->post_type == OW_VOTES_TYPE){
                remove_all_filters('get_the_excerpt');
            }
            add_filter('get_the_excerpt', array(
                &$this,
                'filter'
            ));
        }

        public function filter($text) {
            global $post;
            if($post->post_type == OW_VOTES_TYPE){
            // Extract options (skip collisions)
            if (is_array($this->options)) {
                extract($this->options, EXTR_SKIP);
                $this->options = null; // Reset
            }
            extract($this->default_options, EXTR_SKIP);

            // Avoid custom excerpts
            if (!empty($text) && !$no_custom)
                return $text;
                

            // Get the full content and filter it
            $full_text = get_the_content('');            
            $text = get_the_content('');
            if (1 == $no_shortcode)
                $text = strip_shortcodes($text);
            $text = apply_filters('the_content', $text);

            // From the default wp_trim_excerpt():
            // Some kind of precaution against malformed CDATA in RSS feeds I suppose
            $text = str_replace(']]>', ']]&gt;', $text);

            // Determine allowed tags
            if (!isset($allowed_tags))
                $allowed_tags = self::$options_all_tags;

            if (isset($exclude_tags))
                $allowed_tags = array_diff($allowed_tags, $exclude_tags);

            // Strip HTML if allow-all is not set
            if (!in_array('_all', $allowed_tags)) {
                if (count($allowed_tags) > 0)
                    $tag_string = '<' . implode('><', $allowed_tags) . '>';
                else
                    $tag_string = '';
                $text = strip_tags($text, $tag_string);
            }

            // Create the excerpt
            $text = $this->text_excerpt($text, $length, $use_words, $finish_word, $finish_sentence);
            
            if(strlen($full_text) > strlen($text)){
                // Add the ellipsis or link
                $text = $this->text_add_more($text, $ellipsis, ($add_link) ? $read_more : false);
            }
            return $text;
            }
            else{
                return $text;
            }
        }

        public function text_excerpt($text, $length, $use_words, $finish_word, $finish_sentence) {
            $tokens = array();
            $out = '';
            $w = 0;

            // Divide the string into tokens; HTML tags, or words, followed by any whitespace
            // (<[^>]+>|[^<>\s]+\s*)
            preg_match_all('/(<[^>]+>|[^<>\s]+)\s*/u', $text, $tokens);
            foreach ($tokens[0] as $t) { // Parse each token
                if ($w >= $length && !$finish_sentence) { // Limit reached
                    break;
                }
                if ($t[0] != '<') { // Token is not a tag
                    if ($w >= $length && $finish_sentence && preg_match('/[\?\.\!]\s*$/uS', $t) == 1) { // Limit reached, continue until ? . or ! occur at the end
                        $out .= trim($t);
                        break;
                    }
                    if (1 == $use_words) { // Count words
                        $w++;
                    } else { // Count/trim characters
                        $chars = trim($t); // Remove surrounding space
                        $c = strlen($chars);
                        if ($c + $w > $length && !$finish_sentence) { // Token is too long
                            $c = ($finish_word) ? $c : $length - $w; // Keep token to finish word
                            $t = substr($t, 0, $c);
                        }
                        $w += $c;
                    }
                }
                // Append what's left of the token
                $out .= $t;
            }

            return trim(force_balance_tags($out));
        }

        public function text_add_more($text, $ellipsis, $read_more) {
            // New filter in WP2.9, seems unnecessary for now
            if ($read_more)
                $ellipsis .= sprintf(' <a href="%s" class="read_more">%s</a>', get_permalink(), $read_more);

            $pos = strrpos($text, '</');
            if ($pos !== false)
            // Inside last HTML tag
                $text = substr_replace($text, $ellipsis, $pos, 0);
            else
            // After the content
                $text .= $ellipsis;

            return $text;
        }

        public function install() {
            foreach ($this->default_options as $k => $v) {
                add_option($this->name . '_' . $k, $v);
            }
        }

        public function uninstall() {
            // Nothing to do (note: deactivation hook is also disabled)
        }

        private function load_options() {
            foreach ($this->default_options as $k => $v) {
                $this->default_options[$k] = get_option($this->name . '_' . $k, $v);
            }
        }

        private function update_options() {
            $length = (int) $_POST[$this->name . '_length'];
            $use_words = ('on' == $_POST[$this->name . '_use_words']) ? 1 : 0;
            $no_custom = ('on' == $_POST[$this->name . '_no_custom']) ? 1 : 0;
            $no_shortcode = ('on' == $_POST[$this->name . '_no_shortcode']) ? 1 : 0;
            $finish_word = ('on' == $_POST[$this->name . '_finish_word']) ? 1 : 0;
            $finish_sentence = ('on' == $_POST[$this->name . '_finish_sentence']) ? 1 : 0;
            $add_link = ('on' == $_POST[$this->name . '_add_link']) ? 1 : 0;

            // TODO: Drop magic quotes (deprecated in php 5.3)
            $ellipsis = (get_magic_quotes_gpc() == 1) ? stripslashes($_POST[$this->name . '_ellipsis']) : $_POST[$this->name . '_ellipsis'];
            $read_more = (get_magic_quotes_gpc() == 1) ? stripslashes($_POST[$this->name . '_read_more']) : $_POST[$this->name . '_read_more'];

            $allowed_tags = array_unique((array) $_POST[$this->name . '_allowed_tags']);

            update_option($this->name . '_length', $length);
            update_option($this->name . '_use_words', $use_words);
            update_option($this->name . '_no_custom', $no_custom);
            update_option($this->name . '_no_shortcode', $no_shortcode);
            update_option($this->name . '_finish_word', $finish_word);
            update_option($this->name . '_finish_sentence', $finish_sentence);
            update_option($this->name . '_ellipsis', $ellipsis);
            update_option($this->name . '_read_more', $read_more);
            update_option($this->name . '_add_link', $add_link);
            update_option($this->name . '_allowed_tags', $allowed_tags);

            $this->load_options();
            ?>
            <div id="message" class="updated fade settings_upd"><p><?php _e('Options saved.','voting-contest'); ?></p></div>
            <?php
        }

        public function page_options() {
            if ('POST' == $_SERVER['REQUEST_METHOD']) {
                check_admin_referer($this->name . '_update_options');
                $this->update_options();
            }
            extract($this->default_options, EXTR_SKIP);

            $ellipsis = htmlentities($ellipsis);
            $read_more = htmlentities($read_more);

            $tag_list = array_unique(self::$options_basic_tags + $allowed_tags);
            sort($tag_list);
            
			
			require_once(OW_VIEW_PATH.'ow_settings_excerpt_view.php');
			ow_settings_excerpt_view($this->name,$tag_list,self::$options_all_tags,$this->default_options);
		}

	}	    
}
else
die("<h2>".__('Failed to load the Voting Excerpt Controller','voting-contest')."</h2>");

return new Ow_Vote_Excerpt_Controller();
