
create table if not exists `sea_user` (
 `id` INT UNSIGNED AUTO_INCREMENT,
 `username` varchar(16) not null unique,
 `password` varchar(32) not null,
 `coinPassword` varchar(32) not null,
 `nickName` varchar(16) ,
 `headLogo` varchar(32) not null,
 `qq` varchar(16) not null,
 `coin` decimal(10,2) not null default '0',
 `fcoin` decimal(10,2) not null default '0',
 `online` tinyint not null default '0',
 `loginTime` int not null default '0',
 `parentId` int not null default '0',
 `agency` tinyint not null,
  PRIMARY KEY (`id`)
);


create table if not exists `sea_userBank`(
 `id` int unsigned auto_increment,
 `uid` int not null,
 `enable` tinyint not null default '1',
 `bankId` int not null,
 `name` varchar(16) not null,
 `account` varchar(50) not null,
 `counName` varchar(255) not null,
 PRIMARY KEY (`id`)
);

create table if not exists `sea_bank`(
 `id` int unsigned auto_increment,
 `bankName` varchar(32) not null,
 `bankLogo` varchar(32) not null,
 `home` varchar(32) not null,
 `isDelete` tinyint not null default '1',
 PRIMARY KEY (`id`)
);

create table if not exists `sea_admin`(
 `id` int unsigned auto_increment,
 `username` varchar(16) not null unique,
 `password` varchar(32) not null,
 `start` tinyint not null default '1',
 `level` tinyint not null default '1',
 PRIMARY KEY (`id`)
);


create table if not exists `sea_dataTime`(
 `id` int unsigned auto_increment,
 `actionNo` varchar(32) not null,
 `actionTime` varchar(21) not null,
 PRIMARY KEY (`id`)
);


create table if not exists `sea_bets`(
 `id` int unsigned auto_increment,
 `uid` int not null,
 `playedId` tinyint not null,
 `actionNo` varchar(32) not null,
 `actionData` varchar(10) not null,
 `kjData` varchar(10) not null default ' ',
 `kjTime` varchar(50) not null default ' ',
 `orderId` varchar(32) not null,
 `boss` int not null default '0',
 PRIMARY KEY (`id`)
);



create table if not exists `sea_boss`(
 `id` int unsigned auto_increment,
 `uid` int not null,
 `actionNo` varchar(32) not null,
 `state` tinyint not null default '0',
 PRIMARY KEY (`id`) 
);


create table if not exists `sea_played`(
 `id` int unsigned auto_increment,
 `name` varchar(32) not null,
 `odds` int not null,
 `ruleFun` varchar(32) not null,
 PRIMARY KEY (`id`)
);


create table if not exists `sea_type`(
 `id` int unsigned auto_increment,
 `title` varchar(50) not null,
 `info` varchar(1000) not null,
 `onGetNode` varchar(20) not null,
 `data_ftime` int not null,
 `num` int not null,
 PRIMARY KEY (`id`)
);

create table if not exists `sea_data`(
 `id` int unsigned auto_increment,
 `time` varchar(32) not null,
 `number` varchar(32) not null,
 `data` tinyint not null,
 PRIMARY KEY (`id`)
);

create table if not exists `sea_system`(
 `id` int unsigned auto_increment,
 `key` varchar(32) not null,
 `value` varchar(32) not null,
 `default` varchar(32) not null,
  primary key(`id`)
);

insert into sea_system (`key`,`value`,`default`) values ('webName','鱼虾蟹','网站名称');
insert into sea_system (`key`,`value`,`default`) values ('daili','1','代理投注开关');
insert into sea_system (`key`,`value`,`default`) values ('boss','1','抢庄开关');
insert into sea_system (`key`,`value`,`default`) values ('maxamount','1000','最大下注');
insert into sea_system (`key`,`value`,`default`) values ('minamount','10','最小下注');
insert into sea_system (`key`,`value`,`default`) values ('maxrecharge','50000','最大充值');
insert into sea_system (`key`,`value`,`default`) values ('minrecharge','10','最小充值');
insert into sea_system (`key`,`value`,`default`) values ('maxdeposit','50000','最大提现金额');
insert into sea_system (`key`,`value`,`default`) values ('mindeposit','10','最小提现金额');
insert into sea_system (`key`,`value`,`default`) values ('kefu','-','客服地址');
insert into sea_system (`key`,`value`,`default`) values ('webState','1','网站开关');