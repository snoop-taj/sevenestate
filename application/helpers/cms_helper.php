<?php

function add_meta_title ($string)
{
	$CI =& get_instance();
	$CI->data['meta_title'] = e($string) . ' - ' . $CI->data['meta_title'];
}

function btn_view($uri)
{
	return anchor($uri, '<i class=" icon-search"></i> '.lang('view'), array('class'=>'btn btn-primary'));
}

function btn_view_curr($uri)
{
	return anchor($uri, '<i class=" icon-search"></i> '.lang('view_curr'), array('class'=>'btn btn-primary'));
}

function btn_view_sent($uri)
{
	return anchor($uri, '<i class=" icon-th-list"></i> '.lang('view_sent'), array('class'=>'btn btn btn-info'));
}

function btn_edit($uri)
{
	return anchor($uri, '<i class="icon-edit"></i> '.lang('edit'), array('class'=>'btn btn-primary'));
}

function btn_edit_invoice($uri)
{
	return anchor($uri, '<i class="icon-edit"></i> '.lang('edit_invoice'), array('class'=>'btn btn-primary'));
}

function btn_delete($uri)
{
	return anchor($uri, '<i class="icon-remove"></i> '.lang('delete'), array('onclick' => 'return confirm(\''.lang('Are you sure?').'\')', 'class'=>'btn btn-danger'));
}

function btn_delete_debit($uri)
{
	return anchor($uri, '<i class="icon-remove"></i> '.lang('delete_debit'), array('onclick' => 'return confirm(\''.lang('delete_debit?').'\')', 'class'=>'btn btn-danger'));
}

if ( ! function_exists('get_file_extension'))
{
    function get_file_extension($filepath)
    {
        return substr($filepath, strrpos($filepath, '.')+1);
    }
}

if ( ! function_exists('character_hard_limiter'))
{
    function character_hard_limiter($string, $max_len)
    {
        if(strlen($string)>$max_len)
        {
            return substr($string, 0, $max_len-3).'...';
        }
        
        return $string;
    }
}

function article_link($article){
	return 'article/' . intval($article->id) . '/' . e($article->slug);
}

function article_links($articles){
	$string = '<ul>';
	foreach ($articles as $article) {
		$url = article_link($article);
		$string .= '<li>';
		$string .= '<h3>' . anchor($url, e($article->title)) .  ' &rsaquo;</h3>';
		$string .= '<p class="pubdate">' . e($article->pubdate) . '</p>';
		$string .= '</li>';
	}
	$string .= '</ul>';
	return $string;
}

function get_excerpt($article, $numwords = 50){
	$string = '';
	$url = article_link($article);
	$string .= '<h2>' . anchor($url, e($article->title)) .  '</h2>';
	$string .= '<p class="pubdate">' . e($article->pubdate) . '</p>';
	$string .= '<p>' . e(limit_to_numwords(strip_tags($article->body), $numwords)) . '</p>';
	$string .= '<p>' . anchor($url, 'Read more &rsaquo;', array('title' => e($article->title))) . '</p>';
	return $string;
}

function limit_to_numwords($string, $numwords){
	$excerpt = explode(' ', $string, $numwords + 1);
	if (count($excerpt) >= $numwords) {
		array_pop($excerpt);
	}
	$excerpt = implode(' ', $excerpt);
	return $excerpt;
}

function e($string){
	return htmlentities($string);
}

