<?php
// header("content-type: image/jpeg");
// readfile(__DIR__."/5mb.dat");
// exit(0);
$size=$_GET['kb']*1;
$size=max(1,min($size,1024*128));
header("Content-length: ".($size*1024)); //needed for ajax update
header("Content-Encoding: none"); //otherwise it will gzip and no progress available

$str="";
for ($i=0;$i<1024;++$i)
	$str.=pack("H*",md5($i));

for ($i=0;$i<$size;++$i)
{
	echo substr($str,-$i*1024,1024);
	// flush();
	// usleep(0.001*1000*1000);
}
