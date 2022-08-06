<?php

/**
 * Plugin Name:       Film Guide
 * Description:       Film Guide provides an easy-to-use interface for creating and managing films.
 * Version:           1.0.0
 * Author:            Michael Landon
 */
function fb_activate_plugins() {

    $plugins = (!empty(get_option('active_plugins')) ? get_option('active_plugins') : array());
    $pugins_to_active = array(
        'wp-term-colors/wp-term-colors.php',
        'wp-term-images/wp-term-images.php',
        'simple-taxonomy-ordering/yikes-custom-taxonomy-order.php'
    );

    foreach ($pugins_to_active as $plugin) {
        if (!in_array($plugin, $plugins)) {
            array_push($plugins, $plugin);
            update_option('active_plugins', $plugins);
        }
    }
}

add_action('admin_init', 'fb_activate_plugins');

function AFIMovies() {

    $labels = array(
        'name' => _x('Films', 'post type general name'),
        'singular_name' => _x('Films', 'post type singular name'),
        'add_new' => _x('Add New Film', 'movies'),
        'add_new_item' => __('Add New Film'),
        'edit_item' => __('Edit Film'),
        'new_item' => __('New Film'),
        'all_items' => __('All Films'),
        'view_item' => __('View Film'),
        'search_items' => __('Search Films'),
        'not_found' => __('No Film found'),
        'not_found_in_trash' => __('No Film found in the Trash'),
        'menu_name' => 'Festival Guide'
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'menu_position' => 5,
        'query_var' => true,
        'rewrite' => false,
        'supports' => array('title', 'editor', 'thumbnail'),
        'has_archive' => true,
        'map_meta_cap' => true,
        'show_in_menu' => true,
        'capability_type' => array('bounty_product_t','movies'),
        'taxonomies' => array('post_tag'),
        'menu_icon' => 'dashicons-video-alt2',
    );
    register_post_type('movies', $args);
}

add_action('init', 'AFIMovies');

function movieVenue() {

    $labels = array(
        'name' => _x('Venues', 'taxonomy general name'),
        
        'singular_name' => _x('Venue', 'taxonomy singular name'),
        'search_items' => __('Search Venues'),
        'all_items' => __('All Venues'),
        'parent_item' => __('Parent Venue'),
        'parent_item_colon' => __('Parent Venue:'),
        'edit_item' => __('Edit Venue'),
        'update_item' => __('Update Venue'),
        'add_new_item' => __('Add New Venue'),
        'new_item_name' => __('New Venue Name'),
        'menu_name' => __('Venues'),
        'not_found' => __('No venues found')
    );

    register_taxonomy('venues', array('movies'), array(
        'hierarchical' => true,
         'capabilities' => array (
             'manage_terms' => 'add_venues',
                'edit_terms'   => 'edit_venues',
                'delete_terms' => 'delete_venues',
                'assign_terms' => 'assign_venues',
                
                ),
        'show_count' => true,
        'labels' => $labels,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'venues'),
        'description' => false,
        'show_ui' => true,
        'show_in_quick_edit' => false,
        'meta_box_cb' => false,
    ));
}

add_action('init', 'movieVenue', 0);

function movieYear() {

    $labels = array(
        'name' => _x('Festival Year', 'taxonomy general name'),
        'singular_name' => _x('Year', 'taxonomy singular name'),
        'search_items' => __('Search Years'),
        'all_items' => __('All Years'),
        'parent_item' => __('Parent Year'),
        'parent_item_colon' => __('Parent Year:'),
        'edit_item' => __('Edit Year'),
        'update_item' => __('Update Year'),
        'add_new_item' => __('Add New Year'),
        'new_item_name' => __('New Year Name'),
        'menu_name' => __('Festival Year'),
    );

    register_taxonomy('movie_year', array('movies'), array(
        'hierarchical' => true,
        'capabilities' => array (
             'manage_terms' => 'add_movie_year',
                'edit_terms'   => 'edit_movie_year',
                'delete_terms' => 'delete_movie_year',
                'assign_terms' => 'assign_movie_year',
                
                ),
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'movie-year'),
    ));
}

add_action('init', 'movieYear', 0);

function movieCategory() {

    $labels = array(
        'name' => _x('Festival Categories', 'taxonomy general name'),
        'singular_name' => _x('Category', 'taxonomy singular name'),
        'search_items' => __('Search Categories'),
        'all_items' => __('All Categories'),
        'parent_item' => __('Parent Category'),
        'parent_item_colon' => __('Parent Category:'),
        'edit_item' => __('Edit Category'),
        'update_item' => __('Update Category'),
        'add_new_item' => __('Add New Category'),
        'new_item_name' => __('New Category Name'),
        'menu_name' => __('Festival Categories'),
    );

    register_taxonomy('movie_type', array('movies'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'capabilities' => array (
             'manage_terms' => 'add_movie_type',
                'edit_terms'   => 'edit_movie_type',
                'delete_terms' => 'delete_movie_type',
                'assign_terms' => 'assign_movie_type',
                
                ),
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'meta_box_cb' => false,
        'rewrite' => array('slug' => 'movie-type'),
    ));
}

add_action('init', 'movieCategory', 0);

function registerFilmImport() {
    add_submenu_page('edit.php?post_type=movies', 'Festival Guide Data Importer', 'Festival Guide Data Importer', 'manage_options', 'film-import', 'filmImport');
}

add_action('admin_menu', 'registerFilmImport');

function filmImport() {
    ?>
    <div class="wrap">
        <h2><?php _e('Festival Guide Data Importer'); ?></h2>
        <p>Choose a WXR (.xml) file to upload, then click Upload file and import.</p>
        <form enctype="multipart/form-data" id="import-upload-form" method="post" class="wp-upload-form" action="<?php echo admin_url('admin-post.php'); ?>">
            <p>
                <label for="upload">Choose a file from your computer:</label> (Maximum size: 1 MB)
                <input type="file" id="upload" name="import" size="25" accept="text/xml">
                <input type="hidden" name="action" value="importfilms">
                <?php wp_nonce_field('importfilms', 'importfilms_nonce'); ?>
            </p>
            <p>
                <label>Enter Year: <input type="text" name="movie_year_cat" id="movie_year_cat" placeholder="Enter value of movie year category" style="width: 230px;"></label>
            </p>
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Upload file and import" disabled="">
            </p>
        </form>		
    </div>
    <?php
}

