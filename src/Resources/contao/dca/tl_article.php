<?php

$GLOBALS['TL_DCA']['tl_article']['config']['onsubmit_callback'][] = ['\Anker\ModulesBundle\Classes\Sitemap', 'updateSitemapLastmod'];
