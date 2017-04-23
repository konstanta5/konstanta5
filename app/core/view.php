<?php

/**
 * @autor fedornabilkin icq: 445537491
 */

class View {

    /**
     * Название дирректории файлов видов.
     */
    public $dir;
    /**
     * Сохраняем все шаблоны, которые использовали
     */
    public static $views = array();
    
    /**
     * Путь к файлу шаблона
     */
    private $render_file;
    /**
     * Название шаблона
     */
    private $tpl_name;
    
    /**
     * Устанавливает каталог для поиска файла шаблона
     * @param string $dir
     * @return object $this
     */
    public function setDir($dir) {
        $this->dir = $dir;
        return $this;
    }
    
    /**
     * Возвращает отрендеренный шаблон представления
     * @param string $view название шаблона
     * @param array $param массив данных (ключ view зарезервирован)
     * 
     * @return string
    */
    public function renderView($view, $param=false) {
        $dir = ($this->dir) ? $this->dir .'/': '';
        $this->tpl_name = $view;
        self::$views[] = $this->render_file = APP_DIR . '/views/' . $dir . $view. '.php';
//        if(is_file($this->render_file)){
        $param['view'] = $this;
            return $this->renderFile($param);
//        }
    }
    
    
    /**
     * Возвращает отрендеренный шаблон layout
     * @param string $view название шаблона
     * @param array $param массив данных
     * @return string
    */
    public function renderLayout($view, $param=false) {
        $this->render_file = APP_DIR . '/template/' . $view .'.php';
        $param['view'] = $this;
        if(is_file($this->render_file)){
            return $this->renderFile($param);
        }
    }
    
    /**
     * Возвращает строку шаблона
     * @param array $param массив данных
     * @return string 
    */
    private function renderFile($param, $extr=true) {
        if(!is_file($this->render_file)) {
            $param['path'] = $this->render_file;
            $param['tpl'] = $this->tpl_name;
//            trigger_error("Шаблон '{$this->render_file}' не найден", E_USER_ERROR);
            $this->render_file = APP_DIR . '/views/404/noview.php';
        }
        if(is_array($param) && $extr) {
            extract($param, EXTR_SKIP);
        }
        ob_start();
        include $this->render_file;
        $res = ob_get_contents();
        ob_end_clean();
        return $res;
    }
    
}