function importFilms() {


    if (!isset($_POST['importfilms_nonce']) || !wp_verify_nonce($_POST['importfilms_nonce'], 'importfilms'))
        return;

    $post_movie_year = array($_REQUEST['movie_year_cat']);
    $m_year = $_REQUEST['movie_year_cat'];
    
    $xml = json_decode(json_encode(simplexml_load_file($_FILES['import']['tmp_name'])), TRUE);
    foreach ($xml['RESULTSET']['ROW'] as $key => $value) {

        $rec_movies = array();
        if (!empty($value['COL'][23]['DATA']) || !empty($value['COL'][23]['DATA'])) {
            $rec_movies[] = $value['COL'][23]['DATA'];
        }
        if (!empty($value['COL'][24]['DATA']) || !empty($value['COL'][24]['DATA'])) {
            $rec_movies[] = $value['COL'][24]['DATA'];
        }
        if (!empty($value['COL'][25]['DATA']) || !empty($value['COL'][25]['DATA'])) {
            $rec_movies[] = $value['COL'][25]['DATA'];
        }
        
        $page_post = get_posts(array(
            'post_type' => 'movies',
            'title' => $value['COL'][0]['DATA'],
            'tax_query' => array(
                array(
                    'taxonomy' => 'movie_year',
                    'field' => 'slug',
                    'terms' => $_REQUEST['movie_year_cat'],
                )
            )
        ));
     
        if (empty($page_post)) {
          
            if (!empty($value['COL'][20]['DATA'])) {
                $screening['name'] = $value['COL'][20]['DATA'];
                $screening['dates'] = $value['COL'][21]['DATA'];
                $screening['time'] = $value['COL'][22]['DATA'];
               
                if (!empty($screening)) {
                    $timings = array();
                    $venues_arr = array();
                    foreach ($screening['name'] as $screeningKey => $screeningValue) {
                        if ($screeningValue != '' && !is_array($screeningValue)) {

                            wp_insert_category(array(
                                'cat_name' => trim($screeningValue),
                                'category_nicename' => preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower(trim($screeningValue))),
                                'taxonomy' => 'venues'
                            ));
                            $term_venues = get_term_by('slug', strtolower(trim($screeningValue)), 'venues');
                            $venues_arr[] = $term_venues->term_id;
                            $timings[] = trim($term_venues->term_id) . '-' . date('F j, Y h:i a', strtotime($screening['dates'][$screeningKey] . ' ' . $screening['time'][$screeningKey]));
                        }
                    }
                }
            }
            $content = '[vc_row][vc_column][vc_tta_tabs]';
            if (!empty($value['COL'][3]['DATA'])) {

                $desc = '';
                if (!empty($value['COL'][3]['DATA'])) {
                    $desc .= $value['COL'][3]['DATA'];
                }

                $content.=
                        '[vc_tta_section title="Description" tab_id="' . uniqid() . '"][vc_wp_text]
    			' . $desc . '    		
    			[/vc_wp_text][/vc_tta_section]';
            }
            if (!empty($value['COL'][4]['DATA'])) {
                $content.=
                        '[vc_tta_section title="Bios" tab_id="' . uniqid() . '"][vc_wp_text]
    			' . $value['COL'][4]['DATA'] . '
    			[/vc_wp_text][/vc_tta_section]';
            }

            if (!empty($value['COL'][6]['DATA'])) {
                $content.=
                        '[vc_tta_section title="Contact" tab_id="' . uniqid() . '"][vc_wp_text]
    			' . $value['COL'][6]['DATA'] . '
    			[/vc_wp_text][/vc_tta_section]';
            }

            if (!empty($value['COL'][5]['DATA'])) {
                $content.=
                        '[vc_tta_section title="Trailer" tab_id="' . uniqid() . '"]
    				[vc_video link="' . $value['COL'][5]['DATA'] . '"]
    			[/vc_tta_section]';
            }
            $content.='[/vc_tta_tabs][/vc_column][/vc_row]';
            $data = array
                (
                'post_type' => 'movies',
                'post_title' => $value['COL'][0]['DATA'],
                'post_content' => $content,
                'post_status' => 'publish',
                'comment_status' => 'closed', // if you prefer
                'ping_status' => 'closed', // if you prefer
            );
            $postId = wp_insert_post($data);
            $ticket_wi = $value['COL'][0]['DATA'];
            $event = str_replace(' ', '', $ticket_wi);
            if ($postId) {
                $str = str_replace(", ", "@", $value['COL'][7]['DATA']);
                $str = str_replace(",", "@", $str);
                $str = str_replace(" ,", "@", $str);

                $country_array = explode('@', $str);
                $countries = array();
                if (have_rows('countries_settings', 'option')):

                    while (have_rows('countries_settings', 'option')) : the_row();
                        $countries[] = get_sub_field('country_name', 'option');
                    endwhile;
                endif;
                foreach ($country_array as $country) {
                    if (empty($countries) || !(in_array($country, array_map("strtolower", $countries)) || in_array($country, array_map("strtoupper", $countries)) ) ) {
                        $row = array(
                            'country_name' => $country,
                        );
                        add_row('countries_settings', $row, 'option');
                    }
                }

                add_post_meta($postId, 'teaser-name', (!empty($value['COL'][2]['DATA']) ? $value['COL'][2]['DATA'] : NULL));
               
                add_post_meta($postId, 'movie-recommended_films', esc_attr(implode('@', $rec_movies)));
                add_post_meta($postId, 'movie-country', (!empty($value['COL'][7]['DATA']) ? $str : NULL));
                add_post_meta($postId, 'movie-year', (!empty($value['COL'][8]['DATA']) ? $value['COL'][8]['DATA'] : NULL));
                add_post_meta($postId, 'director', (!empty($value['COL'][9]['DATA']) ? $value['COL'][9]['DATA'] : NULL));
                add_post_meta($postId, 'screenwriters', (!empty($value['COL'][10]['DATA']) ? $value['COL'][10]['DATA'] : NULL));
                add_post_meta($postId, 'producers', (!empty($value['COL'][11]['DATA']) ? $value['COL'][11]['DATA'] : NULL));
                add_post_meta($postId, 'ep', (!empty($value['COL'][12]['DATA']) ? $value['COL'][12]['DATA'] : NULL));
                add_post_meta($postId, 'dop', (!empty($value['COL'][13]['DATA']) ? $value['COL'][13]['DATA'] : NULL));
                add_post_meta($postId, 'editor', (!empty($value['COL'][14]['DATA']) ? $value['COL'][14]['DATA'] : NULL));
                add_post_meta($postId, 'pd', (!empty($value['COL'][15]['DATA']) ? $value['COL'][15]['DATA'] : NULL));
                add_post_meta($postId, 'music', (!empty($value['COL'][16]['DATA']) ? $value['COL'][16]['DATA'] : NULL));
                add_post_meta($postId, 'credits', (!empty($value['COL'][17]['DATA']) ? $value['COL'][17]['DATA'] : NULL));
                add_post_meta($postId, 'running-time', (!empty($value['COL'][18]['DATA']) ? $value['COL'][18]['DATA'] : NULL));
                add_post_meta($postId, 'language', (!empty($value['COL'][19]['DATA']) ? $value['COL'][19]['DATA'] : NULL));
                wp_set_post_terms($postId, $venues_arr, 'venues');
                add_post_meta($postId, 'venue-datetime', (!empty($timings) ? esc_attr(implode('@', $timings)) : NULL));
                add_post_meta($postId, 'ticket-widget', $event);

                /* select film year */
                if (count($post_movie_year) > 0) {

                    foreach ($post_movie_year as $m_year) {
                        $term = get_term_by('slug', $m_year, 'movie_year');
                        $m_term_id = '';
                        if ($term != false) {
                            $m_term_id = $term->term_id;
                        } else {
                            $m_year_slug = strtolower(str_replace(" ", "-", $m_year));

                            $new_m_year = wp_insert_term(
                                    $m_year, // the term 
                                    'movie_year', // the taxonomy
                                    array(
                                'description' => '',
                                'slug' => $m_year_slug,
                                    )
                            );
                            $m_term_id = $new_m_year['term_id'];
                        }
                        if ($m_term_id != '') {
                            wp_set_post_terms($postId, $m_term_id, 'movie_year', true);
                        }
                    }
                }

                /* select movie categories */
                if (!empty($value['COL'][1]['DATA'])) {
                    $post_movie_types = explode(',', $value['COL'][1]['DATA']);
                    foreach ($post_movie_types as $m_type) {

                        $term_query = new WP_Term_Query(array('taxonomy' => 'movie_type', 'name' => $m_type, 'hide_empty' => false, 'meta_key' => 'festival_year', 'meta_value' => $m_year));
                        $m_term_id = '';
                        if (!empty($term_query->terms)) {
                            $terms = $term_query->terms;
                            $term = $terms[0];
                            $m_term_id = $term->term_id;
                            wp_set_post_terms($postId, $m_term_id, 'movie_type', true);
                        } else {
                            $m_type_slug = strtolower(str_replace(" ", "-", $m_type));
                            $new_m_type = wp_insert_term(
                                    $m_type, // the term 
                                    'movie_type', // the taxonomy
                                    array(
                                'description' => '',
                                'slug' => $m_type_slug . '-' . $m_year,
                                    )
                            );
                            if (!is_wp_error($new_m_type)) {
                                $m_term_id = $new_m_type['term_id'];
                                add_term_meta($m_term_id, 'festival_year', $m_year);
                                wp_set_post_terms($postId, $m_term_id, 'movie_type', true);
                            }
                        }
                        if ($m_term_id != '') {
                            wp_set_post_terms($postId, $m_term_id, 'movie_type', true);
                        }
                    }
                }
            }
        } else {

            $movie_post = get_page_by_title($value['COL'][0]['DATA'], OBJECT, 'movies');
            $postId = $movie_post->ID;

            if (!empty($value['COL'][20]['DATA'])) {
                $screening['name'] = $value['COL'][20]['DATA'];
                $screening['dates'] = $value['COL'][21]['DATA'];
                $screening['time'] = $value['COL'][22]['DATA'];

                if (!empty($screening)) {
                    $timings = array();
                    $venues_arr = array();
                    foreach ($screening['name'] as $screeningKey => $screeningValue) {
                        if ($screeningValue != '' && !is_array($screeningValue)) {

                            wp_insert_category(array(
                                'cat_name' => trim($screeningValue),
                                'category_nicename' => preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower(trim($screeningValue))),
                                'taxonomy' => 'venues'
                            ));
                            $term_venues = get_term_by('slug', strtolower(trim($screeningValue)), 'venues');
                            $venues_arr[] = $term_venues->term_id;
                            $timings[] = trim($term_venues->term_id) . '-' . date('F j, Y h:i a', strtotime($screening['dates'][$screeningKey] . ' ' . $screening['time'][$screeningKey]));
                        }
                    }
                }
            }

            $content = '[vc_row][vc_column][vc_tta_tabs]';
            if (!empty($value['COL'][3]['DATA'])) {


                $desc = '';
                if (!empty($value['COL'][3]['DATA'])) {
                    $desc .= $value['COL'][3]['DATA'];
                }

                $content.=
                        '[vc_tta_section title="Description" tab_id="' . uniqid() . '"][vc_wp_text]
    			' . $desc . '    			
    			[/vc_wp_text][/vc_tta_section]';
            }
            if (!empty($value['COL'][4]['DATA'])) {
                $content.=
                        '[vc_tta_section title="Bios" tab_id="' . uniqid() . '"][vc_wp_text]
    			' . $value['COL'][4]['DATA'] . '
    			[/vc_wp_text][/vc_tta_section]';
            }

            if (!empty($value['COL'][6]['DATA'])) {
                $content.=
                        '[vc_tta_section title="Contact" tab_id="' . uniqid() . '"][vc_wp_text]
    			' . $value['COL'][6]['DATA'] . '
    			[/vc_wp_text][/vc_tta_section]';
            }

            if (!empty($value['COL'][5]['DATA'])) {
                $content.=
                        '[vc_tta_section title="Trailer" tab_id="' . uniqid() . '"]
    				[vc_video link="' . $value['COL'][5]['DATA'] . '"]
    			[/vc_tta_section]';
            }
            $content.='[/vc_tta_tabs][/vc_column][/vc_row]';
            $data = array
                (
                'ID' => $postId,
                'post_type' => 'movies',
                'post_title' => $value['COL'][0]['DATA'],
                'post_content' => $content,
                'post_status' => 'publish',
                'comment_status' => 'closed', // if you prefer
                'ping_status' => 'closed', // if you prefer
            );

            wp_update_post($data);

            if ($postId) {
                $str = str_replace(", ", "@", $value['COL'][7]['DATA']);
                $str = str_replace(",", "@", $str);
                $str = str_replace(" ,", "@", $str);
                $country_array = explode('@', $str);
                $countries = array();
                if (have_rows('countries_settings', 'option')):

                    while (have_rows('countries_settings', 'option')) : the_row();
                        $countries[] = get_sub_field('country_name', 'option');
                    endwhile;
                endif;
                foreach ($country_array as $country) {
                  
                    if (empty($countries) || !(in_array($country, array_map("strtolower", $countries)) || in_array($country, array_map("strtoupper", $countries)) ) ) {
                        $row = array(
                            'country_name' => $country,
                        );
                        add_row('countries_settings', $row, 'option');
                    }
                }
                $ticket_wi = $value['COL'][0]['DATA'];
                $event = str_replace(' ', '', $ticket_wi);
                update_post_meta($postId, 'teaser-name', (!empty($value['COL'][2]['DATA']) ? $value['COL'][2]['DATA'] : NULL));
             
                update_post_meta($postId, 'movie-recommended_films', esc_attr(implode('@', $rec_movies)));
                update_post_meta($postId, 'movie-country', (!empty($value['COL'][7]['DATA']) ? $str : NULL));
                add_post_meta($postId, 'movie-year', (!empty($value['COL'][8]['DATA']) ? $value['COL'][8]['DATA'] : NULL));
                update_post_meta($postId, 'director', (!empty($value['COL'][9]['DATA']) ? $value['COL'][9]['DATA'] : NULL));
                update_post_meta($postId, 'screenwriters', (!empty($value['COL'][10]['DATA']) ? $value['COL'][10]['DATA'] : NULL));
                update_post_meta($postId, 'producers', (!empty($value['COL'][11]['DATA']) ? $value['COL'][11]['DATA'] : NULL));
                update_post_meta($postId, 'ep', (!empty($value['COL'][12]['DATA']) ? $value['COL'][12]['DATA'] : NULL));
                update_post_meta($postId, 'dop', (!empty($value['COL'][13]['DATA']) ? $value['COL'][13]['DATA'] : NULL));
                update_post_meta($postId, 'editor', (!empty($value['COL'][14]['DATA']) ? $value['COL'][14]['DATA'] : NULL));
                update_post_meta($postId, 'pd', (!empty($value['COL'][15]['DATA']) ? $value['COL'][15]['DATA'] : NULL));
                update_post_meta($postId, 'music', (!empty($value['COL'][16]['DATA']) ? $value['COL'][16]['DATA'] : NULL));
                update_post_meta($postId, 'credits', (!empty($value['COL'][17]['DATA']) ? $value['COL'][17]['DATA'] : NULL));
                update_post_meta($postId, 'running-time', (!empty($value['COL'][18]['DATA']) ? $value['COL'][18]['DATA'] : NULL));
                update_post_meta($postId, 'language', (!empty($value['COL'][19]['DATA']) ? $value['COL'][19]['DATA'] : NULL));
                update_post_meta($postId, 'venue-datetime', (!empty($timings) ? esc_attr(implode('@', $timings)) : NULL));
                wp_set_post_terms($postId, $venues_arr, 'venues');
                update_post_meta($postId, 'ticket-widget', $event);

                /* select film year */
                if (count($post_movie_year) > 0) {

                    foreach ($post_movie_year as $m_year) {
                        $term = get_term_by('slug', $m_year, 'movie_year');
                        $m_term_id = '';
                        if ($term != false) {
                            $m_term_id = $term->term_id;
                        } else {
                            $m_year_slug = strtolower(str_replace(" ", "-", $m_year));

                            $new_m_year = wp_insert_term(
                                    $m_year, // the term 
                                    'movie_year', // the taxonomy
                                    array(
                                'description' => '',
                                'slug' => $m_year_slug,
                                    )
                            );
                            $m_term_id = $new_m_year['term_id'];
                        }
                        if ($m_term_id != '') {
                            wp_set_post_terms($postId, $m_term_id, 'movie_year', true);
                        }
                    }
                }

                /* select movie categories */
                if (!empty($value['COL'][1]['DATA'])) {
                    $post_movie_types = explode(',', $value['COL'][1]['DATA']);
                    foreach ($post_movie_types as $m_type) {

                        $term_query = new WP_Term_Query(array('taxonomy' => 'movie_type', 'name' => $m_type, 'hide_empty' => false, 'meta_key' => 'festival_year', 'meta_value' => $m_year));
                        $m_term_id = '';
                        if (!empty($term_query->terms)) {

                            $terms = $term_query->terms;
                            $term = $terms[0];
                            $m_term_id = $term->term_id;

                            wp_set_post_terms($postId, $m_term_id, 'movie_type', true);
                        } else {
                            $m_type_slug = strtolower(str_replace(" ", "-", $m_type));
                            $new_m_type = wp_insert_term(
                                    $m_type, // the term 
                                    'movie_type', // the taxonomy
                                    array(
                                'description' => '',
                                'slug' => $m_type_slug . '-' . $m_year,
                                    )
                            );
                            if (!is_wp_error($new_m_type)) {

                                $m_term_id = $new_m_type['term_id'];

                                wp_set_post_terms($postId, $m_term_id, 'movie_type', true);
                                add_term_meta($m_term_id, 'festival_year', $m_year);
                            } else {
                                if ($value['COL'][0]['DATA'] == 'WIDOWS') {
                                    echo $new_m_type->get_error_message();
                                }
                            }
                        }
                        if ($m_term_id != '') {

                            wp_set_post_terms($postId, $m_term_id, 'movie_type', true);
                        }
                    }
                }
            }
        }
    }

    wp_redirect(get_site_url() . '/wp-admin/edit.php?post_type=movies');
}

