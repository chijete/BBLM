<?php

require '../BBLM.php';

$bblmInstance = new BBLM();

echo $bblmInstance->BBLMtoPlainText('
;##ta:center##;;##fs:125px##;Hola ;+q+;mundo;+q+;;///;
;##c:rgba(223, 31, 31, 0.8)##;;_;i;_;Este;_;i;_; ;_;s;_;es;_;s;_; ;_;u;_;un;_;u;_; ;_;b;_;;#fs:25px#;;_;i;_;texto;_;i;_;;_;b;_;;///;
;##ff:sans-serif##;;##b:rgba(223, 31, 31, 0.8)##;;_;a;_;;~href:https://chijete.com/~;Hola mundo;_;a;_;;///;
;_;ul;_;;_;li;_;Hola mundo;_;li;_;;_;li;_;Hola mundo;_;li;_;;_;li;_;Hola mundo;_;li;_;;_;ul;_;;///;
;_;ol;_;;_;li;_;Hola mundo;_;li;_;;_;li;_;Hola mundo;_;li;_;;_;li;_;Hola mundo;_;li;_;;_;ol;_;;///;
;_;hr;_;;_;hr;_;;///;
;##ta:center##;;_;img;_;;#ht:255px#;;~src:https://static.fundacion-affinity.org/cdn/farfuture/PVbbIC-0M9y4fPbbCsdvAD8bcjjtbFc0NSP3lRwlWcE/mtime:1643275542/sites/default/files/los-10-sonidos-principales-del-perro.jpg~;;_;img;_;;///;
;_;table;_;;#wt:100%#;
;_;thead;_;
;_;tr;_;
;_;th;_;;#ff:sans-serif#;Hola 1;_;th;_;
;_;th;_;;#fs:40px#;Hola 2 -----<a href="https://google.com/">hola</a> &gt; ;_;th;_;
;_;th;_;Hola 3 ;_;a;_;;~href:https://chijete.com/~;ENLACE;_;a;_;;_;th;_;
;_;tr;_;
;_;thead;_;
;_;table;_;
');

?>