<?php
if(!function_exists('generateBreadcrumb')){
function generateBreadcrumb(){
  $ci = &get_instance();
  $i=1; 
  $uri = $ci->uri->segment($i);
  $link = '<ul class="homebreadcrumb">';
 
  while($uri != ''){
    $prep_link = '';
  for($j=1; $j<=$i;$j++){
    $prep_link .= $ci->uri->segment($j).'/';
  }
 
  if($ci->uri->segment($i+1) == ''){
    $link.='<li>&raquo; <a href="'.ucfirst(site_url($prep_link)).'"><b>';
    $link.=$ci->uri->segment($i).'</b></a></li> ';
  }else{
    $link.='<li>&raquo;<a href="'.ucfirst(site_url($prep_link)).'"><b>';
    $link.=$ci->uri->segment($i).'</b></a></li> ';
  }
 
  $i++;
  $uri = $ci->uri->segment($i);
  }
    $link .= '</ul>';
    return $link;
  }
}
?>