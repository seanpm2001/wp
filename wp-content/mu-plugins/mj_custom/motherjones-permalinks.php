<?php

if ( !class_exists( 'MJ_Permalinks' ) ) {
  class MJ_Permalinks {

    private static $instance;
    public static function instance() {
      if ( ! isset( self::$instance ) ) {
        self::$instance = new MJ_Permalinks;
        self::$instance->setup();
      }
      return self::$instance;
    }

    public function setup() {
			add_filter('init', array($this, 'create_url_querystring'));   
			add_filter('pre_get_posts', array($this, 'alter_the_query'));   
//			add_filter('rewrite_rules_array', array($this, 'permalink_rewrite'));   
//      add_action( 'wp_loaded', array($this, 'flush_rewrite_rules') );
    }

		public function flush_rewrite_rules() {
        $wp_rewrite->flush_rules();
    }

    public function create_url_querystring() {
      $blogtypes = get_terms( array(
        'taxonomy' => 'mj_blog_type',
        'hide_empty' => false,
      ) );
      foreach ($blogtypes as $blogtype) {
        add_rewrite_rule(
          '^' . $blogtype->slug . '/([^/]*)$',
          'index.php?blog=' . $blogtype->slug . '&postname=[1]',
          'top'
        );
        add_rewrite_rule(
          '^' . $blogtype->slug . '/?$',
          'index.php?blog=' . $blogtype->slug,
          'top'
        );
      }

      $mediatypes = get_terms( array(
        'taxonomy' => 'mj_media_type',
        'hide_empty' => false,
      ) );
      foreach ($mediatypes as $mediatype) {
        add_rewrite_rule(
          '^' . $mediatype->slug . '/',
          'index.php?mediatype=' . $mediatype->slug,
          'top'
        );
      }

    }

    public function alter_the_query( $request ) {

        // this is the actual manipulation; do whatever you need here
        /*
        if ($query['category_name'] && $query['name']) {
          $query['post_type'] = array('mj_article', 'mj_full_width');
          if (get_terms( array( // is blog post
              'slug' => $dummy_query->query['category_name'],
              'taxonomy' => 'mj_blog_type'
          ) ) ) {
            $request['post_type'] = 'mj_blog_post';
            $request['tax_query'] = array( array(
              'taxonomy' => 'mj_blog_type',
              'field' => 'slug',
              'terms' => $request['category_name'],
            ) );
            unset($request['category_name']);
          }
        } elseif ( preg_match('/^author\//', $dummy_query->query['category_name']) ) { //is author
          $request['post_type'] = array('mj_article', 'mj_full_width', 'mj_blog_post');

          $request['author_name'] = str_replace ('author/', '', $dummy_query->query['category_name']);
          $request['author_name'] = str_replace ('/page', '', $request['author_name']);

          if( $request['year'] ) {
            $request['paged'] = $request['year'];
          }

          $request['tax_query'] = array( array(
            'taxonomy' => 6,
            'field' => 'slug',
            'terms' => $request['author_name'],
          ) );
          unset($request['category_name']);
          unset($request['year']);
        }  elseif ( //is topic
          !get_terms( array(
            'taxonomy' => 'category', 
            'slug' => $request['category_name']
          ) ) &&
          get_terms( array(
            'taxonomy' => 'mj_primary_tag', 
            'slug' => $request['category_name']) 
          ) ) {
            $request['post_type'] = array('mj_article', 'mj_full_width', 'mj_blog_post');
            $request['tax_query'] = array( array(
              'taxonomy' => 'mj_primary_tag',
              'field' => 'slug',
              'terms' => $request['category_name'],
            ) );
            unset($request['category_name']);
        }  elseif ( //is media type
          !get_terms( array(
            'taxonomy' => 'category', 
            'slug' => $request['category_name']) 
          ) &&
          get_terms( array(
            'taxonomy' => 'mj_media_type', 
            'slug' => $request['category_name']) 
          ) ) {
            $request['post_type'] = array('mj_article', 'mj_full_width', 'mj_blog_post');
            $request['tax_query'] = array( array(
              'taxonomy' => 'mj_media_type',
              'field' => 'slug',
              'terms' => $request['category_name'],
            ) );
            unset($request['category_name']);
        }  elseif ( //is blog posts
          !get_terms( array(
            'taxonomy' => 'category', 
            'slug' => $request['category_name']) 
          ) &&
          get_terms( array(
            'taxonomy' => 'mj_blog_type', 
            'slug' => $request['category_name']) 
          ) ) {
            $request['post_type'] = array('mj_blog_post');
            $request['tax_query'] = array( array(
              'taxonomy' => 'mj_blog_type',
              'field' => 'slug',
              'terms' => $request['category_name'],
            ) );
            unset($request['category_name']);
        }
         */
        return $request;
    }

			// Adapted from get_permalink function in wp-includes/link-template.php
		public function permalink_rewrite($permalink, $post_id, $leavename) {
			$post = get_post($post_id);
			$rewritecode = array(
				'%year%',
				'%monthnum%',
				'%day%',
				'%hour%',
				'%minute%',
				'%second%',
				$leavename? '' : '%postname%',
				'%post_id%',
				'%category%',
				'%author%',
				$leavename? '' : '%pagename%',
			);

			if ( '' != $permalink && !in_array($post->post_status, array('draft', 'pending', 'auto-draft')) ) {
				$unixtime = strtotime($post->post_date);

				$category = '';
				if ( strpos($permalink, '%category%') !== false ) {
					$cats = get_the_category($post->ID);
					if ( $cats ) {
						usort($cats, '_usort_terms_by_ID'); // order by ID
						$category = $cats[0]->slug;
						if ( $parent = $cats[0]->parent )
							$category = get_category_parents($parent, false, '/', true) . $category;
					}
					// show default category in permalinks, without
					// having to assign it explicitly
					if ( empty($category) ) {
						$default_category = get_category( get_option( 'default_category' ) );
						$category = is_wp_error( $default_category ) ? '' : $default_category->slug;
					}
				}
				$author = '';
				if ( strpos($permalink, '%author%') !== false ) {
					$authordata = get_userdata($post->post_author);
					$author = $authordata->user_nicename;
				}

				$date = explode(" ",date('Y m d H i s', $unixtime));
				$rewritereplace =
					array(
						$date[0],
						$date[1],
						$date[2],
						$date[3],
						$date[4],
						$date[5],
						$post->post_name,
						$post->ID,
						$category,
						$author,
						$post->post_name,
					);
				$permalink = str_replace($rewritecode, $rewritereplace, $permalink);
			} else { // if they're not using the fancy permalink option
			}
			return $permalink;
		}

  }

  function MJ_Permalinks() {
    return MJ_Permalinks::instance();
  }
}
?>
