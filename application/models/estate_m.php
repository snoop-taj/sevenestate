<?php

class Estate_m extends MY_Model {
    
    protected $_table_name = 'property';
    protected $_order_by = 'id';
    public $rules = array(
        'gps' => array('field'=>'gps', 'label'=>'lang:Gps', 'rules'=>'trim|required|xss_clean|callback_gps_check'),
        'date' => array('field'=>'date', 'label'=>'lang:DateTime', 'rules'=>'trim|required|xss_clean'),
        'address' => array('field'=>'address', 'label'=>'lang:Address', 'rules'=>'trim|xss_clean|quote_fix'),
        'is_featured' => array('field'=>'is_featured', 'label'=>'lang:Featured', 'rules'=>'trim'),
        'is_activated' => array('field'=>'is_activated', 'label'=>'lang:Activated', 'rules'=>'trim'),
        'agent' => array('field'=>'agent', 'label'=>'lang:Agent', 'rules'=>'trim')
   );

    public function get_new()
	{
        $estate = new stdClass();
        $estate->gps = '';
        $estate->address = '';
        $estate->date = date('Y-m-d H:i:s');
        $estate->agent = NULL;
        $estate->is_featured = '0';
        $estate->is_activated = '0';
        $estate->counter_views = 0;
        return $estate;
	}
    
    public function get_new_array()
	{
        $estate = array();
        $estate['gps'] = '';
        $estate['address'] = '';
        $estate['date'] = date('Y-m-d H:i:s');
        $estate['agent'] = NULL;
        $estate['is_featured'] = '0';
        $estate['is_activated'] = '0';
        $estate['counter_views'] = 0;
        return $estate;
	}
    
    public function update_counter($property_id)
    {
        $this->db->set('counter_views', 'counter_views+1', FALSE);
        $this->db->where('id', $property_id);
        $this->db->update($this->_table_name); 
    }
    
    public function get_search($search_tag)
    {
        // Fetch pages without parents
        $this->db->distinct();
        $this->db->select($this->_table_name.'.id, gps, address, is_featured, is_activated');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_value', $this->_table_name.'.id = '.$this->_table_name.'_value.property_id');
        $this->db->like('value', $search_tag);
        $this->db->or_like('address', $search_tag);
        
        if($this->session->userdata('type') != 'ADMIN')
        {
            $this->db->join('property_user', $this->_table_name.'.id = property_user.property_id', 'right');
            $this->db->where('user_id', $this->session->userdata('id'));
        }
        
        $query = $this->db->get();
        $results = $query->result();
        
        return $results;
    }
    
    public function get_last($n = 5)
    {
        $this->db->select('property.*');
        $this->db->limit($n);
        $this->db->from($this->_table_name);
        
        if($this->session->userdata('type') != 'ADMIN')
        {
            $this->db->select('property.*, property_user.user_id');
            $this->db->join('property_user', $this->_table_name.'.id = property_user.property_id', 'left');
            $this->db->where('user_id', $this->session->userdata('id'));
        }
        
        $this->db->order_by($this->_table_name.'.id DESC');

        $query = $this->db->get();
        return $query->result();
    }
    
    public function get_dynamic($id)
    {
        $data = parent::get($id);
        
        if($data == NULL) return NULL;
        
        $this->db->where('property_id', $id);
        $query = $this->db->get('property_value');
        
        foreach ($query->result() as $row)
        {
            $data->{'option'.$row->option_id.'_'.$row->language_id} = $row->value;
        }
        
        // Get agent
        $data->agent = null;
        $this->db->where('property_id', $id);
        $this->db->limit(1);
        $query = $this->db->get('property_user');
        foreach ($query->result() as $row)
        {
            $data->agent = $row->user_id;
        }
        
        return $data;
    }
    
    public function get_dynamic_array($id)
    {
        $data = parent::get_array($id);
        
        if($data == NULL) return NULL;
        
        $this->db->where('property_id', $id);
        $query = $this->db->get('property_value');
        
        foreach ($query->result() as $row)
        {
            $data['option'.$row->option_id.'_'.$row->language_id] = $row->value;
        }
        
        // Get agent
        $data['agent'] = null;
        $this->db->where('property_id', $id);
        $this->db->limit(1);
        $query = $this->db->get('property_user');
        foreach ($query->result() as $row)
        {
            $data['agent'] = $row->user_id;
        }
        
        return $data;
    }
    
