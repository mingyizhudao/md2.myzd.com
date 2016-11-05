<?php 
$this->setPageTitle('我的账户');
$urlCardList = $this->createUrl('userbank/cardList', array('addBackBtn' => 1));
$urlAccoutDetail = $this->createUrl('userbank/accountDetail', array('addBackBtn' => 1));
$urlDrawCash = $this->createUrl('userbank/drawCash', array('addBackBtn' => 1));
$urlDoctorView = $this->createUrl('doctor/view');
$urlCreate = $this->createUrl('userbank/create', array('addBackBtn' => 1));
$urlAjaxWithdraw = $this->createUrl('userbank/ajaxWithdraw');

// $isRealAuth = Yii::app()->request->getQuery('isRealAuth', '');
// var_dump($date_update);die;
$this->show_footer = false;
?>
<style>
	#accountList_header nav.right{width:auto!important;background-color: inherit!important;margin-top: 12px;}
    header nav.right {
    background-color: #fff;
    border-radius: 50%;
    width: 1.7em;
    height: 1.7em;
    padding: 0;
    line-height: 1.5em;
    margin-top: 8px;
    margin-right: 10px;
}
header nav.right {
    position: absolute;
    right: 0;
    top: 0;
    z-index: 2;
}
.b-h{border: 1px solid #D3D3D3;border-radius: 5px;}
.c-h{color:#D3D3D3;}
.btn-c1{background: #F58124;}
.btn-c2{background: #FFB45D;}
</style>
<header class="bg-green" id="accountList_header">
    <nav class="left">
        <a href="<?php echo $urlDoctorView; ?>" data-target="link">
            <div class="pl5">
                <img src="http://static.mingyizhudao.com/146968435878253" class="w11p">
            </div>
        </a>

    </nav>
    <h1 class="title">我的银行卡</h1>
    <nav class="right">
        <a href="javascript:;" id="card">银行卡</a>
   </nav>
</header>
<article id="accountList_article" class="active" data-scroll="true">
 <div class="mt20 pad10">
 	<div class="pad10 b-h">
 		<div class="pl10 grid">
 			<div class="col-0 text-right font-w800"style="width:40%">累计总收入：</div>
 			<div class="col-1 text-right"style="color:#32c9c0;">￥<?php echo $count;?></div>
 		</div>
 		<div class="pl10 grid pt10">
 			<div class="col-0  pt10 text-right pr5 font-w800"style="width:40% ;font-size:13px;">已提现金额：</div>
 			<div class="col-1 text-right pt10"style="color:#32c9c0;">￥<?php echo $cash;?></div>
 		</div>
 	</div>
 	<div class="pt10 text-right font-s12 c-h">
 		*更新日期为<?php echo $date_update;?>
 	</div>
 	<div class="mt50">
 		<a href="<?php echo $urlAccoutDetail;?>"><button class="btn btn-block btn-c1">查看详情</button></a>
 	</div>
 	<div class="mt20">
 		<button class="btn btn-block btn-c2"id="drawCash">申请提现</button>
 	</div>
 </div>
</article>
<script>
	$(function(){
        var cardBind='<?php echo $cardBind;?>';
        
       $("#drawCash").click(function(){
        $.ajax({
            url:'<?php echo $urlAjaxWithdraw;?>',
            success:function(data){
                // console.log(data);
                if(data.code==1){
                     J.showToast(data.msg);
                }else{
                    location.href='<?php echo $urlDrawCash;?>';
                }
            }
        })

       });

       $("#card").click(function(){
        if(cardBind!=1){
            location.href="<?php echo $urlCreate;?>" ;
        }else{
           location.href="<?php echo $urlCardList;?>" ;
        }
       })
	})
</script>