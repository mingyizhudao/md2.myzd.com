<?php
$this->setPageTitle('公益医生');
$urlViewDoctor = $this->createUrl('doctor/viewDoctor');
$this->show_footer=false;
?>
<article id="commonwealList_article" class="active" data-scroll="true">
    <div class="pageBg">
        <?php
        if (isset($data->results->page[1]) && !(is_null($data->results->page[1]))) {
            $doctorList = $data->results->page[1];
            for ($i = 0; $i < count($doctorList); $i++) {
                ?>
                <div class="bg-white mt10 border-grayD2">
                    <a href="<?php echo $urlViewDoctor; ?>/id/<?php echo $doctorList[$i]->id; ?>/addBackBtn/1" class="color-black10">
                        <div class="pb10">
                            <div class="grid pl15 pr15 pb10 pt10">
                                <div class="col-1 w25">
                                    <div class="w60p h60p br50" style="overflow:hidden;">
                                        <img class="imgDoc" src="<?php echo $doctorList[$i]->imageUrl; ?>">
                                    </div>
                                </div>
                                <div class="ml10 col-1 w75">
                                    <div class="grid">
                                        <div class="col-0 font-s18 font-w800"><?php echo $doctorList[$i]->name; ?><span class="ml5 font-s16"><?php echo $doctorList[$i]->aTitle; ?></span></div>
                                    </div>
                                    <div class="color-black6"><?php echo $doctorList[$i]->hpDeptName; ?><span class="ml5"><?php echo $doctorList[$i]->mTitle; ?></span></div>
                                    <div class="color-black6"><?php echo $doctorList[$i]->hpName; ?></div>
                                </div>
                            </div>
                            <div class="ml10 mr10 pad10 bg-gray2 text-justify">擅长：<?php echo mb_substr($doctorList[$i]->desc, 0, 45, 'utf-8'); ?>...</div>
                        </div>
                    </a>
                </div>
                <?php
            }
        }
        ?>
    </div>
</article>