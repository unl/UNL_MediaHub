<script type="text/javascript">
<?php
if (false !== $context->info) {
    print "parent.upload.updateInfo(".$context->info['bytes_uploaded'].",".$context->info['bytes_total'].",".$context->info['est_sec'].")";
} else {
    print "parent.upload.updateInfo()";
}
?>
</script>
<pre>
<?php
print "Date : " . date('c', time()) . "\n";
print "ID   : ". $context->options['id'] ."\n";
print var_dump($context->info): . "\n";
var_dump($info);
?>
</pre>