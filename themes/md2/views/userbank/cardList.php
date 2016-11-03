<?php
$this->setPageTitle('我的银行卡');
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$urlAjaxCardList = $this->createUrl('userbank/ajaxCardList');
$urlCreate = $this->createUrl('userbank/create', array('addBackBtn' => 1));
$urlViewInputKey = $this->createUrl('userbank/viewInputKey', array('addBackBtn' => 1, 'type' => 1, 'id' => ''));
$urlDoctorView = $this->createUrl('doctor/view');
 $urlAjaxDelete = $this->createUrl('userbank/ajaxDelete');
$urlCardList = $this->createUrl('userbank/cardList', array('addBackBtn' => 1));
$a=count($data->results->cards);

$cardId = Yii::app()->request->getQuery('card_id', '');
// var_dump($data);die;
$this->show_footer = false;

?>

<header class="bg-green" id="patientList_header">
    <nav class="left">
        <a href="<?php echo $urlDoctorView; ?>" data-target="link">
            <div class="pl5">
                <img src="http://static.mingyizhudao.com/146968435878253" class="w11p">
            </div>
        </a>

    </nav>
    <h1 class="title">我的银行卡</h1>

    <?php if (isset($data) && !(is_null($data))&&(count($data->results->cards) > 0) ) { ?>
        <nav class="right">
        <a id="delCard" href="javascript:;">删除</a>
   </nav>
    <?php }?>
</header>
<?php
if (isset($data) && !(is_null($data))&&(count($data->results->cards) > 0) ) {

    ?>
    <footer class="bg-white ">
        <a href="#" class="btn btn-block bg-yellow color-white"id="change" data-target="link">更换银行卡</a>
    </footer>
    <?php
}
?> 
<article id="cardList_article" class="active" data-scroll="true">
    <div class="pad10">
        <div id="cardList">
            <?php
            if (isset($data) && !(is_null($data))&&(count($data->results->cards) > 0)) {
                $cards = $data->results->cards;
                for ($i = 0; $i < count($cards); $i++) {
                    $card = $cards[$i];
                    ?>
                    <div class="cardBg">
                        <div class="grid">
                            <div class="col-1 select"data-active="1"data-id="<?php echo $card->id;?>">
                                <?php echo $card->bank; ?>
                            </div>
                           
                        </div>
                        <div class="pt30 pb10">
                            <?php echo $card->cardNo; ?>
                        </div>
                       
                    </div>
                    <?php
                }
            }
            ?>
        </div>

        <?php
        if (isset($data) && !(is_null($data))&&(count($data->results->cards) == 0) ) {

            ?>
            <div class="text-center pt20">
                <img src="http://static.mingyizhudao.com/146659004210229" class="w170p">
            </div>
            
            <div class="pt30">
                <a href="<?php echo $urlCreate; ?>"id="btn" class="btn btn-full bg-yellow color-white" data-target="link">新增银行卡</a>
            </div>
            <div class="pt30 font-s12 "style="color:#909090;">
                <div >* 为什么要添加银行卡？</div>
                <div class="text-justify ">名医主刀会将每日结算金融打入您提供的银行卡里，请您务必提供真实有效的信息</div>
            </div
            <?php
        }
        ?>
    </div>
</article>
<script>
    $(function(){
       
        var length='<?php echo count($data->results->cards);?>';
       if(length>0){
        $("#delCard").click(function(){
             J.customConfirm('',
                        '<div class="mt10 mb10">是否删除该卡</div>',
                        '<a id="del" class="w50">确认删除</a>',
                        '<a id="close" class="color-green w50">关闭弹框</a>',
                        function () {
                        },
                        function () {
                        });
             $("#close").click(function(){
                J.closePopup();
             });
            
        $("#del").click(function(){ 
               var cardList=new Array();
               $('.select').each(function(){
                if($(this).attr('data-active')==1){
                    var id=$(this).attr('data-id');
                    cardList.push(id);
                }
            })
            
               $.ajax({
                 type:'post',
                 url:'<?php echo $urlAjaxDelete?>',
                 data:{ids:cardList},
                 success: function (data) {
                    console.log('ss',data);
                    if (data.status == 'ok') {
                        J.hideMask();
                        J.showToast('删除成功', '', '1500');
                        setTimeout(function () {
                           location.href = '<?php echo $urlCardList; ?>';
                            // location.reload();
                        }, 1500);
                    } else {
                        J.hideMask();
                        J.showToast(data.errors, '', '1500');
                    }
                },
                error: function (XmlHttpRequest, textStatus, errorThrown) {
                    enable(btnDelete);
                    console.log(XmlHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });

            });
        })
     //更换银行卡
     $("#change").click(function(){
        location.href="<?php echo $urlCreate;?>";
            
     });

      }
      
    })
</script>