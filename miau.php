<?php
$hoy = new DateTime(date('Y-m-d'));
$fecha_nacimiento =  new DateTime(date("1994-03-07"));
$edad = $hoy->diff($fecha_nacimiento);
echo $edad->format('%y años');
