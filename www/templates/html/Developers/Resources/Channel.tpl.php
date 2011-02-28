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