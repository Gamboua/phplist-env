<?php

if (!isset($_GET['page']) || (isset($_GET['page']) && in_array($_GET['page'], array('home', 'about', 'dashboard', 'community','login')))) {
    $news = '';
    
    $max = 10;
    if (empty($_SESSION['adminloggedin'])) { 
      $max = 3; // show last 3 only on login page
    }
    
    include dirname(__FILE__).'/onyx-rss.php';
    $rss = new ONYX_RSS();
    $rss->setDebugMode(false);
    $rss->setCachePath($GLOBALS['tmpdir']);
    $rss->setExpiryTime(1440);
    $parseresult = $rss->parse('https://www.phplist.org/newslist/feed/', 'phplistnews');
    $count = 0;
    if ($parseresult) {
        while ($item = $rss->getNextItem()) {
            $count++;
            if ($count > $max) break;
            $date = $item['pubdate'];
            $date = str_replace('00:00:00 +0000', '', $date);
            $date = str_replace('00:00:00 +0100', '', $date);
            $date = str_replace('+0000', '', $date);
            if (preg_match('/\d+:\d+:\d+/', $date, $regs)) {
                $date = str_replace($regs[0], '', $date);
            }

            ## remove the '<p>&nbsp;</p>' in the descriptions
            $desc = $item['description'];
            $desc = str_replace('<p>&nbsp;</p>', '', $desc);
            $desc = '';

            $news .= '<li>
      <div class="publisheddate">'.$date.'</div> <a href="'.$item['link'].'?utm_source=phplist-'.VERSION.'&utm_medium=newspanel&utm_content='.urlencode($item['title']).'&utm_campaign=newspanel" target="_blank">'.$item['title'].'</a>
      '.$desc.'
      </li>';
        }
    }

    if (!empty($news)) {
        print '<div id="newsfeed" class="menutableright block">';
        print '
       <h3><span class="glyphicon glyphicon-bullhorn text-danger"></span> '.s('phpList community news').'</h3>
        <ul>'.$news.'</ul>';
        print '</div>';
    }
}
