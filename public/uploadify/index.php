<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Admin felület</title>
        <link href="/_gearoscope/public/skins/gearoscope/css/typo.css" media="screen" rel="stylesheet" type="text/css" />
        <link rel="stylesheet"
              href="/_gearoscope/public/skins/gearoscope/css/iframe.css"
              type="text/css" />
        <link rel="stylesheet"
              href="/_gearoscope/public/uploadify/uploadify.css"
              type="text/css" />
        <script type="text/javascript" src="/_gearoscope/public/skins/gearoscope/js/jquery-1.7.1.min.js"></script>     
        <script type="text/javascript" src="/_gearoscope/public/uploadify/jquery.uploadify-3.1.min.js"></script>
        
        <script type="text/javascript">
            $(function() {
                    $("#file_upload_1").uploadify({
                            'formData'      : {'folder' : '<?php print $_GET["folder"] ?>'},
                            'height'        : 30,
                            'swf'           : 'uploadify.swf',
                            'uploader'      : 'uploadify.php',
                            'width'         : 120
                    });
            });
        </script>
    </head>
    <body>
        <div id="content-outer">
            <!-- start content -->

            <div id="content">
                <div class="wrapper">
                    <p><input type="file" name="file_upload" id="file_upload_1" /></p>                                
                </div>
            </div>
            <!--  end content -->		
        </div>

    </body>
</html>
