<?php

return [
  "cache_enabled" => env("EASY_SET_CONFIG_CACHE_ENABLED" , "true") == "true" ,
  "cache_driver" => env("EASY_SET_CONFIG_CACHE_DRIVER")
];
