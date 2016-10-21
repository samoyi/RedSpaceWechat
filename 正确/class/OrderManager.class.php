<?php

class OrderManager
{
    //通过订单号查询openid
    public function getOPENIDbyORDERID($order_id) 
    {
        $postJson = '{"order_id": "' . $order_id . '"}';
        $resultorder =  request_post('https://api.weixin.qq.com/merchant/order/getbyid?access_token=' . ACCESS_TOKEN, $postJson) ;
        $resultorderObj = json_decode($resultorder);
        $buyer_openid = $resultorderObj->{'order'}->{'buyer_openid'};
        return $buyer_openid;
    }

    //获取订单详情
    public function getOrderDetail() 
    {
        $info=array('orderId'=>ORDERID,'userId'=>USERID,'hostId'=>HOSTID);
        $data=array('order_id' => ORDERID);
        $url_get="https://api.weixin.qq.com/merchant/order/getbyid?access_token=".ACCESS_TOKEN;
        $res_get=request_post($url_get,json_encode($data));
        $res_arr=json_decode($res_get,true);
        $order_arr=$res_arr['order'];
        return $order_arr;
    }
}

?>