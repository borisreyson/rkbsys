<?php

return array(


    'pdf' => array(
        'enabled' => true,
#        'binary'  => '/var/www/html/rkbsys/vendor/bin/wkhtmltopdf-i386',
        'binary'  => 'wkhtmltopdf',
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary'  => '"C:\Program Files\wkhtmltopdf\bin\wkhtmltoimage"',
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),


);
