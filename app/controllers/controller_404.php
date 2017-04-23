<?php

/* 
 * autor: fedornabilkin
 * icq: 445537491
 */

class Controller_404 extends Controller {

    
    function action_index() {
        $this->errorPage();
        return $this;
    }
}

// ﻿