<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $this->config->item('title') ?></title>
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" 
            href="<?php echo $this->config->item('base_url').'/assets/themes/'.$this->config->item('interface_theme') ?>/css/style.css"/>
        <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/cupertino/jquery-ui.css"/>    
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
	    <script type="text/javascript" src="<?php echo $this->config->item('base_url').'/assets/js/common.js' ?>"></script>
	    
	    <!--[if IE]>
	        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <!--[if lt IE 9]>
            <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script><![endif]-->
        <!--[if lt IE 9]>
	    <link rel="stylesheet" type="text/css" media="all" href="css/plugins/extras/ie6.css"/><![endif]-->
    </head>

    <body>
        <?php $this->place_template('header') ?>
        
        <div id="main" class="container">
            <?php $this->place_template('sidebar') ?>
            
            <div id="page">
                <div id="notice_container">
                    <?php if($this->session->weak_data('success')): ?>
                    <div class="success">
                        <?php echo $this->session->weak_data('success') ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->weak_data('error')): ?>
                    <div class="error">
                        <?php echo $this->session->weak_data('error') ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div id="page_container">
                    <?php echo $place_content ?>
                </div>
            </div>
            
            <?php $this->place_template('footer') ?>
            
        </div>
    </body>
    
    <script type="text/javascript">
        $(document).ready(function(){
            $("#items tr").bind("mouseover", function(){
                if($(this).has("ul")){
                    $(this).find("ul").css("display", "block");   
                }
            });
            
            $("#items tr").bind("mouseout", function(){
                if($(this).has("ul")){
                    $(this).find("ul").css("display", "none");   
                }
            });
            
            $(".item-menu").bind("mouseover", function(){
                $(this).css("display", "block");   
            });
            
            $(".success").delay(2000).fadeOut();
        });
    </script>
</html>
