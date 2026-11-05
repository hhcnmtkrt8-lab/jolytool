# 南通高朋建材（GOPEX）官网

英文站 + 中文站的企业官网，纯静态多页站点（HTML / CSS / JS），可直接托管到 GitHub Pages 或原站服务器。

- 英文站：`/`
- 中文站：`/cn/`

## 页面结构

- 首页、关于我们、产品中心、新闻资讯、联系我们（中英各一套）
- 8 个产品详情页：玻纤自粘胶带、玻纤自粘网格布、玻纤网格布、金属护角带、接缝纸带、玻纤水泥板胶带、玻纤毡带、金属补墙板
- 1 篇新闻详情：第 122 届广交会

页面文案与规格表取自原站 asiagaopeng.com 的中英文内容，只做了排版与标点规范化，未改动数据。

## 目录

```
assets/css/main.css     设计系统与全部样式
assets/js/main.js       导航、滚动显现等交互
assets/img/             图片资源
tools/content.mjs       中英文案与产品数据（唯一内容源）
tools/build.mjs         生成全部 HTML 页面
```

## 修改内容后重新生成

```bash
node tools/build.mjs
```

生成结果会覆盖根目录与 `cn/` 下的 HTML，请勿直接手改生成的 HTML。

## 产品图片

每个产品默认使用 `assets/img/products/<slug>.svg` 矢量产品图。拿到实拍照片后，直接在 `assets/img/products/` 放入同名 `.jpg`，重新执行构建即可自动优先使用照片，无需改代码。

产品图同时用于：产品中心网格、首页/相关产品列表缩略图、产品详情主图。

## 本地预览

```bash
npx serve .
# 或任意静态服务器，例如 python -m http.server
```
