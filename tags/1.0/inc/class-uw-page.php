<?php
/**
 * UW Page
 * 
 * Creates a WordPress Admin page.
 * 
 * @package uw
 * @since 1.0
 */
class UW_Page{
    
    private $page_options;
    
    private $settings_options;
    
    private $default_options;
    
    public function __construct($page_options, $settings_options) {

        $this->page_options = $page_options;
        $this->settings_options = $settings_options;
        
        $this->default_options = $this->get_default_options();
        
        add_action('admin_init', array($this, 'uw_register_settings'));
        
        ! get_option('uwpml_options') && update_option('uwpml_options', $this->default_options);        

        add_action('admin_menu', array( $this, 'uw_page' ) );
    }
    
    public function uw_page(){
        $func = 'add_'.$this->page_options['page_type'].'_page';
        $func(
            // The text to be displayed in the title tags  
            $this->page_options['page_title'], 
            // The text to be used for the menu    
            $this->page_options['menu_name'], 
            // The capability required for this menu to be displayed.    
            $this->page_options['cap_required'],
            // The slug name to refer to this menu by.   
            $this->page_options['page_slug'], 
            // The function to be called to output the content for this page.    
            array( $this, 'uwpage_cb')
        );
    }
    
    function uwpage_cb(){
        
    echo '<div class="wrap">';        
    echo '<div id="icon-options-general" class="icon32"><br /></div>';
    echo '<h2>' . $this->page_options['page_title'] . '</h2>';

    echo '<form action="options.php" method="post">';        

        global $pagenow;
        if ( 'options-general.php' == $pagenow && 
                isset( $_GET['page'] ) && 
                'pml_options' == $_GET['page'] ) :
        
        foreach ($this->settings_options['sections'] as $section){
        //add_settings_section($id, $title, $callback, $page)
        add_settings_section(
            // $id
            $section['slug'],
            // $title
            $section['title'],
            // $callback
            array($this, 'uw_settings_sections_cb'),
            // $page
            'uwpage'
        );

        foreach ($section['fields'] as $field){
            //$basefield = $section['slug'] . '_' . $field['slug'];            
            add_settings_field(
                // $id
                $section['slug'] . '_' . $field['slug'],
                // $title
                $field['title'],
                // $callback
                array( $this, 'uw_settings_fields_cb' ),
                // $page
                'uwpage', 
                // $section = 'default'
                $section['slug'], 
                // $args = array()
                $field['data']
            );
        }
        }

        endif;   
        
        settings_fields('uwpml_options');
        do_settings_sections('uwpage');        
        
        echo '<p class="submit">';
        echo '<input name="uwpml_options[submit]" type="submit" class="button button-primary" value="' . __('Save Settings', 'uwpml') . '" />';
        echo '&nbsp;';
        echo '<input name="uwpml_options[reset]" type="submit" class="button button-secondary" value="' . __('Reset Defaults', 'uwpml') . '" />';
        echo '</p>';
        
    echo '</form>';    
    echo '</div>';
    
    }

    function uw_settings_sections_cb($current_section){
        foreach ($this->settings_options['sections'] as $section){
            if($section['slug'] == $current_section['id']){
                echo $section['description'];
            }
        }    
    }

    function uw_settings_fields_cb($data) {

        $current_options = get_option( 'uwpml_options' );
        
        foreach ($this->settings_options['sections'] as $section){
            foreach ($section['fields'] as $field){
                if($field['slug'] == $data['slug']){                                        
                    $field_section = $section['slug'];
                    $id = $data['slug'];
                    break;
                }                
            }
        }
        
        $name = 'uwpml_options[' . $field_section . '][' . $id . ']';          
        $value = $current_options[$field_section][$id];
        $description = $data['description'];              

        if( $data['type'] == 'text' ){
            echo '<input type="text" name="'.$name.'" id="'.$id.'" value="'
                    .$value.'" size="30" />';
            echo '<span class="description"> '.$description.'</span>'; 
        } else if( $data['type'] == 'checkbox' ){
           $value = ($value == 'true') ? true : false; 
           echo '<input type="checkbox" name="'.$name.'" id="'.$id.
                   '" ',$value ? ' checked="checked"' : '','/> ';
           echo '<span class="description">'.$description.'</span>';  
        }    

    }
    
    function get_default_options() {               
        foreach ($this->settings_options['sections'] as $section){
            $default_options[$section['slug']] = array();
            foreach ($section['fields'] as $field){
                $default_options[$section['slug']][$field['slug']] = $field['default'];                
            }
        }
        return $default_options;
    }    
    
    function uw_register_settings(){
        register_setting( 'uwpml_options', 'uwpml_options', array($this,'uwpml_options_validate' ));
    }
    
    function uwpml_options_validate($input){
    
    $valid_input = get_option( 'uwpml_options' );

    if(! empty( $input["submit"])){

       foreach ($this->settings_options['sections'] as $section){ 
            foreach ($section['fields'] as $field){
                if($field['data']['type'] == 'text'){
                    $valid_input[$section['slug']][$field['slug']] = 
                        sanitize_text_field(
                                $input[$section['slug']][$field['slug']]
                        );                
                } else if($field['data']['type'] == 'checkbox'){
                    $valid_input[$section['slug']][$field['slug']] = 
                        (bool)$input[$section['slug']][$field['slug']];    
                }
            }
        }               
       
    } else if(! empty( $input["reset"])){
        $valid_input = $this->default_options;
    }
    
    return $valid_input; 
    }
}
?>