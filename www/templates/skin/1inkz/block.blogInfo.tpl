<li class="block orange">
    <div class="title"><a class="link" href="#"><h1>{$aLang.block_blog_info}</h1></a></div><div class="simply_block">
        <div id="block_blog_info"></div>
    </div>
    <div class="block_bottom"></div>
</li>
<li class="block green">
    <div class="title"><a class="link" href="#"><h1>{$aLang.block_blog_info_note}</h1></a></div>
    <div class="simply_block">
        <div>{$aLang.block_blog_info_note_text}</div>
    </div>
    <div class="block_bottom"></div>
</li>

<script>
ajaxBlogInfo($('blog_id').value);
</script>