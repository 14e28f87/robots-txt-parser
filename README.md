robots-txt-parser
=================

php class for parse all directives from robots.txt files.
php класс для парса файлов robots.txt.

[![Build Status](https://travis-ci.org/bopoda/robots-txt-parser.svg?branch=master)](https://travis-ci.org/bopoda/robots-txt-parser)

Php class to parse robots.txt rules according to Google & Yandex specifications. Specifications:
* [Google: Robots.txt Specifications](https://developers.google.com/webmasters/control-crawl-index/docs/robots_txt)
* [Yandex: Robots.txt Specifications](https://help.yandex.com/webmaster/controlling-robot/robots-txt.xml)

Это форк репозитория https://github.com/t1gor/Robots.txt-Parser-Class где я являлся контрибутором, но внесён ряд улучшений:
<ol>
<li>парс директивы Clean-param в соответствии с clean-param синтаксисом</li>
<li>удаление комментариев (everything following the '#' character, up to the first line break, is disregarded)</li>
<li>улучшение парса host - межсекционная директива, должна относиться к user-agent '*'; при наличи нескольких host поисковики берут значение первой</li>
<li>из класса удалены неиспользуеме методы, сделан рафакторинг, исправлена область видимости свойств класса</li>
<li>добавлено больше тестовых кейсов, а также тестовые кейсы добавлены на весь новый функционал</li>
</ol>


### Поддерживаемые директивы:
* DIRECTIVE_ALLOW = 'allow';
* DIRECTIVE_DISALLOW = 'disallow';
* DIRECTIVE_HOST = 'host';
* DIRECTIVE_SITEMAP = 'sitemap';
* DIRECTIVE_USERAGENT = 'user-agent';
* DIRECTIVE_CRAWL_DELAY = 'crawl-delay';
* DIRECTIVE_CLEAN_PARAM = 'clean-param';

### Usage example
```php
$parser = new RobotsTxtParser(file_get_contents('http://example.com/robots.txt'));
var_dump($parser->getRules());
```

```php
$parser = new RobotsTxtParser("
	User-Agent: *
	Disallow: /ajax
	Disallow: /search
	Clean-param: param1 /path/file.php

	User-agent: Yahoo
	Disallow: /

	Host: example.com
	Host: example2.com
");

var_dump($parser->getRules());
array(2) {
  ["*"]=>
  array(3) {
    ["disallow"]=>
    array(2) {
      [0]=>
      string(5) "/ajax"
      [1]=>
      string(7) "/search"
    }
    ["clean-param"]=>
    array(1) {
      [0]=>
      string(21) "param1 /path/file.php"
    }
    ["host"]=>
    string(11) "example.com"
  }
  ["yahoo"]=>
  array(1) {
    ["disallow"]=>
    array(1) {
      [0]=>
      string(1) "/"
    }
  }
}
```