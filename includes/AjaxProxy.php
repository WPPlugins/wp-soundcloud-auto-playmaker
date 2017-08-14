<?php
require_once('_scXmlParser.php');
   if($_POST['limit'] == "" || $_POST['offset'] = ""){
        // no username entered
        echo "Invalid ajax request parameters.";
    }else{
        echo "In the get color is ";
        /*
        print('<pre>');
            print_r($_POST);
        print('<pre>');
         */
        echo $__POST['color'];
        $feedtype = $_POST['feedtype'];
        $listtype = $_POST['listtype'];
        $offset = $_POST['os'];
        $profilelink = $_POST['profilelink'];
        $tracklink = $_POST['tracklink'];
        $color  = $_POST['color'];
        $user = $_POST['user'];
        $limit = $_POST['limit'];
        $uri = "http://api.soundcloud.com/$listtype/$user/$feedtype?limit=$limit&offset=$offset";
        $parser = new scXmlParser();
        echo $parser->GetPlayerFeedWithOptions($uri, $profilelink, $tracklink, $color);
    }
?>
