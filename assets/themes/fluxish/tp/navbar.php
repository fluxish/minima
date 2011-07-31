<ul class="menu floatRight">
    <?php if($this->_load->library('session')->data('identity')){ ?>
    <li><?php echo $this->_load->library('session')->data('identity') ?></li>
    <li><?php echo anchor('logout', 'Logout') ?></li>
    <?php }else{ ?>
    <li><?php echo anchor('login', 'Login') ?></li>
    <?php } ?>
</ul>
