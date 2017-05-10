<?php

require_once ('Functions.php');

/**
 * Class Settings
 */
class Settings
{
    const MIN_LINKS_QUANTITY=10;

    public function __construct()
    {
        $this->add_actions();
    }

    /**
     * add all actions for settings
     */
    private function add_actions()
    {
        add_action('admin_menu', [$this, 'internal_link_plugin_page']);
        add_action('admin_init', [$this, 'internal_link_settings']);
    }

    /**
     * add options page
     */
    public function internal_link_plugin_page()
    {
        add_options_page('Internal link settings', 'Internal link', 'manage_options', 'internal_link', [$this, 'internal_link_output']);
    }

    /**
     * callback for admin page creating
     */
    public function internal_link_output()
    {
        ?>
        <div class="wrap">
            <h2><?php echo get_admin_page_title() ?></h2>

            <form action="options.php" method="POST">
                <?php
                settings_fields('internal_link_group');
                do_settings_sections('internal_link_page');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * add settings and fields to the settings page
     */
    public function internal_link_settings()
    {
        register_setting('internal_link_group', 'internal_link_option_name', [$this, 'internal_link_callback']);

        add_settings_section('internal_link_section', 'Main settings', '', 'internal_link_page');
        add_settings_section('internal_link_section_post', 'Post types', '', 'internal_link_page');

        add_settings_field('IL_field1', 'Minimum quantity of links', [$this, 'fill_IL_field1'], 'internal_link_page', 'internal_link_section');
        //add_settings_field('internal_link_unique', 'Unique links', [$this, 'internal_link_unique_cb'], 'internal_link_page', 'internal_link_section');

        $qountity_types = Functions::get_list_post_types();

        foreach ($qountity_types as $qountity_type) {
            add_settings_field('IL_field' . $qountity_type, ucfirst($qountity_type), [$this, 'fill_IL_field2'], 'internal_link_page', 'internal_link_section_post', $qountity_type);
        }
    }


    /**
     * callback for field1 fill
     */
    public function fill_IL_field1()
    {
        $val = Functions::get_IL_option('input');

        $data = $val ? $val : self::MIN_LINKS_QUANTITY;

        ?>
        <input type="text" name="internal_link_option_name[input]" value="<?php echo esc_attr($data) ?>"/>
        <?php
    }

    /**
     * callback for field2 fill
     *
     * @param $args
     */
    public function fill_IL_field2($args)
    {
        $val = Functions::get_IL_option('post_types');
        $data = $val[$args];
        ?>
        <label><input type="checkbox" name="internal_link_option_name[post_types][<?php echo $args ?>]"
                      value="1" <?php checked(1, $data) ?> /></label>
        <?php
    }

   /* public function internal_link_unique_cb()
    {
        $data = Functions::get_IL_option('unique');

        ?>
        <label><input type="checkbox" name="internal_link_option_name[unique]"
                      value="1" <?php checked(1, $data) ?> /></label>
        <?php
    }*/

    /**
     * simple input validation
     *
     * @param $options
     * @return mixed
     */
    public function internal_link_callback($options)
    {
        foreach ($options as $name => & $val) {
            if ($name == 'input')
                $val = absint(strip_tags($val));
            if ($name == 'checkbox')
                $val = intval($val);
        }
        return $options;
    }

}