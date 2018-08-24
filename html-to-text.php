<?php
$url = 'https://www.lipsum.com';

get_text($url);

function get_text($url)
{
    $html = file_get_contents('https://droom.in');
    $html = preg_replace('/\<script[\s\S]+?<\/script>/', '', $html);
    $html = preg_replace('/\<noscript[\s\S]+?<\/noscript>/', '', $html);
    $html = preg_replace('/\<!--[\s\S]+?-->/', '', $html);
    $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $html);
    fclose($myfile);

    $foutput = fopen("output_file.txt","a") or die("unable to open file");

    if ($fh = fopen('newfile.txt', 'r')) {
        while (!feof($fh)) {
            $line = fgets($fh);
            $line = trim(strip_tags(strip_tags_review($line)));
            $line = preg_replace('/\s\s+/', '', $line);
            $line = preg_replace('/">|" \/+>/', '', $line);
            $line = preg_replace('/".*/', '', $line);
            $line = preg_replace('/\|+/', '', $line);
            $line = preg_replace('/^{|^}/', '', $line);
            $line = preg_replace('/^,|-->/', '', $line);

            if (strlen($line) > 0) {
                //echo $line . '<br>';
                fwrite($foutput,"\n".$line);

            }
        }
        fclose($foutput);
        fclose($fh);
    }
}

function strip_tags_review($str, $allowable_tags = '')
{
    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($allowable_tags), $tags);
    $tags = array_unique($tags[1]);

    if (is_array($tags) and count($tags) > 0) {
        $pattern = '@<(?!(?:' . implode('|', $tags) . ')\b)(\w+)\b.*?>(.*?)</\1>@i';
    } else {
        $pattern = '@<(\w+)\b.*?>(.*?)</\1>@i';
    }
    $str = preg_replace($pattern, '', $str);
    return preg_match($pattern, $str) ? strip_tags_review($str, $allowable_tags) : $str;
}