function get_menu ($array, $child = FALSE, $lang_code)
{
	$CI =& get_instance();
	$str = '';
    
    $is_logged_user = ($CI->user_m->loggedin() == TRUE);
	
	if (count($array)) {
		$str .= $child == FALSE ? '<ul class="nav navbar-nav nav-collapse collapse navbar-main" id="main-top-menu" role="navigation">' . PHP_EOL : '<ul class="dropdown-menu">' . PHP_EOL;
		$position = 0;
		foreach ($array as $key=>$item) {
			$position++;
            
            $active = $CI->uri->segment(2) == url_title_cro($item['id'], '-', TRUE) ? TRUE : FALSE;
            
            if($position == 1 && $child == FALSE){
                $item['navigation_title'] = '<img src="assets/img/home-icon.png" alt="'.$item['navigation_title'].'" />';
                
                if($CI->uri->segment(2) == '')
                    $active = TRUE;
            }
            
            if($item['is_visible'] == '1')
            if($item['is_private'] == '0' || $item['is_private'] == '1' && $is_logged_user)
			if (isset($item['children']) && count($item['children'])) {
			 
                $href = site_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title'], '-', TRUE));
                if(substr($item['keywords'],0,4) == 'http')
                    $href = $item['keywords'];
                    
                if($item['keywords'] == '#')
                    $href = '#';
             
				$str .= $active ? '<li class="dropdown active">' : '<li class="dropdown">';
				$str .= '<a  class="dropdown-toggle" data-toggle="dropdown" href="' . $href . '">' . $item['navigation_title'];
				$str .= '<b class="caret"></b></a>' . PHP_EOL;
				$str .= get_menu($item['children'], TRUE, $lang_code);
                
			}
			else {
			 
                $href = site_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title'], '-', TRUE));
                if(substr($item['keywords'],0,4) == 'http')
                    $href = $item['keywords'];
                    
                if($item['keywords'] == '#')
                    $href = '#';
             
				$str .= $active ? '<li class="active">' : '<li>';
				$str .= '<a href="' . $href . '">' . $item['navigation_title'] . '</a>';
                
			}
			$str .= '</li>' . PHP_EOL;
		}
		
		$str .= '</ul>' . PHP_EOL;
	}
	
	return $str;
}

