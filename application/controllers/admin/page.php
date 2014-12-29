<?php

class Page extends Admin_Controller
{
	public function __construct(){
		parent::__construct();
        $this->load->model('page_m');
        $this->load->model('file_m');
        $this->load->model('repository_m');

        // Get language for content id to show in administration
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
        
        $this->data['template_css'] = base_url('templates/'.$this->data['settings']['template']).'/'.config_item('default_template_css');
	}
    
    public function index()
	{
	    // Fetch all pages
        $this->data['page_languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['pages_nested'] = $this->page_m->get_nested_tree($this->data['content_language_id']);
        
        // Load view
		$this->data['subview'] = 'admin/page/index';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function order()
    {
		$this->data['sortable'] = TRUE;
        
        // Load view
		$this->data['subview'] = 'admin/page/order';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    public function update_ajax($filename = NULL)
    {
        // Save order from ajax call
        if(isset($_POST['sortable']) && $this->config->item('app_type') != 'demo')
        {
            $this->page_m->save_order($_POST['sortable']);
        }
        
        $data = array();
        $length = strlen(json_encode($data));
        header('Content-Type: application/json; charset=utf8');
        header('Content-Length: '.$length);
        echo json_encode($data);
        
        exit();
    }
    
    public function edit($id = NULL)
	{
	    // Fetch a page or set a new one
	    if($id)
        {
            $this->data['page'] = $this->page_m->get_lang($id, FALSE, $this->data['content_language_id']);
            count($this->data['page']) || $this->data['errors'][] = 'User could not be found';
            
            // Fetch file repository
            $repository_id_t = $this->data['page']->repository_id;

            if(empty($repository_id_t))
            {
                // Create repository
                $repository_id_new = $this->repository_m->save(array('name'=>'page_m'));
                // exit();
                // Update page with new repository_id
                $this->page_m->save(array('repository_id'=>$repository_id_new), $this->data['page']->id);
            }
        }
        else
        {
            $this->data['page'] = $this->page_m->get_new();
        }
        
		// Pages for dropdown
        //$this->data['pages_no_parents'] = $this->page_m->get_no_parents($this->data['content_language_id']);
        $this->data['pages_no_parents'] = $this->page_m->get_no_parents_news($this->data['content_language_id'], 'No parent');
        $this->data['page_languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['templates_page'] = $this->page_m->get_templates('page_');
        
        // Fetch all files by repository_id
        $files = $this->file_m->get();
        foreach($files as $key=>$file)
        {
            $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/_blank.png');
            $file->zoom_enabled = false;
            $file->download_url = base_url('files/'.$file->filename);
            $file->delete_url = site_url_q('files/upload/rep_'.$file->repository_id, '_method=DELETE&amp;file='.rawurlencode($file->filename));

            if(file_exists(FCPATH.'/files/thumbnail/'.$file->filename))
            {
                $file->thumbnail_url = base_url('files/thumbnail/'.$file->filename);
                $file->zoom_enabled = true;
            }
            else if(file_exists(FCPATH.'admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png'))
            {
                $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png');
            }
            
            $this->data['files'][$file->repository_id][] = $file;
        }
        
        // Set up the form
        $rules = $this->page_m->rules;
        $this->form_validation->set_rules($this->page_m->get_all_rules());

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('admin/page/edit/'.$id);
                exit();
            }
            
            $data = $this->page_m->array_from_post(array('type', 'template', 'parent_id', 'is_visible', 'is_private'));
            
            if($id == NULL)
            {
                //get max order in parent id and set
                $parent_id = $this->input->post('parent_id');
                $data['order'] = $this->page_m->max_order($parent_id);
            }

            $data_lang = $this->page_m->array_from_post($this->page_m->get_lang_post_fields());
            if($id == NULL)
            {
                $data['date'] = date('Y-m-d H:i:s');
                $data['date_publish'] = date('Y-m-d H:i:s');
            }
                
            
            $id = $this->page_m->save_with_lang($data, $data_lang, $id);
            
            $this->generate_sitemap();
            
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
            
            redirect('admin/page/edit/'.$id);
        }
        
        // Load the view
		$this->data['subview'] = 'admin/page/edit';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    private function generate_sitemap()
    {
        $this->load->model('estate_m');
        $this->load->model('page_m');
        $this->load->model('option_m');
        
        $this->data['listing_uri'] = config_item('listing_uri');
        if(empty($this->data['listing_uri']))$this->data['listing_uri'] = 'property';
        
        $sitemap = $this->page_m->get_sitemap();
        $properties = $this->estate_m->get_sitemap();
        
        //For all visible languages, get options
        $langs = $this->language_m->get_array_by(array('is_frontend'=>1));
        
        $options = array();
        foreach($langs as $key=>$row_lang)
        {
            $options[$row_lang['id']] = $this->option_m->get_options($row_lang['id'], array(10));
        }
        
        $content = '';
        $content.= '<?xml version="1.0" encoding="UTF-8"?>'."\n".
                   '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'."\n".
                   '  	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'."\n".
                   '  	xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9'."\n".
                   '			    http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'."\n";
        
        $available_langs_array = array();
        foreach($langs as $lang_code=>$lang)
        {
            $available_langs_array[] = $lang['id'];
        }
        
        foreach($sitemap as $page_obj)
        {
            if(in_array($page_obj->language_id ,$available_langs_array))
            {
                $content.= '<url>'."\n".
                        	'	<loc>'.site_url($this->language_m->get_code($page_obj->language_id).'/'.$page_obj->id.'/'.url_title_cro($page_obj->navigation_title, '-', TRUE)).'</loc>'."\n".
                        	//'	<lastmod>'.$page_obj->date.'</lastmod>'.
                        	'	<changefreq>weekly</changefreq>'."\n".
                        	'	<priority>0.5</priority>'."\n".
                        	'</url>'."\n";
            }
        }
        
        foreach($properties as $estate_obj)
        {
            foreach($langs as $lang_code=>$lang)
            {
            $content.= '<url>'."\n".
                    	'	<loc>'.site_url($this->data['listing_uri'].'/'.$estate_obj->id.'/'.$lang['code'].'/'.(isset($options[$lang['id']][$estate_obj->id][10])?url_title_cro($options[$lang['id']][$estate_obj->id][10], '-', TRUE):'')).'</loc>'."\n".
                    	//'	<lastmod>'.$page_obj->date.'</lastmod>'.
                    	'	<changefreq>weekly</changefreq>'."\n".
                    	'	<priority>0.5</priority>'."\n".
                    	'</url>'."\n";
            }
        }
        
        // [Showroom START] //
        if(file_exists(APPPATH.'controllers/admin/showroom.php'))
        {
            $this->load->model('showroom_m');
            $showrooms = $this->showroom_m->get_by(array('type'=>'COMPANY'));
            
            foreach($showrooms as $showroom_obj)
            {
                foreach($langs as $lang_code=>$lang)
                {
                $content.= '<url>'."\n".
                        	'	<loc>'.site_url('showroom/'.$showroom_obj->id.'/'.$lang['code']).'</loc>'."\n".
                        	//'	<lastmod>'.$page_obj->date.'</lastmod>'.
                        	'	<changefreq>weekly</changefreq>'."\n".
                        	'	<priority>0.5</priority>'."\n".
                        	'</url>'."\n";
                }
            }
        }
        // [Showroom END] //

        $content.= '</urlset>';
        
        $fp = fopen(FCPATH.'sitemap.xml', 'w');
        fwrite($fp, $content);
        fclose($fp);
    }
    
    public function delete($id)
	{
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/page');
            exit();
        }
       
		$this->page_m->delete($id);
        redirect('admin/page');
	}
    
	public function parent_check($parent_id)
	{
	    if($parent_id==0 || $this->input->post('type') == 'ARTICLE')
            return TRUE;
            
        $page_parent = $this->page_m->get($parent_id);
        if($page_parent->parent_id == 0)
        {
            return TRUE;
        }

    	$this->form_validation->set_message('parent_check', lang_check('Just 2 page levels allowed'));
    	return FALSE;
	}
    
    public function _unique_slug($str)
    {
        // Do NOT validate if slug alredy exists
        // UNLESS it's the slug for the current page
        
        $id = $this->uri->segment(4);
        $this->db->where('slug', $this->input->post('slug'));
        !$id || $this->db->where('id !=', $id);
        
        $page = $this->page_m->get();
        
        if(count($page))
        {
            $this->form_validation->set_message('_unique_slug', '%s should be unique');
            return FALSE;
        }
        
        return TRUE;
    }
    
}