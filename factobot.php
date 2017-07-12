<?php
    /*Facto Bot 1.0 Beta*/
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
    
    include "vk.php";
    
    
    $ansver_tocken='5a549ff8';//Код возвращаемы сервером
    
    $priroda="&#128522;";
    $istoria="&#128515;";                   
    $tehnika=" &#128521;";
    /*😄😁😊😃*/ 
    $token='01bbe4290e1ca6d67e9f10cb45ca33d5c0e65ab670cbe74723f5e9c41c2c2a0792a804abb1469db66cc1d';
    
    $fact;
    $id;
    
    $category;
    
/*Главная функция*/
function Category($mes,$token){
    include "db.php";
    //токен паблика (API)
    switch ($mes){
        case 1:
            $imn="priroda";//фото
            $query_cat="priroda";
            $message ="Ваш факт о природе";
             $h = 400; //высота
             $w = 30; //ширина
            // Размер шрифта
            $font_size = 50;
            
            // Ширина области для вывода текста
            $width_text = 1800;
        break;
        
        case 2:
            $imn="chelovek";
            $message ="Ваш факт о человеке";
            $query_cat="chelovek";
            $h = 200; //высота
            $w = 30; //ширина

            // Размер шрифта
            $font_size = 25;
            
            // Ширина области для вывода текста
            $width_text = 500;
        break;
    }
    
             $img="img/".$imn.".jpg";//Путь к изображению
             
             //Запрос
             $query = "SELECT * FROM `".$query_cat."`";
               $res = mysql_query($query); 
                while($row = mysql_fetch_array($res))
                {
                    $array[] = $row['fact'];
               }
                
                  $number = mt_rand(0, count($array) - 1); // Берём случайное число от 0 до (длины массива минус 1) включительно
                  $fact = $array[$number]; // Выводим цитату
                    

 header("Content-type: image/jpeg");

// Создаем изображение
$im = ImageCreateFromjpeg($img);

// Разбиваем наш текст на массив слов
$arr = explode(' ', $fact);

// Возращенный текст с нужными переносами строк, пока пустая
$ret = "";

// Перебираем наш массив слов
foreach($arr as $word)
	{
		// Временная строка, добавляем в нее слово
		$tmp_string = $ret.' '.$word;
		
		// Получение параметров рамки обрамляющей текст, т.е. размер временной строки 
		$textbox = imagettfbbox($font_size, 0, "fonts/consolab.ttf", $tmp_string);
		
		// Если временная строка не укладывается в нужные нам границы, то делаем перенос строки, иначе добавляем еще одно слово
		if($textbox[2] > $width_text)
			$ret.=($ret==""?"":"\n").$word;
		else
			$ret.=($ret==""?"":" ").$word;
	}

        $color=ImageColorAllocate($im, 255, 255, 255); //получаем идентификатор цвета

        $name="history/priroda".time().".jpg";
        /* выводим текст на изображение */
        imagettftext($im, $font_size, 0, $w, $h, $color, "fonts/consolab.ttf", $ret);
        imagejpeg($im,$name); //сохраняем рисунок в формате JPEG
        imagedestroy($im); //освобождаем память и закрываем изображение
// Накладываем возращенный многострочный текст на изображение
        $curl=curl_init();
        $file=$name;
        
        $file=curl_file_create($file, mime_content_type($file), pathinfo($file)['basename']);
        curl_setopt($curl, CURLOPT_URL, $result_s['response']['upload_url']);
        curl_setopt($curl, CURLOPT_POST, true);
         curl_setopt($curl,CURLOPT_HTTPHEADER, ['Content-type: multipart/form-data;charset=utf-8']);
         curl_setopt($curl, CURLOPT_POSTFIELDS, ['file'=>$file]);
         curl_setopt($curl,  CURLOPT_RETURNTRANSFER, true);
         curl_setopt($curl,  CURLOPT_TIMEOUT, 10);
         curl_setopt($curl,  CURLOPT_FOLLOWLOCATION, true);
         
        $response_image = json_decode(curl_exec($curl), true);
        curl_close($curl);
        
        $request_params = array(  
                        'server'=> $response_image['server'],
                        'photo'=>$response_image['photo'],
                        'hash'=> $response_image['hash'],
        				'access_token' => $token, 
        				'v' => '5.62' 
        				); 
        				
        	$get_params = http_build_query($request_params); 
        	$url_photo='https://api.vk.com/method/photos.saveMessagesPhoto?'.$get_params;
	        
	        $ok_image=json_decode(file_get_contents($url_photo),true);
	       
	  $request_params = array( 
      'message' => $message, 
      'attachment'=>'photo'.$ok_image['response'][0]['owner_id'].'_'.$ok_image['response'][0]['id'],
      'user_id' => $user_id, 
      'access_token' => $token, 
      'v' => '5.0' 
    ); 

$get_params = http_build_query($request_params); 

file_get_contents('https://api.vk.com/method/messages.send?'. $get_params); 

//Возвращаем "ok" серверу Callback API 
    return('ok'); 
}

    