add_action('admin_post_importfilms', 'importFilms');

function registerFilmSettings() {
    add_submenu_page('edit.php?post_type=movies', 'Festival Settings', 'Festival Settings', 'manage_options', 'film-settings', 'filmSettings');
}

add_action('admin_menu', 'registerFilmSettings');

function filmSettings() {
    $default = get_stylesheet_directory_uri() . '/images/filmGuide-imgOverlay.png';
    $overlayImage = (!empty(get_option('overlay_image')) ? get_option('overlay_image') : $default);
    $display = (($default == $overlayImage) ? "display:none;" : "");
    ?>
    <div class="wrap">
        <h2><?php _e('Festival Settings'); ?></h2>
        <!-- If we have any error by submiting the form, they will appear here -->

        <form enctype="multipart/form-data" method="post" class="wp-upload-form" action="<?php echo admin_url('admin-post.php'); ?>">
            <h2>Manage Overlay Image for Festival Categories.</h2>
            <h3 id="overlay-error" style="color: red;display:none">File exceeds the allowed limit of dimensions</h3>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row">Upload Overlay Image</th>
                        <td>        
                            <input type="hidden" id="overlay_image_url" name="overlay_image_url" value="<?php echo $default ?>">
                            <input type="hidden" name="action" value="uploadoverlayimage">
                                    <!--<input id="delete_overlay_image" type="submit" class="button reset-to-default" value="Delete Overlay Image">-->
                            <input id="upload_overlay_image" type="button" class="button" value="Upload Image">&nbsp;&nbsp;&nbsp;<span>Allowed dimension for overlay image is width(858) X height(266)</span>
                            <?php wp_nonce_field('uploadoverlayimage', 'uploadoverlayimage_nonce'); ?>

                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Overlay Image Preview</th>
                        <td>    
                            <div style=" min-height: 100px;">
                                <img id="overlay_image_preview" style="<?php echo $display; ?> max-width:100%;" src="<?php echo $overlayImage ?>">
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="submit">
                <input id="submit_options_form" type="submit" class="button-primary" value="Save Image" />
                <input id="reset_to_default" type="submit" class="button-secondary reset-to-default" value="Reset To Default"/>
            </p>
        </form>
    </div>
    <script>
        var $ = jQuery.noConflict();
        $(document).ready(function () {
            event.preventDefault();
            var mediaUploader;
            $('#upload_overlay_image').click(function (event) {

                mediaUploader = wp.media.frames.file_frame = wp.media({
                    title: 'Choose an image',
                    button: {
                        text: 'Set an overlay image',
                    },
                    library: {type: 'image'},
                    multiple: false
                });
                mediaUploader.on('select', function () {
                    
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    console.log(attachment);
                    if (attachment.height <= 266 && attachment.width <= 858)
                    {
                        $("#overlay_image_url").val(attachment.url);
                        $("#overlay_image_preview").attr('src', attachment.url);
                        $('#submit_options_form').prop('disabled', false);
                        $('#reset_to_default').prop('disabled', false);
                        $("#overlay-error").hide();
                        $("#overlay_image_preview").show();
                        //$('#delete_overlay_image').prop('disabled', false);
                    }
                    else
                    {
                        $("#overlay-error").show();
                    }
                });
                mediaUploader.open();
            });
            $('.reset-to-default').click(function (event) {
                event.preventDefault();
                $("#overlay_image_url").val('<?php echo $default ?> ');
                $("#overlay_image_preview").attr('src', '<?php echo $default ?>');
            })
        });
    </script>
    <?php
}

