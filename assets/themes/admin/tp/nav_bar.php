                   
                    <div id="nav_bar" class="menu mini-menu h_basic_menu">
                        <ul>
                            <?php if($this->input->get('controller') == 'home'):?>
                            <li class="active">
                            <?php else: ?>
                            <li>
                            <?php endif; echo anchor(url(array('controller'=>'home','action'=>'index')),
                                lang('process_home_title'), lang('process_home_title'), array('class' => 'item icon-text home-mini')) ?></li>
            
                            
                            <div class="group right">
                            
                                <?php if($this->_load->library('session')->data('identity')): ?>
                                <li><span class="user item"><?php $user = $this->_load->library('session')->data('identity'); echo $user['username'] ?></span></li>
                                <li><?php echo anchor(url(array('controller'=>'home','action'=>'logout')), 'Logout', 'Logout', array('class' => 'item icon-text logout-mini')) ?></li>
                                <?php else: ?>
                                <li><?php echo anchor(url(array('controller'=>'home','action'=>'login')), 'Login', 'Login', array('class' => 'item icon-text login-mini')) ?></li>
                                <?php endif; ?>
                            </div>
                        </ul>
                    </div>
