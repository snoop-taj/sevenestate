<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang('Estate')?>
          <!-- page meta -->
          <span class="page-meta"><?php echo empty($estate->id) ? lang('Add a estate') : lang('Edit estate').' "' . $estate->id.'"'?></span>
        </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/estate')?>"><?php echo lang('Estates')?></a>
    </div>
    
    <div class="clearfix"></div>

</div>

<div class="matter">
        <div class="container">
        
            <div class="row">
                <div class="col-md-12" style="text-align:right;"> 
                <?php if(!empty($estate->id) && file_exists(APPPATH.'controllers/admin/reviews.php') && check_acl('reviews')): ?>
                    <?php echo anchor('admin/reviews/index/'.$estate->id, '<i class="icon-star"></i>&nbsp;&nbsp;'.lang('Reviews'), 'class="btn btn-primary"')?>
                <?php endif; ?>
                </div>
            </div>

          <div class="row">

            <div class="col-md-8">
              <div class="widget wlightblue">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Estate data')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
                    <?php echo validation_errors()?>
                    <?php if($this->session->flashdata('message')):?>
                    <?php echo $this->session->flashdata('message')?>
                    <?php endif;?>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open(NULL, array('class' => 'form-horizontal form-estate', 'role'=>'form'))?>                              
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Address')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_input('address', set_value('address', $estate->address), 'class="form-control" id="inputAddress" placeholder="'.lang('Address').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Gps')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_input('gps', set_value('gps', $estate->gps), 'class="form-control" id="inputGps" placeholder="'.lang('Gps').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('DateTime')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_input('date', set_value('date', $estate->date), 'class="form-control" id="inputDate" placeholder="'.lang('DateTime').'"')?>
                                  </div>
                                </div>
                                
                                <?php if($this->session->userdata('type') == 'ADMIN'):?>
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Agent')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_dropdown('agent', $available_agent, set_value('agent', $estate->agent), 'class="form-control" id="inputAgent" placeholder="'.lang('Agent').'"')?>
                                  </div>
                                </div>
                                <?php endif;?>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Featured')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_checkbox('is_featured', '1', set_value('is_featured', $estate->is_featured), 'id="inputFeatured"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Activated')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_checkbox('is_activated', '1', set_value('is_activated', $estate->is_activated), 'id="inputActivated"')?>
                                  </div>
                                </div>
                                
                                <hr />
                                <h5><?php echo lang('Translation data')?></h5>
                                <div style="margin-bottom: 0px;" class="tabbable">
                                  <ul class="nav nav-tabs">
                                    <?php $i=0;foreach($this->option_m->languages as $key=>$val):$i++;?>
                                    <li class="<?php echo $i==1?'active':''?> lang"><a data-toggle="tab" href="#<?php echo $key?>"><?php echo $val?></a></li>
                                    <?php endforeach;?>
                                    <li class="pull-right"><a href="#" id="copy-lang" class="btn btn-default" type="button"><?php echo lang_check('Copy to other languages')?></a></li>
                                  </ul>
                                  <div style="padding-top: 9px; border-bottom: 1px solid #ddd;" class="tab-content">
                                    <?php $i=0;foreach($this->option_m->languages as $key=>$val):$i++;?>
                                    <div id="<?php echo $key?>" class="tab-pane <?php echo $i==1?'active':''?>">
                                        <?php foreach($options as $key_option=>$val_option):?>
                                        <?php if($val_option->type == 'CATEGORY'):?>
                                        <hr />
                                        <h5><?php echo $val_option->option?></h5>
                                        <hr />
                                        <?php elseif($val_option->type == 'INPUTBOX'):?>
                                            <div class="form-group">
                                              <label class="col-lg-3 control-label"><?php echo $val_option->option?></label>
                                              <div class="col-lg-9">
                                                <?php echo form_input('option'.$val_option->id.'_'.$key, set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:''), 'class="form-control" id="inputOption_'.$key.'_'.$val_option->id.'" placeholder="'.$val_option->option.'"')?>
                                              </div>
                                            </div>
                                        <?php elseif($val_option->type == 'DROPDOWN'):?>
                                            <div class="form-group">
                                              <label class="col-lg-3 control-label"><?php echo $val_option->option?></label>
                                              <div class="col-lg-9">
                                                <?php
                                                if(isset($options_lang[$key][$key_option]))
                                                    $drop_options = array_combine(explode(',',check_combine_set(isset($options_lang[$key])?$options_lang[$key][$key_option]->values:'', $val_option->values, '')),explode(',',check_combine_set($val_option->values, isset($options_lang[$key])?$options_lang[$key][$key_option]->values:'', '')));
                                                else
                                                    $drop_options = array();

                                                $drop_selected = set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:'');
                                                
                                                echo form_dropdown('option'.$val_option->id.'_'.$key, $drop_options, $drop_selected, 'class="form-control" id="inputOption_'.$key.'_'.$val_option->id.'" placeholder="'.$val_option->option.'"')
                                                
                                                ?>
                                                <?php //=form_dropdown('option'.$val_option->id.'_'.$key, explode(',', $options_lang[$key][$key_option]->values), set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:''), 'class="form-control" id="inputOption_'.$val_option->id.'" placeholder="'.$val_option->option.'"')?>
                                              </div>
                                            </div>
                                        <?php elseif($val_option->type == 'TEXTAREA'):?>
                                            <div class="form-group">
                                              <label class="col-lg-3 control-label"><?php echo $val_option->option?></label>
                                              <div class="col-lg-9">
                                                <?php echo form_textarea('option'.$val_option->id.'_'.$key, set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:''), 'class="cleditor form-control" id="inputOption_'.$key.'_'.$val_option->id.'" placeholder="'.$val_option->option.'"')?>
                                              </div>
                                            </div>
                                        <?php elseif($val_option->type == 'CHECKBOX'):?>
                                            <div class="form-group">
                                              <label class="col-lg-3 control-label"><?php echo $val_option->option?></label>
                                              <div class="col-lg-9">
                                                <?php echo form_checkbox('option'.$val_option->id.'_'.$key, 'true', set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:''), 'id="inputOption_'.$key.'_'.$val_option->id.'"')?>
                                                <?php
                                                    if(file_exists(FCPATH.'templates/'.$settings['template'].'/assets/img/icons/option_id/'.$val_option->id.'.png'))
                                                    {
                                                        echo '<img class="results-icon" src="'.base_url('templates/'.$settings['template'].'/assets/img/icons/option_id/'.$val_option->id.'.png').'" alt="'.$val_option->option.'"/>';
                                                    }
                                                ?>
                                              </div>
                                            </div>
                                        <?php endif;?>
                                        <?php endforeach;?>
                                    </div>
                                    <?php endforeach;?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <div class="col-lg-offset-3 col-lg-9">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/estate')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
                                  </div>
                                </div>
                       <?php echo form_close()?>
                  </div>
                </div>
                  <div class="widget-foot">
                    <!-- Footer goes here -->
                  </div>
              </div>  

            </div>
            
            
            <div class="col-md-4">
              <div class="widget wblue">
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Location')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="gmap" id="mapsAddress">

                  </div>
                </div>


              </div> 
            </div>
            

            
            
            
            
            
            
            
            
            

          </div>
          
          <div class="row">
        <div class="col-md-12">

              <div class="widget worange">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Files')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">

