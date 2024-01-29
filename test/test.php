<?php

require '../BBLM.php';

$bblmInstance = new BBLM();

$bblmFromHTML = $bblmInstance->HTMLtoBBLM('
<p>Hola 🥖👉⚠️👈🌐👀🖤🧤🦆🥊</p>
<div>
<ul>
<li>123</li>
<li>123</li>
<li>123</li>
</ul>
</div>
<p>Yo soy un pato.</p>
<div>Holiwis</div>
<section>123</section>
<section>
<ol>
<li>123</li>
<li>123</li>
<li>123</li>
</ol>
</section>
', true);

$htmlFromBBLM = $bblmInstance->BBLMtoHTML($bblmFromHTML);

echo $htmlFromBBLM;

?>