function get_lang_menu ($array, $lang_code)
{
    $CI =& get_instance();
    
    if(count($array) == 1)
        return '';
    
    $str = '<ul>';
    foreach ($array as $item) {
        $active = $lang_code == $item['code'] ? TRUE : FALSE;
        
        $flag_icon = '';
        $CI =& get_instance();
        if(isset($CI->data['settings_template']))
        {
            $template_name = $CI->data['settings_template'];
            if(file_exists(FCPATH.'templates/'.$template_name.'/assets/img/flags/'.$item['code'].'.png'))
            {
                $flag_icon = '&nbsp; <img src="'.'assets/img/flags/'.$item['code'].'.png" alt="" />';
            }
        }

        if($CI->uri->segment(1) == 'property')
        {
            if($active)
            {
                $str.='<li class="active">'.anchor('property/'.$CI->uri->segment(2).'/'.$item['code'], $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li>'.anchor('property/'.$CI->uri->segment(2).'/'.$item['code'], $item['code'].$flag_icon).'</li>';
            }
        }
        else if($CI->uri->segment(1) == 'showroom')
        {
            if($active)
            {
                $str.='<li class="active">'.anchor('showroom/'.$CI->uri->segment(2).'/'.$item['code'], $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li>'.anchor('showroom/'.$CI->uri->segment(2).'/'.$item['code'], $item['code'].$flag_icon).'</li>';
            }
        }
        else if($CI->uri->segment(1) == 'profile')
        {
            if($active)
            {
                $str.='<li class="active">'.anchor('profile/'.$CI->uri->segment(2).'/'.$item['code'], $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li>'.anchor('profile/'.$CI->uri->segment(2).'/'.$item['code'], $item['code'].$flag_icon).'</li>';
            }
        }
        else if(is_numeric($CI->uri->segment(2)))
        {
            if($active)
            {
                $str.='<li class="active">'.anchor($item['code'].'/'.$CI->uri->segment(2), $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li>'.anchor($item['code'].'/'.$CI->uri->segment(2), $item['code'].$flag_icon).'</li>';
            }
        }
        else if($CI->uri->segment(2) != '')
        {
            if($active)
            {
                $str.='<li class="active">'.anchor($CI->uri->segment(1).'/'.$CI->uri->segment(2).'/'.$item['code'].'/'.$CI->uri->segment(4), $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li>'.anchor($CI->uri->segment(1).'/'.$CI->uri->segment(2).'/'.$item['code'].'/'.$CI->uri->segment(4), $item['code'].$flag_icon).'</li>';
            }
        }
        else
        {
            if($active)
            {
                $str.='<li class="active">'.anchor($item['code'], $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li>'.anchor($item['code'], $item['code'].$flag_icon).'</li>';
            }
        }
    }
    $str.='</ul>';
    
    return $str;
}

function get_admin_menu($array)
{
    $CI =& get_instance();
    
    $str = '<ul class="nav">';
    foreach ($array as $item) {
        $active = $CI->uri->segment(1).'/'.$CI->uri->segment(2) == $item['uri'] ? TRUE : FALSE;
        
        if($active)
        {
            $str.='<li class="active">'.anchor($item['uri'], $item['title']).'</li>';
        }
        else
        {
            $str.='<li>'.anchor($item['uri'], $item['title']).'</li>';
        }
    }
    $str.='</ul>';
    
    return $str;
}

/**
* Dump helper. Functions to dump variables to the screen, in a nicley formatted manner.
* @author Joost van Veen
* @version 1.0
*/
if (!function_exists('dump')) {
    function dump ($var, $label = 'Dump', $echo = TRUE)
    {
        // Store dump in variable
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        
        // Add formatting
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';
        
        // Output
        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}
 
 
if (!function_exists('dump_exit')) {
    function dump_exit($var, $label = 'Dump', $echo = TRUE)
    {
        dump ($var, $label, $echo);
        exit;
    }
}


if ( ! function_exists('get_ol'))
{
    function get_ol ($array, $child = FALSE)
    {
    	$str = '';
    	
    	if (count($array)) {
    		$str .= $child == FALSE ? '<ol class="sortable" id="option_sortable">' : '<ol>';
    		
    		foreach ($array as $item) {
    		  
                if($child == FALSE){
                    $item_children = null;
                    if(isset($item['children']))$item_children = $item['children'];
                    $item = $item['parent'];
                    if(isset($item_children))$item['children'] = $item_children;
                }
              
                $visible = '';
                if($item['visible'] == 1)
                    $visible = '<i class="icon-th-large"></i>';
                
                $locked='';
                if($item['is_hardlocked'])
                    $locked = '<i class="icon-lock" style="color:red;"></i>';
                else if($item['is_locked'] == 1)
                    $locked = '<i class="icon-lock"></i>';
                    
                $frontend='';
                if($item['is_frontend'] == 0)
                    $frontend = '<i class="icon-eye-close"></i>';
                
                $icon = '';
                $CI =& get_instance();
                $template_name = $CI->data['settings']['template'];
                if(file_exists(FCPATH.'templates/'.$template_name.'/assets/img/icons/option_id/'.$item['id'].'.png'))
                {
                    $icon = '<img class="results-icon" src="'.base_url('templates/'.$template_name.'/assets/img/icons/option_id/'.$item['id'].'.png').'" alt="'.$item['option'].'"/>&nbsp;&nbsp;';
                }
                
    			$str .= '<li id="list_' . $item['id'] .'">';
    			$str .= '<div class="" alt="'.$item['id'].'" >#'.$item['id'].'&nbsp;&nbsp;&nbsp;'.$icon.$item['option'].'&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-'.$item['color'].'">'.$item['type'].'</span>&nbsp;&nbsp;'.$visible.'&nbsp;&nbsp;'.$locked.'&nbsp;&nbsp;'.$frontend.'<span class="pull-right">
                            <div class="btn-group btn-group-xs">
                              <a class="btn btn-xs btn-primary" href="'.site_url('admin/estate/edit_option/'.$item['id']).'"><i class="icon-edit"></i></a>'.
                              ($item['is_locked']||$item['is_hardlocked']?'':'<a onclick="return confirm(\''.lang('Are you sure?').'\')" class="btn btn-xs btn-danger delete" data-loading-text="'.lang('Loading...').'" href="'.site_url('admin/estate/delete_option/'.$item['id']).'"><i class="icon-remove"></i></a>')
                            .'</div></span></div>';
    			
                // Do we have any children?
    			if (isset($item['children']) && count($item['children'])) {
    				$str .= get_ol($item['children'], TRUE);
    			}
    			
    			$str .= '</li>' . PHP_EOL;
    		}
    		
    		$str .= '</ol>' . PHP_EOL;
    	}
    	
    	return $str;
    }
}

if ( ! function_exists('get_ol_pages'))
{
    function get_ol_pages ($array, $child = FALSE)
    {
    	$str = '';
    	
    	if (count($array)) {
    		$str .= $child == FALSE ? '<ol class="sortable" id="page_sortable" rel="2">' : '<ol>';
    		
    		foreach ($array as $item) {  

    			$str .= '<li id="list_' . $item['id'] .'">';
    			$str .= '<div class="" alt="'.$item['id'].'" ><i class="icon-file-alt"></i>&nbsp;&nbsp;' . $item['title'] .'&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-warning">'.$item['template'].'</span>';
                if($item['type'] == 'ARTICLE')
                    $str .= '&nbsp;<span class="label label-info">'.lang_check($item['type']).'</span>';
                $str .= '<span class="pull-right">
                            <div class="btn-group btn-group-xs">
                              <a class="btn btn-xs btn-primary" href="'.site_url('admin/page/edit/'.$item['id']).'"><i class="icon-edit"></i></a>
                              <a onclick="return confirm(\''.lang('Are you sure?').'\')" class="btn btn-xs btn-danger delete" data-loading-text="'.lang('Loading...').'" href="'.site_url('admin/page/delete/'.$item['id']).'"><i class="icon-remove"></i></a>
                            </div></span></div>';
    			
                // Do we have any children?
    			if (isset($item['children']) && count($item['children'])) {
    				$str .= get_ol_pages($item['children'], TRUE);
    			}
    			
    			$str .= '</li>' . PHP_EOL;
    		}
    		
    		$str .= '</ol>' . PHP_EOL;
    	}
    	
    	return $str;
    }
}

if ( ! function_exists('get_ol_pages_tree'))
{
    function get_ol_pages_tree ($array, $parent_id = 0)
    {
    	$str = '';
    	
    	if (count($array)) {
    		$str .= $parent_id == 0 ? '<ol class="sortable" id="page_sortable" rel="3">' : '<ol>';

    		foreach ($array[$parent_id] as $k_parent_id => $item) {  
    			$str .= '<li id="list_' . $item['id'] .'" rel='.$parent_id.'>';
    			$str .= '<div class="" alt="'.$item['id'].'" ><i class="icon-file-alt"></i>&nbsp;&nbsp;' . $item['title'] .'&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-warning">'.$item['template'].'</span>';
                if($item['type'] == 'ARTICLE')
                    $str .= '&nbsp;<span class="label label-info">'.lang_check($item['type']).'</span>';
                if($item['is_visible'] == '0')
                    $str .= '&nbsp;&nbsp;<i class="icon-eye-close"></i>';
                    
                $str .= '<span class="pull-right">
                            <div class="btn-group btn-group-xs">
                              <a class="btn btn-xs btn-primary" href="'.site_url('admin/page/edit/'.$item['id']).'"><i class="icon-edit"></i></a>
                              <a onclick="return confirm(\''.lang('Are you sure?').'\')" class="btn btn-xs btn-danger delete" data-loading-text="'.lang('Loading...').'" href="'.site_url('admin/page/delete/'.$item['id']).'"><i class="icon-remove"></i></a>
                            </div></span></div>';
    			
                // Do we have any children?
    			if (isset($array[$k_parent_id])) {
    				$str .= get_ol_pages_tree($array, $k_parent_id);
    			}
    			
    			$str .= '</li>' . PHP_EOL;
    		}
    		
    		$str .= '</ol>' . PHP_EOL;
    	}
    	
    	return $str;
    }
}

if ( ! function_exists('get_ol_news'))
{
    function get_ol_news ($array, $child = FALSE)
    {
    	$str = '';
    	
    	if (count($array)) {
    		$str .= $child == FALSE ? '<ol class="sortable" id="page_sortable" rel="2">' : '<ol>';
    		
    		foreach ($array as $item) {                
    			$str .= '<li id="list_' . $item['id'] .'">';
    			$str .= '<div class="" alt="'.$item['id'].'" ><i class="icon-file-alt"></i>&nbsp;&nbsp;' . $item['title'] .'&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-warning">'.$item['template'].'</span><span class="pull-right">
                            <div class="btn-group btn-group-xs">
                              <a class="btn btn-xs btn-success" href="'.site_url('admin/news/index/'.$item['id']).'"><i class="icon-list"></i></a>
                              <a class="btn btn-xs btn-primary" href="'.site_url('admin/news/edit_category/'.$item['id']).'"><i class="icon-edit"></i></a>
                              <a onclick="return confirm(\''.lang('Are you sure?').'\')" class="btn btn-xs btn-danger delete" data-loading-text="'.lang('Loading...').'" href="'.site_url('admin/news/delete/'.$item['id']).'"><i class="icon-remove"></i></a>
                            </div></span></div>';
    			
                // Do we have any children?
    			if (isset($item['children']) && count($item['children'])) {
    				$str .= get_ol_news($item['children'], TRUE);
    			}
    			
    			$str .= '</li>' . PHP_EOL;
    		}
    		
    		$str .= '</ol>' . PHP_EOL;
    	}
    	
    	return $str;
    }
}

if ( ! function_exists('get_ol_showroom_tree'))
{
    function get_ol_showroom_tree ($array, $parent_id=0)
    {        
    	$str = '';
    	
    	if (count($array)) {
    		$str .= $parent_id == 0 ? '<ol class="sortable" id="showroom_sortable" rel="2">' : '<ol>';

    		foreach ($array[$parent_id] as $k_parent_id => $item) {  
    			$str .= '<li id="list_' . $item['id'] .'" rel='.$parent_id.'>';
    			$str .= '<div class="" alt="'.$item['id'].'" ><i class="icon-file-alt"></i>&nbsp;&nbsp;' . $item['title'];
                //if($item['type'] == 'ARTICLE')
                //    $str .= '&nbsp;<span class="label label-info">'.lang_check($item['type']).'</span>';
                $str .= '<span class="pull-right">
                            <div class="btn-group btn-group-xs">
                              <a class="btn btn-xs btn-success" href="'.site_url('admin/showroom/index/'.$item['id']).'"><i class="icon-list"></i></a>
                              <a class="btn btn-xs btn-primary" href="'.site_url('admin/showroom/edit_category/'.$item['id']).'"><i class="icon-edit"></i></a>
                              <a onclick="return confirm(\''.lang('Are you sure?').'\')" class="btn btn-xs btn-danger delete" data-loading-text="'.lang('Loading...').'" href="'.site_url('admin/showroom/delete/'.$item['id']).'"><i class="icon-remove"></i></a>
                            </div></span></div>';
    			
                // Do we have any children?
    			if (isset($array[$k_parent_id])) {
    				$str .= get_ol_showroom_tree($array, $k_parent_id);
    			}
    			
    			$str .= '</li>' . PHP_EOL;
    		}
    		
    		$str .= '</ol>' . PHP_EOL;
    	}
    	
    	return $str;
    }
}

if ( ! function_exists('get_ol_expert_tree'))
{
    function get_ol_expert_tree ($array, $parent_id=0)
    {        
    	$str = '';
    	
    	if (count($array)) {
    		$str .= $parent_id == 0 ? '<ol class="sortable" id="expert_sortable" rel="2">' : '<ol>';

    		foreach ($array[$parent_id] as $k_parent_id => $item) {  
    			$str .= '<li id="list_' . $item['id'] .'" rel='.$parent_id.'>';
    			$str .= '<div class="" alt="'.$item['id'].'" ><i class="icon-file-alt"></i>&nbsp;&nbsp;' . $item['question'];
                //if($item['type'] == 'ARTICLE')
                //    $str .= '&nbsp;<span class="label label-info">'.lang_check($item['type']).'</span>';
                $str .= '<span class="pull-right">
                            <div class="btn-group btn-group-xs">
                              <a class="btn btn-xs btn-success" href="'.site_url('admin/expert/index/'.$item['id']).'"><i class="icon-list"></i></a>
                              <a class="btn btn-xs btn-primary" href="'.site_url('admin/expert/edit_category/'.$item['id']).'"><i class="icon-edit"></i></a>
                              <a onclick="return confirm(\''.lang('Are you sure?').'\')" class="btn btn-xs btn-danger delete" data-loading-text="'.lang('Loading...').'" href="'.site_url('admin/expert/delete/'.$item['id']).'"><i class="icon-remove"></i></a>
                            </div></span></div>';
    			
                // Do we have any children?
    			if (isset($array[$k_parent_id])) {
    				$str .= get_ol_showroom_tree($array, $k_parent_id);
    			}
    			
    			$str .= '</li>' . PHP_EOL;
    		}
    		
    		$str .= '</ol>' . PHP_EOL;
    	}
    	
    	return $str;
    }
}

function calculateCenter($object_locations) 
{
    $minlat = false;
    $minlng = false;
    $maxlat = false;
    $maxlng = false;

    foreach ($object_locations as $estate) {
         $geolocation = array();
         $gps_string_explode = explode(', ', $estate->gps);
         
         if(count($gps_string_explode)>0)
         {
             $geolocation['lat'] = $gps_string_explode[0];
             $geolocation['lon'] = $gps_string_explode[1];
             
             if ($minlat === false) { $minlat = $geolocation['lat']; } else { $minlat = ($geolocation['lat'] < $minlat) ? $geolocation['lat'] : $minlat; }
             if ($maxlat === false) { $maxlat = $geolocation['lat']; } else { $maxlat = ($geolocation['lat'] > $maxlat) ? $geolocation['lat'] : $maxlat; }
             if ($minlng === false) { $minlng = $geolocation['lon']; } else { $minlng = ($geolocation['lon'] < $minlng) ? $geolocation['lon'] : $minlng; }
             if ($maxlng === false) { $maxlng = $geolocation['lon']; } else { $maxlng = ($geolocation['lon'] > $maxlng) ? $geolocation['lon'] : $maxlng; }
        
         }
    }

    // Calculate the center
    $lat = $maxlat - (($maxlat - $minlat) / 2);
    $lon = $maxlng - (($maxlng - $minlng) / 2);

    return $lat.', '.$lon;
}

function calculateCenterArray($array_locations) 
{
    $minlat = false;
    $minlng = false;
    $maxlat = false;
    $maxlng = false;

    foreach ($array_locations as $estate) {
         $geolocation = array();
         $gps_string_explode = explode(', ', $estate['gps']);
         
         if(count($gps_string_explode)>0)
         {
             $geolocation['lat'] = $gps_string_explode[0];
             $geolocation['lon'] = $gps_string_explode[1];
             
             if ($minlat === false) { $minlat = $geolocation['lat']; } else { $minlat = ($geolocation['lat'] < $minlat) ? $geolocation['lat'] : $minlat; }
             if ($maxlat === false) { $maxlat = $geolocation['lat']; } else { $maxlat = ($geolocation['lat'] > $maxlat) ? $geolocation['lat'] : $maxlat; }
             if ($minlng === false) { $minlng = $geolocation['lon']; } else { $minlng = ($geolocation['lon'] < $minlng) ? $geolocation['lon'] : $minlng; }
             if ($maxlng === false) { $maxlng = $geolocation['lon']; } else { $maxlng = ($geolocation['lon'] > $maxlng) ? $geolocation['lon'] : $maxlng; }
        
         }
    }

    // Calculate the center
    $lat = $maxlat - (($maxlat - $minlat) / 2);
    $lon = $maxlng - (($maxlng - $minlng) / 2);

    return array($lat, $lon);
}

function lang_check($line, $id = '')
{
	$r_line = lang($line, $id);

    if(empty($r_line))
        $r_line = $line;
    
	return $r_line;
}

function check_set($test, $default)
{
    if(isset($test))
        return $test;
        
    return $default;
}

function check_combine_set($main, $test, $default)
{
    if(count(explode(',', $main)) == count(explode(',', $test)) && 
       count(explode(',', $main)) > 0 && count(explode(',', $test)) > 0)
    {
        return $main;
    }

    return $default;
}

/* Extra simple acl implementation */
function check_acl($uri_for_check = NULL)
{
    $CI =& get_instance();
    $user_type = $CI->session->userdata('type');
    $acl_config = $CI->acl_config;
    //echo $CI->uri->uri_string();
    //echo $user_type;
    
    if($uri_for_check !== NULL)
    {
        if(in_array($uri_for_check, $acl_config[$user_type]))
        {
            return true;
        }
        
        $uri_for_check_explode = explode('/', $uri_for_check);
        if(in_array($uri_for_check_explode[0], $acl_config[$user_type]))
        {
            return true;
        }
        
        return false;
    }
    
    if(in_array($CI->uri->segment(2), $acl_config[$user_type]))
    {
        return true;
    }
    
    if(in_array($CI->uri->segment(2).'/index', $acl_config[$user_type]) && $CI->uri->segment(3) == '')
    {
        return true;
    }
    
    if(in_array($CI->uri->segment(2).'/'.$CI->uri->segment(3), $acl_config[$user_type]))
    {
        return true;
    }
    
    return false;
}

if ( ! function_exists('return_value'))
{
    function return_value($array, $key, $default='')
    {
        if(isset($array[$key]))
        {
            return $array[$key];
        }
        
        return $default;
    }
}

if ( ! function_exists('return_value_nempty'))
{
    function return_value_nempty($array, $key, $default='')
    {
        if(isset($array[$key]) && !empty($array[$key]))
        {
            return $array[$key];
        }
        
        return $default;
    }
}

/**
* Returns the specified config item
*
* @access	public
* @return	mixed
*/
if ( ! function_exists('config_db_item'))
{
	function config_db_item($item)
	{
		static $_config_item = array();
        static $_db_settings = array();

		if ( ! isset($_config_item[$item]))
		{
			$config =& get_config();
            
            // [check-database]
            if(count($_db_settings) == 0)
            {
                $CI =& get_instance();
                $CI->load->model('masking_m');
                $_db_settings = $CI->settings_m->get_fields();
            }

            if(isset($_db_settings[$item]))
            {
                $_config_item[$item] = $_db_settings[$item];
                return $_config_item[$item];
            }
            // [/check-database]
            
			if ( ! isset($config[$item]))
			{
				return FALSE;
			}
			$_config_item[$item] = $config[$item];
		}

		return $_config_item[$item];
	}
}


