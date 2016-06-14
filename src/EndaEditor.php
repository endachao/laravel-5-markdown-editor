<?php
/**
 * 等待扩展
 * User: 袁超<yccphp@163.com>
 * Time: 2015.05.18 下午2:11
 */
namespace YuanChao\Editor;

use Illuminate\Support\Facades\Request;

class EndaEditor{

    static $_errors=array();

    protected static function addError($message){
        if(!empty($message)){
            self::$_errors[] = $message;
        }
    }

    protected static function getLastError(){
        return empty(self::$_errors) ? '' : array_pop(self::$_errors);
    }

    /**
     * EndaEditor Upload ImgFile
     * @param string $path
     * @return array
     */
    public static function uploadImgFile($path){
        try{
            // File Upload
            if (Request::hasFile('image')){
                $pic = Request::file('image');
                if($pic->isValid()){
                    $newName = md5(rand(1,1000).$pic->getClientOriginalName()).".".$pic->getClientOriginalExtension();
                    $pic->move($path,$newName);
                    $url = asset($path.'/'.$newName);
                }else{
                    self::addError('The file is invalid');
                }
            }else{
                self::addError('Not File');
            }
        }catch (\Exception $e){
            self::addError($e->getMessage());
        }

        $data = array(
            'status'=>empty($message)?0:1,
            'message'=>self::getLastError(),
            'url'=>!empty($url)?$url:''
        );

        return $data;
    }


    /**
     * Set the Validator instance resolver.
     *
     * @param  \Closure  $resolver
     * @return void
     */
    public function resolver(Closure $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Resolve a new Validator instance.
     *
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return \Illuminate\Validation\Validator
     */
    protected function resolve()
    {
        if (is_null($this->resolver))
        {
            return new \YuanChao\Editor\Parsedown();
        }

        return call_user_func($this->resolver);
    }

    /**
     * 转换 mark 文本
     * @param $markdownText
     * @return string
     */
    public function MarkDecode($markdownText){
        $parsedown = $this->resolve();
        return $parsedown->text($markdownText);
    }

}