$request_params = array(  
        				'access_token' => $token, 
        				'v' => '5.62' 
        				); 
        				
        	$get_params = http_build_query($request_params); 
        	$server_url=file_get_contents('https://api.vk.com/method/photos.getMessagesUploadServer?'.$get_params);
	
	        $result_s=(json_decode($server_url, true));


//Обработка фото и добавление факта
       /**************************************/

        /*************************************/


if (!isset($_REQUEST)) { 
  return; 
} 

//Строка для подтверждения адреса сервера из настроек Callback API 
$confirmation_token = $ansver_tocken; 

//Ключ доступа сообщества 


//Получаем и декодируем уведомление 
$data = json_decode(file_get_contents('php://input')); 

//Проверяем, что находится в поле "type" 
switch ($data->type) { 
  //Если это уведомление для подтверждения адреса сервера... 
  case 'confirmation': 
    //...отправляем строку для подтверждения адреса 
    echo $confirmation_token; 
    break; 

//Если это уведомление о новом сообщении... 
  case 'message_new': 
    //...получаем id его автора 
    $user_id = $data->object->user_id; 
    //затем с помощью users.get получаем данные об авторе 
    $user_info = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids={$user_id}&v=5.0")); 
    
//и извлекаем из ответа его имя 
    $user_name = $user_info->response[0]->first_name; 
    $ms=$data->object->body;
    
    Category($ms,$token);



   /*if ($mes!=='1'||'2'){
        $request_params = array( 
      'message' => "Вот список рубрик:\n 1-природа\n2-человек\n\n\nPS: Бот в бете и будет расти - потерпите))", 
      'user_id' => $user_id, 
      'access_token' => $token, 
      'v' => '5.0' 
    ); 

$get_params = http_build_query($request_params); 

file_get_contents('https://api.vk.com/method/messages.send?'. $get_params);
echo('ok'); 


}*/

}













































/*





    switch ($mes){
        case '1':
             $img="img/priroda.jpg"; //Путь к изображению
             $query = "SELECT * FROM `priroda`";
               $res = mysql_query($query); 
                while($row = mysql_fetch_array($res))
                {
                    $array[] = $row['fact'];
               }
                
                  $number = mt_rand(0, count($array) - 1); // Берём случайное число от 0 до (длины массива минус 1) включительно
                  $fact = $array[$number]; // Выводим цитату
                    

 header("Content-type: image/jpeg");
 $h = 400; //высота
 $w = 30; //ширина
// Создаем изображение
$im = ImageCreateFromjpeg($img);

// Шрифт текста

// Размер шрифта
$font_size = 50;

// Ширина области для вывода текста
$width_text = 1800;


// Разбиваем наш текст на массив слов
$arr = explode(' ', $fact);

// Возращенный текст с нужными переносами строк, пока пустая
$ret = "";

// Перебираем наш массив слов
foreach($arr as $word)
	{
		// Временная строка, добавляем в нее слово
		$tmp_string = $ret.' '.$word;
		
		// Получение параметров рамки обрамляющей текст, т.е. размер временной строки 
		$textbox = imagettfbbox($font_size, 0, "fonts/consolab.ttf", $tmp_string);
		
		// Если временная строка не укладывается в нужные нам границы, то делаем перенос строки, иначе добавляем еще одно слово
		if($textbox[2] > $width_text)
			$ret.=($ret==""?"":"\n").$word;
		else
			$ret.=($ret==""?"":" ").$word;
	}

        $color=ImageColorAllocate($im, 255, 255, 255); //получаем идентификатор цвета

        $name="history/priroda".time().".jpg";
        /* выводим текст на изображение */
        //imagettftext($im, $font_size, 0, $w, $h, $color, "fonts/consolab.ttf", $ret);
       // imagejpeg($im,$name); //сохраняем рисунок в формате JPEG
       // imagedestroy($im); //освобождаем память и закрываем изображение
