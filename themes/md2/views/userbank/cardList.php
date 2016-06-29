<?php
$this->setPageTitle('我的银行卡');
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$urlAjaxCardList = $this->createUrl('userbank/ajaxCardList');
$urlCreate = $this->createUrl('userbank/create', array('addBackBtn' => 1));
$urlViewInputKey = $this->createUrl('userbank/viewInputKey', array('addBackBtn' => 1, 'type' => 1, 'id' => ''));
?>
<div id="section_container" <?php echo $this->createPageAttributes(); ?> >
    <section id="main_section" class="active">
        <?php
        if (isset($data) && !(is_null($data)) && (count($data->results->cards) > 0)) {
            ?>
            <footer class="bg-white">
                <a href="<?php echo $urlCreate; ?>" class="btn btn-block bg-yellow color-white" data-target="link">新增银行卡</a>
            </footer>
            <?php
        }
        ?>
        <article id="cardList_article" class="active" data-scroll="true">
            <div class="pad10">
                <div id="cardList">
                    <?php
                    if (isset($data) && !(is_null($data))) {
                        $cards = $data->results->cards;
                        for ($i = 0; $i < count($cards); $i++) {
                            $card = $cards[$i];
                            ?>
                            <div class="cardBg">
                                <div class="grid">
                                    <div class="col-1">
                                        <?php echo $card->bank; ?>
                                    </div>
                                    <div class="col-0">
                                        <a href="<?php echo $urlViewInputKey; ?>/<?php echo $card->id; ?>" class="color-white">修改</a>
                                    </div>
                                </div>
                                <div class="pt20 pb20">
                                    <?php echo $card->cardNo; ?>
                                </div>
                                <div class="grid">
                                    <div class="col-1">
                                        持卡人：<?php echo $card->name; ?>
                                    </div>
                                    <div class="col-0">
                                        <?php
                                        if ($card->isDefault == 1) {
                                            echo '默认';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <?php
                if (isset($data) && !(is_null($data)) && (count($data->results->cards) == 0)) {
                    ?>
                    <div class="text-center pt20">
                        <img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146659004210229" class="w170p">
                    </div>
                    <div class="text-center pt10">
                        <div>
                            请您提供正确的银行卡信息，
                        </div>
                        <div>
                            名医主刀将用作为和您结账的工具。
                        </div>
                    </div>
                    <div class="pt30">
                        <a href="<?php echo $urlCreate; ?>" class="btn btn-full bg-yellow color-white" data-target="link">添加银行卡</a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </article>
    </section>
</div>