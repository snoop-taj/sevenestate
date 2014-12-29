<?php

class File_m extends MY_Model {
    
    protected $_table_name = 'file';
    protected $_order_by = 'order, id';
    
    protected $_revision_id = 1;

    public function set_revision($revision_id)
    {
        if($revision_id != NULL)
            $this->_revision_id = $revision_id;
    }
    
    public function get_max_order()
    {
        // get max order
        return parent::max_order();
    }

}



