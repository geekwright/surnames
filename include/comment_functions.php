<?php

function surnames_com_update($link_id, $total_num)
{
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = 'UPDATE '.$db->prefix('surnames').' SET comment_count = '.$total_num.' WHERE id = '.$link_id;
    $db->query($sql);
}
