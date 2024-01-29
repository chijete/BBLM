<?php

#[AllowDynamicProperties]
class BBLM {
  function __construct() {
    $this->styles = [
      'ta' => [
        'css' => 'text-align',
        'allowed_values' => [
          'center',
          'justify',
          'left',
          'right',
        ],
        'allowed_sites' => [
          'block' => true,
        ],
      ],
      'fs' => [
        'css' => 'font-size',
        'allowed_values' => [
          ['VALUE_TYPE_INT', 'px'],
          ['VALUE_TYPE_INT', 'em'],
        ],
        'allowed_sites' => [
          'block' => true,
          'entities' => true,
        ],
      ],
      'ff' => [
        'css' => 'font-family',
        'allowed_values' => [
          'serif',
          'sans-serif',
          'monospace',
          'arial',
        ],
        'allowed_sites' => [
          'block' => true,
          'entities' => true,
        ],
      ],
      'c' => [
        'css' => 'color',
        'allowed_values' => '/^rgba\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*,\s*(1|0?\.\d+)\s*\)$/',
        'allowed_sites' => [
          'block' => true,
          'entities' => true,
        ],
        'is_rgba' => true,
      ],
      'b' => [
        'css' => 'background',
        'css_alters' => [
          'background-color'
        ],
        'allowed_values' => '/^rgba\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*,\s*(1|0?\.\d+)\s*\)$/',
        'allowed_sites' => [
          'block' => true,
          'entities' => true,
        ],
        'is_rgba' => true,
      ],
      'ht' => [
        'css' => 'height',
        'allowed_values' => [
          ['VALUE_TYPE_INT', 'px'],
        ],
        'allowed_sites' => [
          'entities' => [
            'img',
            'video',
            'iframe'
          ],
        ],
      ],
      'wt' => [
        'css' => 'width',
        'allowed_values' => [
          ['VALUE_TYPE_INT', 'px'],
        ],
        'allowed_sites' => [
          'entities' => [
            'img',
            'video',
            'iframe'
          ],
        ],
      ],
    ];
    $this->entities = [
      'b' => [
        'html' => 'b',
      ],
      'i' => [
        'html' => 'i',
      ],
      's' => [
        'html' => 's',
      ],
      'u' => [
        'html' => 'u',
      ],
      'a' => [
        'html' => 'a',
      ],
      'ul' => [
        'html' => 'ul',
      ],
      'ol' => [
        'html' => 'ol',
      ],
      'li' => [
        'html' => 'li',
      ],
      'hr' => [
        'html' => 'hr',
        'autoclose' => true,
      ],
      'table' => [
        'html' => 'table',
      ],
      'thead' => [
        'html' => 'thead',
      ],
      'tbody' => [
        'html' => 'tbody',
      ],
      'tr' => [
        'html' => 'tr',
      ],
      'th' => [
        'html' => 'th',
      ],
      'td' => [
        'html' => 'td',
      ],
      'img' => [
        'html' => 'img',
        'autoclose' => true,
      ],
      'video' => [
        'html' => 'video',
      ],
      'iframe' => [
        'html' => 'iframe',
      ],
      'span' => [
        'html' => 'span',
      ],
    ];
    $this->attributes = [
      'src' => [
        'html' => 'src',
        'regex_filter' => '/[^a-zA-Z0-9\+:;#\-_=&\?\/\.\,]/',
        'allowed_entities' => [
          'img',
          'video',
          'iframe'
        ],
      ],
      'href' => [
        'html' => 'href',
        'regex_filter' => '/[^a-zA-Z0-9:;#\-_=&\?\/\.\,]/',
        'allowed_entities' => [
          'a'
        ],
      ],
    ];
    $this->property_styles_regex = '/[^a-zA-Z0-9:;\-_=\?\/\.\,\(\)]/';
    $this->blocks_tag = 'div';
    $this->blocks_tag_protection = 'section';
    $this->blocks_separator = ';///;';
    $this->notation = [
      'block_styles' => [';##', '##;'],
      'entity' => ';_;',
      'entity_attributes' => [';~', '~;'],
      'entity_styles' => [';#', '#;'],
    ];
    $this->single_quote_code = ';+q+;';
    $this->hard_html_tags = [
      'strong' => 'b',
      'em' => 'i'
    ];
    return true;
  }

