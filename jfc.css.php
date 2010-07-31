<?php
header("Content-type: text/css");
?>

#jfcg {
    position:relative;
}

#jfcg a:link,
#jfcg a:hover,
#jfcg a:visited
 {
    text-decoration: none;
}

#jfcg.cont {
    position:relative;
    background-image:url('./header-pic.png');
    background-repeat:no-repeat;
}

#jfcg div {
    position:absolute;
    z-index:8;
    filter: alpha(opacity=0);
    opacity:0.0;
    background-color:transparent;
}

#jfcg h2 {
    position:absolute;
    display:none;
}

#jfcg div.active {
    z-index:10;
    filter: alpha(opacity=100);
    opacity:1.0;
}

#jfcg div.last-active {
    z-index:9;
    filter: alpha(opacity=0);
    opacity:0.0;
}

/*
 * Below is some extra CSS that I have used for this plugin in the past.
 * The idea is to give you an example of how I moved the gallery around 
 * my page and changed the caption position and style.
 * This CSS should be placed in your Theme CSS files to avoid any problems when upgrading the plugin.
 */

/*
#jfcg div {
    top: 0px;
    left:-150px;
}

#jfcg h2 {
    font-family: Arial,Verdana,"Helvetica",Times New Roman;
    font-size: 1.5em;
    left: 30px;
    top: 175px;
    background-color:none;
    color:#000d61;
    width:60%;
    margin-bottom:0;
}

#jfcg a img{
}
*/

