            <header id="header">
                <div id="top_header" class="container container_12">
                    <figure id="logo"></figure>
                </div>
                <div id="bottom_header" class="container container_12">
                    <nav id="nav_bar" class="menu-container">
                        <ul class="menu mini-menu">
                            <li style="float: left; margin-left: 0; margin-right: 10px;">
                                <?php echo anchor('index', 'Home', array('class' => 'home item')) ?>
                            </li>

                            <?php if($this->_load->library('session')->data('identity')): ?>
                            <li class="align-right"><span class="user item"><?php echo $this->_load->library('session')->data('identity') ?></span></li>
                            <li class="align-right"><?php echo anchor('logout', 'Logout', array('class' => 'logout item')) ?></li>

                            <?php else: ?>
                            <li class="align-right"><?php echo anchor('login', 'Login', array('class' => 'login item')) ?></li>

                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </header>
