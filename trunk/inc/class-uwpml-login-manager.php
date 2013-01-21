<?php
/**
 * Login Manager
 * 
 * Core PML functionality is implemented inside 
 * UWPML_Login_Manager class.
 * 
 * @package uwpml
 * @since 1.0.1
 * 
 */
class UWPML_Login_Manager{
    
    public function __construct() {        
        add_action( 'set_auth_cookie', 
                array($this, 'set_auth_transient'), 10, 5 );
        add_action( 'clear_auth_cookie', 
                array($this, 'clear_auth_transient'));
        add_action('auth_cookie_valid', 
                array($this, 'uwpml_auth_cookie_valid'), 10, 2);
        add_filter('authenticate', 
                array($this, 'uwpml_authenticate'), 40, 3);
    }
    
    /**
     * Set Auth Transient
     * 
     * Setup 'Auth Transient' with 'Auth Cookie'
     * 
     * Auth cookie is generated upon user login. Create a transient for this 
     * user using 'set_auth_cookie' action hook.
     * 
     * @hook set_auth_cookie
     * 
     * @param type $logged_in_cookie
     * @param type $expire
     * @param type $expiration
     * @param type $user_id
     * @param type $scheme
     * 
     * @return void action hook returns nothing
     */
    public function set_auth_transient( $logged_in_cookie, $expire, 
            $expiration, $user_id, $scheme)
    {
        $transient = 'uwpml_' . $user_id;
        $value = array(
            'logged_in_coockie' => $logged_in_cookie,
            'auth_time' => time(),
            'time' => time(),
            'expiration' => $expiration
        );
        
        $expiration = $expiration - time();
        
        set_transient($transient, $value, $expiration);        
    }
    
    /**
     * Clear Auth Transient
     * 
     * Clears the 'Auth Transient' with 'Auth Transient'
     * 
     * @hook clear_auth_cookie
     * 
     * @return void action hook returns nothing
     */
    public function clear_auth_transient(){
        $transient = 'uwpml_' . get_current_user_id();
        delete_transient($transient);
    }
    
    /**
     * UWPML Auth Cookie Valid
     * 
     * Fires with each visit to the site. Authorizes the user.
     * 
     * If the auth cookie is valid, update the transients data.
     * 
     * @hook auth_cookie_valid
     * 
     * @param type $cookie_elements
     * @param type $user
     * 
     * @return void action hook returns nothing
     */
    public function uwpml_auth_cookie_valid($cookie_elements, $user){        
        $transient = 'uwpml_' . $user->ID;
        $user_data = get_transient($transient);
        extract($cookie_elements, EXTR_OVERWRITE); 
        
        if($user_data){ // Update transient expiration
            $expiration = $expiration - time() ;
        }
        
        $value = array(
            'logged_in_coockie' => $hmac,
            'auth_time' => $user_data['auth_time'],
            'time' => time(),
            'expiration' => $expiration
        );
        set_transient($transient, $value, $expiration);            
    }
    
    /**
     * UWPML Authenticate
     * 
     * Throws an error if multiple login attempt
     * 
     * If $user is a WP_User, then authentication is a success.
     * 
     * @hook authenticate
     * 
     * @param type $user
     * @param type $username
     * @param type $password
     * 
     * @return $user|WP_Error $user if not a multiple login attempt.
     */
    public function uwpml_authenticate($user, $username, $password){
        if ( is_a($user, 'WP_User')) {
            $transient = 'uwpml_' . $user->ID; 
            $user_data = get_transient($transient);
            if($user_data){
                
                $error = __('Alreay Logged In', 'uwpml');                                
                
                $error = apply_filters(
                        'uwpml_already_logged_in_message', 
                        $error, $user, $user_data
                );
                
                do_action('uwpml_multiple_login_attempt', $user, $error);
                
                return new WP_Error(
                        'authentication_failed', 
                        $error
                );
            }
        }
        
        return $user;
    }
   
    
}
?>