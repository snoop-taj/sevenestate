<?php

class Admin_Controller extends MY_Controller 
{
    var $modules_acl_config = array();
    
	public function __construct(){
		parent::__construct();
        $this->data['meta_title'] = 'Real estate script';
        $this->load->helper('form');
        $this->load->helper('text');
        $this->load->library('form_validation');
        $this->load->library('session');
        
        //Change language if user have defined
        $user_lang = $this->session->userdata('lang');
        if(!empty($user_lang))
            $this->config->set_item('language', $user_lang);
            
        $this->lang->load('backend_base');
        
        $this->load->model('user_m');
        $this->load->model('enquire_m');
        $this->load->model('language_m');
        $this->load->model('file_m');
        $this->load->model('repository_m');
        
        $this->form_validation->set_error_delimiters('<p class="label label-important validation">', '</p>');
        
        // Fetch settings
        $this->load->model('settings_m');
        $this->data['settings'] = $this->settings_m->get_fields();
        
	    // Fetch 3 users
		$this->data['users_3'] = $this->user_m->get_by(NULL, FALSE, 3, 'id DESC');
        
	    // Fetch 3 enquire
		$this->data['enquire_3'] = $this->enquire_m->get_by(NULL, FALSE, 3, 'id DESC');
        
        $CI =& get_instance();
        $CI->form_languages = $this->language_m->get_form_dropdown('language', FALSE, FALSE);
        $CI->app_settings = $this->data['settings'];
        
        $CI->acl_config = array();
        $CI->acl_config['ADMIN'] = array('enquire', 'dashboard', 'estate', 'page', 'settings', 'slideshow', 'user', 
                                         'upload', 'order', 'upload_slideshow', 'upload_estate', 'upload_user', 'news', 
                                         'ads', 'expert', 'companies', 'upload_ads', 'showroom', 'upload_showroom', 'expert', 
                                         'booking', 'packages', 'monetize', 'backup', 'reviews');
                                         
        $CI->acl_config['AGENT'] = array('enquire', 'enquire/edit', 'dashboard', 'estate/index', 'estate/edit', 'estate/delete', 'user/edit', 
                                         'upload', 'order', 'upload_estate', 'upload_user', 
                                         'packages/mypackage', 'packages/do_purchase_package', 'packages/cancel_payment');
                                         
        $CI->acl_config['USER'] = array('order', 'upload_estate', 'upload', 'upload_user');
        
        //if(isset($this->modules_acl_config['ADMIN']))
        //$CI->acl_config['ADMIN'] = array_merge($CI->acl_config['ADMIN'], $this->modules_acl_config['ADMIN']);
        
        // Login check
        $exception_uris = array(
            'admin/user/login',
            //'admin/user/login_secret',
            'admin/user/logout',
            'admin/user/register',
            'admin/user/login',
            'admin/user/forgetpassword',
            'admin/user/resetpassword',
            'admin/user/logout',
            'admin/user/register'
        );
        
        $uri_string = uri_string();
        if(substr($uri_string, 0, 1) == '/')$uri_string = substr($uri_string, 1);
        
        if(strpos($uri_string, 'admin/user/resetpassword') === FALSE)
        if(in_array($uri_string, $exception_uris) == FALSE)
        {
            if($this->user_m->loggedin() == FALSE)
            {
                //redirect('admin/user/login_secret', 'refresh');
                redirect('admin/user/login', 'refresh');
            }
            else
            {
                // Check acl
                if(check_acl())
                {
                    
                }
                else
                {
                    $this->session->set_flashdata('error', 
                        lang('You have no permissions'));
                    //redirect('admin/user/login_secret', 'refresh');
                    redirect('admin/user/login', 'refresh');
                }

            }
        }

	}
}