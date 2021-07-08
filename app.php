<?php

class logPlugin extends PluginBase {
    function __construct() {
        parent::__construct();
    }

    public function regiest() {
        $this->hookRegiest(array(
            'globalRequest' => 'logPlugin.record',
            'templateCommonHeader' => 'logPlugin.addMenu'
        ));
    }

    public function addMenu() {
        $config = $this->getConfig();
        $subMenu = $config['menuSubMenu'];
        navbar_menu_add(array(
            'name' => 'Log',
            'icon' => $this->appIcon(),
            'url' => $this->pluginApi,
            'target' => '_blank',
            'subMenu' => $subMenu,
            'use' => '1'
        ));
    }

    private function role() {
        return array(
            'explorer.index' => '文件管理首页',
            'explorer.pathInfo' => '文件夹内容列表',
            'explorer.pathList' => '文件夹列表',
            'explorer.treeList' => '文件夹树列表',
            'explorer.pathChmod' => '文件夹权限修改',
            'explorer.mkdir' => '新建文件夹',
            'explorer.mkfile' => '新建文件',
            'explorer.pathRname' => '文件夹重命名',
            'explorer.pathDelete' => '文件夹删除',
            'explorer.zip' => '文件压缩',
            'explorer.unzip' => '文件解压',
            'explorer.unzipList' => '文件解压列表',
            'explorer.pathCopy' => '文件夹复制',
            'explorer.pathCute' => '文件夹剪切',
            'explorer.pathCuteDrag' => '文件夹拖拽剪切',
            'explorer.pathCopyDrag' => '文件夹拖拽复制',
            'explorer.clipboard' => '文件复制',
            'explorer.pathPast' => '文件夹粘贴',
            'explorer.serverDownload' => '远程下载',
            'explorer.fileUpload' => '文件上传',
            'explorer.search' => '搜索',
            'explorer.pathDeleteRecycle' => '清空回收站',
            'explorer.fileDownload' => '文件下载',
            'explorer.zipDownload' => '压缩文件下载',
            'explorer.fileDownloadRemove' => '文件下载后删除',
            'explorer.fileProxy' => '文件代理输出',
            'explorer.fileSave' => '文件通用保存',
            'explorer.officeView' => 'office文件查看',
            'explorer.officeSave' => 'office文件保存',
            'app.userApp' => '用户app添加/编辑',
            'app.initApp' => 'app初始化',
            'app.add' => 'app添加',
            'app.edit' => 'app编辑',
            'app.del' => 'app删除',
            'editor.fileGet' => '编辑器查看文件',
            'editor.fileSave' => '编辑器保存文件',
            'user.changePassword' => '用户修改密码',
            'user.commonJs' => '用户通用js',
            'user.logout' => '退出登录',
            'userShare.set' => '用户分享设置',
            'userShare.del' => '用户分享删除',
            'setting.set' => '系统设置查看',
            'setting.systemSetting' => '系统设置编辑保存',
            'setting.phpInfo' => '查看phpinfo',
            'setting.systemTools' => '清除缓存/回收站',
            'fav.add' => '收藏添加',
            'fav.del' => '收藏删除',
            'fav.edit' => '收藏编辑',
            'systemMember.get' => '用户查看',
            'systemMember.add' => '用户添加',
            'systemMember.edit' => '用户编辑',
            'systemMember.doAction' => '用户批量操作',
            'systemMember.getByName' => '用户查看',
            'systemGroup.get' => '用户组查看',
            'systemGroup.add' => '用户组添加',
            'systemGroup.del' => '用户组删除',
            'systemGroup.edit' => '用户组编辑',
            'systemRole.add' => '用户组权限添加',
            'systemRole.del' => '用户组权限删除',
            'systemRole.edit' => '用户组权限编辑',
            'systemRole.roleGroupAction' => '用户组权限列表配置',
            'pluginApp.index' => '插件查看',
            'pluginApp.appList' => '插件列表',
            'pluginApp.changeStatus' => '插件启停',
            'pluginApp.setConfig' => '插件配置',
            'pluginApp.install' => '插件安装',
            'pluginApp.unInstall' => '插件卸载',
        );
    }

