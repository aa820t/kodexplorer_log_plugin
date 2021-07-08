<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>操作日志</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="<?php echo $this->pluginHost; ?>static/layui-v2.6.8/css/layui.css">

    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="layui-container" style="margin-top:30px;">
    <blockquote class="layui-elem-quote">操作日志查看</blockquote>
    <br>
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">用户名</label>
                <div class="layui-input-inline">
                    <input type="tel" name="username" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">操作名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="title" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">访问地址</label>
                <div class="layui-input-inline">
                    <input type="text" name="url" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <button class="layui-btn" lay-submit="" lay-filter="search">搜索</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>

    <div class="layui-row">
        <div class="layui-col-xs12">
            <table class="layui-hide" id="lists"></table>
        </div>
    </div>
</div>

<script src="<?php echo $this->pluginHost; ?>static/layui-v2.6.8/layui.js"></script>
<script>
    layui.use(['layer', 'form', 'util'], function () {
        var layer = layui.layer,
            form = layui.form,
            util = layui.util,
            table = layui.table;

        table.render({
            elem: '#lists'
            , url: '<?php echo $this->pluginApi . "ajaxGetLists"; ?>'
            , cellMinWidth: 80
            , page: true
            , cols: [[
                {field: 'username', width: 120, title: '用户名'}
                , {field: 'url', title: '访问地址' }
                , {field: 'title', title: '操作名称'}
                , {field: 'content', title: '访问参数'}
                , {field: 'ip', title: 'IP地址'}
                , {field: 'useragent', title: '浏览器'}
                , {field: 'createtime', title: '访问时间', templet:function(d){
                        return util.toDateString(d.createtime * 1000, "yyyy-MM-dd HH:mm:ss");
                    }}
            ]]
        });

        form.on('submit(search)', function(data){
            table.reload('lists', {
                where: {
                    username: data.field.username,
                    title: data.field.title,
                    url: data.field.url,
                },
                page: {
                    curr: 1
                }
            });
            return false;
        });
    });
</script>
</body>
</html>