// Накладываем возращенный многострочный текст на изображение, отступим сверху и слева по 50px


        //$name="img/promo.jpg";
        
       // $curl=curl_init();
       // $file=$name;
        
       /* $file=curl_file_create($file, mime_content_type($file), pathinfo($file)['basename']);
        curl_setopt($curl, CURLOPT_URL, $result_s['response']['upload_url']);
        curl_setopt($curl, CURLOPT_POST, true);
         curl_setopt($curl,CURLOPT_HTTPHEADER, ['Content-type: multipart/form-data;charset=utf-8']);
         curl_setopt($curl, CURLOPT_POSTFIELDS, ['file'=>$file]);
         curl_setopt($curl,  CURLOPT_RETURNTRANSFER, true);
         curl_setopt($curl,  CURLOPT_TIMEOUT, 10);
         curl_setopt($curl,  CURLOPT_FOLLOWLOCATION, true);
         
        
        $response_image = json_decode(curl_exec($curl), true);
        curl_close($curl);
        
        //print_r($response_image);
        $request_params = array(  
                        'server'=> $response_image['server'],
                        'photo'=>$response_image['photo'],
                        'hash'=> $response_image['hash'],
        				'access_token' => $tocken, 
        				'v' => '5.62' 
        				); 
        				
        	$get_params = http_build_query($request_params); 
        	$url_photo='https://api.vk.com/method/photos.saveMessagesPhoto?'.$get_params;
	        
	        $ok_image=json_decode(file_get_contents($url_photo),true);
	       
	  $request_params = array( 
      'message' => "Ваш факт о природе", 
      'attachment'=>'photo'.$ok_image['response'][0]['owner_id'].'_'.$ok_image['response'][0]['id'],
      'user_id' => $user_id, 
      'access_token' => $token, 
      'v' => '5.0' 
    ); 

$get_params = http_build_query($request_params); 

file_get_contents('https://api.vk.com/method/messages.send?'. $get_params); 

//Возвращаем "ok" серверу Callback API 
    echo('ok'); 

break; 

case '2':
     $img="img/chelovek.jpg"; //Путь к изображению
    $query = "SELECT * FROM `chelovek`";
               $res = mysql_query($query); 
                while($row = mysql_fetch_array($res))
                {
                    $array[] = $row['fact'];
               }
                
                  $number = mt_rand(0, count($array) - 1); // Берём случайное число от 0 до (длины массива минус 1) включительно
                  $fact = $array[$number]; // Выводим цитату
                    */
    /*********************************************************************************************/                
       /* $pic = ImageCreateFromjpeg($img); //открываем рисунок в формате JPEG
        header("Content-type: image/jpeg"); //указываем на тип передаваемых данных
        $color=ImageColorAllocate($pic, 255, 255, 255); //получаем идентификатор цвета
        
        // определяем место размещения текста по вертикали и горизонтали 
        $h = 200; //высота
        $w = 30; //ширина
        
        $name="history/test".time().".jpg";
        /* выводим текст на изображение */
       /* imagettftext($pic, 12, 0, $w, $h, $color, "fonts/consolab.ttf", $fact);
        imagejpeg($pic,$name); //сохраняем рисунок в формате JPEG
        imagedestroy($pic); //освобождаем память и закрываем изображение*/
