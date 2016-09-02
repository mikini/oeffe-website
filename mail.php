<?php

// get configured email address
require 'oeffemaildest';

// secure the input
$securepost = array();
$maxkeysize=0;
foreach($_POST as $key => $value)
{
  $secure = array();
  foreach(array($key,$value) as $input)
    $secure[] = htmlspecialchars(stripslashes(trim($input)));
  $securepost[$secure[0]] = $secure[1];

  if (strlen($secure[1]) == 0)
    die ("Vi skal desværre bruge lidt mere information end du har angivet i feltet \"" . $secure[0] . "\", prøv venligst igen.");
  if (strlen($secure[1]) > 100 || strlen($secure[0]) > 100)
    die ("Vi har ikke brug for så mange data som du forsøger at sende.");

  // save length of longest key
  if (strlen($secure[0]) > $maxkeysize)
    $maxkeysize=strlen($secure[0]);
}

// format a textual representation of input
$data='';
foreach($securepost as $key => $value)
{
  $data .= $key;
  for ($i=strlen($key); $i<$maxkeysize; $i++)
    $data .= ' ';
  $data .= ' : ' . $value . "\n";
}

// Get some client id
$ip = $_SERVER['REMOTE_ADDR'];
$agent = $_SERVER['HTTP_USER_AGENT'];
$time = date('c', $_SERVER['REQUEST_TIME']);

// Generate mail for ØFFE staffers
$mailsubject = '[ØFFE] Ny indmelding: ' . $securepost['fornavn'] . " " . $securepost['efternavn'];
$mailbody = <<<EOF
Hej ØFFE-administrator.

Dette er en automatisk genereret email fra indmeldingsformularen på ØFFE's
hjemmeside.

Nedenfor er gengivet de oplysninger der blev indtastet i formularen.

$data

Indmeldingen blev foretaget $time fra IP-adressen $ip med browseren "$agent".

Hilsen
ØFFE's hjemmeside
EOF;

// Generate message for user
$name = $securepost['fornavn'];
$msg = <<<EOF
Hej $name.

Tak for din indmelding, du er godt på vej til at blive medlem af ØFFE.

Som beskrevet på hjemmesiden skal du nu sørge for at indbetale indmeldingsgebyret, se hvorledes på http://øffe.tk/#blivmedlem. Vi vil vende tilbage til dig når vi har bekræftet indbetalingen, hvorefter du vil få tilsendt et medlemsnummer så du kan komme i gang med at bestille dejlige lokale fødevarer. Forvent op til en uges ekspeditionstid.

Har du problemer eller spørgsmål, så svar blot på denne email eller spørg evt. i gruppen på Facebook (se http://øffe.tk/#kontakt).

Du har tilmeldt dig med følgende information:

$data

Økologiske hilsner
ØFFE

PS: indmeldingen blev foretaget fra IP-adressen $ip med browseren "$agent".  
EOF;

// Generate page output
$htmlmsg = str_replace("\n\n","<p />", $msg);
$htmlmsg = str_replace("\n","<br>", $htmlmsg);

echo <<<EOF
<html><head><title>ØFFE indmeldingsformular</title></head><body>
<h2>Indmelding modtaget</h2>
Vi har sendt dig en email med følgende indhold:
<hr width="80%">
$htmlmsg
</body></html>
EOF;

// send mails
$headers =  "Content-Type: text/plain; charset=UTF-8\n"
         .  "From: =?UTF-8?B?" . base64_encode("Økologisk FødevareFællesskab Esbjerg") . "?= <oeffesbjerg@gmail.com>\n";
mail($securepost['email'], '[ØFFE] Velkommen som medlem', $msg, $headers);
foreach($oeffemaildest as $maildest)
  mail($maildest, $mailsubject, $mailbody, $headers);
