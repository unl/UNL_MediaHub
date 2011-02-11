<h2>My Subscriptions</h2>
<?php
if (count($context->items)) : ?>
    <table>
    <thead>
        <tr>
            <th>Type</th>
            <th>Value</th>
            <th>View Matches</th>
            <th>Edit/Remove</th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($context->items as $subscription) : ?>
        <tr>
            <td><?php echo $subscription->filter_class; ?></td>
            <td>"<?php echo $subscription->filter_option; ?>"</td>
            <td><a href="<?php echo $subscription->getResultURL(); ?>">View results</a></td>
            <td>
                <!-- Link to LinkSubscription view &id=$subscription->id -->
                <!-- Remove form! -->
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
<?php endif; ?>