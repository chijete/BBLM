<?php

require '../BBLM.php';

$bblmInstance = new BBLM();

$htmlString = '
<html>
  <head></head>
  <body>

<p style="font-size: 18px;">
  <span style="font-size: 18px;">Hola 🥖👉⚠️👈🌐👀🖤🧤🦆🥊</span>
</p>
<div style="display: block;">
  <ul>
    <li>123</li>
    <li>123</li>
    <li>123</li>
  </ul>
</div>
<p>Yo soy un pato.</p>
<div>Holiwis</div>
<section style="font-size: 18px;">123</section>
<section>
  <ol>
    <li>123</li>
    <li>123</li>
    <li>123</li>
  </ol>
</section>
<section>
  <div><div style="display: block; font-size: 28px;"><section style="font-size: 20px;">Hola, mundo!</section></div></div>
</section>
<hr>
<ul>
  <li>Hola
    <ul>
      <li>Calabaza</li>
      <li>Calabazín</li>
      <li>Sandía
        <ul>
          <li>Calabaza</li>
          <li>Calabazín</li>
          <li>Sandía
          </li>
        </ul>
      </li>
    </ul>
  </li>
  <li>🦆🦆</li>
  <li>🧤🧤</li>
</ul>

  </body>
</html>
';

// $htmlString = '
// <b>Hola, mundo</b>
// <ul>
//   <li>Hola</li>
// </ul>
// ';

$bblmFromHTML = $bblmInstance->HTMLtoBBLM($htmlString, true);

// echo $bblmFromHTML;
// die();

// echo PHP_EOL . PHP_EOL . '-----' . PHP_EOL . PHP_EOL . PHP_EOL;

$htmlFromBBLM = $bblmInstance->BBLMtoHTML($bblmFromHTML);

echo $htmlFromBBLM;

?>