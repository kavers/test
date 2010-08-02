{include file='header.tpl'}

<script type="text/javascript">
    var sWebPluginSkin = "{$sWebPluginSkin}";
    var sWebPluginPath = "{$sWebPluginPath}";
</script>

{if $tpl_content}
  {include file="$tpl_content"}
{/if}
			
{include file='footer.tpl'}