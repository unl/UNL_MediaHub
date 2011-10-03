<script type="text/javascript">
<?php
if (false !== $context->info) {
    print "parent.upload.updateInfo(".$context->info['current'].",".$context->info['total'].",".$context->info['start_time'].")";
} else {
    print "parent.upload.updateInfo()";
}
?>
</script>
<pre>
<?php
print "Date : " . date('c', time()) . "\n";
print "ID   : ". $context->options['id'] ."\n";
print var_dump($context->info) . "\n";
?>
</pre>