    <div class="resource">
        <h1 id="search" class="sec_main">Search Resource <a href='#resources' class='resources'>Go to the list of resources.</a></h1>
        <h3>Details</h3>
        <ul>
            <li>
                <h4 id="search-uri"><a href="#search-uri">Resource URI</a></h4>
                <blockquote>
                    <p><?php echo UNL_MediaHub_Controller::$url; ?>search/{query}</p>
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
                                if (!$result = file_get_contents(UNL_MediaHub_Controller::$url."search/idk?format=json")) {
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
                                if (!$result = file_get_contents(UNL_MediaHub_Controller::$url."search/idk?format=xml")) {
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
                                if (!$partial = file_get_contents(UNL_MediaHub_Controller::$url."search/idk?format=partial")) {
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