/*header("Content-type: image/jpeg");
 $h = 200; //высота
        $w = 30; //ширина
// Создаем изображение
$im = ImageCreateFromjpeg($img);

// Шрифт текста

// Размер шрифта
$font_size = 25;

// Ширина области для вывода текста
$width_text = 500;

// Создаем цвета, которые понадобятся
//$blue	= imagecolorallocate($im, 0x88, 0x88, 0xFF);	// голубой
//$black	= imagecolorallocate($im, 0x00, 0x00, 0x00);	// черный

// Заливаем изображение цветом
//imagefill($im, 1, 1, $blue);

// Разбиваем наш текст на массив слов
$arr = explode(' ', $fact);

// Возращенный текст с нужными переносами строк, пока пустая
$ret = "";

// Перебираем наш массив слов
foreach($arr as $word)
	{
		// Временная строка, добавляем в нее слово
		$tmp_string = $ret.' '.$word;
		
		// Получение параметров рамки обрамляющей текст, т.е. размер временной строки 
		$textbox = imagettfbbox($font_size, 0, "fonts/consolab.ttf", $tmp_string);
		
		// Если временная строка не укладывается в нужные нам границы, то делаем перенос строки, иначе добавляем еще одно слово
		if($textbox[2] > $width_text)
			$ret.=($ret==""?"":"\n").$word;
		else
			$ret.=($ret==""?"":" ").$word;
	}

        $color=ImageColorAllocate($im, 255, 255, 255); //получаем идентификатор цвета

        $name="history/chelovek".time().".jpg";
        /* выводим текст на изображение */
       // imagettftext($im, $font_size, 0, $w, $h, $color, "fonts/consolab.ttf", $ret);
        //imagejpeg($im,$name); //сохраняем рисунок в формате JPEG
       // imagedestroy($im); //освобождаем память и закрываем изображение
// Накладываем возращенный многострочный текст на изображение, отступим сверху и слева по 50px


/**************************************************************************************************/
        //$name="img/promo.jpg";
        
       /* $curl=curl_init();
        $file=$name;
        
        $file=curl_file_create($file, mime_content_type($file), pathinfo($file)['basename']);
        curl_setopt($curl, CURLOPT_URL, $result_s['response']['upload_url']);
        curl_setopt($curl, CURLOPT_POST, true);
         curl_setopt($curl,CURLOPT_HTTPHEADER, ['Content-type: multipart/form-data;charset=utf-8']);
         curl_setopt($curl, CURLOPT_POSTFIELDS, ['file'=>$file]);
         curl_setopt($curl,  CURLOPT_RETURNTRANSFER, true);
         curl_setopt($curl,  CURLOPT_TIMEOUT, 10);
         curl_setopt($curl,  CURLOPT_FOLLOWLOCATION, true);
         
        
        $response_image = json_decode(curl_exec($curl), true);
        curl_close($curl);
        
        //print_r($response_image);
        $request_params = array(  
                        'server'=> $response_image['server'],
                        'photo'=>$response_image['photo'],
                        'hash'=> $response_image['hash'],
        				'access_token' => $tocken, 
        				'v' => '5.62' 
        				); 
        				
        	$get_params = http_build_query($request_params); 
        	$url_photo='https://api.vk.com/method/photos.saveMessagesPhoto?'.$get_params;
	        
	        $ok_image=json_decode(file_get_contents($url_photo),true);
	       
	  $request_params = array( 
      'message' => "Ваш факт о человеке", 
      'attachment'=>'photo'.$ok_image['response'][0]['owner_id'].'_'.$ok_image['response'][0]['id'],
      'user_id' => $user_id, 
      'access_token' => $token, 
      'v' => '5.0' 
    ); 

$get_params = http_build_query($request_params); 

file_get_contents('https://api.vk.com/method/messages.send?'. $get_params); 

//Возвращаем "ok" серверу Callback API 
    echo('ok'); 

break; 
    }
//С помощью messages.send и токена сообщества отправляем ответное сообщение 
    
} 
*/
        /*/
    
    
   
    
    /*TextOnPhoto();
?>