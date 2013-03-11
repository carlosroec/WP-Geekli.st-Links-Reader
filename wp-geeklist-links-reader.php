<?php
	/*
		Plugin Name: Geekli.st Links Reader
		Plugin URI: http://www.kooper.ec/wp-geekli-st-links-reader/
		Description: Get the last shared links for your Geekli.st account
		Version: 1.0
		Author: Carlos G. Rodríguez @carlosro_ec | @KooperEC
		Author URI: http://geekli.st/carlosro_ec
	*/
	
	include dirname( __FILE__ ) . '/dominikgehl/class.geeklist.php';

	class GeeklistLinksReader extends WP_Widget
	{
		function GeeklistLinksReader()
		{
			$widget_ops = array('classname' => 'GeeklistLinksReader', 'description' => 'Get the last shared links for a Geeklist account');
			$this->WP_Widget('GeeklistLinksReader', 'Geeklist Links Reader', $widget_ops);
		}

		function form($instance)
		{
			$instance = wp_parse_args((array) $instance, array('title' => '', 'consumer_key' => '', 'consumer_secret' => '', 'accesstoken_key' => '', 'accesstoken_secret' => '', 'max_links' => '5'));
			$title = $instance['title'];
			$consumer_key = $instance['consumer_key'];
			$consumer_secret = $instance['consumer_secret'];
			$accesstoken_key = $instance['accesstoken_key'];
			$accesstoken_secret = $instance['accesstoken_secret'];
			$max_links = $instance['max_links'];

			$widget_form = "<p><label for='" . $this->get_field_id('title') . "'>Title: <input class='widgetfat' id='" . $this->get_field_id('title') . "' name='" . $this->get_field_name('title') . "' type='text' value='" . attribute_escape($title) . "' /></label></p>";
			$widget_form .= "<p><label for='" . $this->get_field_id('consumer_key') . "'>CONSUMER KEY: <input class='widgetfat' id='" . $this->get_field_id('consumer_key') . "' name='" . $this->get_field_name('consumer_key') . "' type='text' value='" . attribute_escape($consumer_key) . "' /></label></p>";
			$widget_form .= "<p><label for='" . $this->get_field_id('consumer_secret') . "'>CONSUMER SECRET: <input class='widgetfat' id='" . $this->get_field_id('consumer_secret') . "' name='" . $this->get_field_name('consumer_secret') . "' type='text' value='" . attribute_escape($consumer_secret) . "' /></label></p>";
			$widget_form .= "<p><label for='" . $this->get_field_id('accesstoken_key') . "'>ACCESSTOKEN KEY: <input class='widgetfat' id='" . $this->get_field_id('accesstoken_key') . "' name='" . $this->get_field_name('accesstoken_key') . "' type='text' value='" . attribute_escape($accesstoken_key) . "' /></label></p>";
			$widget_form .= "<p><label for='" . $this->get_field_id('accesstoken_secret') . "'>ACCESSTOKEN SECRET: <input class='widgetfat' id='" . $this->get_field_id('accesstoken_secret') . "' name='" . $this->get_field_name('accesstoken_secret') . "' type='text' value='" . attribute_escape($accesstoken_secret) . "' /></label></p>";
			$widget_form .= "<p><label for='" . $this->get_field_id('max_links') . "'>MAX LINKS: <input class='widgetfat' id='" . $this->get_field_id('max_links') . "' name='" . $this->get_field_name('max_links') . "' type='text' value='" . attribute_escape($max_links) . "' /></label></p>";

			echo $widget_form;
		}

		function update($new_instace, $old_instance)
		{
			$instance = $old_instance;
			$instance['title'] = $new_instace['title'];
			$instance['consumer_key'] = $new_instace['consumer_key'];
			$instance['consumer_secret'] = $new_instace['consumer_secret'];
			$instance['accesstoken_key'] = $new_instace['accesstoken_key'];
			$instance['accesstoken_secret'] = $new_instace['accesstoken_secret'];
			$instance['max_links'] = $new_instace['max_links'];

			return $instance;
		}

		function widget($args, $instance)
		{
			extract($args, EXTR_SKIP);

			echo $before_widget;

			$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);

			if ( ! empty($title))
			{
				echo $before_title . $title . $after_title;
			}

			//CODe
			echo $this->get_links($instance);

			echo $after_widget;
		}

		function get_links($instance)
		{
			$consumer_key = $instance['consumer_key'];
			$consumer_secret = $instance['consumer_secret'];
			$accesstoken_key = $instance['accesstoken_key'];
			$accesstoken_secret = $instance['accesstoken_secret'];
			$max_links = $instance['max_links'];

			$geeklist = new Geeklist($consumer_key, $consumer_secret);
			$geeklist->setAccessToken($accesstoken_key, $accesstoken_secret);
			
			$result = $geeklist->getLinks(null, null, $max_links);

			$links_list = "";

			if ( ! $result->error )
			{
				$data = json_decode($result->response, true);
				
				$links_list = "<ul>";

				foreach ($data["data"]["links"] as $link) {
					$url = "http://geekli.st" . $link['permalink'];
					$title = $link['title'];

					$links_list .= "<li><a href='" . $url . "' target='_blank' >" . $title . "</a></li>";					
				}

				$links_list .= "</ul>";
			}
			else
			{
				$links_list = "<h5>Oops, something is wrong...</h5>";
			}

			return $links_list;
		}		
	}

	add_action('widgets_init', create_function('', 'return register_widget("GeeklistLinksReader");'));
?>