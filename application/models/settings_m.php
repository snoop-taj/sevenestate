<?php

class Settings_m extends MY_Model {
    
    protected $_table_name = 'settings';
    protected $_order_by = 'id';
    
    public $rules_contact = array(
        'address' => array('field'=>'address', 'label'=>'lang:Address', 'rules'=>'trim'),
        'gps' => array('field'=>'gps', 'label'=>'lang:Gps', 'rules'=>'trim'),
        'email' => array('field'=>'email', 'label'=>'lang:ContactMail', 'rules'=>'trim'),
        'email_alert' => array('field'=>'email_alert', 'label'=>'lang:inputContactMailAlert', 'rules'=>'trim'),
        'phone' => array('field'=>'phone', 'label'=>'lang:Phone', 'rules'=>'trim'),
        'fax' => array('field'=>'fax', 'label'=>'lang:Fax', 'rules'=>'trim'),
        'address_footer' => array('field'=>'address_footer', 'label'=>'lang:Address Footer', 'rules'=>'trim'),
    );
    
    public $rules_template = array(
        'template' => array('field'=>'address', 'label'=>'lang:Template', 'rules'=>'trim'),
        'tracking' => array('field'=>'tracking', 'label'=>'lang:Tracking', 'rules'=>'trim'),
        'facebook' => array('field'=>'facebook', 'label'=>'lang:Facebook or Social code', 'rules'=>'trim'),
        'facebook_jsdk' => array('field'=>'facebook_jsdk', 'label'=>'lang:Facebook Javascript SDK code', 'rules'=>'trim'),
        'facebook_comments' => array('field'=>'facebook_comments', 'label'=>'lang:Facebook comments code', 'rules'=>'trim'),
    );
    
    public $rules_system = array(
        'noreply' => array('field'=>'noreply', 'label'=>'lang:No-reply email', 'rules'=>'trim|valid_email'),
        'zoom' => array('field'=>'zoom', 'label'=>'lang:Zoom index', 'rules'=>'trim|is_natural'),
        'paypal_email' => array('field'=>'paypal_email', 'label'=>'lang:PayPal payment email', 'rules'=>'trim|valid_email'),
        'listing_expiry_days' => array('field'=>'listing_expiry_days', 'label'=>'lang:Listing expiry days', 'rules'=>'trim|is_natural'),
        'activation_price' => array('field'=>'activation_price', 'label'=>'lang:Activation price', 'rules'=>'trim|is_numeric'),
        'featured_price' => array('field'=>'featured_price', 'label'=>'lang:Featured price', 'rules'=>'trim|is_numeric'),
        'default_currency' => array('field'=>'default_currency', 'label'=>'lang:Default currency code', 'rules'=>'trim|required'),
        'adsense728_90' => array('field'=>'adsense728_90', 'label'=>'lang:AdSense 728x90 code', 'rules'=>'trim'),
        'adsense160_600' => array('field'=>'adsense160_600', 'label'=>'lang:AdSense 160x600 code', 'rules'=>'trim'),
        //'agent_masking_enabled' => array('field'=>'agent_masking_enabled', 'label'=>'lang:Enable masking', 'rules'=>'trim'),
        //'rating_enabled' => array('field'=>'rating_enabled', 'label'=>'lang:Enable rating', 'rules'=>'trim'),
        'reviews_enabled' => array('field'=>'reviews_enabled', 'label'=>'lang:Enable reviews', 'rules'=>'trim'),
        'reviews_public_visible_enabled' => array('field'=>'reviews_public_visible_enabled', 'label'=>'lang:Enable reviews public visible', 'rules'=>'trim'),
        'withdrawal_details' => array('field'=>'withdrawal_details', 'label'=>'lang:Withdrawal payment details', 'rules'=>'trim')
    );

    public function get_new()
	{
        $setting = new stdClass();
        $setting->field = '';
        $setting->value = '';
        
        return $setting;
	}
    
    public function get_fields()
    {
        $query = $this->db->get($this->_table_name);

        $data = array();
        foreach($query->result() as $key=>$setting)
        {
            $data[$setting->field] = $setting->value;
        }
        
        return $data;
    }
    
    public function save_settings($post_data)
    {
        $this->delete_fields($post_data);
        
        $data = array();
        foreach($post_data as $key=>$value)
        {
            $data[] = array(
               'field' => $key,
               'value' => $value
            );
        }
        
        $this->db->insert_batch($this->_table_name, $data); 
    }
    
    public function delete_fields($fields = array())
    {
        $this->db->where_in('field', array_keys($fields));
        $this->db->delete($this->_table_name);
    }
    
}