  public function BBLMtoHTML($base_string, $delete_breaklines = true, $e_htmlentities = true) {
    $final_html = '';
    if ($delete_breaklines) {
      $base_string = preg_replace("/[\r\n]/", '', $base_string);
    }
    if ($e_htmlentities) {
      $base_string = htmlentities($base_string);
    }
    $base_string = str_replace($this->single_quote_code, "'", $base_string);
    $blocks = explode($this->blocks_separator, $base_string);
    foreach ($blocks as $block) {
      $block = trim($block);
      $block_css = [];
      $block = preg_replace_callback('/'.$this->notation['block_styles'][0].'(.*?)'.$this->notation['block_styles'][1].'/', function($coincidence) use (&$block_css) {
        $propertyParts = explode(':', preg_replace($this->property_styles_regex, "", $coincidence[1]), 2);
        if (count($propertyParts) === 2) {
          if (isset($this->styles[$propertyParts[0]])) {
            if (isset($this->styles[$propertyParts[0]]['allowed_sites']['block'])) {
              $accessGranted = false;
              if ($this->styles[$propertyParts[0]]['allowed_sites']['block']) {
                $accessGranted = true;
              }
              if ($accessGranted) {
                $allowedValue = false;
                if (is_string($this->styles[$propertyParts[0]]['allowed_values'])) {
                  if (preg_match($this->styles[$propertyParts[0]]['allowed_values'], $propertyParts[1])) {
                    $allowedValue = true;
                  }
                } elseif (is_array($this->styles[$propertyParts[0]]['allowed_values'][0])) {
                  foreach ($this->styles[$propertyParts[0]]['allowed_values'] as $allowedValArr) {
                    if (count($allowedValArr) === 1) {
                      if ($allowedValArr[0] == 'VALUE_TYPE_INT') {
                        if (is_numeric($propertyParts[1])) {
                          $allowedValue = true;
                        }
                      } else {
                        if ($propertyParts[1] === $allowedValArr[0]) {
                          $allowedValue = true;
                        }
                      }
                    } elseif (count($allowedValArr) === 2) {
                      $propValFirstPart = str_replace($allowedValArr[1], '', $propertyParts[1]);
                      $propValSecondPart = mb_substr($propertyParts[1], 0 - mb_strlen($allowedValArr[1]));
                      if ($propValSecondPart === $allowedValArr[1]) {
                        if ($allowedValArr[0] == 'VALUE_TYPE_INT') {
                          if (is_numeric($propValFirstPart)) {
                            $allowedValue = true;
                          }
                        } else {
                          if ($propValFirstPart === $allowedValArr[0]) {
                            $allowedValue = true;
                          }
                        }
                      }
                    }
                  }
                } elseif (in_array($propertyParts[1], $this->styles[$propertyParts[0]]['allowed_values'])) {
                  $allowedValue = true;
                }
                if ($allowedValue) {
                  $block_css[$this->styles[$propertyParts[0]]['css']] = $propertyParts[1];
                }
              }
            }
          }
        }
        return '';
      }, $block);
      foreach ($this->entities as $entity => $entityData) {
        $block = preg_replace_callback('/'.$this->notation['entity'].''.$entity.''.$this->notation['entity'].'(.*?)'.$this->notation['entity'].''.$entity.''.$this->notation['entity'].'/', function($coincidence) use ($entity, $entityData) {
          $contentText = $coincidence[1];
          $entity_attributes = [];
          $entity_attributes_regex = $this->notation['entity_attributes'][0].'(.*?)'.$this->notation['entity_attributes'][1];
          $contentText = preg_replace_callback('/(?:'.$this->notation['entity'].'.*'.$this->notation['entity'].'[^)]*'.$this->notation['entity'].'.*'.$this->notation['entity'].')(*SKIP)(*FAIL)|'.$entity_attributes_regex.'/', function($coincidence_i) use (&$entity_attributes, $entity) {
            $propertyParts = explode(':', $coincidence_i[1], 2);
            if (count($propertyParts) === 2) {
              $propertyParts[0] = preg_replace("/[^a-zA-Z0-9]/", "", $propertyParts[0]);
              if (isset($this->attributes[$propertyParts[0]])) {
                if (in_array($entity, $this->attributes[$propertyParts[0]]['allowed_entities'])) {
                  $entity_attributes[$this->attributes[$propertyParts[0]]['html']] = preg_replace($this->attributes[$propertyParts[0]]['regex_filter'], "", $propertyParts[1]);
                }
              }
            }
            return '';
          }, $contentText);
          $entity_css = [];
          $entity_styles_regex = $this->notation['entity_styles'][0].'(.*?)'.$this->notation['entity_styles'][1];
          $contentText = preg_replace_callback('/(?:'.$this->notation['entity'].'.*'.$this->notation['entity'].'[^)]*'.$this->notation['entity'].'.*'.$this->notation['entity'].')(*SKIP)(*FAIL)|'.$entity_styles_regex.'/', function($coincidence_i) use (&$entity_css, $entity) {
            $propertyParts = explode(':', preg_replace($this->property_styles_regex, "", $coincidence_i[1]), 2);
            if (count($propertyParts) === 2) {
              if (isset($this->styles[$propertyParts[0]])) {
                if (isset($this->styles[$propertyParts[0]]['allowed_sites']['entities'])) {
                  $accessGranted = false;
                  if (is_array($this->styles[$propertyParts[0]]['allowed_sites']['entities'])) {
                    if (in_array($entity, $this->styles[$propertyParts[0]]['allowed_sites']['entities'])) {
                      $accessGranted = true;
                    }
                  } elseif ($this->styles[$propertyParts[0]]['allowed_sites']['entities']) {
                    $accessGranted = true;
                  }
                  if ($accessGranted) {
                    $allowedValue = false;
                    if (is_string($this->styles[$propertyParts[0]]['allowed_values'])) {
                      if (preg_match($this->styles[$propertyParts[0]]['allowed_values'], $propertyParts[1])) {
                        $allowedValue = true;
                      }
                    } elseif (is_array($this->styles[$propertyParts[0]]['allowed_values'][0])) {
                      foreach ($this->styles[$propertyParts[0]]['allowed_values'] as $allowedValArr) {
                        if (count($allowedValArr) === 1) {
                          if ($allowedValArr[0] == 'VALUE_TYPE_INT') {
                            if (is_numeric($propertyParts[1])) {
                              $allowedValue = true;
                            }
                          } else {
                            if ($propertyParts[1] === $allowedValArr[0]) {
                              $allowedValue = true;
                            }
                          }
                        } elseif (count($allowedValArr) === 2) {
                          $propValFirstPart = str_replace($allowedValArr[1], '', $propertyParts[1]);
                          $propValSecondPart = mb_substr($propertyParts[1], 0 - mb_strlen($allowedValArr[1]));
                          if ($propValSecondPart === $allowedValArr[1]) {
                            if ($allowedValArr[0] == 'VALUE_TYPE_INT') {
                              if (is_numeric($propValFirstPart)) {
                                $allowedValue = true;
                              }
                            } else {
                              if ($propValFirstPart === $allowedValArr[0]) {
                                $allowedValue = true;
                              }
                            }
                          }
                        }
                      }
                    } elseif (in_array($propertyParts[1], $this->styles[$propertyParts[0]]['allowed_values'])) {
                      $allowedValue = true;
                    }
                    if ($allowedValue) {
                      $entity_css[$this->styles[$propertyParts[0]]['css']] = $propertyParts[1];
                    }
                  }
                }
              }
            }
            return '';
          }, $contentText);
          $entity_css_str = '';
          foreach ($entity_css as $property => $value) {
            $entity_css_str .= "$property: $value; ";
          }
          $entity_css_str = trim($entity_css_str);
          $style_attr_html = '';
          if (!empty($entity_css_str)) {
            $style_attr_html = 'style="'.$entity_css_str.'"';
          }
          $entity_attributes_str = '';
          foreach ($entity_attributes as $attribute => $value) {
            $entity_attributes_str .= "$attribute=\"$value\" ";
          }
          $entity_attributes_str = trim($entity_attributes_str);
          return '<'.$entityData['html'].' '.$style_attr_html.' '.$entity_attributes_str.'>' . $contentText . '</'.$entityData['html'].'>';
        }, $block);
      }
      $block_css_str = '';
      foreach ($block_css as $property => $value) {
        $block_css_str .= "$property: $value; ";
      }
      $block_css_str = trim($block_css_str);
      $style_attr_html = '';
      if (!empty($block_css_str)) {
        $style_attr_html = 'style="'.$block_css_str.'"';
      }
      $final_html .= '<'.$this->blocks_tag.' '.$style_attr_html.'>' . $block . '</'.$this->blocks_tag.'>';
    }
    return $final_html;
  }

