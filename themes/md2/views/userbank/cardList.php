<?php
$this->setPageTitle('我的银行卡');
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$urlAjaxCardList = $this->createUrl('userbank/ajaxCardList');
$urlCreate = $this->createUrl('userbank/create', array('addBackBtn' => 1));
$urlViewInputKey = $this->createUrl('userbank/viewInputKey', array('addBackBtn' => 1, 'type' => 1, 'id' => ''));
$urlAjaxDelete = $this->createUrl('userbank/ajaxDelete');
$urlDoctorView = $this->createUrl('doctor/view');
$card_id=Yii::app()->request->getQuery('card_id', '');
$urlAjaxCreate = $this->createUrl('userbank/ajaxCreate');
$urlCardList = $this->createUrl('userbank/cardList', array('addBackBtn' => 1));
$this->show_footer = false;
// var_dump($data);die;
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
    <?php if (isset($data) && !(is_null($data)) && (count($data->results->cards) > 0)) { ?>
    <nav class="right">
        <a id="editCard" href="javascript:;">编辑</a>
        <a id="cancelCard" class="hide" href="javascript:;">取消</a>
    </nav>
    <?php }?>
</header>
<?php
if (isset($data) && !(is_null($data)) && (count($data->results->cards) > 0)) {
    ?>
    <footer class="bg-white"id="addCard">
        <a href="<?php echo $urlCreate; ?>" class="btn btn-block bg-yellow color-white" data-target="link">新增银行卡</a>
       <!--  -->
    </footer>
     <footer class="bg-white hide "id="delCard">
        <div class="btn btn-block bg-yellow color-white ">解除绑定</div>
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
                    <div class="grid middle">
                        <?php if($card->isDefault==1){?>
                        
                        <div class="col-0 pr10 hide selectBtn" data-active="1"data-id="<?php echo $card->id;?>">
                        <img src="http://static.mingyizhudao.com/147625968339249" width="21" >
                        </div>
                    <?php }else if($card->id==$card_id){?>
                         <div class="col-0 pr10 hide selectBtn" data-active="1"data-id="<?php echo $card->id;?>">
                        <img src="http://static.mingyizhudao.com/147625968339249" width="21" >
                        </div>
                        <?php }else { ?>
                        <div class="col-0 pr10 hide selectBtn"data-id="<?php echo $card->id;?>">
                        <img src="http://static.mingyizhudao.com/147625867170645" width="21">
                        </div>
                        <?php }?>
                     
                     <div class="cardBg col-1 ">
                       <div class='col-1 grid vertical'>
                            <div class="col-1 grid">
                                <div class="col-1 ">
                                  <img src="http://static.mingyizhudao.com/14762545733435" width="27">
                                  <span class=""><?php echo $card->bank; ?></span> 
                                  <a href="<?php echo $urlViewInputKey; ?>/<?php echo $card->id; ?>" class="color-white">修改</a> 
                                </div> 
                                <div class="col-0">
                                  <?php if($card->isDefault==1){?>
                                  <div class="color-white current"data-active="1" data-id="<?php echo $card->id;?>"><img src="http://static.mingyizhudao.com/14762566281804"width="16"><span class="pl5">当前使用</span></div>
                                  <?php }else if($card->id==$card_id){?>
                                  <div class="color-white current"data-active="1" data-id="<?php echo $card->id;?>"><img src="http://static.mingyizhudao.com/14762566281804"width="16"><span class="pl5">当前使用</span></div>
                                  <?php }else{?>
                                   <div class="color-white current" data-id="<?php echo $card->id;?>"><img src="http://static.mingyizhudao.com/147633795597639"width="16"><span class="pl5">使用该卡</span></div>
                                  <?php }?>  
                                </div>
                            </div>
                            <div class="col-1 pt40"><?php echo $card->cardNo; ?></div>卡
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
                <img src="http://static.mingyizhudao.com/146659004210229" class="w170p">
            </div>
            <!-- <div class="text-center pt10">
                <div>
                    请您提供正确的银行卡信息，
                </div>
                <div>
                    名医主刀将用作为和您结账的工具。
                </div>
            </div> -->
            <div class="pt50">
                <a href="<?php echo $urlCreate; ?>" class="btn btn-full bg-yellow color-white" data-target="link">新增银行卡</a>
            </div>
            <div class="pt30 font-s12 "style="color:#909090;">
                <div >* 为什么要添加银行卡？</div>
                <div class="text-justify ">名医主刀会将每日结算金融打入您提供的银行卡里，请您务必提供真实有效的信息</div>
            </div>
            
            <?php
        }
        ?>
    </div>
