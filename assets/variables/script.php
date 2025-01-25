<?php
    function fetchData($url){
        return file_get_contents($url);
    }
    function srcData($image)
    {
    
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
    
        // reads your image's data and convert it to base64
        $data = base64_encode($image);
    
        // Create the image's SRC:  "data:{mime};base64,{data};"
        return 'data: ' . finfo_buffer($finfo, $image) . ';base64,' . $data;
    
    }
?>