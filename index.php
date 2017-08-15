<?php
/*
  Siap Crot
*/

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$q      = $_GET['q'];

if(isset($_GET['start'])){
  $rs     = [$_GET['start']];
}else{
  $rs     = [0,100,200];
}


$mt     = [];
foreach($rs as $x){

  $prok  = 'au,melb.au,bul,egy,iom,isr,fin,br,ca,vanc.ca.west,frank.gr,ice,ire,in,jp,nl,uk,lon.uk,ro,ru,mos.ru,swe,swiss,bg,hk,cr,hg,ind,my,thai,turk,tun,mx,singp,saudi,fr,pl,czech,gre,it,sp,no,por,za,den,vn,pa,sk,cn,lv,lux,nz,md,uae,slk,fl.east.usa,atl.east.usa,ny.east.usa,chi.central.usa,dal.central.usa,la.west.usa,lv.west.usa,sa.west.usa,nj.east.usa,central.usa,west.usa,east.usa,';
  $prok  = explode(',',$prok);
  shuffle($prok);
  $prok  = $prok[0];

  $r      = 'https://www.google.com/search?q=site:amazon.com/slp/+'.str_replace(array(' ','-'),'+',$q).'&num=100&start='.$x.'&sa=N&biw=1222&bih=877';
  $config['useragent']    = 'Mozilla/2.0 (X11; Fedora; Linux x86_64; rv:42.0) Gecko/20100101 Firefox/42.0';
  //$config['cookie_file']  = './google.cookies';
  $ch = curl_init();
  $timeout = 30;
  curl_setopt($ch, CURLOPT_URL, $r);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  curl_setopt($ch, CURLOPT_USERAGENT, $config['useragent']);

  if(isset($_GET['proxy'])){
    curl_setopt($ch, CURLOPT_PROXYUSERPWD,'mugomugoiso@gmail.com:silit1234');
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
    curl_setopt($ch, CURLOPT_PROXY,$prok.'.torguardvpnaccess.com:6060');
  }

  curl_setopt($ch, CURLOPT_REFERER, 'https://www.google.com');
  $r = curl_exec($ch);
  curl_close($ch);


  $domknt = new domDocument();
  @$domknt->loadHTML($r);
  $dodknt = $domknt->getElementsByTagName('a');
  $i = 1;foreach($dodknt as $c){
    $w = $c->getAttribute('href');
    if(strpos($w,'/slp/')!==false){
      $s = explode('/slp/',$w);
      $s = rtrim($s[1],'/');
      $s = explode('/',$s);
      $s = $s[0];
      if(strpos($s,'-')!==false){
        if(preg_match("/^[a-zA-Z- ]+$/", $s) == 1) {

          if(isset($_GET['must'])){
            if(strpos($s,$_GET['must'])!==false){
              $mt[] = $s;
            }
          }else{
            $mt[] = $s;
          }

        }
      }
    }
  $i++;}
}
$mt = array_unique($mt);
$mt = array_values($mt);

$ret= [
  'count' => count($mt),
  'data'  => $mt
];
echo json_encode($ret);