function uploadOverlayImage() {

    if (!isset($_POST['uploadoverlayimage_nonce']) || !wp_verify_nonce($_POST['uploadoverlayimage_nonce'], 'uploadoverlayimage'))
        return;
    update_option('overlay_image', $_POST['overlay_image_url']);

    wp_redirect(get_site_url() . '/wp-admin/edit.php?post_type=movies&page=film-settings');
}

add_action('admin_post_uploadoverlayimage', 'uploadOverlayImage');

function categoryStatus($term) {
    $termId = $term->term_id;
    $checked = get_option("tax_" . $termId);
    ?>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="catpic"><?php _e('Status', ''); ?></label>
        </th>
        <td>
            <input type="checkbox" id="afi_enable_category" name="afi_enable_category" value="1" <?php checked($checked, 1, true); ?> > Option to enable/disable category
        </td>
    </tr>
    <?php
}

add_action('movie_type_add_form_fields', 'categoryStatus');
add_action('movie_type_edit_form_fields', 'categoryStatus');

function saveCategoryStatus($termId) {
    if (isset($_POST['afi_enable_category'])) {
        $catMeta = get_option("tax_" . $termId);
        if ($cat_meta !== false) {
            update_option("tax_" . $termId, $_POST['afi_enable_category']);
        } else {
            add_option("tax_" . $termId, $_POST['afi_enable_category'], '', 'yes');
        }
    }
}

