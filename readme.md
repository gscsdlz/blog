# 博客项目
## 简介
采用Laravel5.4和Redis开发的NoSQL博客，除此之外并无特殊，纯粹为了学习
Laravel框架和Redis。前端采用AmazeUI。
## 基本功能
- 单用户博客
- 首页支持简易的自定义配置
- 文章可以评论，评论时留下来访者的邮箱可以通知到你填写的邮箱
- 博客支持分类
- 文章编辑基于开源MarkDown
- 基于MIT

## Redis数据库说明
### 1号数据库-用户相关（博主）
### 2号数据库-博客相关
#### BlogID:XXX
> 记录博文内容的散列表，内容的键值对有
title（标题）, textPath（HTML文件路径）, mdtextPath（MD文件路径）,
 type（类型）, time（发布时间）, view（浏览次数）,
  updateTime（更新时间）,updateCount（更新次数）
#### primaryKey
> 记录博客当前到哪个编号了，类似于关系数据库的自增值主码。
#### Types:XXXX
> 记录博客类型与博客的关系，集合类型，主要存储了博客的ID

### 3号数据库-网站配置信息
#### 字符串内容
##### adminMail
> 记录管理员邮箱，用于登录时发送验证码给指定账号。