</article>
<script>
    $(function(){
        
      var card_id='<?php echo $card_id;?>';
      if(card_id){
      //   $(".selectBtn").each(function(){
      //      $(this).find('img').attr('src','http://static.mingyizhudao.com/147625867170645'); 
      //   });
      //   $(".current").each(function(){
      //        $(this).find('img').attr('src', 'http://static.mingyizhudao.com/147633795597639');
      //           $(this).removeAttr('data-active');
      //            $(this).find('span').html('使用该卡');
      //   })
              J.customConfirm('',
                        '<div class="mt10 mb10">是否将您最新添加的银行卡设为默认卡片(名医主导将与此卡发生结算)</div>',
                        '<a id="pause" class="w50">咱不</a>',
                        '<a id="ok" class="color-green w50">是的</a>',
                        function () {
                        },
                        function () {
                        });
             $('#pause').click(function () {
                    J.closePopup();
                });
             $('#ok').click(function(){
                $('.selectBool').each(function(){
                    if($(this).attr('data-id')==card_id){
                      $(this).find('img').attr('src','http://static.mingyizhudao.com/147625968339249');
                    }
                })
                $(".current").each(function(){
                    if($(this).attr('data-id')==card_id){

                      $(this).find('img').attr('src', 'http://static.mingyizhudao.com/14762566281804');
                      $(this).attr('data-active', 1);
                      $(this).find('span').html('当前使用');
                    }
                })

                
                J.closePopup();
             })
               
      }
        //编辑
        $("#editCard").click(function(){
          
           $(this).addClass('hide');
            $("#cancelCard").removeClass('hide');
            $(".selectBtn").removeClass('hide');
            $('#addCard').addClass('hide');
            $("#delCard").removeClass('hide');

        });
        //取消
        $("#cancelCard").click(function(){
            $(this).addClass('hide');
            $("#editCard").removeClass('hide');
            $("#addCard").removeClass('hide');
            $("#delCard").addClass('hide');
            $(".selectBtn").addClass('hide');
        })
        //解除
        $("#delCard").click(function(){

            var cardList=new Array();
            var selectBool=false;
            var i=0;
            $('.selectBtn').each(function(){
                if($(this).attr('data-active')==1){
                    selectBool=true;
                    i++;
                    var id=$(this).attr('data-id');
                    cardList.push(id);
                }
            });
            if(i==1){
                J.showToast('请选择要使用的卡片');
            }else if(selectBool){
                console.log(cardList);
                var urlAjaxDelete='<?php echo $urlAjaxDelete;?>';
                // console.log('aa',urlAjaxDelete);
                $.ajax({
                    type:'post',
                    url:'<?php echo $urlAjaxDelete?>',
                    data:{ids:cardList},
                    success:function(data){
                         // console.log('dd',data);
                         if(data.status=='ok'){
                            location.href='<?php echo $urlCardList?>/'+card_id;
                         }
                    }
                })
            }
           
      })


        
        $('.selectBtn').click(function(){
            var dataid=$(this).attr('data-id');
          if($(this).attr('data-active')==1){
            $(this).find('img').attr('src','http://static.mingyizhudao.com/147625867170645');
            $(this).removeAttr('data-active');
            // if($(".current").attr('data-id')==dataid){
            //      $(this).find('img').attr('src', 'http://static.mingyizhudao.com/147633795597639');
            //     $(this).removeAttr('data-active');
            //      $(this).find('span').html('使用该卡');
            // }
          } else{
            $(this).find('img').attr('src','http://static.mingyizhudao.com/147625968339249');
            $(this).attr('data-active','1');
          //   if($(".current").attr('data-id')==dataid){
          //       $(this).find('img').attr('src', 'http://static.mingyizhudao.com/14762566281804');
          //       $(this).attr('data-active', 1);
          //       $(this).find('span').html('当前使用');
          // }
      }
        });   

        $('.current').click(function() {
            var dataid=$(this).attr('data-id');
            if ($(this).attr('data-active') == 1) {
                $('.current').each(function() {
                    $(this).removeAttr('data-active');
                    $(this).find('img').attr('src', 'http://static.mingyizhudao.com/147633795597639');
                    $(this).find('span').html('使用该卡');
                });
                $(this).find('img').attr('src', 'http://static.mingyizhudao.com/147633795597639');
                $(this).removeAttr('data-active');
                 $(this).find('span').html('使用该卡');
                 $('.selectBtn').each(function(){
                    $(this).find('img').attr('src','http://static.mingyizhudao.com/147625867170645');
                    $(this).removeAttr('data-active');
                    if($(this).attr('data-id')==dataid){
                         $(this).find('img').attr('src','http://static.mingyizhudao.com/147625867170645');
                         $(this).removeAttr('data-active');
                    }
                 })
            } else {
                patientId = $(this).attr('data-id');
                $('.current').each(function() {
                  $(this).removeAttr('data-active');
                    $(this).find('img').attr('src', 'http://static.mingyizhudao.com/147633795597639');
                    $(this).find('span').html('使用该卡');
                });
                $(this).find('img').attr('src', 'http://static.mingyizhudao.com/14762566281804');
                $(this).attr('data-active', 1);
                $(this).find('span').html('当前使用');
                
                $('.selectBtn').each(function(){
                     $(this).find('img').attr('src','http://static.mingyizhudao.com/147625867170645');
                     $(this).removeAttr('data-active');
                    if($(this).attr('data-id')==dataid){
                         $(this).find('img').attr('src','http://static.mingyizhudao.com/147625968339249');
                     $(this).attr('data-active','1');

                    }
                 })
            }
        });

    })
</script>