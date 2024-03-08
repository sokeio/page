<?php

//code php
if (!function_exists('pageWithBuilder')) {
    function pageWithBuilder()
    {
        return function_exists('moduleIsActive') && moduleIsActive('builder');
    }
}