  public function BBLMtoPlainText($base_string, $delete_breaklines = false) {
    $final_text = '';
    if ($delete_breaklines) {
      $base_string = preg_replace("/[\r\n]/", '', $base_string);
    }
    $blocks = explode(';///;', $base_string);
    foreach ($blocks as $block) {
      $block = trim($block);
      $block = preg_replace_callback('/'.$this->notation['block_styles'][0].'(.*?)'.$this->notation['block_styles'][1].'/', function($coincidence) {
        return '';
      }, $block);
      foreach ($this->entities as $entity => $entityData) {
        $block = preg_replace_callback('/'.$this->notation['entity'].''.$entity.''.$this->notation['entity'].'(.*?)'.$this->notation['entity'].''.$entity.''.$this->notation['entity'].'/', function($coincidence) use ($entity, $entityData) {
          $contentText = $coincidence[1];
          $contentText = preg_replace_callback('/'.$this->notation['entity_attributes'][0].'(.*?)'.$this->notation['entity_attributes'][1].'/', function($coincidence_i) {
            return '';
          }, $contentText);
          $contentText = preg_replace_callback('/'.$this->notation['entity_styles'][0].'(.*?)'.$this->notation['entity_styles'][1].'/', function($coincidence_i) {
            return '';
          }, $contentText);
          return $contentText;
        }, $block);
      }
      $final_text .= $block . PHP_EOL;
    }
    return $final_text;
  }

