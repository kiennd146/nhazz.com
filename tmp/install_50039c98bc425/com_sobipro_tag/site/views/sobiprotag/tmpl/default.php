<?php echo $this->tag;?> <br/>
<?php 
foreach ($this->totalId as $value) {
    $entry = SPFactory::Entry($value);
    $id = $entry->get('id');
    $fid = $entry->get('primary');
    $title = $entry->get('name');
    $url =  Sobi::Url( array('title' => $title, 'pid' => $fid, 'sid' => $id));
    echo "<a href='$url'>".$title."</a>";
    echo "<br/>";
}
?>
