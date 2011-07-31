<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $this->config->item('title') ?></title>
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" 
            href="<?php echo $this->config->item('base_url').'/assets/themes/'.$this->config->item('interface_theme') ?>/css/style.css"/>
	    <?php echo $this->JQuery->output_libraries(); ?>
	    
	    <!--[if IE]>
	        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <!--[if lt IE 9]>
            <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script><![endif]-->
        <!--[if lt IE 9]>
	    <link rel="stylesheet" type="text/css" media="all" href="css/plugins/extras/ie6.css"/><![endif]-->
    </head>
    
    <body>
        <?php $this->place_template('header') ?>
        
        <div id="page" class="container container_12">
            <div id="middle">
                <div id="column_1" class="grid_9">            
                    <?php if($this->session->weak_data('notice')): ?>
                    <div class="notice">
                        <?php echo $this->session->weak_data('notice') ?>
                    </div>
                    <?php endif; ?>
                    <?php echo $place_content ?>
                </div>
                <div id="column_2" class="grid_3">
                </div>
                <div class="clear"/>
            </div>
        </div>
        
        <?php //$this->place_template('footer') ?>
        
        <?php echo $this->jQuery->output_scripts() ?>
    </body>
</html>