  function styleStringToArray($estilosCSS) {
    $estilosCSS = trim($estilosCSS);
    if (empty($estilosCSS)) {
      return [];
    }

    // Divide la cadena en pares clave-valor usando el punto y coma como delimitador
    $paresClaveValor = explode(';', $estilosCSS);

    // Inicializa un array para almacenar los estilos
    $arrayEstilos = array();

    // Itera sobre los pares clave-valor y agrega al array
    foreach ($paresClaveValor as $par) {
      // Divide cada par usando dos puntos como delimitador
      $elemento = explode(':', $par);

      // Elimina espacios en blanco alrededor de la clave y el valor
      $clave = trim($elemento[0]);

      if (!empty($clave)) {
        $valor = trim($elemento[1]);
  
        // Agrega al array
        $arrayEstilos[$clave] = $valor;
      }
    }

    return $arrayEstilos;
  }

  function convertColorToRGBA($colorHex) {
    $colorHex = trim($colorHex);
    $patron = '/#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})/';
    if (preg_match($patron, $colorHex)) {
      $transparencia = 1;
      $colorHex = str_replace('#', '', $colorHex);
      $r = hexdec(substr($colorHex, 0, 2));
      $g = hexdec(substr($colorHex, 2, 2));
      $b = hexdec(substr($colorHex, 4, 2));
      return "rgba($r, $g, $b, $transparencia)";
    } else {
      return $colorHex;
    }
  }

  function convertRGBtoRGBA($colorRGB) {
    $colorRGB = trim($colorRGB);
    $patron = '/rgb\((\d{1,3}), (\d{1,3}), (\d{1,3})\)/';
    if (preg_match($patron, $colorRGB)) {
      $colorRGB = 'rgba' . mb_substr($colorRGB, 3);
      $colorRGB = mb_substr($colorRGB, 0, -1) . ', 1)';
      return $colorRGB;
    } else {
      return $colorRGB;
    }
  }