add_action('create_movie_type', 'saveCategoryStatus');
add_action('edited_movie_type', 'saveCategoryStatus');

function remove_description_form() {
    echo "<style> .term-description-wrap { display:none; } </style>";
}

add_action("venues_edit_form", 'remove_description_form');
add_action("venues_add_form", 'remove_description_form');

function remove_taxonomy_description($columns) {
    if (!isset($_GET['taxonomy']) || $_GET['taxonomy'] != 'venues') {
        return $columns;
    }

    if ($posts = $columns['description']) {
        unset($columns['description']);
    }
    return $columns;
}

add_filter('manage_edit-venues_columns', 'remove_taxonomy_description');

function detail_meta_box() {
    global $_wp_post_type_features;

    add_meta_box(
            $id = 'page_details_meta_box', $title = __('Details'), $callback = 'render_movie_details_metabox', $post_type = 'movies', $context = 'normal', $priority = 'core'
    );
}

add_action('add_meta_boxes', 'detail_meta_box');

function screeing_meta_box() {
    global $_wp_post_type_features;

    add_meta_box(
            $id = 'page_screening_meta_box', $title = __('Screenings'), $callback = 'render_movie_screeing_metabox', $post_type = 'movies', $context = 'normal', $priority = 'core'
    );
}

add_action('add_meta_boxes', 'screeing_meta_box');

function buy_ticket_meta_box() {
    global $_wp_post_type_features;

    add_meta_box(
            $id = 'page_ticket_meta_box', $title = __('Ticket widget'), $callback = 'render_movie_ticket_metabox', $post_type = 'movies', $context = 'normal', $priority = 'core'
    );
}

add_action('add_meta_boxes', 'buy_ticket_meta_box');

function date_meta_box() {
    global $_wp_post_type_features;

    add_meta_box(
            $id = 'page_date_meta_box', $title = __('Manage Dates'), $callback = 'render_date_screeing_metabox', $post_type = 'movies', $context = 'normal', $priority = 'low'
    );
}

//add_action('add_meta_boxes', 'date_meta_box');

function render_movie_ticket_metabox($post) {
    ?>
    <h3>Ticket widget name</h3>
    <p>
        <input style="width: 50%;height: 36px;" type="text" name="ticket-widget" value="<?php echo get_post_meta($post->ID, "ticket-widget", true) ?>"/> 
    </p>
    <?php
}

