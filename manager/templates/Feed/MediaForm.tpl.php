<h1>Add media</h1>
<?php
// Display the form to edit a feed.

require_once 'HTML/QuickForm.php';
require_once 'HTML/QuickForm/Renderer/Tableless.php';
$form = new HTML_QuickForm('feed', null, '?view=feed');
$form->addElement('header', 'feed_header', 'Existing Media');
$form->addElement('text', 'url', 'URL', $this->url);
$form->addElement('text', 'title', 'Title', $this->title);
$form->addElement('textarea', 'description', 'Description', $this->description);
$form->addElement('submit', 'submit', 'Save');

$form->addElement('header', 'feed_header', 'Upload new media');
$form->addElement('file', 'file_upload', 'File');
$form->addElement('radio', 'upload_target', 'What would you like to do with this file?', 'Encode it');
$form->addElement('radio', 'upload_target', '', 'Go Live');
$form->addElement('submit', 'submit', 'Save');
$renderer = new HTML_QuickForm_Renderer_Tableless();
$form->accept($renderer);
echo $renderer->toHtml();
?>