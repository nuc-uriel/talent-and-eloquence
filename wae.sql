CREATE TABLE `wae_user` (
    id int unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
    wx_id varchar(32) NOT NULL COMMENT '微信openid',
    wx_name varchar(32) NOT NULL COMMENT '微信昵称',
    wx_avatar varchar(128) NOT NULL COMMENT '微信头像',
    wx_gender tinyint(3) NOT NULL COMMENT '微信性别',
    wx_address varchar(128) NOT NULL COMMENT '微信地址(country:province:city)',
    wx_session_key varchar(32) NOT NULL COMMENT '微信session_key',
    wx_country_code varchar(8) NOT NULL DEFAULT '' COMMENT '微信绑定区号',
    wx_phone_number varchar(16) NOT NULL DEFAULT '' COMMENT '微信绑定手机号',
    u_name varchar(32) NOT NULL DEFAULT '' COMMENT '用户自定义昵称(优先展示)',
    u_avatar varchar(128) NOT NULL DEFAULT '' COMMENT '用户自定义头像(优先展示)',
    u_type tinyint(5) NOT NULL DEFAULT '2' COMMENT '用户类型 0-超级管理员 1-管理员(讲师) 2-普通用户(学员)',
    u_pass varchar(40) NOT NULL DEFAULT '' COMMENT '用户密码(sha1加密)',
    u_key varchar(40) NOT NULL COMMENT '用户登录凭证',
    created_at TIMESTAMP NOT NULL COMMENT '最后登录时间',
    updated_at TIMESTAMP NOT NULL COMMENT '创建时间',
    PRIMARY KEY (id),
    KEY (u_key),
    KEY (wx_id)
) ENGINE=InnoDB COMMENT='用户信息表';


CREATE TABLE `wae_course` (
	id int unsigned NOT NULL AUTO_INCREMENT COMMENT '课程ID',
	u_id int unsigned NOT NULL COMMENT '用户ID',
	c_name varchar(32) NOT NULL COMMENT '课程名称',
	c_intro varchar(32) NOT NULL COMMENT '课程介绍',
	created_at TIMESTAMP NOT NULL COMMENT '创建时间',
    updated_at TIMESTAMP NOT NULL COMMENT '修改时间',
    is_delete tinyint(3) NOT NULL DEFAULT 0 COMMENT '是否删除，默认0(未删除)',
    PRIMARY KEY (id)
) ENGINE=InnoDB COMMENT='课程信息表';


CREATE TABLE `wae_theme` (
	id int unsigned NOT NULL AUTO_INCREMENT COMMENT '主题ID',
	c_id int unsigned NOT NULL COMMENT '课程ID',
	t_name varchar(32) NOT NULL COMMENT '主题名称',
	t_date varchar(10) NOT NULL COMMENT '主题日期',
	t_detail mediumtext NOT NULL COMMENT '主题内容',
	created_at TIMESTAMP NOT NULL COMMENT '创建时间',
    updated_at TIMESTAMP NOT NULL COMMENT '修改时间',
    is_delete tinyint(3) NOT NULL DEFAULT 0 COMMENT '是否删除，默认0(未删除)',
	PRIMARY KEY (id),
	KEY (c_id)
)ENGINE=InnoDB COMMENT='主题信息表';


CREATE TABLE `wae_sign` (
	id int unsigned NOT NULL AUTO_INCREMENT COMMENT '打卡ID',
	t_id int unsigned NOT NULL COMMENT '主题ID',
	u_id int unsigned NOT NULL COMMENT '用户ID',
	s_detail varchar(10000) NOT NULL DEFAULT '' COMMENT '打卡内容',
	s_is_choiceness tinyint(3) NOT NULL DEFAULT 0 COMMENT '是否精选，默认0(非精选)',
	is_delete tinyint(3) NOT NULL DEFAULT 0 COMMENT '是否删除，默认0(未删除)',
	created_at TIMESTAMP NOT NULL COMMENT '创建时间',
    updated_at TIMESTAMP NOT NULL COMMENT '修改时间',
	PRIMARY KEY (id),
	KEY (t_id),
	KEY (u_id)
)ENGINE=InnoDB COMMENT='打卡表';


CREATE TABLE `wae_comment_like` (
	id int unsigned NOT NULL AUTO_INCREMENT COMMENT '评论ID',
	s_id int unsigned NOT NULL COMMENT '打卡ID',
	u_id int unsigned NOT NULL COMMENT '用户ID',
	cl_type tinyint(3) NOT NULL COMMENT '评论1，点赞0',
	cl_detail varchar(10000) NOT NULL DEFAULT '' COMMENT '评论内容',
	cl_voice varchar(128) NOT NULL DEFAULT '' COMMENT '评论录音',
	is_delete tinyint(3) NOT NULL DEFAULT 0 COMMENT '是否删除，默认0(未删除)',
	created_at TIMESTAMP NOT NULL COMMENT '创建时间',
    updated_at TIMESTAMP NOT NULL COMMENT '修改时间',
    PRIMARY KEY (id),
    KEY (s_id),
    KEY (u_id)
)ENGINE=InnoDB COMMENT='评论点赞表';


CREATE TABLE `wae_adjunct` (
	id int unsigned NOT NULL AUTO_INCREMENT COMMENT '附件ID',
	s_id int unsigned NOT NULL COMMENT '打卡ID',
	a_type tinyint(3) NOT NULL COMMENT '附件类型：录音0，图片1',
	a_path varchar(128) NOT NULL COMMENT '附件路径',
	is_delete tinyint(3) NOT NULL DEFAULT 0 COMMENT '是否删除，默认0(未删除)',
	created_at TIMESTAMP NOT NULL COMMENT '创建时间',
    updated_at TIMESTAMP NOT NULL COMMENT '修改时间',
    PRIMARY KEY (id),
    KEY (s_id)
);


