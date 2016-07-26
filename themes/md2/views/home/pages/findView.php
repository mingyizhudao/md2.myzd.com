<?php
$this->setPageTitle('发现');
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$source = Yii::app()->request->getQuery('app', 1);
if ($source == 1) {
    $this->show_footer = false;
    $urlKaTeEr = $this->createUrl('home/page', array('view' => 'kataer'));
    $urlLuJinsong = $this->createUrl('home/page', array('view' => 'lujinsong'));
    $urlRenShancheng = $this->createUrl('home/page', array('view' => 'renshancheng'));
    $urlMillionWelfare = $this->createUrl('home/page', array('view' => 'millionWelfare'));
    $urlForbes = $this->createUrl('home/page', array('view' => 'forbes'));
    $urlDiDoctor = $this->createUrl('home/page', array('view' => 'diDoctor'));
} else {
    $urlKaTeEr = $this->createUrl('home/page', array('view' => 'kataer', 'app' => 0));
    $urlLuJinsong = $this->createUrl('home/page', array('view' => 'lujinsong', 'app' => 0));
    $urlRenShancheng = $this->createUrl('home/page', array('view' => 'renshancheng', 'app' => 0));
    $urlMillionWelfare = $this->createUrl('home/page', array('view' => 'millionWelfare', 'app' => 0));
    $urlForbes = $this->createUrl('home/page', array('view' => 'forbes', 'app' => 0));
    $urlDiDoctor = $this->createUrl('home/page', array('view' => 'diDoctor', 'app' => 0));
}
?>
<article id="findView_article" class="active" data-scroll="true" data-active="find_footer">
    <div>
        <div>
            <a href="<?php echo $urlKaTeEr; ?>">
                <img src="http://static.mingyizhudao.com/146345307724930" class="w100">
            </a>
        </div>
        <div class="pad10 text-center bg-white">
            卡塔尔王子中国寻医记
        </div>
        <div class="mt10">
            <a href="<?php echo $urlLuJinsong; ?>">
                <img src="http://static.mingyizhudao.com/146345357860329" class="w100">
            </a>
        </div>
        <div class="pad10 text-center bg-white">
            医生访谈陆劲松
        </div>
        <div class="mt10">
            <a href="<?php echo $urlRenShancheng; ?>">
                <img src="http://static.mingyizhudao.com/146345375303173" class="w100">
            </a>
        </div>
        <div class="pad10 text-center bg-white">
            医生访谈任善成
        </div>
        <div class="mt10">
            <a href="<?php echo $urlMillionWelfare; ?>">
                <img src="http://static.mingyizhudao.com/146345400344854" class="w100">
            </a>
        </div>
        <div class="pad10 text-center bg-white">
            百万公益冬日暖阳
        </div>
        <div class="mt10">
            <a href="<?php echo $urlForbes; ?>">
                <img src="http://static.mingyizhudao.com/146345567301652" class="w100">
            </a>
        </div>
        <div class="pad10 text-center bg-white">
            名医主刀CEO入选亚洲年轻领袖榜单
        </div>
        <div class="mt10">
            <a href="<?php echo $urlDiDoctor; ?>">
                <img src="http://static.mingyizhudao.com/146349221777345" class="w100">
            </a>
        </div>
        <div class="pad10 text-center bg-white">
            一键呼叫专家医生，随车上门问诊
        </div>
    </div>
</article>