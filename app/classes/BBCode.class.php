<?php
/**
 * Парсер BB-code
 * [b]bold[/b] [i]italic[/i] [u]underline[/u] [color=red]color red[/color] [url=http://site.ru]site.ru[/url]
 * [color=red][b]red bold[/b][/color] [b][color=red]red bold[/color] [color=green]green bold[/color] bold[/b]
 * [color=red][b][url=http://site.ru]site.ru[/url][/b][/color]
 * 
 * @author fedornabilkin icq: 445537491
 */

class BBCode {

    private $rules = array(); // какие bb-code не обрабатывать set_rule(array('url'))
    private $text = '';
    private $codes = array('url','hn','quote','img','strong','color','list','list1','i','u','b','hr','br','span','nr');
    private $parser_used; // если парсер был запущен
    private $clear; // если bb-code не обрабатывать

    /**
     * Устанавливает строку, в которой необходимо разобрать BB-code
     * @param string $str
     */
    function __construct($str = false) {
        if ($str){
            $this->text = $str;
        }
    }

    function __destruct() {
        $this->text = null;
    }

    // $regurl = '/\[url=([^]]*)]/i';
    public function run() {
        $this->beforeClear();

        foreach($this->codes as $val){
            $method = 'set_' . $val;
            $this->clear = false;
            if(!method_exists($this, $method)){
                continue;
            }
            if(in_array($val, $this->rules)){
                $this->clear = true;
            }
            $this->$method();
        }// foreach

        $this->parser_used = TRUE;
        return $this;
    }

    /**
     * Устанавливает строку для обработки BB-codes
     * @param string $str
     * @return object $this
     */
    public function setText($str) {
        $this->parser_used = FALSE;
        $this->text = $str;
        return $this;
    }

    /**
     * Возвращает текст после разбора BB-code
     * @return string
     */
    public function getText() {
        if ($this->parser_used != TRUE){
            $this->run();
        }
        return $this->text;
    }

    /**
     * Устанавливает BB-codes, которые необходимо исключить из обработки
     * @param array $param
     * @return object $this
     */
    public function set_rule($param) {
        if (is_array($param)){
            $this->rules += $param;
        }
        return $this;
    }

    /**
     * @return object $this
     * @deprecated
     */
    private function set_br() {
        if (strpos($this->text, '[br]') !== false){
            $this->text = str_replace("[br]", "<br>", $this->text);
        }
        //$this->text = str_replace("#\n#", "<br>", $this->text);
        return $this;
    }

    // url
    private function set_url() {
        if(!$this->clear){
            if (preg_match_all('#\[url=(.*?)\](.*?)\[\/url\]#', $this->text, $match)){
                $i = 0;
                foreach ($match[1] as $key => $val) {
                    $title = (strpos($match[2][$i], '[') !== false) ? '' : $match[2][$i];
                    $this->text = str_replace('[url=' . $val . ']', '<a title="' . $title . '" href="' . $val . '">', $this->text);
                    $i++;
                }
                $this->text = str_replace('[/url]', "</a>", $this->text);
            }
        }
        else{
            if (strpos($this->text, '[url') !== false){
                $this->text = preg_replace('#\[url=(.*?)\](.*?)\[\/url\]#', '$1', $this->text);
            }  
        }
        return $this;
    }

    // img
    private function set_img() {
//        if(!$this->clear){
//            if (strpos($this->text, '[img alt=&quot;(.*?)&quot; title=&quot;(.*?)&quot;]') !== false && strpos($this->text, '[/img]') !== false){
//                $this->text = preg_replace('#\[img alt=&quot;(.*?)&quot; title=&quot;(.*?)&quot;\](.*?)\[\/img\]#', '<img src="$3" alt="$1" title="$2" >', $this->text);
//            }
//        }
        
//        if (preg_match_all('#\[img alt=&quot;(.*?)&quot; title=&quot;(.*?)&quot;](.*?)\[\/img\]#', $this->text, $match)){
//            print_r($match);
//            foreach ($match[1] as $val) {
//                $this->text = str_replace('[img alt=&quot;'.$val[0].'&quot; title=&quot;'.$val[1].'&quot;]'.$val[2].'[/img]', '<img src="'.$val[2].'" alt="'.$val[0].'" title="'.$val[1].'" >', $this->text);
//            }
////            $this->text = str_replace('[/img]', "</span>", $this->text);
//        }
//        if(!$this->clear){
//            if (strpos($this->text, '[img]') !== false && strpos($this->text, '[/img]') !== false){
//                $this->text = preg_replace('#\[img\](.*?)\[\/img\]#', '<img src="$1" alt="" />', $this->text);
//            }
//        }
        if(!$this->clear){
            if (strpos($this->text, '[img') !== false && strpos($this->text, '[/img]') !== false){
                $this->text = preg_replace('#\[img(.*?)\](.*?)\[\/img\]#', '<img src="$2" $1>', $this->text);
            }
        }
        return $this;
    }

