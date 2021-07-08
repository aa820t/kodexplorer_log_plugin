CREATE TABLE `fa_admin_log` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
    `username` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户名',
    `url` varchar(1500) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '操作地址',
    `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '操作标题',
    `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
    `ip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'IP',
    `useragent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'User-Agent',
    `createtime` int(10) DEFAULT NULL COMMENT '操作时间',
    PRIMARY KEY (`id`),
    KEY `name` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='操作日志表';