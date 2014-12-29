<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang('Option')?>
          <!-- page meta -->
          <span class="page-meta"><?php echo empty($option->id) ? lang('Add a option') : lang('Edit option').' #' . $option->id.''?></span>
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

            <div class="col-md-12">


              <div class="widget wlightblue">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Option data')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
                    <?php echo validation_errors()?>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                              


                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Type')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_dropdown('type', $this->option_m->option_types, $this->input->post('type') ? $this->input->post('type') : $option->type, 'class="form-control"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Parent')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_dropdown('parent_id', $options_no_parents, $this->input->post('parent_id') ? $this->input->post('parent_id') : $option->parent_id, 'class="form-control"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Visible in table')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_checkbox('visible', '1', $this->input->post('visible') ? $this->input->post('visible') : $option->visible)?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Locked')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_checkbox('is_locked', '1', $this->input->post('is_locked') ? $this->input->post('is_locked') : $option->is_locked)?>
                                    <span class="label label-warning"><?php echo lang_check('After delete, template changes needed')?></span>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Visible in frontend')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_checkbox('is_frontend', '1', $this->input->post('is_frontend') ? $this->input->post('is_frontend') : $option->is_frontend)?>
                                  </div>
                                </div>
                                
                                <hr />
                                <h5><?php echo lang('Translation data')?></h5>
                                <div style="margin-bottom: 18px;" class="tabbable">
                                  <ul class="nav nav-tabs">
                                    <?php $i=0;foreach($this->option_m->languages as $key=>$val):$i++;?>
                                    <li class="<?php echo $i==1?'active':''?>"><a data-toggle="tab" href="#<?php echo $key?>"><?php echo $val?></a></li>
                                    <?php endforeach;?>
                                  </ul>
                                  <div style="padding-top: 9px; border-bottom: 1px solid #ddd;" class="tab-content">
                                    <?php $i=0;foreach($this->option_m->languages as $key=>$val):$i++;?>
                                    <div id="<?php echo $key?>" class="tab-pane <?php echo $i==1?'active':''?>">
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label"><?php echo lang('Option name')?></label>
                                          <div class="col-lg-9">
                                            <?php echo form_input('option_'.$key, set_value('option_'.$key, $option->{"option_$key"}), 'class="form-control" id="inputOption_'.$key.'" placeholder="'.lang('Option name').'"')?>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label"><?php echo lang_check('Values (Without spaces)')?></label>
                                          <div class="col-lg-9">
                                            <?php echo form_input('values_'.$key, set_value('values_'.$key, $option->{"values_$key"}), 'class="form-control" id="inputValues_'.$key.'" placeholder="'.lang('Values').'"')?>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label"><?php echo lang_check('Prefix')?></label>
                                          <div class="col-lg-9">
                                            <?php echo form_input('prefix_'.$key, set_value('prefix_'.$key, $option->{"prefix_$key"}), 'class="form-control" id="inputPrefix_'.$key.'" placeholder="'.lang_check('Prefix').'"')?>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label"><?php echo lang('Suffix')?></label>
                                          <div class="col-lg-9">
                                            <?php echo form_input('suffix_'.$key, set_value('suffix_'.$key, $option->{"suffix_$key"}), 'class="form-control" id="inputSuffix_'.$key.'" placeholder="'.lang('Suffix').'"')?>
                                          </div>
                                        </div>
                                    </div>
                                    <?php endforeach;?>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <div class="col-lg-offset-3 col-lg-9">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/estate/options')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
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

          </div>
          

          
          

        </div>
		  </div>