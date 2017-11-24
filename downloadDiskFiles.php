<?
function ViewByUserTest($arFile, $arOptions = array())
{
    /** @global CMain $APPLICATION */
    global $APPLICATION;

    $fastDownload = (COption::GetOptionString('main', 'bx_fast_download', 'N') == 'Y');

    $attachment_name = "";
    $content_type = "";
    $specialchars = false;
    $force_download = false;
    $cache_time = 10800;
    $fromClouds = false;

    if(is_array($arOptions))
    {
        if(isset($arOptions["content_type"]))
            $content_type = $arOptions["content_type"];
        if(isset($arOptions["specialchars"]))
            $specialchars = $arOptions["specialchars"];
        if(isset($arOptions["force_download"]))
            $force_download = $arOptions["force_download"];
        if(isset($arOptions["cache_time"]))
            $cache_time = intval($arOptions["cache_time"]);
        if(isset($arOptions["attachment_name"]))
            $attachment_name = $arOptions["attachment_name"];
    }

    if($cache_time < 0)
        $cache_time = 0;

    if(is_array($arFile))
    {
        if(isset($arFile["SRC"]))
        {
            $filename = $arFile["SRC"];
        }
        elseif(isset($arFile["tmp_name"]))
        {
            $filename = "/".ltrim(substr($arFile["tmp_name"], strlen($_SERVER["DOCUMENT_ROOT"])), "/");
        }
        else
        {
            $filename = CFile::GetFileSRC($arFile);
        }
    }
    else
    {
        if(($arFile =  CFile::GetFileArray($arFile)))
        {
            $filename = $arFile["SRC"];
        }
        else
        {
            $filename = '';
        }
    }

    if($filename == '')
    {
        return false;
    }

    if($content_type == '' && isset($arFile["CONTENT_TYPE"]))
    {
        $content_type = $arFile["CONTENT_TYPE"];
    }

    //we produce resized jpg for original bmp
    if($content_type == '' || $content_type == "image/bmp")
    {
        if(isset($arFile["tmp_name"]))
        {
            $content_type =  CFile::GetContentType($arFile["tmp_name"], true);
        }
        else
        {
            $content_type =  CFile::GetContentType($_SERVER["DOCUMENT_ROOT"].$filename);
        }
    }

    if($arFile["ORIGINAL_NAME"] <> '')
        $name = $arFile["ORIGINAL_NAME"];
    elseif($arFile["name"] <> '')
        $name = $arFile["name"];
    else
        $name = $arFile["FILE_NAME"];
    if(isset($arFile["EXTENSION_SUFFIX"]) && $arFile["EXTENSION_SUFFIX"] <> '')
        $name = substr($name, 0, -strlen($arFile["EXTENSION_SUFFIX"]));

    $name = str_replace(array("\n", "\r"), '', $name);

    if($attachment_name)
        $attachment_name = str_replace(array("\n", "\r"), '', $attachment_name);
    else
        $attachment_name = $name;

    if(!$force_download)
    {
        if(! CFile::IsImage($name, $content_type) || $arFile["HEIGHT"] <= 0 || $arFile["WIDTH"] <= 0)
        {
            //only valid images can be downloaded inline
            $force_download = true;
        }
    }

    $content_type =  CFile::NormalizeContentType($content_type);

    if($force_download)
    {
        $specialchars = false;
    }

    $src = null;
    $file = new Bitrix\Main\IO\File($_SERVER["DOCUMENT_ROOT"].$filename);

    //dd( $arFile['ORIGINAL_NAME']  );exit;

    if(substr($filename, 0, 1) == "/")
    {
        try
        {
            $src = $file->open(Bitrix\Main\IO\FileStreamOpenMode::READ); //данные


            $test = \Bitrix\Main\IO\File::putFileContents( $_SERVER["DOCUMENT_ROOT"].'/tmp2/'.$arFile['ORIGINAL_NAME'] ,$src );

        }
        catch(Bitrix\Main\IO\IoException $e)
        {
            return false;
        }
    }
    else
    {
        if(!$fastDownload)
        {
            $src = new \Bitrix\Main\Web\HttpClient();
        }
        elseif(intval($arFile['HANDLER_ID']) > 0)
        {
            $fromClouds = true;
        }
    }

    //dd(  $src->get()  );exit;

    $APPLICATION->RestartBuffer();
    while(ob_end_clean());

    $cur_pos = 0;
    $filesize = ($arFile["FILE_SIZE"] > 0? $arFile["FILE_SIZE"] : $arFile["size"]);
    $size = $filesize-1;
    $p = strpos($_SERVER["HTTP_RANGE"], "=");
    if(intval($p)>0)
    {
        $bytes = substr($_SERVER["HTTP_RANGE"], $p+1);
        $p = strpos($bytes, "-");
        if($p !== false)
        {
            $cur_pos = floatval(substr($bytes, 0, $p));
            $size = floatval(substr($bytes, $p+1));
            if ($size <= 0)
            {
                $size = $filesize - 1;
            }
            if ($cur_pos > $size)
            {
                $cur_pos = 0;
                $size = $filesize - 1;
            }
        }
    }

    if($arFile["tmp_name"] <> '')
    {
        $tmpFile = new Bitrix\Main\IO\File($arFile["tmp_name"]);
        $filetime = $tmpFile->getModificationTime();
    }
    else
    {
        $filetime = intval(MakeTimeStamp($arFile["TIMESTAMP_X"]));
    }

    if($_SERVER["REQUEST_METHOD"] == "HEAD")
    {
        CHTTP::SetStatus("200 OK");
        header("Accept-Ranges: bytes");
        header("Content-Type: ".$content_type);
        header("Content-Length: ".($size-$cur_pos+1));

        if($filetime > 0)
            header("Last-Modified: ".date("r", $filetime));
    }
    else
    {
        $lastModified = '';
        if($cache_time > 0)
        {
            //Handle ETag
            $ETag = md5($filename.$filesize.$filetime);
            if(array_key_exists("HTTP_IF_NONE_MATCH", $_SERVER) && ($_SERVER['HTTP_IF_NONE_MATCH'] === $ETag))
            {
                CHTTP::SetStatus("304 Not Modified");
                header("Cache-Control: private, max-age=".$cache_time.", pre-check=".$cache_time);
                die();
            }
            header("ETag: ".$ETag);

            //Handle Last Modified
            if($filetime > 0)
            {
                $lastModified = gmdate('D, d M Y H:i:s', $filetime).' GMT';
                if(array_key_exists("HTTP_IF_MODIFIED_SINCE", $_SERVER) && ($_SERVER['HTTP_IF_MODIFIED_SINCE'] === $lastModified))
                {
                    CHTTP::SetStatus("304 Not Modified");
                    header("Cache-Control: private, max-age=".$cache_time.", pre-check=".$cache_time);
                    die();
                }
            }
        }

        $utfName = CHTTP::urnEncode($attachment_name, "UTF-8");
        $translitName = CUtil::translit($attachment_name, LANGUAGE_ID, array(
            "max_len" => 1024,
            "safe_chars" => ".",
            "replace_space" => '-',
            "change_case" => false,
        ));


        //$force_download =false; ///////////////////
        if($force_download)
        {
            //Disable zlib for old versions of php <= 5.3.0
            //it has broken Content-Length handling
            if(ini_get('zlib.output_compression'))
                ini_set('zlib.output_compression', 'Off');

            if($cur_pos > 0)
                CHTTP::SetStatus("206 Partial Content");
            else
                CHTTP::SetStatus("200 OK");

            header("Content-Type: ".$content_type);
            header("Content-Disposition: attachment; filename=\"".$translitName."\"; filename*=utf-8''".$utfName);
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: ".($size-$cur_pos+1));
            if(is_resource($src))
            {
                header("Accept-Ranges: bytes");
                header("Content-Range: bytes ".$cur_pos."-".$size."/".$filesize);
            }
        }
        else
        {
            //dd(  111  );exit;
            header("Content-Type: ".$content_type);
            header("Content-Disposition: inline; filename=\"".$translitName."\"; filename*=utf-8''".$utfName);
        }

        if($cache_time > 0)
        {
            header("Cache-Control: private, max-age=".$cache_time.", pre-check=".$cache_time);
            if($filetime > 0)
                header('Last-Modified: '.$lastModified);
        }
        else
        {
            header("Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0");
        }

        header("Expires: 0");
        header("Pragma: public");

        // Download from front-end
        if($fastDownload)
        {
            if($fromClouds)
            {
                $filename = preg_replace('~^(http[s]?)(\://)~i', '\\1.' , $filename);
                $cloudUploadPath = COption::GetOptionString('main', 'bx_cloud_upload', '/upload/bx_cloud_upload/');
                header('X-Accel-Redirect: '.$cloudUploadPath.$filename);
            }
            else
            {
                $filename = CHTTP::urnEncode($filename, "UTF-8");
                header('X-Accel-Redirect: '.$filename);
            }
        }
        else
        {
            session_write_close();
            if ($specialchars)
            {
                echo "<", "pre" ,">";
                if(is_resource($src))
                {
                    while(!feof($src))
                        echo htmlspecialcharsbx(fread($src, 32768));
                    $file->close();
                }
                else
                {
                    echo htmlspecialcharsbx($src->get($filename));
                }
                echo "<", "/pre", ">";
            }
            else
            {
                if(is_resource($src))
                {
                    $file->seek($cur_pos);
                    while(!feof($src) && ($cur_pos <= $size))
                    {
                        $bufsize = 131072; //128K
                        if($cur_pos + $bufsize > $size)
                            $bufsize = $size - $cur_pos + 1;
                        $cur_pos += $bufsize;
                        echo fread($src, $bufsize);
                    }
                    $file->close();
                }
                else
                {
                    $fp = fopen("php://output", "wb");
                    $src->setOutputStream($fp);
                    $src->get($filename);
                }
            }
        }
    }
}