function render_movie_details_metabox($post) {
    global $post;
    $directors = get_post_meta($post->ID, "director", true);
    $teaser_name = get_post_meta($post->ID, "teaser-name", true);
    $movie_year_detail = get_post_meta($post->ID, "movie-year", true);
    $screen_writers = get_post_meta($post->ID, "screenwriters", true);
    $producer_detail = get_post_meta($post->ID, "producers", true);
    $executive_producer_detail = get_post_meta($post->ID, "ep", true);
    $director_ph_detail = get_post_meta($post->ID, "dop", true);
    $editor_detail = get_post_meta($post->ID, "editor", true);
    $production_designer_detail = get_post_meta($post->ID, "pd", true);
    $music_detail = get_post_meta($post->ID, "music", true);
    $runnig_rime_detail = get_post_meta($post->ID, "running-time", true);
    $language_detail = get_post_meta($post->ID, "language", true);
    $credits_detail = get_post_meta($post->ID, "credits", true);
    $contact_detail = get_post_meta($post->ID, "contact", true);
    $country = explode('@', get_post_meta($post->ID, "movie-country", true));
    wp_nonce_field('movie_meta_box', 'movie_meta_box_nonce');
    $currently_selected = date('Y');
    $earliest_year = 1900;
    $latest_year = date('Y');

    $r_movies = explode('@', get_post_meta($post->ID, "movie-recommended_films", true));

    $teaser_image = get_post_meta($post->ID, "teaser-image", true);
    $display = 'none';
    $teaser_image_url = '';
    if ($teaser_image != '') {
        $display = 'block';
        $teaser_image_url = wp_get_attachment_url($teaser_image);
    }
    ?>
    <div class='inside'>
        <h3>Teaser Name</h3>
        <p>
            <input style="width: 100%;height: 36px;" type="text" name="teaser-name" value="<?php echo $teaser_name; ?>"/> 
        </p>
        <h3>Teaser Image</h3>
        <p>
            <a href="#" class="teaser_upload_image_button teaser_image_action">Upload image</a>
            <a href="#" class="teaser_remove_image_button teaser_image_action" style="display:none">Remove image</a>
        <div>
            <input type="hidden" id="teaser-pic" name="teaser-image" value="<?php echo $teaser_image; ?>">
            <img id="teaser-data" src="<?php echo $teaser_image_url; ?>" style="display: <?php echo $display; ?>; max-width: 100%; color: #555;border-color: #ccc;background: #f7f7f7;box-shadow: 0 1px 0 #ccc;vertical-align: top;" >
        </div>
    </p>
    <?php $cc=$arr= implode(",",$country); ?>
    <h3>Country</h3>
    <p>
    <input type="hidden" name="get_selected_c" id="get_selected_c" value="<?php echo $cc;?>">
        <select data-placeholder="Select Country..." class="chosen-detail" multiple name="movie-country[]" id="movie_country_dy">
            <option value=""></option>
            <?php
            if (have_rows('countries_settings', 'option')):

                // loop through the rows of data
                while (have_rows('countries_settings', 'option')) : the_row();
                    $country_in = get_sub_field('country_name', 'option');
                    ?>
                    <option <?php if (in_array($country_in, $country)) echo 'selected="selected"'; ?> value="<?php echo $country_in; ?>"><?php echo $country_in; ?></option>
                    <?php
                endwhile;

            else :


            endif;
            ?>   
        </select>
    </p>
    <h3>Recommended Films</h3>
    <p>
        <select data-placeholder="Select Recommended Films..." class="chosen-detail" multiple name="movie-recommended_films[]">
            <option value=""></option>
            <?php
            $args = array(
                'post_type' => 'movies',
                'post_status' => 'publish',
                'posts_per_page' => -1
            );
            $movie_query = new WP_Query($args);

            if ($movie_query->have_posts()) {

                while ($movie_query->have_posts()) {
                    $movie_query->the_post();
                    $m_title = get_the_title(get_the_ID());
                    ?>
                    <option <?php if (in_array($m_title, $r_movies)) echo 'selected="selected"'; ?> value="<?php echo $m_title ?>"><?php echo $m_title ?></option>;
                    <?php
                }
            }
            /* Restore original Post Data */
            wp_reset_postdata();
            ?>
        </select>
    </p>
    <h3>Year</h3>
    <p>

        <select data-placeholder="Select Year..." class="chosen-detail" name="movie-year">
            <option value=""></option>
            <?php foreach (range($latest_year, $earliest_year) as $i) { ?>
                <option <?php selected($movie_year_detail, $i); ?> value="<?php echo $i ?>"><?php echo $i ?></option>
            <?php } ?>
        </select>
    </p>
    <h3>Director</h3>
    <p>
        <input type="text" class="tagit" name="director" value="<?php echo $directors; ?>" /> 
    </p>
    <h3>Screenwriters</h3>
    <p>
        <input type="text" class="tagit" name="screenwriters" value="<?php echo $screen_writers; ?>" /> 
    </p>
    <h3>Producers</h3>
    <p>
        <input type="text" class="tagit" name="producers" value="<?php echo $producer_detail; ?>" /> 
    </p>
    <h3>Executive Producers</h3>
    <p>
        <input type="text" class="tagit" name="ep" value="<?php echo $executive_producer_detail; ?>" /> 
    </p>
    <h3>Director of Photography</h3>
    <p>

        <input type="text" class="tagit" name="dop" value="<?php echo $director_ph_detail; ?>" /> 
    </p>
    <h3>Editor</h3>
    <p>
        <input type="text" class="tagit" name="editor" value="<?php echo $editor_detail; ?>" /> 
    </p>
    <h3>Production Designer</h3>
    <p>
        <input type="text" class="tagit" name="pd" value="<?php echo $production_designer_detail; ?>" /> 
    </p>
    <h3>Music</h3>
    <p>
        <input type="text" class="tagit" name="music" value="<?php echo $music_detail; ?>" /> 
    </p>
    <h3>Running Time (minutes)</h3>
    <p>
        <input style="width: 100%;height: 36px;" type="text" name="running-time"  value="<?php echo $runnig_rime_detail; ?>"/>
    </p> 
    <h3>Language</h3>
    <p>
        <input type="text" class="tagit" name="language" value="<?php echo $language_detail; ?>" /> 
    </p>
    <h3>Credits</h3>
    <p>
        <input type="text" class="tagit" name="credits" value="<?php echo $credits_detail; ?>" /> 
    </p>
    </div>

    <script>
        var $ = jQuery.noConflict();
        $(document).ready(function () {
         
            var metaImageFrame;
            $('.tagit').tagit({
                allowSpaces: true
            });
            $(".chosen-detail").chosen({
                disable_search_threshold: 10,
                width: "100%"
            });
            $('.start-date,.end-date').datepicker();
            $('.asl-date,.go-live-date,.tbc-date,.tbo-date').datetimepicker();
            $('.datetime').datetimepicker({
                timeFormat: 'hh:mm tt'
            });
            $('.teaser_upload_image_button').click(function (event) {
                event.preventDefault();
                metaImageFrame = wp.media.frames.metaImageFrame = wp.media({
                    title: 'Choose an image',
                    button: {
                        text: 'Set a teaser image',
                    },
                    multiple: false
                });
                metaImageFrame.on('select', function () {
                    var attachment = metaImageFrame.state().get('selection').first().toJSON();
                    console.log(attachment);
                    $("#teaser-pic").val(attachment.id);
                    $("#teaser-data").attr('src', attachment.url);
                    $("#teaser-data").show();
                    $(".teaser_remove_image_button").show();
                });
                metaImageFrame.open();
            });
            $('.teaser_remove_image_button').click(function (event) {
                event.preventDefault();
                $("#teaser-pic").val('');
                $("#teaser-data").attr('src', '');
                $("#teaser-data").hide();
                $(".teaser_remove_image_button").hide();
            });
        });
            jQuery('#publish').click(function() {
                    var selected=[];
                    jQuery('#movie_country_dy_chosen .chosen-choices .search-choice').each(function(){
                    selected.push(jQuery(this).find('span').text());
        
                });
                jQuery('#get_selected_c').val(selected);
        
                });
       jQuery('#movie_country_dy,#movie_country_dy .chosen-choices').change(function() {
        
        var selected=[];
        $('#movie_country_dy_chosen .chosen-choices .search-choice').each(function(){
        selected.push($(this).find('span').text());
        
        });
        jQuery('#get_selected_c').val(selected);
        
        });
    </script>
    <?php
}

