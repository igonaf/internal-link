<?php

/**
 * Class Functions
 */
class Functions
{
    /**
     * get option from DB
     *
     * @param null $option
     * @return mixed|void
     */
    static function get_IL_option($option = null)
    {
        if ($option) {
            $res = get_option('internal_link_option_name')[$option];
        } else {
            $res = get_option('internal_link_option_name');
        }
        return $res;
    }

    /**
     * get list of current post types, excep attachment type
     *
     * @return array
     */
    static function get_list_post_types()
    {
        $exception = 'attachment';
        $args = [
            'public' => true,
        ];
        $output = 'names'; // names or objects, note names is the default
        $operator = 'and'; // 'and' or 'or'
        $post_types = get_post_types($args, $output, $operator);
        if (array_key_exists($exception, $post_types)) {
            unset($post_types[$exception]);
        }
        return $post_types;
    }
}