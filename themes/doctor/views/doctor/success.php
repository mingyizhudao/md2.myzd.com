<!DOCTYPE html> 
<html lang="zh" xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no" />
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta charset="utf-8" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="pragma" content="no-cache" />
        <title>医生</title>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
        <?php
        Yii::app()->clientScript->registerCssFile('http://static.mingyizhudao.com/common.min.1.0.css');
        Yii::app()->clientScript->registerCssFile('http://static.mingyizhudao.com/custom.min.1.1.css');
        Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/zepto.min.1.0.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/common.min.1.0.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/main.min.1.0.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerCssFile('http://static.mingyizhudao.com/common.min.1.1.css');
        Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/custom.min.1.0.js', CClientScript::POS_END);
        ?>
    </head>
    <style>
        h1{
            padding-bottom: 0px;
        }
        .w90p{width:90px;}
        article{
            background: url('http://static.mingyizhudao.com/147159553773750') no-repeat;
            background-size: 100% 100%;
        }
    </style>
    <body>
        <div id="section_container">
            <section id="main_section" class="active" data-init="true">
                <header class="bg-green">
                    <h1 class="title">医生</h1>
                </header>
                <article class="active" data-scroll="true">
                    <div class="pt50">
                        <div class="text-center pt50">
                            <img src="http://static.mingyizhudao.com/14715956178660" class="w90p">
                        </div>
                        <div class="pt10 text-center font-s16">
                            感谢您的耐心填写
                        </div>
                    </div>
                </article>
            </section>
        </div>
    </body>
</html>