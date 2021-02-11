<?php

function generatePagination($page= 1, $perPage=10, $count, $data){
    $toReturn = new stdClass();
    $toReturn->page = $page;
    $toReturn->perPage = $perPage;
    $toReturn->count = $count;
    $toReturn->data = $data;
    return $toReturn;
}
