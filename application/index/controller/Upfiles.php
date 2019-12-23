<?php
namespace app\index\controller;
use think\Db;
use think\Request;
use think\Controller;

/**
*PHP是世界上最好的语言
*@param 上传文件控制器
**/
class Upfiles extends Common
{
    public function _initialize()
    {
        
        parent::_initialize();
    }
    // public function upload(){
       

    //     header('Content-type:text/html;charset=utf-8');
    //     $base64_image_content = input('data');
    //     //匹配出图片的格式
    //     if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
    //     $type = $result[2];
    //     if($type !=('jpg'|| 'jpeg' || 'png' || 'gif')){
    //         echo json(['code'=>'400','msg'=>'上传正确格式的图片']);die;
    //     }

    //     $new_file = ROOT_PATH . 'public' . DS . 'uploads'.DS.date('Ymd',time()).DS;
    //     if(!file_exists($new_file))
    //     {
    //     //检查是否有该文件夹，如果没有就创建，并给予最高权限
    //     mkdir($new_file, 0700);
    //     }
    //     $new_file_name = $new_file.time().".{$type}";

    //     $src = 'public' . DS . 'uploads'.DS.date('Ymd',time()).DS.time().".{$type}";
    //     if (file_put_contents($new_file_name, base64_decode(str_replace($result[1], '', $base64_image_content)))){
    //         $image = \think\Image::open($new_file_name);
    //         // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
    //        $re = $image->thumb(400, 400)->save($new_file_name);
    //        if($re){
    //             // $re = Db::name('user')->where('uid',session('user_data')['uid'])->update(['src'=>'http://'.THIS_URL.$src]);
    //             if($re){
    //                 $data = json(['code'=>'0','msg'=>'上传成功','src'=>'http://'.THIS_URL.$src]);
    //             }else{
    //                 $data = json(['code'=>'0','msg'=>'api出错']);
    //             }
    //        }else{
    //             $data = json(['code'=>'400','msg'=>'api出错']);
    //        }
          
    //         }else{
    //             $data = json(['code'=>'400','msg'=>'未知错误']);
    //         }

    //         echo $data;
    //     }else{
    //        echo json(['code'=>'400','msg'=>'未知错误']);
    //     }
    // }

    public function upload(){
    // 获取表单上传文件 例如上传了001.jpg
     $fileKey = array_keys(request()->file());
        // 获取表单上传文件 例如上传了001.jpg
     $file = request()->file($fileKey['0']);
    // 移动到框架应用根目录/public/uploads/ 目录下
    $info = $file->validate(['ext'=>'jpg,png,gif,jpeg'])->move(ROOT_PATH . 'public' . DS . 'uploads');
      
      
    if($info){

        $info_name = '/public' . DS . 'uploads'.DS.$info->getSaveName();
		
        $image = \think\Image::open(ROOT_PATH.$info_name);
        $src = '/public' . DS . 'uploads'.DS.$info->getSaveName();
      	
            // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
        $re = $image->thumb(150, 150)->save(ROOT_PATH.$info_name);

        $data = json(['code'=>'0000','msg'=>'上传成功','src'=>THIS_URL.$src]);

    }else{
        // 上传失败获取错误信息
        $data = json(['code'=>'60003','msg'=>$file->getError()]);
    }
    echo $data;
}



    public function file(){
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($fileKey['0']);
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

        if($info){
            $result['code'] = 0;
            $result['info'] = '文件上传成功!';
            $path=str_replace('\\','/',$info->getSaveName());

            $result['src'] = '/uploads/'. $path;
            $result['ext'] = $info->getExtension();
            $result['size'] = byte_format($info->getSize(),2);
            return $result;
        }else{
            // 上传失败获取错误信息
            $result['code'] =1;
            $result['info'] = '文件上传失败!';
            $result['src'] = '';
            return $result;
        }
    }
    public function pic(){
        // 获取上传文件表单字段名
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件
        $file = request()->file($fileKey['0']);
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            $result['code'] = 1;
            $result['info'] = '图片上传成功!';
            $path=str_replace('\\','/',$info->getSaveName());
            $result['src'] = '/uploads/'. $path;
            return json($result,true);
        }else{
            // 上传失败获取错误信息
            $result['code'] =0;
            $result['info'] = '图片上传失败!';
            $result['src'] = '';
            return json($result,true);
        }
    }
    //编辑器图片上传
    public function editUpload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            $result['code'] = 0;
            $result['msg'] = '图片上传成功!';
            $path=str_replace('\\','/',$info->getSaveName());
            $result['data']['src'] = __PUBLIC__.'/uploads/'. $path;
            $result['data']['title'] = $path;
            return json($result,true);
        }else{
            // 上传失败获取错误信息
            $result['code'] =1;
            $result['msg'] = '图片上传失败!';
            $result['data'] = '';
            return json($result,true);
        }
    }
    //多图上传
    public function upImages(){
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件
        $file = request()->file($fileKey['0']);
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            $result['code'] = 0;
            $result['msg'] = '图片上传成功!';
            $path=str_replace('\\','/',$info->getSaveName());
            $result["src"] = '/uploads/'. $path;
            return $result;
        }else{
            // 上传失败获取错误信息
            $result['code'] =1;
            $result['msg'] = '图片上传失败!';
            return $result;
        }
    }
}