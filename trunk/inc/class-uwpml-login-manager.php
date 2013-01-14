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
    
    private $error_heading;
    
    private $error_message;
            
    public function __construct() {  
        
        $this->error_heading = __('Already Logged In.' , 'uwpml');
        
        add_action( 'init', 
                array($this, 'update_user_meta_login') );
        add_action( 'clear_auth_cookie', 
                array($this, 'update_user_meta_logout'));
        add_filter('authenticate', 
                array($this, 'check_login'), 40, 3);
        add_filter('login_errors',
                array($this, 'multiple_login_error_message'));
        add_filter('auth_cookie_expiration',
                array($this, 'set_auth_cookie_expiration'));
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
        
        if ( is_a($user, 'WP_User')) {
            $uwpml_user_meta = get_user_meta(
                    $user->ID, 
                    'uwpml_user_meta', 
                    true
                    );
            
            if($uwpml_user_meta['logged_in']){      
                $uwpml_options = get_option('uwpml_options');
                $last_seen = $uwpml_user_meta['last_seen'];
                $expiration = $uwpml_options['auth']['time'];
                $elapsed = time() - $last_seen;
                $this->error_message = '<br />Auth Cookie Expiration: ' .
                        $expiration / 60 . 'min<br /><br />';
                $this->error_message .= 'Last Visit: ' .
                        date("Y-m-d H:i:s", $last_seen )
                        . '<br /><br />';
                $try_again = ( $expiration - $elapsed ) ;
                
                if($try_again > 0){
                $this->error_message .= 'Try again in: ' .
                        date("H:i:s", $try_again )
                        . '<br />';                
                return new WP_Error(
                        'multiple-login-attempt', 
                        $this->error_heading 
                        );  
                } else {
                    return $user;
                }
            } else {
                return $user;
            }
        
        }        
        return $user;
    }

    public function multiple_login_error_message($errors){
        
        if(strpos($errors, $this->error_heading)){
        
        
        $errors .= $this->error_message;
        return $errors;
        
        }
        
        return $errors;
    }
    
    public function set_auth_cookie_expiration(){
        $uwpml_options = get_option('uwpml_options');
        return $uwpml_options['auth']['time'];
    }
    
}
?>