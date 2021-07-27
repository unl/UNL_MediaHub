
<?php
$page->addHeadLink('https://cdn.jsdelivr.net/highlight.js/9.2.0/styles/solarized-dark.min.css', 'stylesheet');
?>

<div class="developer-docs dcf-grid dcf-col-gap-vw dcf-row-gap-6 dcf-pt-8 dcf-pb-8">
    <div class="dcf-col-100% dcf-col-75%-start@md">
        <?php
        $resource = "UNL_MediaHub_Developers_" . $context->resource;
        $resource = new $resource;
        ?>
        <div class="resource">
            <h1><?php echo $resource->title; ?> Resource</h1>
            
            <h2>Resource URI</h2>
            <code><?php echo $resource->uri; ?></code>
                
            <?php if (isset($resource->note)):?>
                <h2>Note</h2>
                <p>
                    <?php echo $resource->note; ?>
                </p>
            <?php endif;?>
            
            <h2>Resource Properties</h2>
      
            <table class="zentable neutral">
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Description</th>
                        <th>JSON</th>
                        <th>XML</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resource->properties as $property): ?>
                        <tr>
                            <td><?php echo $property[0] ?></td>
                            <td><?php echo $property[1] ?></td>
                            <td><?php echo $property[2] ?></td>
                            <td><?php echo $property[3] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="dcf-tabs dcf-tabs-responsive dcf-mt-6">
                <h2>Examples</h2>
                <ul>
                    <?php
                    foreach ($resource->formats as $format) {
                        echo "<li><a href='#$format'>$format</a></li>";
                    }
                    ?>
                </ul>
                <?php foreach ($resource->formats as $format): ?>
                    <div id="<?php echo $format; ?>">
                        <h3>Request</h3>

                        <code>GET <?php echo $resource->exampleURI; ?>?format=<?php echo $format; ?></code>

                        <h3 class="dcf-mt-4">Response</h3>
                        <?php
                        //Get the output.
                        if (!$result = file_get_contents($resource->exampleURI."?format=$format")) {
                            $result = "Error getting file contents.";
                        }

                        switch($format) {
                            case "json":
                                $code = 'javascript';
                                //Pretty print
                                $result = json_decode($result);
                                $result = json_encode($result, JSON_PRETTY_PRINT);
                                break;
                            case "xml":
                                $code = "xml";
                                break;
                            default:
                                $code = "html";
                        }
                        ?>
                        <pre class="code">
                            <code class="<?php echo $code; ?>" tabindex="0"><?php echo htmlentities($result); ?></code>
                        </pre>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="dcf-col-100% dcf-col-25%-end@md">
        <nav id='resources'>
            <h2>MediaHub API</h2>
            <p>The following is a list of resources for MediaHub.</p>
            <ul>
                <?php
                foreach ($context->resources as $resource) {
                    echo "<li><a href='?resource=$resource'>$resource</a></li>";
                }
                ?>
            </ul>
        </nav>
        <div class="zenbox neutral">
            <h2>Format Information</h2>
            <p>The following is a list of formats used in MediaHub.</p>
            <ul>
                <li><a href='http://www.json.org/'>JSON (JavaScript Object Notation)</a></li>
                <li><a href='http://en.wikipedia.org/wiki/XML'>XML (Extensible Markup Language)</a></li>
                <li>Partial - The un-themed main content area of the page.</li>
            </ul>
        </div>
    </div>
</div>

<?php
$page->addScriptDeclaration("
    if (typeof WDN === 'undefined') {
        require(['dcf/dcf-tabs'], function(DCFTabsModule) {
            var tabs = document.querySelectorAll('.dcf-tabs')
            var unlTabs = new DCFTabsModule.DCFTabs(tabs);
            unlTabs.initialize();
        });
    } else {
        WDN.initializePlugin('tabs');
    }

    require(['jquery', 'https://cdn.jsdelivr.net/highlight.js/9.2.0/highlight.min.js'], function ($, hljs) {
        $('.resource pre.code code').each(function () {
            hljs.highlightBlock(this);
        });
    });
");
?>