    // quote
    private function set_quote() {
        if (preg_match('#\[quote#', $this->text)){
            $this->text = preg_replace('#\[quote\]|\[quote=\]|\[quote=&quot;\]|\[quote&quot;\]|\[quote&quot;&quot;\]#', '[quote=&quot;&quot;]', $this->text);
            //$this->text = preg_replace('#\[quote=\&quot;(.*?)\&quot;\]#', '<blockquote class="blockquote"><div class="blockquote-footer">$1</div>', $this->text, -1, $cnt);
            $this->text = preg_replace('#\[quote=\&quot;(.*?)\&quot;\]#', '<blockquote class="blockquote">', $this->text, -1, $cnt);
            $this->text = preg_replace('#\[\/quote\]#', '</blockquote>', $this->text, $cnt);
        }
        return $this;
    }

    /**
     * Разбирает [color=.*].*[/color]
     * @return object $this
     */
    private function set_color() {
        if (strpos($this->text, '[color') !== false && strpos($this->text, '[/color') !== false){
            $this->text = preg_replace('#\[color=(.*?)\]#', '<span style="color:$1;">', $this->text, -1, $cnt);
            $this->text = preg_replace('#\[\/color\]#', '</span>', $this->text, $cnt);
        }
//        if (strpos($this->text, '[color]') !== false && strpos($this->text, '[/color]') !== false){
//            $this->text = preg_replace('#\[color=(.*?)\](.*?)\[\/color\]#', '<span style="color:$1;">$2</span>', $this->text);
//        }
        
//        if (preg_match_all('#\[color=(.*?)\](.*?)\[\/color\]#', $this->text, $match)){
//            foreach ($match[1] as $val) {
//                $this->text = str_replace('[color=' . $val . ']', '<span style="color:' . $val . ';">', $this->text);
//            }
//            $this->text = str_replace('[/color]', "</span>", $this->text);
//        }
        return $this;
    }

    /**
     * Форматирует ненумерованный список
     * @return object $this
     */
    private function set_list() {        
        if (preg_match_all('#\[list\](.*?)\[\/list\]#s', $this->text, $match)){
            $list = $blist = '';
            foreach ($match[1] as $val) {
                $blist = $val;
                if (strpos($val, '[*]') !== false && strpos($val, '[/*]') !== false){
                    $val = preg_replace('#\[\*\](.*?)\[\/\*\]#s', '<li>$1</li>', $val);
                    $list = $val;
                }
                $this->text = str_replace('[list]' .$blist. '[/list]', '<ul>' .$list. '</ul>', $this->text);
            }
        }
        return $this;
    }

    /**
     * Форматирует нумерованный список
     * @return object $this
     */
    private function set_list1() {        
        if (preg_match_all('#\[list=1\](.*?)\[\/list\]#s', $this->text, $match)){
            $list = $blist = '';
            foreach ($match[1] as $val) {
                $blist = $val;
                if (strpos($val, '[*]') !== false && strpos($val, '[/*]') !== false){
                    $val = preg_replace('#\[\*\](.*?)\[\/\*\]#s', '<li>$1</li>', $val);
                    $list = $val;
                }
                $this->text = str_replace('[list=1]' .$blist. '[/list]', '<ol>' .$list. '</ol>', $this->text);
            }
        }
        return $this;
    }

    /**
     * Разбирает [span class=".*"].*[/sapn]
     * @return object $this
     */
    private function set_span() {
        if (strpos($this->text, '[span') !== false && strpos($this->text, '[/span') !== false){
            $this->text = preg_replace('#\[span class=&quot;(.*?)&quot;\]#', '<span class="$1">', $this->text, -1, $cnt);
            $this->text = preg_replace('#\[\/span\]#', '</span>', $this->text, $cnt);
        }
//        if (strpos($this->text, '[span') !== false && strpos($this->text, '[/span') !== false){
//            $this->text = preg_replace('#\[span(.*?)\](.*?)\[\/span\]#s', '<span$1>$2</span>', $this->text);
//        }
//        if (preg_match_all('#\[span class=&quot;(.*?)&quot;\](.*?)\[\/span\]#s', $this->text, $match)){
//            foreach ($match[1] as $val) {
//                $this->text = str_replace('[span class=&quot;' . $val . '&quot;]', '<span class="' .$val. '">', $this->text);
//            }
//            $this->text = str_replace('[/span]', "</span>", $this->text);
//        }
        return $this;
    }

