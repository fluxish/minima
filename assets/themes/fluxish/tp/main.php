<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	    <title><?php echo $this->config->item('title') ?></title>
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	    <link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('base_url').'/assets/themes/'.$this->config->item('interface_theme') ?>/css/style.css"/>
	    <?php echo $this->JQuery->output_library(); ?>
    </head>

    <body>
	    <div id="body-container">
	        <?php $this->place_template('header') ?>
	        
			<div id="page" class="container container_16">			    
			    
			    <div id="top_bar" class="grid_16"></div>
                
                <div id="main" class="grid_12">
                    <?php echo $place_content ?>
                </div>
                
                <?php $this->place_template('sidebar') ?>
                
                <div id="bottom_bar" class="grid_16"></div>
            </div>
            
            <?php $this->place_template('footer') ?>
        </div>
        <?php echo $this->jQuery->output_scripts() ?>
    </body>
</html>
