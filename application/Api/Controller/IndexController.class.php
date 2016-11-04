<?php
namespace Api\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display();
    }

    public function createJson(){
        $personArray = array(
            'name' => 'tom',
            'age'  => 18,
            'job'  => 'php'
        );
        $personJson = json_encode($personArray);
        dump($personJson);
    }

    public function readJson(){
        $personJson = '{"name":"tom","age":18,"job":"php"}';
        $personObj = json_decode($personJson);
        dump($personObj);
        echo "<hr>";
        $personArray = json_decode($personJson,true);
        dump($personArray);
    }
    
    public function testRequest(){
        $url = 'https://www.baidu.com';
        $content = request($url);
        echo 'this is testRequest'.'<br/>';
        //echo $content;
        dump($content);
    }
    
    public function weather(){
        $city = I('get.city','六安');
        $url = "http://api.map.baidu.com/telematics/v2/weather?location=".$city."&ak=B8aced94da0b345579f481a1294c9094";
        $content = request($url,false);
        $xmlObj = simplexml_load_string($content);
        //dump($xmlObj);
        $todayInfo = $xmlObj->results->result[0];
        echo '实时温度:'.$todayInfo->date.'<br/>';
        echo '天气情况:'.$todayInfo->weather.'<br/>';
        echo '风向风力:'.$todayInfo->wind.'<br/>';
        echo '温度区间:'.$todayInfo->temperature.'<br/>';
        
        
    }
    
    public function getAreaByPhone(){
        $phonenum = I('get.phone',15395196565);
        $url = "http://cx.shouji.360.cn/phonearea.php?number=".$phonenum;
        $content = request($url,false);
        $content = json_decode($content);
        echo $phonenum."<br/>";
        echo $content->data->province."<br/>";
        echo $content->data->city."<br/>";
        echo $content->data->sp."<br/>";            
    }
    
    public function action(){
        $checkcode = $_POST['checkcode'];
        session_start();
        $code = $_SESSION['code'];
        //把生成发送的验证码
        //和用户手机收到的验证码进行比对
        if($code==$checkcode){
            echo 'ok';
        }else{
            echo 'no';
        }
    }
    
    public function send(){
        //要生成手机验证码，并且存储到session里面
        //验证页面需要使用到，所以session存储
        session_start();
        //随机验证码
        $code = rand(100000,999999);
        //生成的验证码存放到session，方便后续的验证操作
        $_SESSION['code']=$code;
        include_once("./CCPRestSmsSDK.php");
        //主帐号,对应开官网发者主账号下的 ACCOUNT SID
        $accountSid= '8aaf07085805254b015810a2f21606e0';
        //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
        $accountToken= '3c69eb35d9a541229a978fd26c9a3767';
        //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
        //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
        $appId='8aaf07085805254b015810a2f37706e5';
        //请求地址
        //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
        //生产环境（用户应用上线使用）：app.cloopen.com
        $serverIP='sandboxapp.cloopen.com';
        //请求端口，生产环境和沙盒环境一致
        $serverPort='8883';
        //REST版本号，在官网文档REST介绍中获得。
        $softVersion='2013-12-26';
        
        
        /**
         * 发送模板短信
         * @param to 手机号码集合,用英文逗号分开
         * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
         * @param $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
         */
        function sendTemplateSMS($to,$datas,$tempId)
        {
            // 初始化REST SDK
            global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;
            $rest = new REST($serverIP,$serverPort,$softVersion);
            $rest->setAccount($accountSid,$accountToken);
            $rest->setAppId($appId);
        
            // 发送模板短信
            // echo "Sending TemplateSMS to $to <br/>";
            $result = $rest->sendTemplateSMS($to,$datas,$tempId);
            if($result == NULL ) {
                return false;
            }
            if($result->statusCode!=0) {
                return false;
                //TODO 添加错误处理逻辑
            }else{
                return true;
                //TODO 添加成功处理逻辑
            }
        }
        
        //Demo调用
        //**************************************举例说明***********************************************************************
        //*假设您用测试Demo的APP ID，则需使用默认模板ID 1，发送手机号是13800000000，传入参数为6532和5，则调用方式为           *
        //*result = sendTemplateSMS("13800000000" ,array('6532','5'),"1");																		  *
        //*则13800000000手机号收到的短信内容是：【云通讯】您使用的是云通讯短信模板，您的验证码是6532，请于5分钟内正确输入     *
        //*********************************************************************************************************************
        //获取传递手机号码
        $telphone = $_GET['telphone'];
        $fp = fopen('c:/ssss.txt','w');
        fwrite($fp,123);
        $res = sendTemplateSMS($telphone,array($code,1),"1");//手机号码，替换内容数组，模板ID
        // var_dump($res);
        if($res){
            echo 1;
        }else{
            echo 0;
        }
    }
    
    public function express(){
        //$type = I('type');
        //$num = I('num');
        $url = 'https://www.kuaidi100.com/query?type=yuantong&postid=883185003506903278';
        $info = request($url);
        $info = json_decode($info);
        dump($info);
    }
    
    public function sendTest(){
        //调用function.php里的sendMail
        // $rs = sendMail('我是php发送的邮件','你好，我是php，你是谁呢？','woai281@163.com');
        $rs = sendMail('我是php发送的邮件','你好，我是php，你是谁呢？','1175860644@qq.com');
        //接收返回值并进行判断
        if($rs === true){
            echo '发送邮件成功！';
        }else{
            echo '发送失败,错误原因为:'.$rs;
        }
    }
    
    
    

}