<?php

class Payments_m extends MY_Model {
    
    protected $_table_name = 'payments';
    protected $_order_by = 'id';
    public $rules = array();
    
    public $currencies = array(
                               'AUD'=>'AUD - Australian Dollar',
                               'BRL'=>'BRL - Brazilian Real',
                               'CAD'=>'CAD - Canadian Dollar',
                               'CZK'=>'CZK - Czech Koruna',
                               'DKK'=>'DKK - Danish Krone ',
                               'EUR'=>'EUR - Euro', 
                               'HKD'=>'HKD - Hong Kong Dollar',
                               'HUF'=>'HUF - Hungarian Forint',
                               'ILS'=>'ILS - Israeli New Sheqel',
                               'JPY'=>'JPY - Japanese Yen',
                               'MYR'=>'MYR - Malaysian Ringgit',
                               'MXN'=>'MXN - Mexican Peso',
                               'NOK'=>'NOK - Norwegian Krone', 
                               'NZD'=>'NZD - New Zealand Dollar',
                               'PHP'=>'PHP - Philippine Peso',
                               'PLN'=>'PLN - Polish Zloty',
                               'GBP'=>'GBP - Pound Sterling',
                               'RUB'=>'RUB - Russian Ruble',
                               'SGD'=>'SGD - Singapore Dollar',
                               'SEK'=>'SEK - Swedish Krona ',
                               'CHF'=>'CHF - Swiss Franc',
                               'TWD'=>'TWD - Taiwan New Dollar',
                               'THB'=>'THB - Thai Baht',
                               'TRY'=>'TRY - Turkish Lira', 
                               'USD'=>'USD - U.S. Dollar'
                               );
    
    public function get_new()
	{
        $item = new stdClass();
        $item->invoice_num = '';
        $item->date_paid = date('Y-m-d H:i:s');
        $item->data_post = '';
        
        return $page;
	}

}


