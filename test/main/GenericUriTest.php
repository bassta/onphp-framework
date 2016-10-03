<?php

namespace onphp\test\main;

use onPHP\core\Exceptions\WrongArgumentException;
use onPHP\main\Utils\ClassUtils;
use onPHP\test\misc\TestCase;

/***************************************************************************
 *   Copyright (C) 2007 by Ivan Khvostishkov                               *
 *   dedmajor@oemdesign.ru                                                 *
 ***************************************************************************/
final class GenericUriTest extends TestCase
{
    private $urls = array('http://ya.ru' => null, '/etc/passwd' => null, 'file:///etc/' => null, 'http:/uri' => null, 'http:uri' => null, 'mailto://localhost.com' => null, 'mailto://spam@localhost' => null, 'mailto://cuckoo@localhost.com:boo.com' => null, 'mailto://cuckoo:localhost.com@boo.com' => null, '//samba/de/janeiro' => null, 'ftp://cnn.example.com&story=breaking_news@10.0.0.1/top_story.htm' => null, 'http://a/b/c/d;p?q' => null, 'g;x?y#s' => null, '' => null, '../../../../g' => null, 'tel:+1-816-555-1212' => null, 'telnet://192.0.2.16:80/' => null, 'urn:oasis:names:specification:docbook:dtd:xml:4.1.2' => null, 'ldap://[2001:db8::7]/c=GB?objectClass?one]' => null, 'mailto:John.Doe@example.com' => null, 'http://[1080:0:0:0:8:800:200C:417A]/' => null, 'http://[1080::8:800:200C:417A]/' => null, 'http://[::FFFF:129.144.52.38]' => null, 'http://[::129.144.52.38]' => null, 'http://[::FFFF:1.1.1]' => null, 'http://[::FFFF:1.1]' => null, 'http://[::66.66.66]' => null, 'http://[::5.5]' => null, 'http://[::FFFF:10]' => null, 'http://[192.168.0.1]' => null, 'http://266.66.66.66' => null, 'mailto:"George, Ted" <Shared@Group.Arpanet>' => null, 'mailto:%22George,%20Ted%22%20%3CShared@Group.Arpanet%3E' => null, 'mailto:astark1@unl.edu,ASTARK1@UNL.EDU?subject=MailTo Comments&cc=ASTARK1@UNL.EDU&bcc=id@internet.node' => null, '//ya0.ru' => null, '//yandex.ru' => 'Url', '//yandex.ru:80' => 'Url', 'ya1.ru' => null, 'ya2.ru' => 'Url', 'ya3.ru' => 'HttpUrl', 'http:ya4.ru' => 'HttpUrl', 'http:/ya5.ru' => 'HttpUrl', 'http:///ya6.ru' => 'HttpUrl', 'hTTP://onphp.org' => 'HttpUrl');

    public function testParser()
    {
        /*
        $genericUriClass = realpath(
        	ONPHP_MAIN_PATH.'Utils'.DIRECTORY_SEPARATOR
        	.'GenericUri'.EXT_CLASS
        );
        
        Assert::isTrue(is_readable($genericUriClass));
        
        xdebug_start_code_coverage();
        */
        $dumpFile  = dirname(__FILE__).'/data/urls/parser.dump';
        $newStamps = array();
        if (is_readable($dumpFile)) {
            $stamps = unserialize(file_get_contents($dumpFile));
        }
        foreach ($this->urls as $testUrl => $parserClass) {
            $dump      = "url: {$testUrl}\n";
            $exception = null;
            if (!$parserClass) {
                $parserClass = 'GenericUri';
            }
            try {
                $url = ClassUtils::callStaticMethod("{$parserClass}::parse", $testUrl, true);
            } catch (WrongArgumentException $e) {
                $exception = $e;
            }
            $fix = $parserClass == 'HttpUrl';
            if ($fix) {
                $url->ensureAbsolute();
            }
            if ($exception) {
                $dump .= "wrong argument exception: {$e->getMessage()}\n";
            } else {
                if (!$fix) {
                    $this->assertEquals($testUrl, $url->toString());
                }
                $dump .= 'class: '.get_class($url).'
'.'toString(): '.$url->toString().'
'.'getSchemeSpecificPart(): '.$url->getSchemeSpecificPart().'
'.'getScheme(): '.$url->getScheme().'
'.'getUserInfo(): '.$url->getUserInfo().'
'.'getHost(): '.$url->getHost().'
'.'getPort(): '.$url->getPort().'
'.'getPath(): '.$url->getPath().'
'.'getQuery(): '.$url->getQuery().'
'.'getFragment(): '.$url->getFragment().'
'.'isValidScheme(): '.$url->isValidScheme().'
'.'isValidUserInfo(): '.$url->isValidUserInfo().'
'.'isValidHost(): '.$url->isValidHost().'
'.'isValidPort(): '.$url->isValidPort().'
'.'isValidPath(): '.$url->isValidPath().'
'.'isValidQuery(): '.$url->isValidQuery().'
'.'isValidFragment(): '.$url->isValidFragment().'
';
            }
            if (isset($stamps[$testUrl])) {
                $this->assertEquals($stamps[$testUrl], $dump);
            }
            $newStamps[$testUrl] = $dump;
        }
        file_put_contents($dumpFile.'.new', serialize($newStamps));
    }
}