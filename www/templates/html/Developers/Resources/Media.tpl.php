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