    public function record() {

        $role = $this->role();
        $url = ST . '.' . ACT;

        $not_record = [
            'pluginApp.to',
            'user.commonJs'
        ];
        if(in_array($url, $not_record)) return;

        if (isset($role[$url]))
            $title = $role[$url];
        else
            $title = '未定义';

        $user = &$_SESSION['kodUser'];
        $parma = $GLOBALS['in'];

        $data = [
            'admin_id' => $user['userID'],
            'username' => $user['name'],
            'url' => $url,
            'title' => $title,
            'content' => json_encode_force($parma),
            'ip' => get_client_ip(),
            'useragent' => $_SERVER['HTTP_USER_AGENT'],
            'createtime' => time(),
        ];

        $db = $this->db();
        $db->insert('admin_log', $data);
    }

    public function index() {
        include($this->pluginPath . '/php/index.php');
    }

    public function ajaxGetLists() {
        $page = $GLOBALS['in']['page'];
        $db = $this->db();
        $db->pageLimit = $GLOBALS['in']['limit'];

        //搜索参数
        if (!empty($GLOBALS['in']['username'])) $db->where("username", '%' . $GLOBALS['in']['username'] . '%', 'like');
        if (!empty($GLOBALS['in']['title'])) $db->where("title", '%' . $GLOBALS['in']['title'] . '%', 'like');
        if (!empty($GLOBALS['in']['url'])) $db->where("url", '%' . $GLOBALS['in']['url'] . '%', 'like');

        $db->orderBy('id', 'desc');
        $lists = $db->paginate('admin_log', $page);

        $this->show_json($lists, 0, ['count' => $db->totalCount, 'msg' => 'ok']);
    }

    private function db() {
        require_once('MysqliDb.php');
        $config = $this->getConfig();

        $db = new MysqliDb($config['dbHost'],$config['dbUsername'],$config['dbPassword'],$config['dbName'], $config['dbPort'], $config['dbCharset'] );
        $db->setPrefix('fa_');
        return $db;
    }

    private function show_json($data = [], $code = 0, $other = []) {
        if ($GLOBALS['SHOW_JSON_RETURN']) {
            return;
        }
        $useTime = mtime() - $GLOBALS['config']['appStartTime'];
        $result = array('code' => $code, 'use_time' => $useTime, 'data' => $data);

        if (!empty($other)) {
            foreach ($other as $k => $v) {
                $result[$k] = $v;
            }
        }
        ob_end_clean();
        if (!headers_sent()) {
            header("X-Powered-By: kodExplorer.");
            header('Content-Type: application/json; charset=utf-8');
        }
        if (class_exists('Hook')) {
            $temp = Hook::trigger("show_json", $result);
            if (is_array($temp)) {
                $result = $temp;
            }
        }
        $json = json_encode_force($result);
        if (isset($_GET['callback'])) {
            if (!preg_match("/^[0-9a-zA-Z_.]+$/", $_GET['callback'])) {
                die("calllback error!");
            }
            echo $_GET['callback'] . '(' . $json . ');';
        } else {
            echo $json;
        }
        if (!isset($GLOBALS['SHOW_JSON_EXIT']) || !$GLOBALS['SHOW_JSON_EXIT']) {
            exit;
        }
    }

    /*CREATE TABLE `fa_admin_log` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
    `username` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '管理员名字',
    `url` varchar(1500) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '操作页面',
    `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '日志标题',
    `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
    `ip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'IP',
    `useragent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'User-Agent',
    `createtime` int(10) DEFAULT NULL COMMENT '操作时间',
    PRIMARY KEY (`id`),
    KEY `name` (`username`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='管理员日志表';*/
}