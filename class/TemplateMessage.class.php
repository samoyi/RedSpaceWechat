<?php

class TemplateMessage
{
    // 一种方法对应发送一种模板消息


    // 发送模板消息的方法名和其对应的模板ID
    private $template_map = array(
        'orderPaymentNotice'=>'_-MiirGkaabk-yTkax4igwEy_UmgPFBK0WNB2XTOohw'
    );


    // 微信小店订单支付成功通知
    /*
     * 参数$orderDetail为OrderManager.class.php中getOrderDetail方法的返回值
	 */
	public function orderPaymentNotice($orderDetail, $sOpenID, $ad="", $detailUrl="")
    {
        $first = "订单详情可点击下方 帮助中心-订单查询\n咨询电话：0376-6506386\n";
        $template = array(
            'touser'        =>  $sOpenID,
            'template_id'   =>  $this->template_map[__FUNCTION__],
            'url'           =>  $detailUrl,
            'data'          =>  array(
                'first'     =>  array('value'   =>$first),
                'keyword1'   =>  array('value'   =>$orderDetail['order_id'], 'color'=> '#ea386c'),
                'keyword2'     =>  array('value'   =>$orderDetail['product_name'], 'color'=> '#ea386c'),
                'keyword3'      =>  array('value'   =>date("Y-m-d H:i:s",$orderDetail['order_create_time']), 'color'=> '#ea386c'),
                'keyword4'      =>  array('value'   =>("￥".$orderDetail['order_total_price']/100), 'color'=> '#ea386c'),
                'keyword5'      =>  array('value'   =>$orderDetail['buyer_nick'], 'color'=> '#ea386c'),
                'remark'    =>  array('value'   =>(" \n$ad"), 'color'=> '#565656')
            )
        );
        $url_post = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . ACCESS_TOKEN;
        return request_post($url_post, json_encode($template));
    }

}
?>
