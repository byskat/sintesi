<div class="msgBox">
    <p><?php if(isset($msg) & !empty($msg)){ 
        echo $msg; ?> 
        <script> 
            $('.msgBox').addClass('activeMsg', 1000, "easeOutBounce"); 
        <?php 
        if($msgColor==1){ ?> $('.msgBox').addClass('green'); $('.msgBox').removeClass('red'); <?php }
        if($msgColor==2){ ?> $('.msgBox').addClass('red'); $('.msgBox').removeClass('green'); <?php }
        ?> </script> <?php } ?>
    </p>
</div>