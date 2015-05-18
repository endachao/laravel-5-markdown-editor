# laravel-5-markdown-editor
Based on the markdown editor laravel 5

一个基于 laravel 5 的markdown 编辑器

# 不需要敲语法可界面操作的功能
1. 加粗字体
2. 加斜字体
3. `无需手写 md插入链接`
4. 引用
5. `无需手写 md 语法插入图片`
6. 数字列表
7. 普通列表
8. 标题
9. 分割
10. 撤销
11. 重做
12. 全屏

# 预览
<img src="http://www.phpcto.org/tmp/m1.jpg" width = "400" height = "200"  align=center />

<img src="http://www.phpcto.org/tmp/m2.jpg" width = "300" height = "200"  align=center />

# Installation

1.在 `composer.json` 的 require里 加入

```
"yuanchao/laravel-5-markdown-editor": "dev-master"
```
2.执行 `composer update`

3.在config/app.php 的 `providers` 数组加入一条

```
'YuanChao\Editor\EndaEditorServiceProvider'
```

4.在config/app.php 的 `aliases` 数组加入一条

```
'EndaEditor' => 'YuanChao\Editor\Facade\EndaEditorFacade'

```

5.执行 `php artisan vendor:publish`

执行完上面的命令后，会生成配置文件和视图文件到你的 config/ 和 views/vendor 目录

# Usage 

1.在需要编辑器的地方插入以下代码

```
// 引入编辑器代码
@include('editor::head')

// 编辑器一定要被一个 class 为 editor 的容器包住
<div class="editor">
	// 创建一个 textarea 而已，具体的看手册，主要在于它的 id 为 myEditor
	{!! Form::textarea('content', '', ['class' => 'form-control','id'=>'myEditor']) !!}
</div>

```

这个时候，编辑器就出来啦～

2.图片上传配置，打开config/editor.php 配置文件，修改里面的 `uploadUrl` 配置项，为你的处理上传的 action 

我的上传 action 代码为

```
public function postUpload(){

        $data = array();
        // 文件上传
        if (Request::hasFile('image')){
            $pic = Request::file('image');
            if($pic->isValid()){
                $newName = md5(rand(1,1000).$pic->getClientOriginalName()).".".$pic->getClientOriginalExtension();
                $pic->move('uploads',$newName);
                $url = asset('uploads'.'/'.$newName);
                $data = array(
                    'status'=>0,
                    'message'=>'',
                    'url'=>$url
                );
            }
        }else{
            $data = array(
                'status'=>1,
                'message'=>'未选择文件',
                'url'=>''
            );
        }

        return json_encode($data);
    }


```

可以看到，上传图片处理完以后，返回一个 `json` 字符串，里面必须有 `status` 参数，0为成功，如果上传成功，必须给 `url` 字段赋值为图片的地址

###完成以上这些配置，你就可以在线插入图片啦

