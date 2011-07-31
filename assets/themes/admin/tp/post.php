<div id="post_<?php echo $post->id ?>" class="post">
    <div class="info grid_2 alpha">
        <ul>
            <li class="type"></li>
            <hr/>
            <li class="date"></li>
            <li class="author"></li>
            <li class="category ui-editable"></li>
            <li class="tags ui-editable">
                <ul>
                </ul>
            </li>
        </ul>
    </div>
    <div class="body grid_10 omega">
        <h1 class="title ui-editable"><?php echo $post->title ?></h1>
        <h2 class="sub-title ui-editable"><?php echo @$post->subtitle ?></h2>
        <div id="post_content_<?php echo $post->id ?>" class="content ui-editable">
            <?php echo @$post->content ?>
        </div>
    </div>
</div>

<?php
    if(!$post->read_only() && $this->_load->library('auth')->is_authenticated()){
        $this->jQuery->in_place_edit('#post_'.$post->id.' h1', 'title', $post->id);
        $this->jQuery->in_place_edit('#post_'.$post->id.' h2', 'subtitle', '#');
        $this->jQuery->in_place_editor('#post_content_'.$post->id, 'content', '#');
        $this->jQuery->in_place_edit('#post_'.$post->id.' .tags', 'tags', '#');
    }
?>
