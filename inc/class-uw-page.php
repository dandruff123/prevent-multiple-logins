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
    
    public function __construct($page_options) {
        
        $this->page_options = $page_options;
        
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
            'uwpage_cb'
        );
    }
    
    //--------------------------------------------------------------------------
    
    function uw_create_sections($page){

    foreach ($page['sections'] as $section){
        $basesection = $section['slug'];
        //add_settings_section($id, $title, $callback, $page)
        add_settings_section(
                // $id
                $basesection,
                // $title
                $section['title'],
                // $callback
                'lp_settings_sections_cb',
                // $page
                'lp'
        );

        foreach ($section['fields'] as $field){
            $basefield = $basesection . '_' . $field['slug'];            
            add_settings_field(
                    // $id
                    $basefield,
                    // $title
                    $field['title'],
                    // $callback
                    'lp_settings_fields_cb',
                    // $page
                    'lp', 
                    // $section = 'default'
                    $basesection, 
                    // $args = array()
                    $field['data']
            );
        }
    }
    }

    function uw_settings_sections_cb($current_section){
        $la_options_array = la_options_array();

        foreach ($la_options_array as $page){
            foreach ($page['sections'] as $section){
                if($section['slug'] == $current_section['id']){
                    echo $section['description'];
                }
            }
        }    
    }

    function lp_settings_fields_cb($data) {

        $theme_lp_options = get_option( 'theme_lp_options' );
        $la_options_array = la_options_array();

        foreach ($la_options_array as $page){
            foreach ($page['sections'] as $section){
                foreach ($section[fields] as $field){
                    if($field['slug'] == $data['slug']){
                        $field_page = $page['page'];
                        $field_section = $section['slug'];
                        $field_field = $data['slug'];
                        break;
                    }                
                }
            }
        }     

        $name = "theme_lp_options[$field_page][$field_section][$field_field]";
        $id = $field_field;
        $value = $theme_lp_options[$field_page][$field_section][$field_field];
        $description = $data['description'];    


        if($data['type'] == 'text'){

         echo '<input type="text" name="'.$name.'" id="'.$id.'" value="'
                 .$value.'" size="30" />';
         echo '<span class="description"> '.$description.'</span>'; 

         } else if($data['type'] == 'checkbox'){
            $value = ($value == 'true') ? true : false; 
            echo '<input type="checkbox" name="'.$name.'" id="'.$id.
                    '" ',$value ? ' checked="checked"' : '','/> ';
            echo '<span class="description">'.$description.'</span>';  
         }    

    }    
    
}
?>
