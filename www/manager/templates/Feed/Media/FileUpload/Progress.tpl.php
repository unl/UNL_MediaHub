<?php
// Ensure output is not cached
header('Expires: Tue, 08 Oct 1991 00:00:00 GMT');
header('Cache-Control: no-cache, must-revalidate');
?>
<script type="text/javascript">
<?php
if (false !== $context->info) {
    $percentComplete = $context->getPercentComplete();

    if ($percentComplete < 100) {
        echo "parent.upload.updateInfo(".$context->info['current'].", ".$context->info['total'].", ".$context->info['start_time'].", ".$context->getPercentComplete().")";
    }
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