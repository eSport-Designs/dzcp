<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$time_start = generatetime();
lang($language);
$dir = "links";
$where = _site_links;

## SECTIONS ##
switch ($action):
    default:
        $admin = permission("links") ? _links_admin : "";

        $qry = db("SELECT * FROM ".$db['links']." ORDER BY banner DESC"); $show = '';
        if(_rows($qry)) {
            while($get = _fetch($qry)) {
                if($get['banner']) {
                    $banner = show(_links_bannerlink, array("id" => $get['id'],
                                                              "banner" => re($get['text'])));
                } else {
                    $banner = show(_links_textlink, array("id" => $get['id'],
                                                          "text" => str_replace('http://','',re($get['url']))));
                }

                $show .= show($dir."/links_show", array("beschreibung" => bbcode($get['beschreibung']),
                                                        "hits" => $get['hits'],
                                                        "hit" => _hits,
                                                        "banner" => $banner));
            }
        }

        $index = show($dir."/links", array("head" => _links_head, "show" => $show));
    break;
    case 'link';
        db("UPDATE ".$db['links']." SET `hits` = hits+1 WHERE `id` = '".intval($_GET['id'])."'");
        $get = db("SELECT `url` FROM ".$db['links']." WHERE `id` = '".intval($_GET['id'])."'",false,true);
        header("Location: ".$get['url']);
    break;
endswitch;

## SETTINGS ##
$title = $pagetitle." - ".$where."";
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where,$time);

## OUTPUT BUFFER END ##
gz_output();