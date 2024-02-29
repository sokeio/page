<?php

//code php
if (!function_exists('page_with_builder')) {
    function page_with_builder()
    {
        return function_exists('module_active') && !!!module_active('builder');
    }
}