  function getBBLMstart($usefulAttributes, $is_block = false) {
    $BBLMstart = '';
    $divStyles = $this->styleStringToArray($usefulAttributes['style']);
    foreach ($this->styles as $styleBBLMName => $styleData) {
      $divHasThisStyle = false;
      if (isset($divStyles[$styleData['css']])) {
        $divHasThisStyle = $styleData['css'];
      } elseif (isset($styleData['css_alters'])) {
        foreach ($divStyles as $divStyleName => $divStyleVal) {
          if (in_array($divStyleName, $styleData['css_alters'])) {
            $divHasThisStyle = $divStyleName;
            break;
          }
        }
      }
      if ($divHasThisStyle !== false) {
        $styleContent = $divStyles[$divHasThisStyle];
        if (isset($styleData['is_rgba']) AND $styleData['is_rgba']) {
          $styleContent = $this->convertColorToRGBA($styleContent);
          $styleContent = $this->convertRGBtoRGBA($styleContent);
        }
        if ($is_block) {
          $notation = $this->notation['block_styles'];
        } else {
          $notation = $this->notation['entity_styles'];
        }
        $BBLMstart .= $notation[0] . $styleBBLMName . ':' . $styleContent . $notation[1];
      }
    }
    foreach ($this->attributes as $attrBBLMName => $attrData) {
      $attrContent = preg_replace($attrData['regex_filter'], "", $usefulAttributes[$attrData['html']]);
      if (!empty($attrContent)) {
        $BBLMstart .= $this->notation['entity_attributes'][0] . $attrBBLMName . ':' . $attrContent . $this->notation['entity_attributes'][1];
      }
    }
    return $BBLMstart;
  }

  function checkIfTagExists($busqueda) {
    $estaPresente = false;
    foreach ($this->entities as $bblmName => $entidad) {
      if ($entidad['html'] === $busqueda) {
        $estaPresente = $bblmName;
        break;
      }
    }
    return $estaPresente;
  }

  function iterateChildNodes($dom, $div, $customTags) {
    $divChildren = $div->childNodes;
    foreach ($divChildren as $childElement) {
      if ($childElement->nodeType === XML_ELEMENT_NODE AND $childElement->parentNode === $div) {
        $childElementUsefulAttributes = [];
        $childElementUsefulAttributes['style'] = $childElement->getAttribute('style');
        foreach ($this->attributes as $attrBBLMName => $attrData) {
          $childElementUsefulAttributes[$attrData['html']] = $childElement->getAttribute($attrData['html']);
        }
        foreach (iterator_to_array($childElement->attributes) as $attr) {
          $childElement->removeAttributeNode($attr);
        }
        $childElementBBLMstart = $this->getBBLMstart($childElementUsefulAttributes, false);
        $allowedTag = $this->checkIfTagExists($childElement->nodeName);
        if ($allowedTag !== false) {
          $customChildElement = $childElement;
          if (isset($customTags[$childElement->nodeName])) {
            $customChildElement = $dom->createElement($customTags[$childElement->nodeName]);
            $childElement->parentNode->replaceChild($customChildElement, $childElement);
          }
          if (!empty($childElementBBLMstart)) {
            $fragmento = $dom->createDocumentFragment();
            $fragmento->appendXML($childElementBBLMstart);
            $customChildElement->appendChild($fragmento);
          }
        }
        if ($childElement->hasChildNodes()) {
          $this->iterateChildNodes($dom, $childElement, $customTags);
        }
      }
    }
  }

  function DOMinnerHTML(DOMNode $element) {
    $innerHTML = ""; 
    $children  = $element->childNodes;

    foreach ($children as $child) 
    { 
        $innerHTML .= $element->ownerDocument->saveHTML($child);
    }

    return $innerHTML;
  }

