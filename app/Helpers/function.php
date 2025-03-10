<?php
    function uploadImg($file ,array $extend  = array() )
    {
        $code = 0;
        // lay duong dan anh
        $baseFilename = public_path() . '/uploads/' . $_FILES[$file]['name'];
        // thong tin file
        $info = new SplFileInfo($baseFilename);

        // duoi file
        $ext = strtolower($info->getExtension());

        // kiem tra dinh dang file
        if ( ! $extend )
        {
            $extend = ['png','jpg'];
        }
        if(in_array($ext,$extend))
        {
            $code = 1;
        }
        // Tên file mới
        $filename = date('d-m-Y').'-'.md5($baseFilename) . '.' . $ext;

        // di chuyen file vao thu muc uploads
    //        move_uploaded_file($_FILES[$file]['tmp_name'], public_path() . '/uploads/' . $filename);
        $data = [
            'name'              => $filename,
            'name_original'     => $info->getFilename(),
            'code'              => $code,
            'tmp_name'          => $_FILES[$file]['tmp_name']
        ];
        return $data;
    }
?>