<?php
/**
 * Login Manager
 * 
 * Most of the plugin specific functionality is implemented in this class.
 * Checks each login to prevent multiple logins.
 * Users are added meta data on successful login. Login related meta data is
 * updated on each visit for logged in user.
 * 
 * @package uwpml
 * @since 1.0.1
 * 
 */
class UWPML_Login_Manager{
    
    private $user_meta;

    public function __construct() {        
        add_action( 'init', 
                array($this, 'update_user_meta_login') );
        add_action( 'clear_auth_cookie', 
                array($this, 'update_user_meta_logout'));
        add_filter('authenticate', 
                array($this, 'check_login'), 40, 3);
        add_filter('login_errors',
                array($this, 'multiple_login_error_message'));
    }
    
    public function update_user_meta_login(){
        if(is_user_logged_in()){
            
        $this->user_meta = array(
            'logged_in' => true,
            'last_seen' => time()
        );       
        
        update_user_meta(get_current_user_id(), 'uwpml_user_meta', $this->user_meta);
        }
    }
    
    // questions/39761/how-to-get-userid-at-wp-logout-action-hook
    public function update_user_meta_logout(){
        if(is_user_logged_in()){
            
        $this->user_meta = array(
            'logged_in' => false,
            'last_seen' => time()
        );
        
        update_user_meta(get_current_user_id(), 'uwpml_user_meta', $this->user_meta);
        }
    }   
    
    public function check_login($user, $username, $password){
        return new WP_Error('expired_session', __('Already Logged In.'));
        //return null;
    }

    public function multiple_login_error_message($errors){
        return $errors;
    }
    
}
?>