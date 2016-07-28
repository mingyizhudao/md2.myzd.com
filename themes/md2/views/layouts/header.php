<?php
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$urlDocView = $this->createUrl('doctor/view');
$showHeader = Yii::app()->request->getQuery('header', 1);
?>
<?php
if ($showHeader == 1) {
    ?>
    <header class="bg-green">
        <?php
        if (isset($addBackBtn) && $addBackBtn == true) {
            echo '<nav class="left"><a href="" data-target="back"><div class="pl5"><img src="http://static.mingyizhudao.com/146968435878253" class="w11p"></div></a></nav>';
        } else {
            echo '<nav class="left" style="display:none;"><a href="" data-target="back"><div class="pl5"><img src="http://static.mingyizhudao.com/146968435878253" class="w11p"></div></a></nav>';
        }
        ?>
        <h1 class="title color-white"><?php echo $this->pageTitle; ?></h1>
        <nav class="right"><a class="header-user" data-target="link" data-icon="user" href="<?php echo $urlDocView ?>"><i class="icon user"></i></a></nav>
    </header>
    <?php
}
?>