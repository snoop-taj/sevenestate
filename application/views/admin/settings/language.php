
    <!-- Page heading -->
    <div class="page-head">
    <!-- Page heading -->
        <h2 class="pull-left"><?php echo lang('Settings')?>
		  <!-- page meta -->
		  <span class="page-meta"><?php echo lang('System settings')?></span>
		</h2>


		<!-- Breadcrumb -->
		<div class="bread-crumb pull-right">
          <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
          <!-- Divider -->
          <span class="divider">/</span> 
          <a class="bread" href="<?php echo site_url('admin/settings')?>"><?php echo lang('Settings')?></a>
          <span class="divider">/</span> 
          <a class="bread-current" href="<?php echo site_url('admin/settings/language')?>"><?php echo lang('Language')?></a>
		</div>

		<div class="clearfix"></div>

    </div>
    <!-- Page heading ends -->



    <!-- Matter -->

    <div class="matter-settings">
    
    <div style="margin-bottom: 8px;" class="tabbable">
      <ul class="nav nav-tabs settings-tabs">
        <li><a href="<?php echo site_url('admin/settings/contact')?>"><?php echo lang('Company contact')?></a></li>
        <li class="active"><a href="<?php echo site_url('admin/settings/language')?>"><?php echo lang('Languages')?></a></li>
        <li><a href="<?php echo site_url('admin/settings/template')?>"><?php echo lang('Template')?></a></li>
        <li><a href="<?php echo site_url('admin/settings/system')?>"><?php echo lang('System settings')?></a></li>
      </ul>
    </div>
    
    <div class="container">
        <div class="row">
            <div class="col-md-12"> 
                <?php echo anchor('admin/settings/language_edit', '<i class="icon-plus"></i>&nbsp;&nbsp;'.lang_check('Add language'), 'class="btn btn-primary"')?>
            </div>
        </div>
          <div class="row">

            <div class="col-md-12">


              <div class="widget wlightblue">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Website language')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <table class="table table-bordered ">
                      <thead>
                        <tr>
                        	<th><?php echo lang('Language');?></th>
                            <th><?php echo lang('Code');?></th>
                            <th><?php echo lang('Default');?></th>
                            <th><?php echo lang_check('Translate files');?></th>
                        	<th class="control"><?php echo lang('Edit');?></th>
                        	<th class="control"><?php echo lang('Delete');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(count($languages)): foreach($languages as $language):?>
                                    <tr>
                                    	<td>
                                            <?php echo anchor('admin/settings/language_edit/'.$language->id, lang_check($language->language))?>
                                            <?php echo ($language->is_locked == 1)?'&nbsp;&nbsp;<i class="icon-lock"></i>':''?>
                                            <?php echo ($language->is_frontend == 0)?'&nbsp;&nbsp;<i class="icon-eye-close"></i>':''?>
                                        </td>
                                        <td><?php echo $language->code?></td>
                                        <td><?php echo ($language->default == 1)?'<i class="icon-ok"></i>':''?></td>
                                        <td style="width: 100px;text-align:center;"><a href="<?php echo site_url('admin/settings/language_files/'.$language->id)?>" class="btn btn-info"><i class="icon-list"></i>&nbsp;<?php echo lang_check('Translate files'); ?></a></td>
                                    	<td><?php echo btn_edit('admin/settings/language_edit/'.$language->id)?></td>
                                    	<td><?php echo btn_delete('admin/settings/language_delete/'.$language->id)?></td>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="6"><?php echo lang('We could not find any languages')?></td>
                                    </tr>
                        <?php endif;?>                   
                      </tbody>
                    </table>

                  </div>
              </div>  

            </div>
          </div>
    </div>
    </div>

	<!-- Matter ends -->

   <!-- Mainbar ends -->	    	
   <div class="clearfix"></div>