    public function get_join($limit = null, $offset = "")
    {
        $this->db->select('property.*, property_user.user_id as agent');
        $this->db->from($this->_table_name);
        $this->db->join('property_user', $this->_table_name.'.id = property_user.property_id', 'left');
        
        if($this->session->userdata('type') != 'ADMIN')
        {
            $this->db->where('user_id', $this->session->userdata('id'));
        }
        
        $this->db->order_by('id DESC');
        
        if($limit != null)
            $this->db->limit($limit, $offset);
        
        $query = $this->db->get();
        
        return $query->result();
    }
    
    public function save_dynamic($data, $id)
    {
        // Delete all
        $this->db->where('property_id', $id);
        $this->db->delete('property_value'); 
        
        // Insert all
        $insert_batch = array();
        foreach($data as $key=>$value)
        {
            if(substr($key, 0, 6) == 'option')
            {
                $pos = strpos($key, '_');
                $option_id = substr($key, 6, $pos-6);
                $language_id = substr($key, $pos+1);
                
                $val_numeric = NULL;
                if( is_numeric($value) )
                {
                    $val_numeric = intval($value);
                }
                
                $insert_arr = array('language_id' => $language_id,
                                    'property_id' => $id,
                                    'option_id' => $option_id,
                                    'value' => $value,
                                    'value_num' => $val_numeric);
                                    
                $insert_batch[] = $insert_arr;
                
                /*
                $this->db->set(array('language_id'=>$language_id,
                                     'property_id'=>$id,
                                     'option_id'=>$option_id,
                                     'value'=>$value));
                $this->db->insert('property_value');
                */
            }
        }
        if(count($insert_batch) > 0)
            $this->db->insert_batch('property_value', $insert_batch); 
        
        // Delete all users
        if(!empty($data['agent']))
        {
            $this->db->where('property_id', $id);
            $this->db->delete('property_user'); 
            $this->db->set(array('property_id'=>$id,
                                 'user_id'=>$data['agent']));
            $this->db->insert('property_user');
        }
    }
    
    public function delete($id)
    {
        // Delete all options
        $this->db->where('property_id', $id);
        $this->db->delete('property_value'); 
        
        $this->db->where('property_id', $id);
        $this->db->delete('enquire'); 
        
        $this->db->where('property_id', $id);
        $this->db->delete('property_user'); 
        
        $this->db->where('property_id', $id);
        $this->db->delete('reservaions');
        
        // [START] remove rates
        $query = $this->db->get_where('rates', array('property_id' => $id));
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            $this->db->where('rates_id', $row->id);
            $this->db->delete('rates_lang'); 
        } 
        $this->db->where('property_id', $id);
        $this->db->delete('rates'); 
        // [END] remove rates
        
        // Remove repository
        $estate_data = $this->get($id, TRUE);
        if(count($estate_data))
        {
            $this->repository_m->delete($estate_data->repository_id);
        }
        
        parent::delete($id);
    }
    
    public function get_sitemap()
	{
        // Fetch pages without parents
        $this->db->select('*');
        //$this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.page_id');
        $estates = parent::get_by(array('is_activated'=>1));
                
        return $estates;
	}
    
    public function check_user_permission($property_id, $user_id)
    {
        $this->db->where('property_id', $property_id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('property_user');
        return $query->num_rows();
    }
    
    public function get_user_properties($user_id)
    {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('property_user');
        
        $properties = array();
        foreach ($query->result() as $row)
        {
          $properties[] = $row->property_id;
        }
        
        return $properties;
    }
    
    public function change_activated_properties($property_ids = array(), $is_activated)
    {
        $data = array(
                       'is_activated' => $is_activated
                    );
        
        $this->db->where_in('id', $property_ids);
        $this->db->update($this->_table_name, $data); 
    }

}



