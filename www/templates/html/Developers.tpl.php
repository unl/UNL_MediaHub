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
    $resources = array('Media', 'Channel', 'Tag', 'Search');
    foreach ($resources as $resource) {
        echo $savvy->render(null, 'Developers/Resources/'.$resource.'.tpl.php');
    }
    ?>
    
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
        <p>The following is a list of formats used in MediaHub.</p>
        <ul>
            <li><a href='http://www.json.org/'>JSON (JavaScript Object Notation)</a></li>
            <li><a href='http://en.wikipedia.org/wiki/XML'>XML (Extensible Markup Language)</a></li>
            <li>Partial - The un-themed main content area of the page.</li>
        </ul>
    </div>
</div>