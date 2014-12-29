<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang('Pages')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang('View all pages')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/page')?>"><?php echo lang('Pages')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
        
        <div class="row">
            <div class="col-md-12"> 
                <?php echo anchor('admin/page/edit', '<i class="icon-plus"></i>&nbsp;&nbsp;'.lang('Add a page'), 'class="btn btn-primary"')?>
            </div>
        </div>

          <div class="row">

            <div class="col-md-12">

                <div class="widget wgreen">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Pages')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                  
                    <!-- Nested Sortable -->
                    <div id="orderResult-3">
                    <?php echo get_ol_pages_tree($pages_nested)?>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
</div>
    
    
    
    
    
</section>