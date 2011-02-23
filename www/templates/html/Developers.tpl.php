<style>
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
         border-left: #F0F0F0 5px solid;
         margin-bottom: 20px;
    }
</style>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
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
    <div class="resource">
        <h1 id="instance" class="sec_main">Media Instance Resource</h1>
        <h3>Details</h3>
        <ul>
            <li>
                <h4 id="instance-uri"><a href="#instance-uri">Resource URI</a></h4>
                <blockquote>
                    <p>http://mediahub.unl.edu/media/{id}</p>
                </blockquote>
            </li>
            <li>
                <h4 id="instance-properties"><a href="#instance-properties">Resource Properties</a></h4>
            </li>
            <li>
                <h4 id="instance-get"><a href="#instance-get">HTTP GET</a></h4>
                <p>Returns a representation of the resource, including the properties above.</p>
            </li>
        </ul>

        <h5 id="instance-get-example-1"><a href="#instance-get-example-1">Example</a></h5>
        
        <ul class="wdn_tabs">
            <li><a href="#instanceTab1">JSON</a></li>
            <li><a href="#instanceTab2">XML</a></li>
            <li><a href="#instanceTab3">Partial</a></li>
        </ul>
        <div class="wdn_tabs_content">
            <div id="instanceTab1">
                <ul>
                    <li>
                        Calling this:
                        <blockquote>
                            <p>GET /media/1874?format=json</p>
                        </blockquote>
                    </li>
                    <li>
                        Provides this:
                        <?php 
                        //Get the output.
                        if (!$result = file_get_contents("http://mediahub.unl.edu/media/1874?format=json")) {
                            $result = "Error getting file contents.";
                        }
                        ?>
                        <pre class="code">
                            <code class="javascript">
                                <?php echo $result; ?>
                            </code>
                        </pre>
                    </li>
                </ul>
            </div>
            <div id="instanceTab2">
                <ul>
                    <li>
                        Calling this:
                        <blockquote>
                            <p>GET /media/1874?format=xml</p>
                        </blockquote>
                    </li>
                    <li>
                        Provides this:
                        <?php 
                        //Get the output.
                        if (!$result = file_get_contents("http://mediahub.unl.edu/media/1874?format=xml")) {
                            $result = "Error getting file contents.";
                        }
                        ?>
                        <pre class="code">
                            <code class="xml">
                                <?php echo $result; ?>
                            </code>
                        </pre>
                    </li>
                </ul>
            </div>
            <div id="instanceTab3">
                <ul>
                    <li>
                        Calling this:
                        <blockquote>
                            <p>GET /media/1874?format=partial</p>
                        </blockquote>
                    </li>
                    <li>
                        Provides this:
                        <?php 
                        //Get the output.
                        if (!$partial = file_get_contents("http://mediahub.unl.edu/media/1874?format=partial")) {
                            $partial = "Error getting file contents.";
                        }
                        ?>
                        <pre class="code">
                            <code class="html">
                                <?php echo htmlentities($partial); ?>
                            </code>
                        </pre>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="resource">
        <h1 id="another" class="sec_main">Another Resource</h1>
        <h3>Details</h3>
        <ul>
            <li>
                <h4 id="instance-uri"><a href="#instance-uri">Resource URI</a></h4>
                <blockquote>
                    <p>http://mediahub.unl.edu/media/{id}</p>
                </blockquote>
            </li>
            <li>
                <h4 id="instance-properties"><a href="#instance-properties">Resource Properties</a></h4>
            </li>
            <li>
                <h4 id="instance-get"><a href="#instance-get">HTTP GET</a></h4>
                <p>Returns a representation of the resource, including the properties above.</p>
            </li>
        </ul>

        <h5 id="instance-get-example-1"><a href="#instance-get-example-1">Example</a></h5>
        
        <ul class="wdn_tabs">
            <li><a href="#tab1">JSON</a></li>
            <li><a href="#tab2">XML</a></li>
            <li><a href="#tab3">Partial</a></li>
        </ul>
        <div class="wdn_tabs_content">
            <div id="tab1">
                <ul>
                    <li>
                        Calling this:
                        <blockquote>
                            <p>GET /media/1874?format=json</p>
                        </blockquote>
                    </li>
                    <li>
                        Provides this:
                        <?php 
                        //Get the output.
                        if (!$result = file_get_contents("http://mediahub.unl.edu/media/1874?format=json")) {
                            $result = "Error getting file contents.";
                        }
                        ?>
                        <pre class="code">
                            <code class="javascript">
                                <?php echo $result; ?>
                            </code>
                        </pre>
                    </li>
                </ul>
            </div>
            <div id="tab2">
                <ul>
                    <li>
                        Calling this:
                        <blockquote>
                            <p>GET /media/1874?format=xml</p>
                        </blockquote>
                    </li>
                    <li>
                        Provides this:
                        <?php 
                        //Get the output.
                        if (!$result = file_get_contents("http://mediahub.unl.edu/media/1874?format=xml")) {
                            $result = "Error getting file contents.";
                        }
                        ?>
                        <pre class="code">
                            <code class="xml">
                                <?php echo $result; ?>
                            </code>
                        </pre>
                    </li>
                </ul>
            </div>
            <div id="tab3">
                <ul>
                    <li>
                        Calling this:
                        <blockquote>
                            <p>GET /media/1874?format=partial</p>
                        </blockquote>
                    </li>
                    <li>
                        Provides this:
                        <?php 
                        //Get the output.
                        if (!$partial = file_get_contents("http://mediahub.unl.edu/media/1874?format=partial")) {
                            $partial = "Error getting file contents.";
                        }
                        ?>
                        <pre class="code">
                            <code class="html">
                                <?php echo htmlentities($partial); ?>
                            </code>
                        </pre>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="col right">
    <div class="zenbox primary" style="width:200px">
        <h3>MediaHub API</h3>
        <p>The following is a list of resources for MediaHub.</p>
        <ul>
            <li><a href="#instance">Media Instance</a></li>
            <li><a href="#another">Another Resource</a></li>
            <li>Item 3</li>
        </ul>
    </div>
</div>