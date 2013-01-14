<?php
/**
 * Manage Plugin Options
 * 
 * Provides an admin interface to manage plugin options.
 * 
 * @package uwpml
 * @since 1.0 
 */
class UWPML_Manage_Options{
    
    private $page;
    
    private $page_options;
    
    private $settings_options;
    
    /**
     * Initialize plugin options management.
     */
    public function __construct() {
        
        $this->page_options = array(
            'page_type' => 'options',
            'page_title' => __('Prevent Multiple Logins', 'uwpml'),
            'menu_name' => __('PML', 'uwpml'),
            'cap_required' => 'manage_options',
            'page_slug' => 'pml_options'
        );
        
        $this->settings_options = array(
            'sections' => array(
                'auth' => array(
                    'title' => __('User Authentication Period', 'uwpml'),
                    'slug' => 'auth',
                    'description' => __('Time used in auth cookie', 'uwpml'),
                    'fields' => array(
                        'time' => array(
                            'title' => __('Expires In', 'uwpml'),
                            'slug' => 'time',
                            'default' => '172800',
                            'data' => array(
                                'type' => 'text',
                                'slug' => 'time',
                                'description' => 
                                __('Time to Expire Autentication.', 'uwpml')
                            )
                        ),
                        'time_remember' => array(
                            'title' => __('Expires In (Remember)', 'uwpml'),
                            'slug' => 'time_remember',
                            'default' => '1209600',
                            'data' => array(
                                'type' => 'text',
                                'slug' => 'time_remember',
                                'description' => 
                                __('Time to Expire Autentication. (Remember)', 'uwpml')
                            )
                        )                        
                    )

                )   
                )
        );        

        include 'class-uw-page.php';
        $this->page = new UW_Page( $this->page_options, $this->settings_options );
    }
}
?>