  public function HTMLtoBBLM($base_string, $hard_conversion = false) {
    $final_bblm = '';

    $bblm_blocks = [];

    // autoclose tags
    $customTags = [];
    foreach ($this->entities as $tagData) {
      if (isset($tagData['autoclose']) AND $tagData['autoclose']) {
        $customTags[$tagData['html']] = 'temp-' . $tagData['html'];
      }
    }

    $htmlString = '<?xml encoding="UTF-8"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>'.$base_string.'</body></html>';
    $dom = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $dom->loadHTML($htmlString);
    $xpath = new DOMXPath($dom);
    $body = $dom->getElementsByTagName('body')->item(0);
    if ($hard_conversion) {
      $from = $this->blocks_tag;
      $to = $this->blocks_tag_protection;
      foreach ($xpath->query('//'.$from) as $targetNode) {
        $toNode = $dom->createElement($to);
        foreach (iterator_to_array($targetNode->attributes) as $attr) {
          $toNode->setAttribute($attr->name, $attr->value);
        }
        while ($targetNode->childNodes->length > 0) {
          $toNode->appendChild($targetNode->childNodes->item(0));
        }
        $targetNode->parentNode->replaceChild($toNode, $targetNode);
      }
      foreach ($xpath->query('/html/body/*') as $bodyChild) {
        if ($bodyChild->parentNode === $body AND $bodyChild->nodeType === XML_ELEMENT_NODE) {
          $tag = $bodyChild->nodeName;
          $attrs = $bodyChild->attributes;
          $init_tag = '<'.$tag;
          foreach ($attrs as $atributo) {
            $init_tag .= ' '.$atributo->name.'="'.$atributo->value.'"';
          }
          $init_tag .= '>';
          $end_tag = '</'.$tag.'>';
          $bodyChildHTML = $this->DOMinnerHTML($bodyChild);
          $nuevoDiv = $dom->createElement($this->blocks_tag);
          $tpl = new DOMDocument('1.0', 'UTF-8');
          $tpl->loadHtml('<?xml encoding="UTF-8"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>' . $init_tag . $bodyChildHTML . $end_tag . '</body></html>');
          $nuevoDiv->appendChild($dom->importNode($tpl->getElementsByTagName('body')->item(0)->firstChild, TRUE));
          $bodyChild->parentNode->replaceChild($nuevoDiv, $bodyChild);
        }
      }
      foreach ($this->hard_html_tags as $from => $to) {
        foreach ($xpath->query('//'.$from) as $targetNode) {
          $toNode = $dom->createElement($to);
          foreach (iterator_to_array($targetNode->attributes) as $attr) {
            $toNode->setAttribute($attr->name, $attr->value);
          }
          while ($targetNode->childNodes->length > 0) {
            $toNode->appendChild($targetNode->childNodes->item(0));
          }
          $targetNode->parentNode->replaceChild($toNode, $targetNode);
        }
      }
    }
    $divs = $body->getElementsByTagName($this->blocks_tag);
    foreach ($divs as $div) {
      if ($div->parentNode === $body) {
        $divUsefulAttributes = [];
        $divUsefulAttributes['style'] = $div->getAttribute('style');
        foreach ($this->attributes as $attrBBLMName => $attrData) {
          $divUsefulAttributes[$attrData['html']] = $div->getAttribute($attrData['html']);
        }
        foreach (iterator_to_array($div->attributes) as $attr) {
          $div->removeAttributeNode($attr);
        }
        $divBBLMstart = $this->getBBLMstart($divUsefulAttributes, true);
        $this->iterateChildNodes($dom, $div, $customTags);
        if (!empty($divBBLMstart)) {
          $fragmento = $dom->createDocumentFragment();
          $fragmento->appendXML($divBBLMstart);
          $div->appendChild($fragmento);
        }
      }
    }

    $htmlResult = $dom->saveHTML();
    foreach ($this->entities as $bblmTag => $tagData) {
      $htmlResult = str_replace('<'.$tagData['html'].'>', $this->notation['entity'] . $bblmTag . $this->notation['entity'], $htmlResult);
      $htmlResult = str_replace('</'.$tagData['html'].'>', $this->notation['entity'] . $bblmTag . $this->notation['entity'], $htmlResult);
      $htmlResult = str_replace('<temp-'.$tagData['html'].'>', $this->notation['entity'] . $bblmTag . $this->notation['entity'], $htmlResult);
      $htmlResult = str_replace('</temp-'.$tagData['html'].'>', $this->notation['entity'] . $bblmTag . $this->notation['entity'], $htmlResult);
    }

    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->loadHTML($htmlResult);
    $body = $dom->getElementsByTagName('body')->item(0);
    $divs = $body->getElementsByTagName($this->blocks_tag);
    foreach ($divs as $div) {
      $bblm_blocks[] = str_replace("'", $this->single_quote_code, strip_tags($div->nodeValue));
    }

    $final_bblm = implode($this->blocks_separator, $bblm_blocks);

    return $final_bblm;
  }
}

?>