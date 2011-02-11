<h2>Add New Subscription</h2>
<form action="<?php echo $context->action; ?>">
<select name="filter_class">
<?php
foreach ($context->filters as $filter) {
    echo '<option value="'.$filter.'">'.$filter::getDescription().'</option>';
}
?>
</select>
Value: <input type="text" name="filter_option" />

<input type="submit" value="Add Subscription" />
</form>