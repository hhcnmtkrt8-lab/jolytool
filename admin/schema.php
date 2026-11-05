<?php
declare(strict_types=1);

/**
 * 后台可编辑的文字区域。
 *
 * 字段类型：
 *   text     单行文字
 *   textarea 多行文字
 *   lines    多行文字，每行保存为一条记录
 *   rows     可增删的表格（cols 定义列）
 *
 * group.root 默认为 site.{lang}，可指定 company.{lang}。
 */
function site_schema(): array
{
    return [
        'home' => [
            'label' => '首页',
            'groups' => [
                [
                    'title' => '顶部主视觉',
                    'desc' => '打开网站第一屏看到的文字。',
                    'fields' => [
                        ['key' => 'heroEyebrow', 'label' => '顶部小标题', 'type' => 'text'],
                        ['key' => 'heroTitle', 'label' => '主标题', 'type' => 'textarea', 'rows' => 2],
                        ['key' => 'heroLede', 'label' => '介绍文字', 'type' => 'textarea', 'rows' => 3],
                        ['key' => 'heroCta', 'label' => '主按钮文字', 'type' => 'text'],
                        ['key' => 'heroCta2', 'label' => '副按钮文字', 'type' => 'text'],
                    ],
                ],
                [
                    'title' => '顶部规格栏',
                    'desc' => '主视觉右下角的四个小数据。',
                    'fields' => [
                        ['key' => 'heroSpec', 'label' => '规格条目', 'type' => 'rows', 'cols' => ['t' => '主文字', 's' => '说明']],
                    ],
                ],
                [
                    'title' => '材料影像带',
                    'desc' => '首页中间那条全幅图片上的文字。',
                    'fields' => [
                        ['key' => 'macroEyebrow', 'label' => '小标题', 'type' => 'text'],
                        ['key' => 'macroText', 'label' => '说明文字', 'type' => 'textarea', 'rows' => 3],
                        ['key' => 'macroMeta', 'label' => '下方参数（每行一条）', 'type' => 'lines', 'rows' => 3],
                    ],
                ],
                [
                    'title' => '公司数据',
                    'desc' => '数字与说明，例如 2004 / 成立年份。',
                    'fields' => [
                        ['key' => 'facts', 'label' => '数据条目', 'type' => 'rows', 'cols' => ['value' => '数字', 'label' => '说明']],
                    ],
                ],
                [
                    'title' => '关于公司',
                    'desc' => '',
                    'fields' => [
                        ['key' => 'introEyebrow', 'label' => '小标题', 'type' => 'text'],
                        ['key' => 'introTitle', 'label' => '标题', 'type' => 'textarea', 'rows' => 2],
                    ],
                ],
                [
                    'title' => '生产能力',
                    'desc' => '编号 + 标题 + 说明，共三到四条。',
                    'fields' => [
                        ['key' => 'capabilitiesTitle', 'label' => '区块标题', 'type' => 'text'],
                        ['key' => 'capabilities', 'label' => '条目', 'type' => 'rows', 'cols' => ['no' => '编号', 'title' => '标题', 'text' => '说明']],
                    ],
                ],
                [
                    'title' => '产品区块',
                    'desc' => '首页中部的产品区文字。',
                    'fields' => [
                        ['key' => 'productsEyebrow', 'label' => '小标题', 'type' => 'text'],
                        ['key' => 'productsTitle', 'label' => '标题', 'type' => 'text'],
                        ['key' => 'productsLede', 'label' => '说明文字', 'type' => 'textarea', 'rows' => 2],
                        ['key' => 'allProducts', 'label' => '按钮文字', 'type' => 'text'],
                    ],
                ],
                [
                    'title' => '品质管理',
                    'desc' => '',
                    'fields' => [
                        ['key' => 'qualityEyebrow', 'label' => '小标题', 'type' => 'text'],
                        ['key' => 'qualityTitle', 'label' => '标题', 'type' => 'text'],
                        ['key' => 'qualityLede', 'label' => '说明文字', 'type' => 'textarea', 'rows' => 2],
                        ['key' => 'qualityItems', 'label' => '条目', 'type' => 'rows', 'cols' => ['k' => '项目', 'v' => '说明']],
                    ],
                ],
                [
                    'title' => '新闻区块',
                    'desc' => '',
                    'fields' => [
                        ['key' => 'newsEyebrow', 'label' => '小标题', 'type' => 'text'],
                        ['key' => 'newsTitle', 'label' => '标题', 'type' => 'text'],
                        ['key' => 'newsAll', 'label' => '「全部新闻」文字', 'type' => 'text'],
                        ['key' => 'readMore', 'label' => '「查看详情」文字', 'type' => 'text'],
                    ],
                ],
                [
                    'title' => '底部行动号召',
                    'desc' => '每个页面末尾的深色区块。',
                    'fields' => [
                        ['key' => 'ctaTitle', 'label' => '标题', 'type' => 'textarea', 'rows' => 2],
                        ['key' => 'ctaText', 'label' => '说明文字', 'type' => 'textarea', 'rows' => 2],
                        ['key' => 'ctaBtn', 'label' => '按钮文字', 'type' => 'text'],
                        ['key' => 'ctaBtn2', 'label' => '第二个按钮文字', 'type' => 'text'],
                    ],
                ],
            ],
        ],

        'about' => [
            'label' => '关于我们',
            'groups' => [
                [
                    'title' => '页面标题',
                    'desc' => '',
                    'fields' => [
                        ['key' => 'aboutTitle', 'label' => '标题', 'type' => 'text'],
                        ['key' => 'aboutAside', 'label' => '标题旁说明', 'type' => 'textarea', 'rows' => 3],
                    ],
                ],
                [
                    'title' => '公司简介',
                    'desc' => '这段文字同时出现在关于页与页脚，请谨慎修改。',
                    'root' => 'company',
                    'fields' => [
                        ['key' => 'name', 'label' => '公司全称', 'type' => 'text'],
                        ['key' => 'shortName', 'label' => '公司简称', 'type' => 'text'],
                        ['key' => 'intro', 'label' => '简介第一段', 'type' => 'textarea', 'rows' => 4],
                        ['key' => 'intro2', 'label' => '简介第二段', 'type' => 'textarea', 'rows' => 3],
                        ['key' => 'intro3', 'label' => '简介第三段', 'type' => 'textarea', 'rows' => 3],
                    ],
                ],
            ],
        ],

        'products' => [
            'label' => '产品中心',
            'groups' => [
                [
                    'title' => '页面标题',
                    'desc' => '产品列表页顶部的文字，具体产品内容在「产品」菜单中编辑。',
                    'fields' => [
                        ['key' => 'productsPageTitle', 'label' => '标题', 'type' => 'text'],
                        ['key' => 'productsPageAside', 'label' => '标题旁说明', 'type' => 'textarea', 'rows' => 3],
                        ['key' => 'quote', 'label' => '「获取报价」按钮文字', 'type' => 'text'],
                        ['key' => 'relatedTitle', 'label' => '「相关产品」标题', 'type' => 'text'],
                        ['key' => 'backToProducts', 'label' => '「全部产品」文字', 'type' => 'text'],
                        ['key' => 'specNote', 'label' => '规格表下方备注', 'type' => 'textarea', 'rows' => 2],
                    ],
                ],
            ],
        ],

        'news' => [
            'label' => '新闻',
            'groups' => [
                [
                    'title' => '页面标题',
                    'desc' => '新闻内容本身在「新闻」菜单中编辑。',
                    'fields' => [
                        ['key' => 'newsPageTitle', 'label' => '标题', 'type' => 'text'],
                        ['key' => 'newsPageAside', 'label' => '标题旁说明', 'type' => 'textarea', 'rows' => 3],
                        ['key' => 'publishLabel', 'label' => '「发布日期」文字', 'type' => 'text'],
                    ],
                ],
            ],
        ],

        'contact' => [
            'label' => '联系我们',
            'groups' => [
                [
                    'title' => '页面标题',
                    'desc' => '电话、邮箱等具体联络信息在「联系方式」菜单中修改，中英文站共用。',
                    'fields' => [
                        ['key' => 'contactPageTitle', 'label' => '标题', 'type' => 'text'],
                        ['key' => 'contactPageAside', 'label' => '标题旁说明', 'type' => 'textarea', 'rows' => 3],
                        ['key' => 'contactNoteWhatsapp', 'label' => 'WhatsApp 联系人说明', 'type' => 'text'],
                        ['key' => 'contactLabels.tel', 'label' => '「电话」标签', 'type' => 'text'],
                        ['key' => 'contactLabels.fax', 'label' => '「传真」标签', 'type' => 'text'],
                        ['key' => 'contactLabels.email', 'label' => '「邮箱」标签', 'type' => 'text'],
                        ['key' => 'contactLabels.skype', 'label' => '「Skype」标签', 'type' => 'text'],
                        ['key' => 'contactLabels.whatsapp', 'label' => '「WhatsApp」标签', 'type' => 'text'],
                        ['key' => 'contactLabels.address', 'label' => '「地址」标签', 'type' => 'text'],
                        ['key' => 'contactLabels.company', 'label' => '「公司」标签', 'type' => 'text'],
                    ],
                ],
            ],
        ],

        'footer' => [
            'label' => '导航与页脚',
            'groups' => [
                [
                    'title' => '顶部导航',
                    'desc' => '链接地址固定，这里只改显示的文字。',
                    'fields' => [
                        ['key' => 'nav.0.label', 'label' => '第 1 项（首页）', 'type' => 'text'],
                        ['key' => 'nav.1.label', 'label' => '第 2 项（关于）', 'type' => 'text'],
                        ['key' => 'nav.2.label', 'label' => '第 3 项（产品）', 'type' => 'text'],
                        ['key' => 'nav.3.label', 'label' => '第 4 项（新闻）', 'type' => 'text'],
                        ['key' => 'nav.4.label', 'label' => '第 5 项（联系）', 'type' => 'text'],
                        ['key' => 'brandSub', 'label' => 'Logo 旁小字', 'type' => 'text'],
                        ['key' => 'breadcrumbHome', 'label' => '「首页」面包屑文字', 'type' => 'text'],
                    ],
                ],
                [
                    'title' => '页脚',
                    'desc' => '',
                    'fields' => [
                        ['key' => 'footerAbout', 'label' => '公司简介', 'type' => 'textarea', 'rows' => 3],
                        ['key' => 'footerCompany', 'label' => '「公司」栏标题', 'type' => 'text'],
                        ['key' => 'footerProducts', 'label' => '「产品」栏标题', 'type' => 'text'],
                        ['key' => 'footerContact', 'label' => '「联系方式」栏标题', 'type' => 'text'],
                        ['key' => 'footerRights', 'label' => '版权说明', 'type' => 'text'],
                        ['key' => 'footerNote', 'label' => '页脚备注地址', 'type' => 'text'],
                    ],
                ],
            ],
        ],
    ];
}

/** 产品表单字段（中英文各一份）。 */
function product_schema(): array
{
    return [
        ['key' => 'name', 'label' => '产品名称', 'type' => 'text'],
        ['key' => 'short', 'label' => '一句话简介', 'type' => 'text'],
        ['key' => 'keywords', 'label' => '关键词（英文逗号分隔）', 'type' => 'csv', 'help' => '用于搜索优化，一般 3–6 个。'],
        ['key' => 'intro', 'label' => '产品介绍段落', 'type' => 'textarea', 'rows' => 5],
        ['key' => 'heading', 'label' => '特点区块标题', 'type' => 'text'],
        ['key' => 'bullets', 'label' => '产品特点（每行一条）', 'type' => 'lines', 'rows' => 6],
        ['key' => 'usesTitle', 'label' => '用途区块标题', 'type' => 'text'],
        ['key' => 'uses', 'label' => '用途（每行一条）', 'type' => 'lines', 'rows' => 4],
        ['key' => 'specTitle', 'label' => '规格表标题', 'type' => 'text'],
        ['key' => 'spec', 'label' => '规格表内容', 'type' => 'table', 'help' => '第一行是表头，之后每行一条规格，用竖线 | 分隔各列。'],
    ];
}
