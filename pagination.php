<?php
$pagination = new Censeo_Pagination(array('previous'=>true, 'next'=>true));

echo $pagination->get_output();
?>