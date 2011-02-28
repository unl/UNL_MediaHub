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
    a.resources 
    {
        float:right;
        font-size:12px
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
        <h1 id="instance" class="sec_main">Media Instance Resource <a href='#resources' class='resources'>Go to the list of resources.</a></h1>
        <h3>Details</h3>
        <ul>
            <li>
                <h4 id="instance-uri"><a href="#instance-uri">Resource URI</a></h4>
                <blockquote>
                    <p><?php echo UNL_MediaYak_Controller::$url; ?>media/{id}</p>
                </blockquote>
            </li>
            <li>
                <h4 id="instance-properties"><a href="#instance-properties">Resource Properties</a></h4> 
                <table class="zentable neutral">
                <thead><tr><th>Property</th><th>Description</th><th>JSON</th><th>XML</th></tr></thead>
                  <tbody>
                   <tr>
                    <td>id</td>
                    <td>int: A numerical id for the media. </td>
                    <td>yes</td>
                    <td>no</td>
                   </tr>
                   <tr>
                    <td>url</td>
                    <td>URL: A url to the actual media file.</td>
                    <td>yes</td>
                    <td>no</td>
                   </tr>
                   <tr>
                    <td>link</td>
                    <td>URL: The url to the media file on Media Hub.</td>
                    <td>yes</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>title</td>
                    <td>Text: The title of the media.</td>
                    <td>yes</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>description</td>
                    <td>Text: The descripton of the media.</td>
                    <td>yes</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>length</td>
                    <td>Int: The size of the media file in bytes.</td>
                    <td>yes</td>
                    <td>no</td>
                   </tr>
                   <tr>
                    <td>image</td>
                    <td>URL: A url to the image of the media.</td>
                    <td>yes</td>
                    <td>no</td>
                   </tr>
                   <tr>
                    <td>type</td>
                    <td>Text: The type of media.</td>
                    <td>yes</td>
                    <td>no</td>
                   </tr>
                   <tr>
                    <td>author</td>
                    <td>Text: The author of the media.</td>
                    <td>yes</td>
                    <td>no</td>
                   </tr>
                   <tr>
                    <td>pubDate</td>
                    <td>Date: The date the media was published. YYYY-MM-DD HH:MM:SS.</td>
                    <td>yes</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>dateupdated</td>
                    <td>Date: The date the media was last updated. YYYY-MM-DD HH:MM:SS.</td>
                    <td>yes</td>
                    <td>no</td>
                   </tr>
                   <tr>
                    <td>lastBuildDate</td>
                    <td>Date: The date and time that the feed was last built.</td>
                    <td>no</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>docs</td>
                    <td>URL: Link to RSS specification</td>
                    <td>no</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>generator</td>
                    <td>Text: Name and version of the generator used to generate the channel.</td>
                    <td>no</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>managingEditor</td>
                    <td>Text: Details about the managing Editor.</td>
                    <td>no</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>webMaster</td>
                    <td>Text: Details about the webmaster.</td>
                    <td>no</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>ttl</td>
                    <td>Int: The maximum number of minutes the chanel has to live before referesing from the source.</td>
                    <td>no</td>
                    <td>yes</td>
                   </tr>
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
                                    <p>GET /media/1?format=json</p>
                                </blockquote>
                            </li>
                            <li>
                                Provides this:
                                <?php 
                                //Get the output.
                                if (!$result = file_get_contents(UNL_MediaYak_Controller::$url."media/1?format=json")) {
                                    $result = "Error getting file contents.";
                                }
                                ?>
                                <pre class="code">
                                    <code class="javascript"><?php echo htmlentities($result); ?></code>
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
                                if (!$result = file_get_contents(UNL_MediaYak_Controller::$url."media/1?format=xml")) {
                                    $result = "Error getting file contents.";
                                }
                                ?>
                                <pre class="code">
                                    <code class="xml"><?php echo htmlentities($result); ?></code>
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
                                if (!$partial = file_get_contents(UNL_MediaYak_Controller::$url."media/1?format=partial")) {
                                    $partial = "Error getting file contents.";
                                }
                                ?>
                                <pre class="code">
                                    <code class="html"><?php echo htmlentities($partial); ?></code>
                                </pre>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>

    <div class="resource">
        <h1 id="channels" class="sec_main">Channel Resource <a href='#resources' class='resources'>Go to the list of resources.</a></h1>
        <h3>Details</h3>
        <ul>
            <li>
                <h4 id="channels-uri"><a href="#channels-uri">Resource URI</a></h4>
                <blockquote>
                    <p><?php echo UNL_MediaYak_Controller::$url; ?>channels/{id}</p>
                </blockquote>
            </li>
            <li>
                <h4 id="channels-properties"><a href="#channels-properties">Resource Properties</a></h4> 
                <table class="zentable neutral">
                <thead><tr><th>Property</th><th>Description</th><th>JSON</th><th>XML</th></tr></thead>
                  <tbody>
                   <tr>
                    <td>id</td>
                    <td>Int: The id of the selected channel</td>
                    <td>yes</td>
                    <td>no</td>
                   </tr>
                   <tr>
                    <td>title</td>
                    <td>Text: The Title of the channel</td>
                    <td>yes</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>link</td>
                    <td>URL: The URL to the channel</td>
                    <td>yes</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>description</td>
                    <td>Text: The descripton of the channel.</td>
                    <td>yes</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>image</td>
                    <td>URL: The URL to the image for the channel</td>
                    <td>yes</td>
                    <td>no</td>
                   </tr>
                   <tr>
                    <td>pubDate</td>
                    <td>Date: The date that the channel was published.</td>
                    <td>yes</td>
                    <td>no</td>
                   </tr>
                   <tr>
                    <td>uidcreated</td>
                    <td>Text: The UID of the user who created the channel</td>
                    <td>yes</td>
                    <td>no</td>
                   </tr>
                   <tr>
                    <td>language</td>
                    <td>Text: The name of the language used in the channel.</td>
                    <td>no</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>pubDate</td>
                    <td>Date: The date that the feed was published. D, d M Y h:m:s Timezone</td>
                    <td>no</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>lastBuildDate</td>
                    <td>Date: The date and time that the feed was last built.</td>
                    <td>no</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>docs</td>
                    <td>URL: Link to RSS specification</td>
                    <td>no</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>generator</td>
                    <td>Text: Name and version of the generator used to generate the channel.</td>
                    <td>no</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>managingEditor</td>
                    <td>Text: Details about the managing Editor.</td>
                    <td>no</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>webMaster</td>
                    <td>Text: Details about the webmaster.</td>
                    <td>no</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>ttl</td>
                    <td>Int: The maximum number of minutes the chanel has to live before referesing from the source.</td>
                    <td>no</td>
                    <td>yes</td>
                   </tr>
                   <tr>
                    <td>{media}</td>
                    <td>A list of the <a href="#instance">media instances</a> in the channel. </td>
                    <td>yes</td>
                    <td>yes</td>
                   </tr>
                  </tbody>
                </table>
            </li>
            <li>
                <h4 id="channels-get"><a href="#channels-get">HTTP GET</a></h4>
                <p>Returns a representation of the resource, including the properties above.</p>
            </li>
            <li>
                <h4 id="channels-get-example-1"><a href="#channels-get-example-1">Example</a></h4>
                
                <ul class="wdn_tabs">
                    <li><a href="#channelsTab1">JSON</a></li>
                    <li><a href="#channelsTab2">XML</a></li>
                    <li><a href="#channelsTab3">Partial</a></li>
                </ul>
                <div class="wdn_tabs_content">
                    <div id="channelsTab1">
                        <ul>
                            <li>
                                Calling this:
                                <blockquote>
                                    <p>GET /media/1?format=json</p>
                                </blockquote>
                            </li>
                            <li>
                                Provides this:
                                <?php 
                                //Get the output.
                                if (!$result = file_get_contents(UNL_MediaYak_Controller::$url."channels/1?format=json")) {
                                    $result = "Error getting file contents.";
                                }
                                ?>
                                <pre class="code">
                                    <code class="javascript"><?php echo htmlentities($result); ?></code>
                                </pre>
                            </li>
                        </ul>
                    </div>
                    <div id="channelsTab2">
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
                                if (!$result = file_get_contents(UNL_MediaYak_Controller::$url."channels/1?format=xml")) {
                                    $result = "Error getting file contents.";
                                }
                                ?>
                                <pre class="code">
                                    <code class="xml"><?php echo htmlentities($result); ?></code>
                                </pre>
                            </li>
                        </ul>
                    </div>
                    <div id="channelsTab3">
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
                                if (!$partial = file_get_contents(UNL_MediaYak_Controller::$url."channels/1?format=partial")) {
                                    $partial = "Error getting file contents.";
                                }
                                ?>
                                <pre class="code">
                                    <code class="html"><?php echo htmlentities($partial); ?></code>
                                </pre>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>

    <div class="resource">
        <h1 id="tags" class="sec_main">Tags Resource <a href='#resources' class='resources'>Go to the list of resources.</a></h1>
        <h3>Details</h3>
        <ul>
            <li>
                <h4 id="tags-uri"><a href="#tags-uri">Resource URI</a></h4>
                <blockquote>
                    <p><?php echo UNL_MediaYak_Controller::$url; ?>tags/{query}</p>
                </blockquote>
            </li>
            <li>
                <h4 id="tags-properties"><a href="#tags-properties">Resource Properties</a></h4> 
                <table class="zentable neutral">
                <thead><tr><th>Property</th><th>Description</th><th>JSON</th><th>XML</th></tr></thead>
                  <tbody>
                   <tr>
                    <td>{results}</td>
                    <td>A list of the <a href="#instance">media instances</a> that were found for the query tag. </td>
                    <td>yes</td>
                    <td>yes</td>
                   </tr>
                  </tbody>
                </table>
            </li>
            <li>
                <h4 id="tags-get"><a href="#tags-get">HTTP GET</a></h4>
                <p>Returns a representation of the resource, including the properties above.</p>
            </li>
            <li>
                <h4 id="tags-get-example-1"><a href="#tags-get-example-1">Example</a></h4>
                
                <ul class="wdn_tabs">
                    <li><a href="#tagsTab1">JSON</a></li>
                    <li><a href="#tagsTab2">XML</a></li>
                    <li><a href="#tagsTab3">Partial</a></li>
                </ul>
                <div class="wdn_tabs_content">
                    <div id="tagsTab1">
                        <ul>
                            <li>
                                Calling this:
                                <blockquote>
                                    <p>GET /tags/idk?format=json</p>
                                </blockquote>
                            </li>
                            <li>
                                Provides this:
                                <?php 
                                //Get the output.
                                if (!$result = file_get_contents(UNL_MediaYak_Controller::$url."tags/idk?format=json")) {
                                    $result = "Error getting file contents.";
                                }
                                ?>
                                <pre class="code">
                                    <code class="javascript"><?php echo htmlentities($result); ?></code>
                                </pre>
                            </li>
                        </ul>
                    </div>
                    <div id="tagsTab2">
                        <ul>
                            <li>
                                Calling this:
                                <blockquote>
                                    <p>GET /tags/idk?format=xml</p>
                                </blockquote>
                            </li>
                            <li>
                                Provides this:
                                <?php 
                                //Get the output.
                                if (!$result = file_get_contents(UNL_MediaYak_Controller::$url."tags/idk?format=xml")) {
                                    $result = "Error getting file contents.";
                                }
                                ?>
                                <pre class="code">
                                    <code class="xml"><?php echo htmlentities($result); ?></code>
                                </pre>
                            </li>
                        </ul>
                    </div>
                    <div id="tagsTab3">
                        <ul>
                            <li>
                                Calling this:
                                <blockquote>
                                    <p>GET /tags/idk?format=partiall</p>
                                </blockquote>
                            </li>
                            <li>
                                Provides this:
                                <?php 
                                //Get the output.
                                if (!$partial = file_get_contents(UNL_MediaYak_Controller::$url."tags/idk?format=partial")) {
                                    $partial = "Error getting file contents.";
                                }
                                ?>
                                <pre class="code">
                                    <code class="html"><?php echo htmlentities($partial); ?></code>
                                </pre>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    
        <div class="resource">
        <h1 id="search" class="sec_main">Search Resource <a href='#resources' class='resources'>Go to the list of resources.</a></h1>
        <h3>Details</h3>
        <ul>
            <li>
                <h4 id="search-uri"><a href="#search-uri">Resource URI</a></h4>
                <blockquote>
                    <p><?php echo UNL_MediaYak_Controller::$url; ?>search/{query}</p>
                </blockquote>
            </li>
            <li>
                <h4 id="search-properties"><a href="#search-properties">Resource Properties</a></h4> 
                <table class="zentable neutral">
                <thead><tr><th>Property</th><th>Description</th><th>JSON</th><th>XML</th></tr></thead>
                  <tbody>
                   <tr>
                    <td>{results}</td>
                    <td>A list of the <a href="#instance">media instances</a> that were found for the search term. </td>
                    <td>yes</td>
                    <td>yes</td>
                   </tr>
                  </tbody>
                </table>
            </li>
            <li>
                <h4 id="search-get"><a href="#search-get">HTTP GET</a></h4>
                <p>Returns a representation of the resource, including the properties above.</p>
            </li>
            <li>
                <h4 id="search-get-example-1"><a href="#search-get-example-1">Example</a></h4>
                
                <ul class="wdn_tabs">
                    <li><a href="#searchTab1">JSON</a></li>
                    <li><a href="#searchTab2">XML</a></li>
                    <li><a href="#searchTab3">Partial</a></li>
                </ul>
                <div class="wdn_tabs_content">
                    <div id="searchTab1">
                        <ul>
                            <li>
                                Calling this:
                                <blockquote>
                                    <p>GET /search/idk?format=json</p>
                                </blockquote>
                            </li>
                            <li>
                                Provides this:
                                <?php 
                                //Get the output.
                                if (!$result = file_get_contents(UNL_MediaYak_Controller::$url."search/idk?format=json")) {
                                    $result = "Error getting file contents.";
                                }
                                ?>
                                <pre class="code">
                                    <code class="javascript"><?php echo htmlentities($result); ?></code>
                                </pre>
                            </li>
                        </ul>
                    </div>
                    <div id="searchTab2">
                        <ul>
                            <li>
                                Calling this:
                                <blockquote>
                                    <p>GET /search/idk?format=xml</p>
                                </blockquote>
                            </li>
                            <li>
                                Provides this:
                                <?php 
                                //Get the output.
                                if (!$result = file_get_contents(UNL_MediaYak_Controller::$url."search/idk?format=xml")) {
                                    $result = "Error getting file contents.";
                                }
                                ?>
                                <pre class="code">
                                    <code class="xml"><?php echo htmlentities($result); ?></code>
                                </pre>
                            </li>
                        </ul>
                    </div>
                    <div id="searchTab3">
                        <ul>
                            <li>
                                Calling this:
                                <blockquote>
                                    <p>GET /search/idk?format=partiall</p>
                                </blockquote>
                            </li>
                            <li>
                                Provides this:
                                <?php 
                                //Get the output.
                                if (!$partial = file_get_contents(UNL_MediaYak_Controller::$url."search/idk?format=partial")) {
                                    $partial = "Error getting file contents.";
                                }
                                ?>
                                <pre class="code">
                                    <code class="html"><?php echo htmlentities($partial); ?></code>
                                </pre>
                            </li>
                        </ul>
                    </div>
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
            <li><a href="#instance">Media Instance</a></li>
            <li><a href="#channels">Channels</a></li>
            <li><a href="#tags">Tags</a></li>
            <li><a href="#search">Search</a></li>
        </ul>
    </div>
    <div class="zenbox neutral" style="width:200px">
        <h3>Format Information</h3>
        <p>The following is a list of formates used in MediaHub.</p>
        <ul>
            <li><a href='http://www.json.org/'>JSON (JavaScript Object Notation)</a></li>
            <li><a href='http://en.wikipedia.org/wiki/XML'>XML (Extensible Markup Language)</a></li>
            <li>Partial - The un-themed main content area of the page.</li>
        </ul>
    </div>
</div>