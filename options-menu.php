<?php
class MySettingsPage
{
    private $options;

    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    public function add_plugin_page()
    {
        add_options_page(
            'CPC People Options',
            'CPC People',
            'manage_options',
            'cpc-people',
            array( $this, 'create_admin_page' )
        );
    }

    public function create_admin_page()
    {
        $this->options = get_option( 'cpc_people_options' );
        ?>
        <div class="wrap">
            <h1>CPC People Settings</h1>
            <form method="post" action="options.php">
            <?php
                settings_fields( 'cpc_option_group' );
                do_settings_sections( 'cpc-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    public function page_init()
    {
        register_setting(
            'cpc_option_group', // Option group
            'cpc_people_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'db_options', // ID
            'Database Options', // Title
            array( $this, 'print_section_info' ), // Callback
            'cpc-setting-admin' // Page
        );

        add_settings_field(
            'hostname', // ID
            'Hostname', // Title
            array( $this, 'hostname_callback' ), // Callback
            'cpc-setting-admin', // Page
            'db_options' // Section
        );

        add_settings_field(
            'table',
            'Table',
            array( $this, 'table_callback' ),
            'cpc-setting-admin',
            'db_options'
        );

        add_settings_field(
            'username',
            'Username',
            array( $this, 'username_callback' ),
            'cpc-setting-admin',
            'db_options'
        );

        add_settings_field(
            'password',
            'Password',
            array( $this, 'password_callback' ),
            'cpc-setting-admin',
            'db_options'
        );
    }

    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['hostname'] ) )
            $new_input['hostname'] = sanitize_text_field( $input['hostname'] );

        if( isset( $input['table'] ) )
            $new_input['table'] = sanitize_text_field( $input['table'] );

        if( isset( $input['username'] ) )
            $new_input['username'] = sanitize_text_field( $input['username'] );

        if( isset( $input['password'] ) )
            $new_input['password'] = sanitize_text_field( $input['password'] );

        return $new_input;
    }

    public function print_section_info()
    {
        print 'Enter your login information below';
    }

    public function hostname_callback()
    {
        printf(
            '<input type="text" id="hostname" name="cpc_people_options[hostname]" value="%s" />',
            isset( $this->options['hostname'] ) ? esc_attr( $this->options['hostname']) : ''
        );
    }

    public function table_callback()
    {
        printf(
            '<input type="text" id="table" name="cpc_people_options[table]" value="%s" />',
            isset( $this->options['table'] ) ? esc_attr( $this->options['table']) : ''
        );
    }

    public function username_callback()
    {
        printf(
            '<input type="text" id="username" name="cpc_people_options[username]" value="%s" />',
            isset( $this->options['username'] ) ? esc_attr( $this->options['username']) : ''
        );
    }

    public function password_callback()
    {
        printf(
            '<input type="password" id="password" name="cpc_people_options[password]" value="%s" />',
            isset( $this->options['password'] ) ? esc_attr( $this->options['password']) : ''
        );
    }
}

if( is_admin() )
    $my_settings_page = new MySettingsPage();