function render_movie_screeing_metabox($post) {
    wp_nonce_field('movie_meta_box', 'movie_meta_box_nonce');
    $terms = get_terms([ 'taxonomy' => 'venues', 'hide_empty' => false]);
    $savedVenue = explode('@', get_post_meta($post->ID, "venue-datetime", true));
   
    $toRender = (!empty($savedVenue) ? count($savedVenue) : 1);
    foreach ($terms as $key => $value) {
        $venues[] = $value->name . '@' . $value->term_id;
    }
    ?>
    <div class='inside'>
        <div class="screening-field">
            <a class="add_screening button-secondary" style="margin-bottom: 5px;">Add Venue</a>

            <?php
            for ($i = 0; $i < $toRender; $i++) {
                $venue = explode('-', $savedVenue[$i]);
                ?>
                <div>
                    <select data-placeholder="Select Venue..." class="chosen-screening" name="venue[]">
                        <option value=""></option>
                        <?php
                        foreach ($venues as $key => $value) {
                            $string_venue = $value;
                            $arr_venue = explode("@", $string_venue, 2);
                            $first = $arr_venue[0];
                            ?>
                            <option <?php selected($venue[0], $arr_venue[1]); ?> value="<?php echo $value ?>"><?php echo $first ?></option>
                        <?php } ?>
                    </select>
                    <input autocomplete="off" readonly="" style="width: 46%;height: 28px;" type="text" class="datetime" name="datetime[]" value="<?php echo $venue[1] ?>" placeholder="Enter Date Time"/> 
                    <?php if ($i >= 0) { ?>
                        <a href="#" class="remove_field" style="padding-left: 0px;">Remove</a>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>

        </div>
    </div>
    <script>
        var $ = jQuery.noConflict();
        $(document).ready(function () {

            var max_fields = 10;
            var x = 1;
            $(".chosen-screening").chosen({
                disable_search_threshold: 10,
                width: "46%"
            });
            var venues = <?php echo json_encode($venues) ?>;


            $('.add_screening').click(function (event) {
                event.preventDefault();
                if (x < max_fields) {
                    x++;
                    var html =
                            '<div>' +
                            '<select data-placeholder="Select Venue..." class="chosen-screening" name="venue[]">' +
                            '<option value=""></option>';
                    if (venues) {
                        for (i = 0; i < venues.length; i++) {
                            var venues_i = venues[i].split('@');
                            var venues_name = venues_i['0'];
                            var venues_val = venues_i['1'];
                            html += '<option value="' + venues[i] + '">' + venues_name + '</option>';
                        }
                    }
                    html +=
                            '</select> ' +
                            '<input autocomplete="off" readonly=""  style="width: 46%;height: 28px;" type="text" class="datetime" name="datetime[]" value="" placeholder="Enter Date Time"/>' +
                            '<a href="#" class="remove_field" style="padding-left: 3px;">Remove</a>' +
                            '<div>';
                    $('.screening-field').append(html);
                    $(".chosen-screening").chosen({
                        disable_search_threshold: 10,
                        width: "46%"
                    });
                    $('.datetime').datetimepicker({
                        timeFormat: 'hh:mm tt'
                    });
                }
            });
            $('.screening-field').on('click', '.remove_field', function (event) {
                event.preventDefault();
                $(this).parent('div').remove();
                x--;
            });
        });
    </script>
    <?php
}

function render_date_screeing_metabox($post) {
    wp_nonce_field('movie_meta_box', 'movie_meta_box_nonce');
    ?>
    <div class='inside'>
        <h3>Start Date</h3>
        <p>
            <input style="width: 100%;height: 36px;" type="text" name="start-date" class="start-date" value="<?php echo get_post_meta($post->ID, "start-date", true) ?>" placeholder=""/> 
        </p>
        <h3>End Date</h3>
        <p>
            <input style="width: 100%;height: 36px;" type="text" name="end-date" class="end-date" value="<?php echo get_post_meta($post->ID, "end-date", true) ?>" placeholder=""/> 
        </p>
        <h3>Go Live Date</h3>
        <p>
            <input style="width: 100%;height: 36px;" type="text" name="go-live-date" class="go-live-date" value="<?php echo get_post_meta($post->ID, "go-live-date", true) ?>" placeholder=""/> 
        </p>
        <h3>Booking Opening Date</h3>
        <p>
            <input style="width: 100%;height: 36px;" type="text" name="tbo-date" class="tbo-date" value="<?php echo get_post_meta($post->ID, "tbo-date", true) ?>" placeholder=""/> 
        </p>
        <h3>Booking Closing Date</h3>
        <p>
            <input style="width: 100%;height: 36px;" type="text" name="tbc-date" class="tbc-date"  value="<?php echo get_post_meta($post->ID, "tbc-date", true) ?>" placeholder=""/> 
        </p>
        <h3>Award Live Date</h3>
        <p>
            <input style="width: 100%;height: 36px;" type="text" name="asl-date" class="asl-date" value="<?php echo get_post_meta($post->ID, "asl-date", true) ?>" placeholder=""/> 
        </p>
    </div>
    <?php
}

function movie_meta_box_save($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (!isset($_POST['movie_meta_box_nonce']) || !wp_verify_nonce($_POST['movie_meta_box_nonce'], 'movie_meta_box'))
        return;

    if (!current_user_can('edit_post'))
        return;
       
  $str= $_POST['get_selected_c'];
  $str=explode(",",$str);
 
    update_post_meta($post_id, 'teaser-name', (!empty($_POST['teaser-name']) ? esc_attr($_POST['teaser-name']) : NULL));
    update_post_meta($post_id, 'teaser-image', (!empty($_POST['teaser-image']) ? esc_attr($_POST['teaser-image']) : NULL));
    update_post_meta($post_id, 'movie-country', (!empty($str) ? esc_attr(implode('@', $str)) : NULL));
    update_post_meta($post_id, 'movie-recommended_films', (!empty($_POST['movie-recommended_films']) ? esc_attr(implode('@', $_POST['movie-recommended_films'])) : NULL));
    update_post_meta($post_id, 'movie-year', (!empty($_POST['movie-year']) ? esc_attr($_POST['movie-year']) : NULL));

    update_post_meta($post_id, 'director', (!empty($_POST['director']) ? esc_attr($_POST['director']) : NULL));
    update_post_meta($post_id, 'screenwriters', (!empty($_POST['screenwriters']) ? esc_attr($_POST['screenwriters']) : NULL));
    update_post_meta($post_id, 'producers', (!empty($_POST['producers']) ? esc_attr($_POST['producers']) : NULL));
    update_post_meta($post_id, 'ep', (!empty($_POST['ep']) ? esc_attr($_POST['ep']) : NULL));
    update_post_meta($post_id, 'dop', (!empty($_POST['dop']) ? esc_attr($_POST['dop']) : NULL));
    update_post_meta($post_id, 'editor', (!empty($_POST['editor']) ? esc_attr($_POST['editor']) : NULL));
    update_post_meta($post_id, 'pd', (!empty($_POST['pd']) ? esc_attr($_POST['pd']) : NULL));
    update_post_meta($post_id, 'music', (!empty($_POST['music']) ? esc_attr($_POST['music']) : NULL));

    update_post_meta($post_id, 'running-time', (!empty($_POST['running-time']) ? esc_attr($_POST['running-time']) : NULL));
    update_post_meta($post_id, 'language', (!empty($_POST['language']) ? esc_attr($_POST['language']) : NULL));
    update_post_meta($post_id, 'credits', (!empty($_POST['credits']) ? esc_attr($_POST['credits']) : NULL));

    $venueDateTime = array();
    $term_meta_entry = array();
    if (isset($_POST['venue']) && isset($_POST['datetime'])) {

        foreach ($_POST['venue'] as $key => $value) {
            if ($value != '' && $_POST['datetime'][$key] != '') {
                $combination = explode("@", $value, 2);
                $term_meta_entry[] = $combination['1'];
                $venueDateTime[] = $combination['1'] . '-' . $_POST['datetime'][$key];
            }
        }
    }
  
    wp_set_post_terms($post_id, $term_meta_entry, 'venues');
    update_post_meta($post_id, 'venue-datetime', esc_attr(implode('@', $venueDateTime)));
   
    update_post_meta($post_id, 'ticket-widget', esc_attr($_POST['ticket-widget']));
}

add_action('save_post', 'movie_meta_box_save');

function film_category($atts) {

    $params = shortcode_atts(array('title' => 'FILM GUIDE'), $atts);
    $terms = get_terms([ 'taxonomy' => 'movie_type', 'hide_empty' => false]);
    $html = '<div class="eltdf-row-grid-section-wrapper new-top-stories-block-ui mb-3">
		<div class="vc_row wpb_row vc_row-fluid mt-4">
			<div class="wpb_column vc_column_container vc_col-sm-12">
				<div class="vc_column-inner ">
					<div class="wpb_wrapper">
						<div class="vc_separator wpb_content_element vc_separator_align_left vc_sep_width_100 vc_sep_pos_align_center vc_sep_color_grey vc_separator-has-text">
							<span class="vc_sep_holder vc_sep_holder_l"><span class="vc_sep_line"></span></span>
							<h4>' . $params['title'] . '</h4>
							<span class="vc_sep_holder vc_sep_holder_r"><span class="vc_sep_line"></span></span>
						</div>
						<div class="vc_empty_space" style="height: 25px"><span class="vc_empty_space_inner"></span></div>
						<div class="eltdf-news-holder eltdf-block1 eltdf-news-block-pp-two-half eltdf-normal-space">
							<div class="eltdf-news-list-inner eltdf-outer-space">
								<div class="eltdf-news-block-part-non-featured" style="width:100% !important;">';
    foreach ($terms as $key => $value) {
        $html.=
                '<div class="eltdf-news-item eltdf-layout7-item eltdf-item-space afi-col-md-2">
											<div class="eltdf-ni-inner afi-eltdf-ni-inner-height">
												<div class="eltdf-ni-image-holder afi-film-guide-img">
													<div class="eltdf-ni-image-inner">
														<div class="eltdf-post-image">
															<a itemprop="url" href="#" title="" class="afi-film-guide-img-inner">
																<img src="' . esc_url(wp_get_attachment_image_url(get_term_meta($value->term_id, 'image', true), 'full')) . '" class="attachment-full size-full wp-post-image afi-post-image afi-post-image" alt="a">
															</a>
														</div>
													</div>
												</div>
												<div class="eltdf-ni-content afi-content-overlay">
													<div itemprop="name" class="entry-title eltdf-post-title">
														<h4 class="text-center" style="color:#FFF !important;"><a itemprop="url" href="#" style="color:#FFF !important;"></a>' . $value->name . '</h4>
													</div>
												</div>
											</div>
										</div>';
    }
    $html.=
            '</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>';
    return $html;
}

add_shortcode('filmcategory', 'film_category');

function strip_name_and_date($searchParams) {
    $datePattern = '~\d{2}/\d{2}/\d{4}~';
    $timePattern = '~\b((1[0-2]|0?[1-9]):([0-5][0-9]) ([AaPp][Mm]))~';
    preg_match_all($datePattern, $searchParams, $date);
    preg_match_all($timePattern, $searchParams, $time);
    $name = trim(preg_replace($datePattern, '', $searchParams));
    $name = trim(preg_replace($timePattern, '', $name));
    return array('name' => explode("\n", $name), 'dates' => $date[0], 'time' => $time[0]);
}

function movie_adding_scripts() {
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-widget');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('tagit-js', plugin_dir_url(__FILE__) . 'lib/js/tag-it.js', array('jquery-ui-core', 'jquery-ui-widget'), true);
    wp_enqueue_script('chosen-js', plugin_dir_url(__FILE__) . 'lib/js/chosen.js', array('jquery'), true);
    wp_enqueue_script('prism-js', plugin_dir_url(__FILE__) . 'lib/js/prism.js', array('jquery'), true);
    wp_enqueue_script('jquery-ui-timepicker-addon', plugin_dir_url(__FILE__) . 'lib/js/jquery-ui-timepicker-addon.js', array('jquery-ui-core', 'jquery-ui-datepicker'), true);
    wp_enqueue_script('custom-js', plugin_dir_url(__FILE__) . 'lib/js/custom.js', array('jquery'), true);
}

add_action('admin_init', 'movie_adding_scripts');

function movie_adding_styles() {
    wp_register_style('tagit-styles', plugin_dir_url(__FILE__) . 'lib/css/jquery.tagit.css');
    wp_register_style('tagit-zen-styles', plugin_dir_url(__FILE__) . 'lib/css/tagit.ui-zendesk.css');
    wp_register_style('prism-styles', plugin_dir_url(__FILE__) . 'lib/css/prism.css');
    wp_register_style('chosen-styles', plugin_dir_url(__FILE__) . 'lib/css/chosen.css');
    wp_register_style('jquery-ui-timepicker-addon', plugin_dir_url(__FILE__) . 'lib/css/jquery-ui-timepicker-addon.css');
    wp_register_style('jquery-ui-styles', plugin_dir_url(__FILE__) . 'lib/css/jquery-ui.css');
    wp_enqueue_style('tagit-styles');
    wp_enqueue_style('tagit-zen-styles');
    wp_enqueue_style('prism-styles');
    wp_enqueue_style('chosen-styles');
    wp_enqueue_style('jquery-ui-timepicker-addon');
    wp_enqueue_style('jquery-ui-styles');
}

add_action('admin_init', 'movie_adding_styles');
add_action('admin_enqueue_scripts', function() {
    wp_enqueue_media();
});