    /**
     * strong
     * @return object $this
     */
    private function set_strong() {
        if (strpos($this->text, '[strong]') !== false && strpos($this->text, '[/strong]') !== false){
            $this->text = preg_replace('#\[strong\](.*?)\[\/strong\]#', '<strong>$1</strong>', $this->text);
        }
        return $this;
    }

    /**
     * Разбирает [h*][/h*]
     * @return object $this
     */
    private function set_hn() {
        if (strpos($this->text, '[h') !== false && strpos($this->text, '[/h') !== false){
            //$this->text = preg_replace("\n", '', $this->text);
            $this->text = preg_replace('#\[h([1-6]?)\](.*?)\[\/h([1-6]?)\]#s', '<h$1>$2</h$1>', $this->text);
        }
        return $this;
    }

    // bold
    private function set_b() {
        if (strpos($this->text, '[b]') !== false && strpos($this->text, '[/b]') !== false){
            $this->text = preg_replace('#\[b\](.*?)\[\/b\]#s', '<b>$1</b>', $this->text);
        }
        return $this;
    }

    // italic
    private function set_i() {
        if (strpos($this->text, '[i]') !== false && strpos($this->text, '[/i]') !== false){
            $this->text = preg_replace('#\[i\](.*?)\[\/i\]#s', '<i>$1</i>', $this->text);
        }
        return $this;
    }

    // under
    private function set_u() {
        if (strpos($this->text, '[u]') !== false && strpos($this->text, '[/u]') !== false){
            $this->text = preg_replace('#\[u\](.*?)\[\/u\]#s', '<u>$1</u>', $this->text);
        }
        return $this;
    }

    // hr
    private function set_hr() {
        if (strpos($this->text, '[hr]') !== false){
            $this->text = preg_replace('#\[hr\]#s', '<hr/>', $this->text);
        }
        return $this;
    }

    // hr
    private function set_nr() {
        if (strpos($this->text, "\n") !== false){
            //Timer::start('setParagraph');
            //$this->text = '<p>' . str_replace("\n", '</p><p>', $this->text) . '</p>';
            //Timer::stop('setParagraph',10);
            
            Timer::start('setParagraphArray');
            $arr = explode("\n", $this->text);
            foreach ($arr as $key => $value) {
                $text[] = '<p>' .$value. '</p>';
            }
            $this->text = implode('', $text);
            Timer::stop('setParagraphArray',10);
            $this->afterClear();
        }
        return $this;
    }
    // <p>text text</p><p>text text</p><p>text text</p>
    // <p>text text</p>
    
    
    /**
     * Очищает лишние переводы строк перед парсингом BBCode
     * @return object $this
     */
    public function beforeClear() {
        $search = array(
            "/[\r\n]+/m" // несколько переводов строки на один
            ,"#\n\[/#" // убираем перевод строки перед закрывающим тегом
            ,"#(\[/h[2-6]\])#m"
            ,"#(\[/list\])#m"
            ,"#(\[/quote\])#m"
            );
        $replace = array(
            "\n"
            ,'[/'
            ,"$1\n"
            ,"$1\n"
            ,"$1\n"
            );
        $this->text = preg_replace($search, $replace, $this->text);
    }
    
    
    /**
     * Обрезает теги <p></p> вокруг заголовков и списков, удаляет пустые <p></p>
     * @return object $this
     */
    public function afterClear() {
        
        $search = array(
            "#<p>(<h[2-6]>.*?</h[2-6]>)</p>#m"
            ,"#<p>(<ul>.*?</ul>)</p>#m"
            ,"#<p>(<ol>.*?</ol>)</p>#m"
            ,"#<p>(<blockquote .*?>.*?</blockquote>)</p>#m"
            ,"#<p></p>#m"
            );
        $replace = array(
            "$1"
            ,"$1"
            ,"$1"
            ,"$1"
            ,""
            );
        $this->text = preg_replace($search, $replace, $this->text);
    }
}
