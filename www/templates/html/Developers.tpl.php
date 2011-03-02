<style type="text/css">
    blockquote {
         background: none repeat scroll 0pt 0pt rgb(240, 240, 240);
         margin: 15px 0pt;
         padding: 10px;
         width: auto;
    }
    
    blockquote > p {
         clear: none;
         margin: 0pt;
         padding: 0pt;
    }
    
    div.resource {
         border-bottom: #F0F0F0 5px solid;
         margin-bottom: 20px;
    }
    
    #maincontent div.resource > ul {
        padding-left:0;
    }

    div.resource > ul > li {
        list-style:none;
    }

    a.resources 
    {
        float:right;
        font-size:12px
    }
</style>

<script type="text/javascript">jQuery = $ = WDN.jQuery;</script>

<script type="text/javascript" src="templates/html/scripts/jquery.beautyOfCode.js"></script>

<script type="text/javascript">
    $.beautyOfCode.init({
        theme: "RDark",
        brushes: ['Xml', 'JScript', 'CSharp', 'Plain', 'Php'],
        ready: function() {
            $.beautyOfCode.beautifyAll();

        }
    });
</script>

<div class="three_col left">
    
    <?php
        $resource = "UNL_MediaYak_Developers_" . $context->resource;
        $resource = new $resource;
        ?>
        <div class="resource">
        <h1 id="instance" class="sec_main"><?php echo $resource->title; ?> Resource</h1>
        <h3>Details</h3>
        <ul>
            <li>
                <h4 id="instance-uri"><a href="#instance-uri">Resource URI</a></h4>
                <blockquote>
                    <p><?php echo $resource->uri; ?></p>
                </blockquote>
            </li>
            <li>
                <h4 id="instance-properties"><a href="#instance-properties">Resource Properties</a></h4> 
                <table class="zentable neutral">
                <thead><tr><th>Property</th><th>Description</th><th>JSON</th><th>XML</th></tr></thead>
                  <tbody>
                  <?php 
                    foreach ($resource->properties as $property) {
                      echo "<tr>
                                <td>$property[0]</td>
                                <td>$property[1]</td>
                                <td>$property[2]</td>
                                <td>$property[3]</td>
                            </tr>";
                    }
                  ?>
                  </tbody>
                </table>
            </li>
            <li>
                <h4 id="instance-get"><a href="#instance-get">HTTP GET</a></h4>
                <p>Returns a representation of the resource, including the properties above.</p>
            </li>
            <li>
                <h4 id="instance-get-example-1"><a href="#instance-get-example-1">Example</a></h4>
                <ul class="wdn_tabs">
                <?php 
                 foreach ($resource->formats as $format) {
                     echo "<li><a href='#$format'>$format</a></li>";
                 }
                ?>
                </ul>
                <div class="wdn_tabs_content">
                     <?php 
                     foreach ($resource->formats as $format) {
                         ?>
                         <div id="<?php echo $format; ?>">
                            <ul>
                                <li>
                                    Calling this:
                                    <blockquote>
                                        <p>GET <?php echo $resource->exampleURI; ?>?format=<?php echo $format; ?></p>
                                    </blockquote>
                                </li>
                                <li>
                                    Provides this:
                                    <?php 
                                    //Get the output.
                                    if (!$result = file_get_contents($resource->exampleURI."?format=$format")) {
                                        $result = "Error getting file contents.";
                                    }
                                    ?>
                                    <pre class="code">
                                        <code class="javascript"><?php echo htmlentities($result); ?></code>
                                    </pre>
                                </li>
                            </ul>
                        </div>
                         <?php
                     }
                     ?>
                    
                </div>
            </li>
        </ul>
    </div>
    
</div>
<div class="col right">
    <div id='resources' class="zenbox primary" style="width:200px">
        <h3>MediaHub API</h3>
        <p>The following is a list of resources for MediaHub.</p>
        <ul>
            <?php 
            foreach ($context->resources as $resource) {
                echo "<li><a href='?resource=$resource'>$resource</a></li>";
            }
            ?>
        </ul>
    </div>
    <div class="zenbox neutral" style="width:200px">
        <h3>Format Information</h3>
        <p>The following is a list of formats used in MediaHub.</p>
        <ul>
            <li><a href='http://www.json.org/'>JSON (JavaScript Object Notation)</a></li>
            <li><a href='http://en.wikipedia.org/wiki/XML'>XML (Extensible Markup Language)</a></li>
            <li>Partial - The un-themed main content area of the page.</li>
        </ul>
    </div>
</div>