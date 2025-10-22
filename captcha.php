<?
function sinusWave($im,$x1,$y1,$x2,$y2,$color){
  $prev_x = $x1;
  $prev_y = $y1;
  $image_width = 150;
  $image_height = 50;

  $amplitude = 10+rand()%20; // wave length
  $frequency = 0.10;   // wave frequency. set great values to make often waves
  $step = 2;           // 1 is best value

  for ($x = $x1; $x <= $x2; $x += $step) {
      $y = $y1 - (int)($amplitude * sin($x * $frequency));
      imageline($im, $prev_x, $prev_y, $x, $y, $color);
      $prev_x = $x;
      $prev_y = $y;
  }
}

function generateCaptcha($width = 150, $height = 50) {
    //$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $chars = 'ACDEFHKLMNPQRTUVWXY367';
    $captcha = substr(str_shuffle($chars), 0, 6);
    $_SESSION['captcha'] = $captcha;

    $image = imagecreate($width, $height);
    imagesetthickness($image, 3);
    $bg = imagecolorallocate($image, 255, 255, 255);
    for ($i=0;$i<=strlen($captcha);$i++){
        $line_color = imagecolorallocate($image, rand()%255, rand()%255, rand()%255);
        //imageline($image, 0, rand()%$height, $width, rand()%$height, $line_color);
        if (!$i%3) sinusWave($image, 0, rand()%$height, $width, rand()%$height, $line_color);
        $text_color = imagecolorallocate($image, rand()%200, rand()%200, rand()%200);
        //imagestring($image, 5, 30+$i*20, 10+rand(-10,10), $captcha[$i], $text_color);
        imagettftext($image, 20, rand(-45,45), 20+$i*20, 30+rand(-10,10), $text_color, './'.((rand()%6)+4).'.ttf', $captcha[$i]);
        $line_color = imagecolorallocate($image, rand()%255, rand()%255, rand()%255);
        //imageline($image, 0, rand()%$height, $width, rand()%$height, $line_color);
        if (($i%3)==1) sinusWave($image, 0, rand()%$height, $width, rand()%$height, $line_color);
    }

    header('Content-type: image/png');
    imagepng($image);
    imagedestroy($image);
}

session_start();
generateCaptcha();
?>