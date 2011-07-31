<?php foreach($posts as $post): ?>
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
        <h1 class="title ui-editable"><a href="<?php echo 'posts/'.$post->id ?>"><?php echo $post->title ?></a></h1>
        <h2 class="subtitle ui-editable"><?php echo @$post->subtitle ?></h2>
        <div class="content">
            <?php echo @$post->content ?>
        </div>
    </div>
</div>
<?php endforeach; ?>
