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
### 0号数据库 Session相关自动设置
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
#### BlogIDIndex
> 列表类型记录了博客的ID用于快速分页，主要为了避免遍历
BlogID键
#### TypeNavbar
> 有序集合，用于记录首页导航条显示的菜单分类及其对应的顺序和其BlogID，现在用了有序集合
可能会换成列表