<?php if(!isset($estate->id)):?>
<span class="label label-danger"><?php echo lang('After saving, you can add files and images');?></span>
<?php else:?>
<div id="page-files-<?php echo $estate->id?>" rel="estate_m">
    <!-- The file upload form used as target for the file upload widget -->
    <form class="fileupload" action="<?php echo site_url('files/upload_estate/'.$estate->id);?>" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="<?php echo site_url('admin/estate/edit/'.$estate->id);?>"></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="fileupload-buttonbar">
            <div class="span7 col-md-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="icon-plus icon-white"></i>
                    <span><?php echo lang('add_files...')?></span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="icon-ban-circle icon-white"></i>
                    <span><?php echo lang('cancel_upload')?></span>
                </button>
                <button type="button" class="btn btn-danger delete">
                    <i class="icon-trash icon-white"></i>
                    <span><?php echo lang('delete_selected')?></span>
                </button>
                <input type="checkbox" class="toggle" />
            </div>
            <!-- The global progress information -->
            <div class="span5 col-md-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="bar" style="width:0%;"></div>
                </div>
                <!-- The extended global progress information -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The loading indicator is shown during file processing -->
        <div class="fileupload-loading"></div>
        <br />
        <!-- The table listing the files available for upload/download -->
        <!--<table role="presentation" class="table table-striped">
        <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery">-->

          <div role="presentation" class="fieldset-content">
            <ul class="files files-list" data-toggle="modal-gallery" data-target="#modal-gallery">      
<?php if(isset($files[$estate->repository_id]))foreach($files[$estate->repository_id] as $file ):?>
            <li class="img-rounded template-download fade in">
                <div class="preview">
                    <img class="img-rounded" alt="<?php echo $file->filename?>" data-src="<?php echo $file->thumbnail_url?>" src="<?php echo $file->thumbnail_url?>">
                </div>
                <div class="filename">
                    <code><?php echo character_hard_limiter($file->filename, 20)?></code>
                </div>
                <div class="options-container">
                    <?php if($file->zoom_enabled):?>
                    <a data-gallery="gallery" href="<?php echo $file->download_url?>" title="<?php echo $file->filename?>" download="<?php echo $file->filename?>" class="zoom-button btn btn-xs btn-success"><i class="icon-search icon-white"></i></a>                  
                    <?php else:?>
                    <a target="_blank" href="<?php echo $file->download_url?>" title="<?php echo $file->filename?>" download="<?php echo $file->filename?>" class="btn btn-xs btn-success"><i class="icon-search icon-white"></i></a>
                    <?php endif;?>
                    <span class="delete">
                        <button class="btn btn-xs btn-danger" data-type="POST" data-url="<?php echo $file->delete_url?>"><i class="icon-trash icon-white"></i></button>
                        <input type="checkbox" value="1" name="delete">
                    </span>
                </div>
            </li>
<?php endforeach;?>
            </ul>
            <br style="clear:both;"/>
          </div>
    </form>

</div>
<?php endif;?>

                  </div>
                </div>
                  <div class="widget-foot">
                    <!-- Footer goes here -->
                  </div>
              </div>  
              
            </div>
          </div>
          
          
          
          
          
          
          
          
          
          
          
          
          
          

        </div>
		  </div>