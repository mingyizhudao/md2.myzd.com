<?php
$this->setPageTitle('申请提现');
$urlMyAccount = $this->createUrl('userbank/myAccount', array('addBackBtn' => 1));
$urlAjaxDraw = $this->createUrl('userbank/ajaxDraw');
$this->show_footer = false;
$withdraw->enable_money=3000;

?>
<style>
  .bt-h{border-top: 1px solid #D3D3D3;margin-top:-10px;} 
  .c-red{color: #F7847F;} 
  .c-h{color:#999;}
</style>

<article id="detail_article" class="active bg-gray" data-scroll="true">
 <div class="mt20 ml10 mr10 bg-white">
   <div class="pad10 ">
       <div class="">到账银行卡：<span><?php echo $withdraw->bankinfo;?></span></div>
       <div class="pt20">提现金额:</div>
       <div class="bb-h pt10  mr30 grid">
        <div class="col-0 pt8">￥<span class="c-h">|</span></div>
        <div class="col-1"><input type="text"style="border:none;box-shadow:none;"value=""></div>
      </div>
       <div class="pad10 font-s12 c-h bt-h">您的账户目前共有<span id="money">
          <?php   if(is_int($withdraw->enable_money)){
                    echo number_format($withdraw->enable_money);
                }else{
                    echo number_format($withdraw->enable_money,2);
                }?>
 
      </span>元<span class="c-red pl10"id="all">全部提现</span></div>
   </div>

 </div>
 <div class="mt50 ml10 mr10">
     <button class="btn btn-block btn-green"id="btnSubmit">确认提现</button>
 </div>
 <div class="mt10 text-center font-s12 c-h">具体到账时间以易宝和银行为准</div>
</article>
<script>
    $(function(){
      document.addEventListener('input', function(e) {
            var bool = true;
            var money='<?php echo $withdraw->enable_money;?>';
            var num1=parseFloat(money);
            $('input').each(function() {
               if ($(this).val() == '') {
                    bool = false;
                    return false;
                }else if($(this).val()>num1){
                   J.showToast('不能超过可提现总数','','1000');
                   bool = false;
                    return false;
                } 
            });
           
            if (bool) {
                $('#btnSubmit').removeAttr('disabled');
            } else {
                $('#btnSubmit').attr('disabled', 'disabled');
            }
        });
        $('input').change(function(){
          var money='<?php echo $withdraw->enable_money;?>';
            var num1=parseFloat(money);
         var value=$(this).val();
         var valTest=/^([1-9][\d]{0,7}|0)(\.[\d]{1,2})?$/;
         if(!valTest.test(value)){
              $(this).val('');
                  $('#btnSubmit').attr('disabled', 'disabled');
                }
        
        })
   

        $('button').click(function(){
           var val=$('input').val();
          $.ajax({
              type:'post',
              url:'<?php echo $urlAjaxDraw?>',
              data:{amount:val},
             success:function(data){
              console.log('aa',data);
              if(data.code==0){
                J.showToast('申请成功！','','1000');
                setTimeout(function(){
                      location.href = '<?php echo $urlMyAccount; ?>';
                },'3000');
              }
             
             }
          })
          
          
        });
        $("#all").click(function(){
              var money=parseFloat($("#money").html().replace(/\,/ig,''));
            $("input").val(money);
             $('#btnSubmit').removeAttr('disabled');
        })
      
    })
</script>