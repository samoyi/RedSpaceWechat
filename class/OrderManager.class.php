<?php

class OrderManager
{
    //通过订单号查询openid
    /*
     * 调用微信【根据订单ID获取订单详情】接口
     */
    public function getOPENIDbyORDERID($order_id)
    {
        $data = '{"order_id": "' . $order_id . '"}';
        $url = 'https://api.weixin.qq.com/merchant/order/getbyid?access_token=' . ACCESS_TOKEN;
        $result =  request_post($url, $data) ;
        $resultObj = json_decode($result);
        return $resultObj->order->buyer_openid;
    }


    //获取订单详情
    /*
     * 调用微信【根据订单ID获取订单详情】接口
     */
    public function getOrderDetail($order_id)
    {
        $data = '{"order_id": "' . $order_id . '"}';
        $url = "https://api.weixin.qq.com/merchant/order/getbyid?access_token=" . ACCESS_TOKEN;
        $result = request_post($url, $data);
        $resultObj = json_decode($result);
        return $resultObj->order;
    }

}

?>
