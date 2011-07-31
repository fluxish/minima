<div id="head">
	<div id="header" class="container_16">
		<h1 class="title grid_12"><a href="<?php echo $this->config->item('base_url') ?>"><?php echo $this->config->item('title') ?></a></h1>
		
		<!-- rss-button -->
		<a id="rss_button" class="rss-button" title="{$theme_lang.subscribeMyFeeds}"
		    onclick="javascript:showLayer('rss-menu')">
		    <div id="item-rss" class="rss-item floatRight"></div>
		</a>
		
		<!-- delicious-button -->
		<a id="delicious_button" class="delicious-button" title="{$theme_lang.addToDelicious}"
		    href="http://del.icio.us/post?v=4&noui&jump=close&url={$fpconfig.general.www}&title={$flatpress.title}&notes={$flatpress.subtitle}">
		    <div id="item-delicious" class="rss-item floatRight"></div>
		</a>
		<h2 class="subtitle grid_12"><?php echo $this->config->item('subtitle') ?></h2>
		<div id="rss-menu" style="display: none;">
            <h2>{$theme_lang.subscribeMyFeeds}</h2>
            <ul>

                <li class="feed-xml">
                <a href="{php}echo $conf[general][www]{/php}?x=feed:rss2">{$theme_lang.subscribeToRSSFeed}</a>
                </li>

                <li class="feed-yahoo">
                <a href="http://add.my.yahoo.com/rss?url={php}echo $conf[general][www]{/php}?x=feed:rss2">{$theme_lang.addToMyYahoo}</a>
                </li>

                <li class="feed-newsgator">
                <a href="http://www.newsgator.com/ngs/subscriber/subext.aspx?url={php}echo $conf[general][www]{/php}?x=feed:rss2">{$theme_lang.subscribeInNewsGator}</a>
                </li>

                <li class="feed-bloglines">
                <a href="http://www.bloglines.com/sub/ {php}echo $conf[general][www]{/php}?x=feed:rss2">{$theme_lang.subscribeWithBloglines}</a>
                </li>

                <li class="feed-netvibes">
                <a href="http://www.netvibes.com/subscribe.php?url={php}echo $conf[general][www]{/php}?x=feed:rss2">{$theme_lang.addToNetvibes}</a>
                </li>

                <li class="feed-google">
                <a href="http://fusion.google.com/add?feedurl={php}echo $conf[general][www]{/php}?x=feed:rss2">{$theme_lang.addToGoogle}</a>
                </li>

            </ul>
        </div>
	</div>
	<div id="sub_header" class="container_16">
	    <div id="navbar"><?php echo $this->place_template('navbar') ?></div>
	</div>
</div>
