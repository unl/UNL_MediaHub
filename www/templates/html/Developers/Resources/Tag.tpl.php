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