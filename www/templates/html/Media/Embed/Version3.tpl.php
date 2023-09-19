<?php
$padding = array(
  '1x1' => '100%',
  '3x4' => '133.33%',
  '4x3' => '75%',
  '9x16' => '177.77%',
  '16x9' => '56.25%',
);

$prefix = 'Video Player: ';
$divStyle = 'padding-top: ' . $padding['16x9'] . ';';
if (!$context->media->isVideo()) {
  $prefix = 'Audio Player: ';
  $divStyle = 'height: 5.62em; max-width: 56.12rem;';
  $aspect_ratio = '16x9';
} else {
  $aspect_ratio_raw = $context->media->getAspectRatio();
  $aspect_ratio = '16x9';
  switch ($aspect_ratio_raw) {
    case UNL_MediaHub_Media::ASPECT_1x1:
      $aspect_ratio = '1x1';
      break;
    case UNL_MediaHub_Media::ASPECT_3x4:
      $aspect_ratio = '3x4';
      break;
    case UNL_MediaHub_Media::ASPECT_4x3:
      $aspect_ratio = '4x3';
      break;
    case UNL_MediaHub_Media::ASPECT_9x16:
      $aspect_ratio = '9x16';
      break;
    default:
      $aspect_ratio = '16x9';
  }
  $divStyle = 'padding-top: ' . $padding[$aspect_ratio] . ';';
}
?>

<?php if($context->media->isVideo() && $aspect_ratio !== '16x9'): ?>
<!-- To force a 16x9 aspect ratio use 'padding-top: 56.25%;' instead of '<?php echo $divStyle; ?>' -->
<?php endif; ?>

<div style="<?php echo $divStyle ?> overflow: hidden; position:relative; -webkit-box-flex: 1; flex-grow: 1;">
  <iframe
    style="bottom: 0; left: 0; position: absolute; right: 0; top: 0; border: 0; height: 100%; width: 100%;"
    src="<?php echo $controller->getURL($context->media)?>?format=iframe&autoplay=0"
    title="<?php echo $prefix ?> <?php echo UNL_MediaHub::escape($context->media->title) ?>"
    allowfullscreen
  ></iframe>
</div>