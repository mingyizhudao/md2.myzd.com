<?php
$this->setPageTitle('查看详情');
$this->show_footer = false;
?>
<style>
    .bb-g{border-bottom: 2px solid #32c9c0;}
    .c-h{color:#999;}
</style>

<article id="detail_article" class="active bg-gray" data-scroll="true">
    <div class="bg-white   grid">
       <div class="col-1 w50 text-center pt10 pb10 bb-g"id="showAll">全部</div> 
       <div class="col-1 w50 text-center pt10 pb10"id="showDrawing">已提现</div> 
    </div>
    <div class="pl10 pr10 mt10 "id="allList">
        <?php for($i=0;$i<count($total);$i++){
             $data=$total[$i];
            ?>
        <div class="mt10">
            <div class="bg-white pad10 grid">
                <div class="w50 col-1"><?php echo $data->date;?></div>
                <div class="w50 col-1 text-right">￥
                    <?php if($data->money>=1000){
                        echo floor($data->money/1000).",".substr($data->money,-3,3);
                        }else{
                        echo $data->money;
                        } ?>
                    
            </div>
        </div>
        <?php }?>
        <div class="text-center font-s12 mt10 c-h mb50">
            账户信息从2016年9月开始计算
        </div>
    </div>
    <div class="pl10 pr10 mt10 hide"id="drawing">
        <?php for($i=0;$i<count($withdraw);$i++){
             $withd=$withdraw[$i];
            ?>
        <div class="mt10">   
           <div class="pad10 bg-white">
             <div>提取金额：￥
                <?php if($withd->money>=1000){
                        echo floor($$withd->money/1000).",".substr($withd->money,-3,3);
                        }else{
                        echo $withd->money;
                        } ?>
                
             <div>提取时间：<?php echo $withd->date;?></div>
           </div>
        </div>
        <?php }?>
        <div class="mt10 font-s12 c-h mb50">
            <div>温馨提示：</div>
            <div class="text-justify">具体到帐时间以易宝为准，通常需要一个工作日，请您留意银行卡到帐信息。</div>
        </div>
     </div>
</article>
<script>
    $(function(){
       $("#showDrawing").click(function(){
        $('#drawing').removeClass('hide');
        $("#allList").addClass('hide');
        $("#showAll").removeClass('bb-g');
        $("#showDrawing").addClass('bb-g');
       });
       $("#showAll").click(function(){
        $("#showAll").addClass('bb-g');
        $('#allList').removeClass('hide');
        $("#drawing").addClass('hide');
        $("#showDrawing").removeClass('bb-g');
       })
    })
</script>