<?php

require_once('Functions.php');

/**
 * Class LinksChecking
 */
class LinksChecking
{

    private $list_post_types = [];

    public function __construct()
    {
        $this->add_actions();
        $this->list_post_types = Functions::get_list_post_types();
    }

    /**
     * set styles
     */
    public function get_enqueue_styles()
    {
        wp_enqueue_style('styles', plugin_dir_url(__FILE__) . '../public/css/IL_styles.css');
    }

    /**
     * after checking. make scripts register
     */
    public function get_enqueue_scripts()
    {
        if (is_string(get_post_type())) {
            if (array_key_exists(get_post_type(), Functions::get_IL_option('post_types'))) {
                wp_register_script('parse', plugin_dir_url(__FILE__) . '../public/js/parse.js', ['jquery']);
                wp_localize_script('parse', 'parse_vars', $this->get_parse_data());
                wp_enqueue_script('parse');
            }
        }

    }

    /**
     * get ajax and localize_scripts params
     *
     * @return array
     */
    private function get_parse_data()
    {
        return [
            'domain' => $this->get_current_domain(),
            'admin_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('check_parse'),
            'min_quatity_of_links' => Functions::get_IL_option('input'),
        ];
    }

    /**
     *get current domain
     *
     * @return mixed
     */
    private function get_current_domain()
    {
        return $_SERVER['SERVER_NAME'];
    }


    /**
     *add actions for admin side working
     *
     */
    private function add_actions()
    {
        add_action('admin_enqueue_scripts', [$this, 'get_enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'get_enqueue_styles']);
        add_action('wp_ajax_check_parse', [$this, 'check_parse']);
    }

    /**
     * ajax callback
     *
     */
    public function check_parse()
    {
        $nonce = $_POST['nonce'];

        if (!wp_verify_nonce($nonce, 'check_parse'))
            wp_die();

        $checked_host_urls = $_POST['validated_host_urls'];
        $counter = 0;

        foreach ($checked_host_urls as $url) {

            if ($parse_url_res = parse_url($url, PHP_URL_QUERY)) {
                parse_str($parse_url_res, $output_vars);
                $custom_types = Functions::get_list_post_types(['_builtin' => false]);
                //$available_custom_types = array_intersect_key($custom_types, Functions::get_IL_option('post_types'));

                if (!empty($custom_types)) {
                    foreach ($custom_types as $custom_type) {
                        if (array_key_exists($custom_type, $output_vars)) {
                            if ($obj = get_page_by_path($output_vars[$custom_type], OBJECT, $custom_types)) {
                                $counter++;
                                continue 2;
                            }
                        }
                    }
                }

            }

            if (url_to_postid($url)) {
                $counter++;
            }

        }

        echo $counter;

        wp_die();
    }
}