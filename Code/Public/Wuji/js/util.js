$(function() {
    //价格更新
    function update_price(){
        if( $('#order_day').val() == '30'){
            $good_price = $('#month').val();
        }else if($('#order_day').val() == '10'){
            $good_price = $('#week').val();
        }else{
            $good_price = $('#day').val();
        }
        $price_content = "一张" + $('#order_day').val() + "天卡x" + $('#order_machines').val() + "台机器x" + $good_price + "点=";
        $price =  $('#order_machines').val() * $good_price;
        $("#price_content").html($price_content + '<code class="C2">' + $price + '点</code>');
    }
    $("#order_day").on('change',function(){
        update_price();
    });
    $("#order_machines").on('change',function(){
        update_price();
    });
});