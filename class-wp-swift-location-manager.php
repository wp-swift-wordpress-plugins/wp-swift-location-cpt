<?php
/*
 * Declare a new class that will handle opening hors
 * 
 * @class       WP_Swift_Google_Maps
 *
 */
class WP_Swift_Location_Manager {
    private $name;
    private $email;
    private $tel;
    private $address;
    private $opening_hours;

    /*
     * Initializes the class.
     */
    // public function __construct($name = '', $email = '', $tel = array(), $address = '', $opening_hours = '') { 
    //     $this->name = $name;
    //     $this->email = $email;
    //     $this->tel = $tel;
    //     $this->address = $address;
    //     $this->opening_hours = $opening_hours;
    // }

    public function __construct($post = null) { 
        if (isset($post->ID)) {
            $post_id = $post->ID;
            if ( get_field('email', $post_id) ) {
                $this->email = get_field('email', $post_id);
                // $location->set_email( get_field('email', $post_id) );
            }
            if ( have_rows('contact_numbers', $post_id) ) {
                while( have_rows('contact_numbers', $post_id) ) {
                    the_row();             
                    $label = get_sub_field('label'); 
                    $number = get_sub_field('number');   
                    $number = wp_swift_process_number_link($number);   
                    $tel[] = array( 
                        "label" => $label,
                        "number" => $number,
                    );  
                }
                // $location->set_tel( $tel ); 
                $this->tel = $tel;
            }  
            $this->name = $post->post_title;
            // $this->email = $email;
            
            // $this->address = $address;
            // $this->opening_hours = $opening_hours;  
            if ( get_field('address', $post_id) ) {
                $this->address = get_field('address', $post_id);
                $this->set_address(get_field('address', $post_id));
            }                      
            if ( get_field('opening_hours', $post_id) ) {
                $this->opening_hours = get_field('opening_hours', $post_id);
            }              
        }
    }   

    public function get_name($raw = false) {
        if ($raw) {
            return $this->name;
        }
        else {
            echo '<h5>'.$this->name.'</h5>';
        }
    }
    public function set_name($name) {
        $this->name = $name;
    } 

    public function get_email($label = '', $tag = '') {
        if ($this->email) {
            if ($tag) echo '<'.$tag.'>';
            echo $label.'<a href="mailto:'.$this->email.'" class="email contact-method contact-email">'.$this->email.'</a>';
            if ($tag) echo '</'.$tag.'>';
        }
       
    }
    public function get_email_raw($raw = false) {
        return $this->email;
    }    
    public function set_email($email) {
        $this->email = $email;
    } 

    public function get_tel($label = '', $tag = '') {
        if (isset($this->tel[0])) {
            if ($tag) echo '<'.$tag.'>';
            echo $label.'<a href="tel:'.$this->tel[0]["number"]["tel"].'" class="tel contact-method contact-number">'.$this->tel[0]["number"]["readable"].'</a>';
            if ($tag) echo '</'.$tag.'>';
        }        
    }
    public function get_tel_raw($raw = false) {
        return $this->tel;
    }    
    public function set_tel($tel) {
        $this->tel = $tel;
    }

    public function get_address($raw = false) {
        if ($raw) {
            return $this->address;
        }
        else {
            echo '<div>'.$this->address.'</div>';
        }
    }
    public function set_address($address) {
        $this->address = $address;
    }  
    public function get_opening_hours($raw = false) {
        if ($raw) {
            return $this->opening_hours;
        }
        else {
            echo '<div>'.$this->opening_hours.'</div>';
        }
    }
    public function set_opening_hours($opening_hours) {
        $this->opening_hours = $opening_hours;
    }                 
    public function get_details() {
        ob_start();

        $this->get_name();
        $this->get_address();
        $this->get_tel('Tel: ', 'div');
        $this->get_email('Email: ', 'div');

        $html = ob_get_contents();
        ob_end_clean();
        
        return $html;
    }  
}