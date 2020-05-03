<?php

/*
    [ Dev : amirhosein-heidari ]
    [ Telgram : @isdev or @amir_sezar ]
    [ Name Bot : azmon online ]
    [ v.1 ]
*/
error_reporting(0);
define('API_KEY','1122636906:AAH1MMYW08QPOFixk5xWlJKMHsEWhhgHGDM'); // Token
//============ [ config ] ==============
$dev = 976058555; 
$token = API_KEY;
//----------------------------------------------------- 
function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}//By amir: @isdev

//============ [ function ] ==============
function SendMessage($chat_id, $text, $mode, $reply, $keyboard = null){
    bot('SendMessage',[
    'chat_id'=>$chat_id,
    'text'=>$text,
    'parse_mode'=>$mode,
    'reply_to_message_id'=>$reply,
    'reply_markup'=>$keyboard
    ]);
}//==========
function editMessageText($chat_id, $message_id, $text, $mode, $keyboard = null){
    bot('editMessageText',[
    'chat_id'=>$chat_id,
    'message_id'=>$message_id, //+1
    'text'=>$text,
    'parse_mode'=>$mode,
    'reply_markup'=>$keyboard
    ]);
}//==========
function sendChatAction($chat_id, $action){
    bot('sendChatAction',[
    'chat_id'=>$chat_id,
    'action'=>$action, //typing 
    ]);
}//==========
function deleteMessage($chat_id, $message_id){
    bot('deleteMessage',[
    'chat_id'=>$chat_id,
    'message_id'=>$message_id, //-1 
    ]);
}//By amir: @isdev
//============ [ mysql ] ==============
$servername = "localhost";
$username = "amirheid_azmon"; // database username
$password = "-%l2B0T1FIIo"; // database password
$dbname = "amirheid_azmon"; // database name
$connect = mysqli_connect($servername, $username, $password, $dbname); //mysqli connect
//============ [ start code ] ==============
$input=file_get_contents("php://input");
$update=json_decode($input,true);
//خروجی آپدیت
$echo=json_encode($update,384);
//SendMessage($dev,$echo,'MarkDown',$message_id,null); 
//file_put_contents('input.txt',$input.PHP_EOL.PHP_EOL,FILE_APPEND);  
if(array_key_exists('message',$update)){    
    @$user_id=$update['message']['from']['id'];
    @$chat_id=$update['message']['chat']['id'];
    @$first_name=$update['message']['from']['first_name'];
    @$forward_from=$update['message']['forward_from']['id'];
    @$phone=(array_key_exists('phone_number', $update['message']['contact']))?$update['message']['contact']['phone_number']:null;
    @$last_name=(array_key_exists('last_name', $update['message']['from']))?$update['message']['from']['last_name']:null;
    @$username=(array_key_exists('username', $update['message']['from']))?$update['message']['from']['username']:null;    
    @$lang=$update['message']['from']['language_code'];
    @$message_id=$update['message']['message_id'];
    @$text=$update['message']['text'];
    @$photo=$update['message']['photo'][2]['file_id'];
    @$first_number=$update['message']['contact']['first_name'];
    @$type = $update['message']['chat']['type'];

}
elseif(array_key_exists('callback_query',$update)){
    @$callback_id=$update['callback_query']['id'];
    @$user_id=$update['callback_query']['from']['id'];
    @$chat_id=$update['callback_query']['message']['chat']['id'];
    @$first_name=$update['callback_query']['from']['first_name'];
    @$forward_from=$update['message']['forward_from']['id'];
    @$username=$update['callback_query']['from']['username'];
    @$lang=$update['callback_query']['from']['language_code'];
    @$message_id=$update['callback_query']['message']['message_id'];
    @$text=$update['callback_query']['data'];
}//By amir: @isdev
elseif (array_key_exists('inline_query', $update)){
    @$query_id=$update['inline_query']['id'];
    @$user_id=$update['inline_query']['from']['id'];
    @$first_name=$update['inline_query']['from']['first_name'];
    @$username=(array_key_exists('username',$update['inline_query']['from']))?$update['inline_query']['from']['username']:null;
    @$last_name=(array_key_exists('last_name',$update['inline_query']['from']))?$update['inline_query']['from']['last_name']:null;
    @$text=$update['inline_query']['query'];
}//----------------------------------------------------- 
@$getProfile=json_decode(bot("getUserProfilePhotos",array('user_id'=>$user_id)),true);
@$lock_channel=json_decode(bot('getChatMember',array('chat_id'=>$channel_ld,'user_id'=>$user_id)),true);
//============ [ step ] ==============
$user = mysqli_fetch_assoc(mysqli_query($connect,"SELECT * FROM user WHERE id = '$user_id' ")); //خواندن چیزی از دیتا بیس
$name =$user['name']?:$first_name; 
$code =$user['code'];
$number1 =$user['number'];
//============ [ keyboard ] ==============
$echo_key = json_encode([
'inline_keyboard'=>[ 
[['text'=>"isdev 🔍",'url'=>"http://t.me/isdev"]],
]]);
//==========
$start = json_encode([
'inline_keyboard'=>[ 
[['text'=>"👨‍🎓👩‍🎓شرکت در آزمون",'callback_data'=>"login"]],
[['text'=>"⚙ در دست اقدام ...",'callback_data'=>"no"]],
]]);
//==========
$yes = json_encode([
'inline_keyboard'=>[ 
[['text'=>"♻️ احراز هویت",'callback_data'=>"hoit"]],
]]);
//==========
$save = json_encode([
'inline_keyboard'=>[ 
[['text'=>"📝 ویرایش اطلاعات",'callback_data'=>"ed"],['text'=>"📤 تایید اطلاعات",'callback_data'=>"sa"]],
]]);
//============ [ start bot ] ==============
if($text=="/start"){
if ($user["id"] != true){
    $connect->query("INSERT INTO user (id, yes, step) VALUES ('$user_id', '0', 'none')");
}//By @isDev
if($user["yes"] == 0){
    sendChatAction($chat_id,'typing');
$matn="
سلام [ $first_name ] 😉👋

🕹 برای شرکت در آزمون آنلاین باید در ربات احراز هویت کنید .

🙇‍♂ لطفا از دکمه اقدام به انجام احراز هویت کنید 👇
";
    SendMessage($user_id,$matn,'MarkDown',$message_id,$yes); 
}else{
    sendChatAction($chat_id,'typing');
$matn="
سلام [ $first_name ] 😉👋

🤗 به ربات آزمون آنلاین خوش امدی برای شرکت در آزمون روی دکمه شرکت در آزمون کلیک کنید 👇
";
    SendMessage($user_id,$matn,'MarkDown',$message_id,$start);     
}}
//----------------------------------------------------- 
if($text=='hoit'){
    sendChatAction($chat_id,'typing');
$matn="
🔹لطفا نام و نام خانوادگی خود را ارسال کنید :

▪️مثال : امیرحسین ایرانی
";
    SendMessage($user_id,$matn,'MarkDown',$message_id,null);
    $connect->query("UPDATE user SET step = 'set name' WHERE id = '$user_id' ");     
sleep(5);
    deleteMessage($chat_id,$message_id);    
}//By amir: @isdev
//----------------------------------------------------- 
if($user['step']=='set name'){
    sendChatAction($chat_id,'typing');
$matn="
✅ نام و نام خانوادگی شما ذخیره شد .
";
    SendMessage($user_id,$matn,'MarkDown',$message_id,null);
sleep(1.2);
    deleteMessage($chat_id,$message_id+1);
$matn="
🔹 حالا کد ملی خود را ارسال کنید :

▪️مثال : 3870552222
";
    SendMessage($user_id,$matn,'MarkDown',null,null);   
    $connect->query("UPDATE user SET name = '$text' ,step = 'set code' WHERE id = '$user_id' ");     
}
//----------------------------------------------------- 
if($user['step']=='set code'){
    sendChatAction($chat_id,'typing');
$matn="
✅ کد ملی شما ذخیره شد .
";
   SendMessage($user_id,$matn,'MarkDown',$message_id,null);
sleep(1.2);
    deleteMessage($chat_id,$message_id+1);
$matn="
🔹 حالا شماره تلفن خود را ارسال کنید :

▪️مثال : 09380008888
";
    SendMessage($user_id,$matn,'MarkDown',null,null);      
    $connect->query("UPDATE user SET code = '$text' ,step = 'set number' WHERE id = '$user_id' ");     
}
//----------------------------------------------------- 
if($user['step']=='set number'){
$matn="
✅ شماره تلفن شما ذخیره شد .
";
    SendMessage($user_id,$matn,'MarkDown',$message_id,null); 
    $connect->query("UPDATE user SET number = '$text' ,step = 'none' WHERE id = '$user_id' ");     
sleep(1);
$matn="
🚹 صبر کنید ...

♻️در حال پردازش اطلاعات شما♻️
";
    editMessageText($chat_id,$message_id + 1,$matn,'MarkDown',null); 
    sendChatAction($chat_id,'typing');
sleep(2.5);
$matn="
✅ اطلاعات شما بدین شرح ذخیره شد و نزد ما محفوظ است :

🚹 یوزر آیدی : [$user_id](tg://user?id=$user_id)
▪️نام و نام خانوادگی : $name
▪️کد ملی : $code
▪️شماره تلفن : $text

♦️ لطفا از دکمه زیر اطلاعات خود  را تایید کنید !
";
    editMessageText($chat_id,$message_id + 1,$matn,'MarkDown',$save); 
}
//----------------------------------------------------- 
if($text=='ed'){
$matn="
😐 کاربر گرامی اطلاعات شما ثبت نشد ❌


برای شرکت در آزمون آنلاین لازم است در ربات احراز هویت کنید❗️

از دکمه زیر استفاده کنید👇
";
    editMessageText($chat_id,$message_id,$matn,'MarkDown',$yes); 
    $connect->query("UPDATE user SET yes = '0' WHERE id = '$user_id' "); 

}//By amir: @isdev
//----------------------------------------------------- 
if($text=='sa'){
    sendChatAction($chat_id,'typing');
$matn="
$name اطلاعات شما ذخیره شد ❇️

لطفا برای شرکت در آزمون ها از دکمه شرکت در آزمون استفاده کنید 👇
"; 
    editMessageText($chat_id,$message_id,$matn,'MarkDown',$start); 
    $connect->query("UPDATE user SET yes = '1' WHERE id = '$user_id' "); 
}
if($user['yes']==1){
$matn="
👋یک کاربر جدید احراز هویت کرد :

🚹 یوزر آیدی : [$user_id](tg://user?id=$user_id)
▪️نام و نام خانوادگی : $name
▪️کد ملی : $code
▪️شماره تلفن : $number1 
";
    SendMessage(976058555,$matn,'MarkDown',null,null);
    $connect->query("UPDATE user SET yes = '2' WHERE id = '$user_id' ");         
}
//-----------------------------------------------------
if($text == "login" or $text == "no"){
$matn="
$name عزیز
این بخش بزودی تکمیل می شود...

pv : @isdev
";    
bot('answerCallbackQuery',[
    'callback_query_id'=>$callback_id,
    'text'=>$matn, 
    'show_alert'=>true, 
    ]);
}






//update for dev ...
if($user_id == $dev){
SendMessage($dev,$echo,'MarkDown',$message_id,$echo_key); 
}
unlink("error_log");

/*
    [ Dev : amirhosein-heidari ]
    [ Telgram : @isdev or @amir_sezar ]
    [ Name Bot : azmon online ]
    [ v.1 ]
*/
?>
