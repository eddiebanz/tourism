<?php    require_once("index_lib.php");     ?>

<html> 
<head>
    <link rel='stylesheet' type='text/css' href='/assets/normalize.css'>
    <link rel='stylesheet' type='text/css' href='/assets/skeleton.css'>
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script type="text/javascript">
    </script>
    <style type="text/css">
        input, td{
            margin:0 auto;
            padding: 0;
        }
        tbody{
            max-height:500px;
            resize:none;
            overflow: scroll;
        }
        .center{
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>
<body class='container'>
    <div class="row">
        <div class="one column"></div>
        <div class="ten column">
            <label>Main Site:</label>
            <form>
                <select name="pagelist">
                    <?php
                        $results = getSiteList();
                        if(isset($results)) {
                            foreach ( $results as $pagelist)
                            {
                                echo "<option value='".$pagelist['mainSite']."'>".$pagelist['mainSite'];
                            }
                        }
                    ?>
                </select>
            </form>
            <a href="#">Set Rules</a>
            <form action='#' method='post'>
            <div class="SiteLinks">
                <table class='table-border u-full-width'>
                    <thead>
                        <tr>
                            <th>Links</th>
                            <th class='center'>Scrape</th>
                        </tr>
                    </thead>
                    <tbody class='partial'>
                        <?php
                            if(isset($subpages)){
                            // if there are any results, go and extract all the links from the parent site
                                foreach ($subpages as $hrefLinks) 
                                {
                                    echo "<tr>";
                                       echo "<td>".$hrefLinks['ref_link']."</td>";
                                       echo "<td class='center'><input type='checkbox' name='crawl' value='".$hrefLinks['_id']."'></td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            </form>
        </div>
    </div>
</body>
</html>